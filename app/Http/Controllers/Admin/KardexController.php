<?php

namespace App\Http\Controllers\Admin;

use App\Application\Kardex\DTOs\AdjustStockDTO;
use App\Application\Kardex\DTOs\KardexFiltersDTO;
use App\Application\Kardex\UseCases\AdjustStock;
use App\Application\Kardex\UseCases\ListKardex;
use App\Application\Kardex\UseCases\ShowProductKardex;
use App\Domain\Kardex\Repositories\KardexRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KardexController extends Controller
{
    public function __construct(
        private ListKardex $listKardex,
        private ShowProductKardex $showProductKardex,
        private AdjustStock $adjustStock,
        private KardexRepositoryInterface $kardexRepository,
    ) {}

    public function index(Request $request)
    {
        $filters = KardexFiltersDTO::fromRequest($request);
        $data = $this->listKardex->execute($filters);

        return view('admin.kardex.index', $data);
    }

    public function show(Request $request, Product $product)
    {
        $filters = KardexFiltersDTO::fromRequest($request);
        $data = $this->showProductKardex->execute($product, $filters);

        return view('admin.kardex.show', $data);
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'new_stock' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $dto = AdjustStockDTO::fromRequest($request);
        $result = $this->adjustStock->execute($dto);

        return redirect()->back()
            ->with('success', "Stock de {$result['product']->name} ajustado a {$result['newStock']} unidades.");
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
        $products = $this->kardexRepository->searchProducts($search, 10);

        $results = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku,
            'stock' => $p->stock,
            'image' => $p->primaryImage?->image_url ?? null,
        ]);

        return response()->json($results);
    }
}
