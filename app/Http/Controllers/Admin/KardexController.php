<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KardexController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product.primaryImage', 'creator']);

        if ($productId = $request->get('product_id')) {
            $query->byProduct($productId);
        }

        if ($type = $request->get('type')) {
            $query->byType($type);
        }

        if ($request->get('date_from') || $request->get('date_to')) {
            $query->byDateRange($request->get('date_from'), $request->get('date_to'));
        }

        $perPage = $request->get('per_page', 15);
        $movements = $query->latest('created_at')->paginate($perPage)->withQueryString();

        $todayStr = today()->toDateTimeString();
        $monthStr = now()->startOfMonth()->toDateTimeString();
        $ks = \DB::selectOne("
            SELECT
                SUM(created_at >= ?) as today,
                SUM(type = 'entrada' AND created_at >= ?) as entries_month,
                SUM(type = 'salida' AND created_at >= ?) as exits_month,
                SUM(type = 'ajuste' AND created_at >= ?) as adjustments_month
            FROM stock_movements
        ", [$todayStr, $monthStr, $monthStr, $monthStr]);
        $movementsToday = (int) ($ks->today ?? 0);
        $entriesMonth = (int) ($ks->entries_month ?? 0);
        $exitsMonth = (int) ($ks->exits_month ?? 0);
        $adjustmentsMonth = (int) ($ks->adjustments_month ?? 0);

        $products = Product::where('is_active', true)->orderBy('name')->limit(200)->get(['id', 'name', 'sku']);

        return view('admin.kardex.index', compact(
            'movements', 'movementsToday', 'entriesMonth', 'exitsMonth', 'adjustmentsMonth', 'products'
        ));
    }

    public function show(Request $request, Product $product)
    {
        $product->load('primaryImage', 'category');

        $query = StockMovement::with('creator')
            ->byProduct($product->id);

        if ($type = $request->get('type')) {
            $query->byType($type);
        }

        if ($request->get('date_from') || $request->get('date_to')) {
            $query->byDateRange($request->get('date_from'), $request->get('date_to'));
        }

        $perPage = $request->get('per_page', 15);
        $movements = $query->latest('created_at')->paginate($perPage)->withQueryString();

        $ms = \DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(CASE WHEN type = 'entrada' THEN quantity ELSE 0 END) as entries,
                SUM(CASE WHEN type = 'salida' THEN quantity ELSE 0 END) as exits,
                SUM(type = 'ajuste') as adjustments
            FROM stock_movements WHERE product_id = ?
        ", [$product->id]);
        $totalEntries = (int) ($ms->entries ?? 0);
        $totalExits = (int) ($ms->exits ?? 0);
        $totalAdjustments = (int) ($ms->adjustments ?? 0);
        $totalMovements = (int) $ms->total;

        return view('admin.kardex.show', compact(
            'product', 'movements', 'totalEntries', 'totalExits', 'totalAdjustments', 'totalMovements'
        ));
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'new_stock' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        StockService::adjust($product, $validated['new_stock'], $validated['notes'] ?? 'Ajuste manual de stock');

        return redirect()->back()
            ->with('success', "Stock de {$product->name} ajustado a {$validated['new_stock']} unidades.");
    }

    public function export(Request $request): StreamedResponse
    {
        $query = StockMovement::with(['product', 'creator']);

        if ($productId = $request->get('product_id')) {
            $query->byProduct($productId);
        }
        if ($type = $request->get('type')) {
            $query->byType($type);
        }
        if ($request->get('date_from') || $request->get('date_to')) {
            $query->byDateRange($request->get('date_from'), $request->get('date_to'));
        }

        $filename = 'kardex_general_' . now()->format('Ymd_His') . '.csv';

        return $this->streamCsv($query->latest('created_at'), $filename, true);
    }

    public function exportProduct(Request $request, Product $product): StreamedResponse
    {
        $query = StockMovement::with('creator')->byProduct($product->id);

        if ($type = $request->get('type')) {
            $query->byType($type);
        }
        if ($request->get('date_from') || $request->get('date_to')) {
            $query->byDateRange($request->get('date_from'), $request->get('date_to'));
        }

        $slug = str_replace(' ', '_', strtolower($product->sku ?: $product->id));
        $filename = "kardex_{$slug}_" . now()->format('Ymd_His') . '.csv';

        return $this->streamCsv($query->latest('created_at'), $filename, false, $product);
    }

    private function streamCsv($query, string $filename, bool $includeProduct, ?Product $product = null): StreamedResponse
    {
        return response()->streamDownload(function () use ($query, $includeProduct, $product) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($product) {
                fputcsv($handle, ['Producto', $product->name], ';');
                fputcsv($handle, ['SKU', $product->sku ?? '—'], ';');
                fputcsv($handle, ['Stock Actual', $product->stock], ';');
                fputcsv($handle, [], ';');
            }

            $headers = ['Fecha', 'Hora'];
            if ($includeProduct) {
                $headers[] = 'Producto';
                $headers[] = 'SKU';
            }
            $headers = array_merge($headers, ['Tipo', 'Cantidad', 'Stock Antes', 'Stock Después', 'Referencia', 'Notas', 'Usuario']);
            fputcsv($handle, $headers, ';');

            $query->with(['product', 'creator'])->chunk(500, function ($movements) use ($handle, $includeProduct) {
                foreach ($movements as $m) {
                    $row = [
                        $m->created_at->format('d/m/Y'),
                        $m->created_at->format('H:i:s'),
                    ];
                    if ($includeProduct) {
                        $row[] = $m->product?->name ?? 'Eliminado';
                        $row[] = $m->product?->sku ?? '—';
                    }
                    $row[] = $m->type_label;
                    $row[] = $m->quantity;
                    $row[] = $m->stock_before;
                    $row[] = $m->stock_after;
                    $row[] = $m->reference_label ?? '—';
                    $row[] = $m->notes ?? '';
                    $row[] = $m->creator ? $m->creator->first_name . ' ' . $m->creator->last_name : 'Sistema';
                    fputcsv($handle, $row, ';');
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

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
                'stock' => $p->stock,
                'image' => $p->primaryImage?->image_url,
            ]);

        return response()->json($products);
    }
}
