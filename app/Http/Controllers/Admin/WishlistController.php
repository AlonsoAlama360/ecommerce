<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::select('products.*')
            ->join('wishlists', 'products.id', '=', 'wishlists.product_id')
            ->selectRaw('COUNT(wishlists.id) as wishlists_count')
            ->selectRaw('MAX(wishlists.created_at) as last_wishlisted_at')
            ->groupBy('products.id');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->get('category_id')) {
            $query->where('products.category_id', $categoryId);
        }

        $orderBy = $request->get('order', 'most_wished');
        if ($orderBy === 'recent') {
            $query->orderByDesc('last_wishlisted_at');
        } else {
            $query->orderByDesc('wishlists_count');
        }

        $perPage = $request->get('per_page', 15);
        $products = $query->with(['primaryImage', 'category'])
            ->paginate($perPage)
            ->withQueryString();

        // Stats
        $totalItems = Wishlist::count();
        $uniqueProducts = Wishlist::distinct('product_id')->count('product_id');
        $uniqueClients = Wishlist::distinct('user_id')->count('user_id');

        $topProduct = Product::select('products.id', 'products.name')
            ->join('wishlists', 'products.id', '=', 'wishlists.product_id')
            ->selectRaw('COUNT(wishlists.id) as total')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->first();

        $categories = Category::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.wishlists.index', compact(
            'products', 'totalItems', 'uniqueProducts', 'uniqueClients', 'topProduct', 'categories'
        ));
    }

    public function show(Request $request, Product $product)
    {
        $product->load('primaryImage', 'category');

        $query = Wishlist::with('user')
            ->where('product_id', $product->id);

        if ($search = $request->get('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $wishlists = $query->latest('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $totalInterested = Wishlist::where('product_id', $product->id)->count();

        return view('admin.wishlists.show', compact('product', 'wishlists', 'totalInterested'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Product::select('products.*')
            ->join('wishlists', 'products.id', '=', 'wishlists.product_id')
            ->selectRaw('COUNT(wishlists.id) as wishlists_count')
            ->groupBy('products.id')
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

        $products = $query->orderByDesc('wishlists_count')->get();
        $filename = 'lista_deseos_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($products) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['Producto', 'SKU', 'Categoría', 'Precio', 'Precio Oferta', 'Veces Deseado', 'Stock'], ';');

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

        $wishlists = $query->latest('created_at')->get();
        $slug = str_replace(' ', '_', strtolower($product->sku ?: $product->id));
        $filename = "clientes_interesados_{$slug}_" . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($wishlists, $product) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['Producto', $product->name], ';');
            fputcsv($handle, ['SKU', $product->sku ?? '—'], ';');
            fputcsv($handle, ['Precio', 'S/ ' . number_format($product->price, 2)], ';');
            fputcsv($handle, [], ';');

            fputcsv($handle, ['Nombre', 'Email', 'Teléfono', 'Fecha Agregado'], ';');

            foreach ($wishlists as $w) {
                fputcsv($handle, [
                    $w->user ? $w->user->first_name . ' ' . $w->user->last_name : 'Usuario eliminado',
                    $w->user?->email ?? '—',
                    $w->user?->phone ?? '—',
                    $w->created_at ? \Carbon\Carbon::parse($w->created_at)->format('d/m/Y H:i') : '—',
                ], ';');
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
