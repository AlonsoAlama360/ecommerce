<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($source = $request->get('source')) {
            $query->where('source', $source);
        }

        if ($paymentMethod = $request->get('payment_method')) {
            $query->where('payment_method', $paymentMethod);
        }

        if ($paymentStatus = $request->get('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $perPage = $request->get('per_page', 10);
        $orders = $query->latest()->paginate($perPage)->withQueryString();

        $totalOrders = Order::count();
        $ordersToday = Order::whereDate('created_at', today())->count();
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', 'cancelado')
            ->sum('total');
        $pendingOrders = Order::where('status', 'pendiente')->count();

        return view('admin.orders.index', compact(
            'orders', 'totalOrders', 'ordersToday', 'monthlyRevenue', 'pendingOrders'
        ));
    }

    public function create()
    {
        return redirect()->route('admin.orders.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'shipping_address' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:efectivo,transferencia,yape_plin,tarjeta',
            'payment_status' => 'required|in:pendiente,pagado,fallido',
            'admin_notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // Find or create user
            $user = null;
            if (!empty($validated['customer_email'])) {
                $user = User::where('email', $validated['customer_email'])->first();
            }

            if (!$user && !empty($validated['customer_email'])) {
                $nameParts = explode(' ', $validated['customer_name'], 2);
                $user = User::create([
                    'first_name' => $nameParts[0],
                    'last_name' => $nameParts[1] ?? '',
                    'email' => $validated['customer_email'],
                    'phone' => $validated['customer_phone'] ?? null,
                    'password' => bcrypt(Str::random(12)),
                    'role' => 'cliente',
                    'is_active' => true,
                ]);
            }

            // Calculate totals
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $price = $product->sale_price ?? $product->price;
                $lineTotal = $price * $item['quantity'];
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                ];
            }

            $order = Order::create([
                'user_id' => $user?->id,
                'source' => 'admin',
                'status' => 'confirmado',
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_status'],
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'shipping_address' => $validated['shipping_address'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($itemsData as $itemData) {
                $order->items()->create($itemData);
                $product = Product::find($itemData['product_id']);
                if ($product) {
                    StockService::decrement($product, $itemData['quantity'], $order, "Venta {$order->order_number}");
                }
            }

            return redirect()->route('admin.orders.index')
                ->with('success', "Venta {$order->order_number} creada exitosamente.");
        });
    }

    public function show(Order $order)
    {
        $order->load(['items.product.primaryImage', 'user', 'creator']);
        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:pendiente,confirmado,en_preparacion,enviado,entregado,cancelado',
            'payment_status' => 'sometimes|in:pendiente,pagado,fallido',
            'payment_method' => 'sometimes|in:efectivo,transferencia,yape_plin,tarjeta',
            'admin_notes' => 'nullable|string|max:2000',
            'shipping_address' => 'nullable|string|max:1000',
            'customer_name' => 'sometimes|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'sometimes|array|min:1',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
        ]);

        return DB::transaction(function () use ($validated, $order, $request) {
            // If items are being updated, handle stock adjustments
            if (isset($validated['items'])) {
                // Restore stock from old items
                foreach ($order->items as $oldItem) {
                    if ($oldItem->product_id) {
                        $oldProduct = Product::find($oldItem->product_id);
                        if ($oldProduct) {
                            StockService::increment($oldProduct, $oldItem->quantity, $order, "Reversión edición venta {$order->order_number}");
                        }
                    }
                }

                // Delete old items
                $order->items()->delete();

                // Create new items and reduce stock
                $subtotal = 0;
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $price = $product->sale_price ?? $product->price;
                    $lineTotal = $price * $item['quantity'];
                    $subtotal += $lineTotal;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => $item['quantity'],
                        'unit_price' => $price,
                        'line_total' => $lineTotal,
                    ]);

                    StockService::decrement($product, $item['quantity'], $order, "Edición venta {$order->order_number}");
                }

                $validated['subtotal'] = $subtotal;
                $validated['total'] = $subtotal - $order->discount_amount + $order->shipping_cost;
            }

            // Remove items from validated since we handled them separately
            unset($validated['items']);

            $order->update($validated);

            if ($request->ajax()) {
                return response()->json(['message' => 'Venta actualizada', 'order' => $order->fresh()]);
            }

            return redirect()->route('admin.orders.index')
                ->with('success', 'Venta actualizada exitosamente.');
        });
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,confirmado,en_preparacion,enviado,entregado,cancelado',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Estado actualizado',
            'status' => $order->status,
            'status_label' => $order->status_label,
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Venta eliminada exitosamente.');
    }

    // API: Search products for order creation
    public function searchProducts(Request $request)
    {
        $search = $request->get('q', '');
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
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

    // API: Search users for order creation
    public function searchUsers(Request $request)
    {
        $search = $request->get('q', '');
        $users = User::where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })
            ->where('is_active', true)
            ->take(10)
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->full_name,
                'email' => $u->email,
                'phone' => $u->phone,
            ]);

        return response()->json($users);
    }
}
