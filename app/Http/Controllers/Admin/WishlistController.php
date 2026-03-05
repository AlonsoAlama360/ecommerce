<?php

namespace App\Http\Controllers\Admin;

use App\Application\Wishlist\DTOs\WishlistFiltersDTO;
use App\Application\Wishlist\UseCases\ListAdminWishlists;
use App\Application\Wishlist\UseCases\ShowProductWishlists;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WishlistController extends Controller
{
    public function __construct(
        private readonly ListAdminWishlists $listAdminWishlists,
        private readonly ShowProductWishlists $showProductWishlists,
    ) {}

    public function index(Request $request)
    {
        $filters = WishlistFiltersDTO::fromRequest($request);

        $data = $this->listAdminWishlists->execute($filters);

        return view('admin.wishlists.index', $data);
    }

    public function show(Request $request, Product $product)
    {
        $search = $request->get('search');
        $perPage = (int) $request->get('per_page', 15);

        $data = $this->showProductWishlists->execute($product, $search, $perPage);

        return view('admin.wishlists.show', $data);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Product::withCount('wishlists')
            ->whereHas('wishlists')
            ->with(['category']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->get('category_id')) {
            $query->where('products.category_id', $categoryId);
        }

        $filename = 'lista_deseos_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['Producto', 'SKU', 'Categoría', 'Precio', 'Precio Oferta', 'Veces Deseado', 'Stock'], ';');

            $query->orderByDesc('wishlists_count')->chunk(500, function ($products) use ($handle) {
                foreach ($products as $p) {
                    fputcsv($handle, [
                        $p->name,
                        $p->sku ?? '—',
                        $p->category?->name ?? '—',
                        number_format($p->price, 2),
                        $p->sale_price ? number_format($p->sale_price, 2) : '—',
                        $p->wishlists_count,
                        $p->stock,
                    ], ';');
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportProduct(Request $request, Product $product): StreamedResponse
    {
        $query = Wishlist::with('user')->where('product_id', $product->id);

        if ($search = $request->get('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $slug = str_replace(' ', '_', strtolower($product->sku ?: $product->id));
        $filename = "clientes_interesados_{$slug}_" . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query, $product) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['Producto', $product->name], ';');
            fputcsv($handle, ['SKU', $product->sku ?? '—'], ';');
            fputcsv($handle, ['Precio', 'S/ ' . number_format($product->price, 2)], ';');
            fputcsv($handle, [], ';');

            fputcsv($handle, ['Nombre', 'Email', 'Teléfono', 'Fecha Agregado'], ';');

            $query->with('user')->latest('created_at')->chunk(500, function ($wishlists) use ($handle) {
                foreach ($wishlists as $w) {
                    fputcsv($handle, [
                        $w->user ? $w->user->first_name . ' ' . $w->user->last_name : 'Usuario eliminado',
                        $w->user?->email ?? '—',
                        $w->user?->phone ?? '—',
                        $w->created_at ? $w->created_at->format('d/m/Y H:i') : '—',
                    ], ';');
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
