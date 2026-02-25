<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'items']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('business_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($supplierId = $request->get('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $perPage = $request->get('per_page', 10);
        $purchases = $query->latest()->paginate($perPage)->withQueryString();

        $totalPurchases = Purchase::count();
        $purchasesToday = Purchase::whereDate('created_at', today())->count();
        $monthlySpending = Purchase::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', 'cancelado')
            ->sum('total');
        $pendingPurchases = Purchase::where('status', 'pendiente')->count();

        $suppliers = Supplier::where('is_active', true)->orderBy('business_name')->get(['id', 'business_name']);

        return view('admin.purchases.index', compact(
            'purchases', 'totalPurchases', 'purchasesToday', 'monthlySpending', 'pendingPurchases', 'suppliers'
        ));
    }

    public function create()
    {
        return redirect()->route('admin.purchases.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $lineTotal = $item['unit_cost'] * $item['quantity'];
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $lineTotal,
                ];
            }

            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'status' => 'pendiente',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'total' => $subtotal,
                'expected_date' => $validated['expected_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($itemsData as $itemData) {
                $purchase->items()->create($itemData);
            }

            return redirect()->route('admin.purchases.index')
                ->with('success', "Compra {$purchase->purchase_number} creada exitosamente.");
        });
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['items.product.primaryImage', 'supplier', 'creator']);
        return response()->json($purchase);
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'supplier_id' => 'sometimes|exists:suppliers,id',
            'status' => 'sometimes|in:pendiente,aprobado,en_transito,recibido,cancelado',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
            'shipping_address' => 'nullable|string|max:1000',
            'items' => 'sometimes|array|min:1',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_cost' => 'required_with:items|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated, $purchase, $request) {
            $oldStatus = $purchase->status;
            $newStatus = $validated['status'] ?? $oldStatus;

            // If items are being updated
            if (isset($validated['items'])) {
                // If purchase was "recibido", revert stock first
                if ($oldStatus === 'recibido') {
                    foreach ($purchase->items as $oldItem) {
                        if ($oldItem->product_id) {
                            $oldProduct = Product::find($oldItem->product_id);
                            if ($oldProduct) {
                                StockService::decrement($oldProduct, $oldItem->quantity, $purchase, "Reversión edición compra {$purchase->purchase_number}");
                            }
                        }
                    }
                }

                $purchase->items()->delete();

                $subtotal = 0;
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $lineTotal = $item['unit_cost'] * $item['quantity'];
                    $subtotal += $lineTotal;

                    $purchase->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => $item['quantity'],
                        'unit_cost' => $item['unit_cost'],
                        'line_total' => $lineTotal,
                    ]);
                }

                $validated['subtotal'] = $subtotal;
                $validated['total'] = $subtotal + ($purchase->tax_amount ?? 0);

                // If still/newly "recibido", apply stock with new items
                if ($newStatus === 'recibido') {
                    $purchase->load('items');
                    foreach ($purchase->items as $newItem) {
                        if ($newItem->product_id) {
                            $newProduct = Product::find($newItem->product_id);
                            if ($newProduct) {
                                StockService::increment($newProduct, $newItem->quantity, $purchase, "Compra {$purchase->purchase_number} recibida (edición)");
                            }
                        }
                    }
                    $validated['received_date'] = $validated['received_date'] ?? now()->toDateString();
                }
            } else {
                // Items not changing, handle status change for stock
                $this->handleStockOnStatusChange($purchase, $oldStatus, $newStatus);
            }

            unset($validated['items']);

            // Set received_date when marking as recibido
            if ($newStatus === 'recibido' && $oldStatus !== 'recibido' && !isset($validated['received_date'])) {
                $validated['received_date'] = now()->toDateString();
            }

            $purchase->update($validated);

            if ($request->ajax()) {
                return response()->json(['message' => 'Compra actualizada', 'purchase' => $purchase->fresh()]);
            }

            return redirect()->route('admin.purchases.index')
                ->with('success', 'Compra actualizada exitosamente.');
        });
    }

    public function updateStatus(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,aprobado,en_transito,recibido,cancelado',
        ]);

        return DB::transaction(function () use ($validated, $purchase) {
            $oldStatus = $purchase->status;
            $newStatus = $validated['status'];

            $this->handleStockOnStatusChange($purchase, $oldStatus, $newStatus);

            $updateData = ['status' => $newStatus];
            if ($newStatus === 'recibido' && $oldStatus !== 'recibido') {
                $updateData['received_date'] = now()->toDateString();
            }

            $purchase->update($updateData);

            return response()->json([
                'message' => 'Estado actualizado',
                'status' => $purchase->status,
                'status_label' => $purchase->status_label,
            ]);
        });
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            // If was "recibido", revert stock
            if ($purchase->status === 'recibido') {
                foreach ($purchase->items as $item) {
                    if ($item->product_id) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            StockService::decrement($product, $item->quantity, $purchase, "Eliminación compra {$purchase->purchase_number}");
                        }
                    }
                }
            }

            $purchase->delete();
        });

        return redirect()->route('admin.purchases.index')
            ->with('success', 'Compra eliminada exitosamente.');
    }

    private function handleStockOnStatusChange(Purchase $purchase, string $oldStatus, string $newStatus): void
    {
        if ($oldStatus === $newStatus) return;

        $purchase->load('items');

        // Going TO recibido → increment stock
        if ($newStatus === 'recibido' && $oldStatus !== 'recibido') {
            foreach ($purchase->items as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        StockService::increment($product, $item->quantity, $purchase, "Compra {$purchase->purchase_number} recibida");
                    }
                }
            }
        }

        // Leaving recibido → decrement stock
        if ($oldStatus === 'recibido' && $newStatus !== 'recibido') {
            foreach ($purchase->items as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        StockService::decrement($product, $item->quantity, $purchase, "Reversión compra {$purchase->purchase_number}");
                    }
                }
            }
        }
    }

    // API: Search suppliers
    public function searchSuppliers(Request $request)
    {
        $search = $request->get('q', '');
        $suppliers = Supplier::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('ruc', 'like', "%{$search}%");
            })
            ->take(10)
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'business_name' => $s->business_name,
                'contact_name' => $s->contact_name,
                'ruc' => $s->ruc,
                'phone' => $s->phone,
            ]);

        return response()->json($suppliers);
    }

    // API: Search products
    public function searchProducts(Request $request)
    {
        $search = $request->get('q', '');
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })
            ->with('primaryImage')
            ->take(10)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => $p->current_price,
                'stock' => $p->stock,
                'image' => $p->primaryImage?->image_url,
            ]);

        return response()->json($products);
    }
}
