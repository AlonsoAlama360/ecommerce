<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->with('category:id,name,slug');

        // Filtro por categoría(s)
        if ($request->filled('categories')) {
            $slugs = is_array($request->categories)
                ? $request->categories
                : explode(',', $request->categories);

            $query->whereHas('category', function ($q) use ($slugs) {
                $q->whereIn('slug', $slugs);
            });
        }

        // Filtro por rango de precio (usa current_price = sale_price ?? price)
        if ($request->filled('price_min')) {
            $min = (float) $request->price_min;
            $query->where(function ($q) use ($min) {
                $q->whereNotNull('sale_price')->where('sale_price', '>=', $min)
                  ->orWhereNull('sale_price')->where('price', '>=', $min);
            });
        }

        if ($request->filled('price_max')) {
            $max = (float) $request->price_max;
            $query->where(function ($q) use ($max) {
                $q->whereNotNull('sale_price')->where('sale_price', '<=', $max)
                  ->orWhereNull('sale_price')->where('price', '<=', $max);
            });
        }

        // Filtro de disponibilidad
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        if ($request->boolean('on_sale')) {
            $query->whereNotNull('sale_price')
                  ->whereColumn('sale_price', '<', 'price');
        }

        // Ordenamiento
        switch ($request->get('sort', 'relevant')) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'newest':
                $query->latest();
                break;
            default: // relevant - featured primero, luego más nuevos
                $query->orderByDesc('is_featured')->latest();
                break;
        }

        $products = $query->select([
            'id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'is_featured',
        ])->paginate(12)->withQueryString();

        $categories = Category::active()
            ->ordered()
            ->select('id', 'name', 'slug', 'icon')
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->get();

        // Rango de precios para el slider
        $priceRange = Product::active()
            ->selectRaw('MIN(COALESCE(sale_price, price)) as min_price, MAX(COALESCE(sale_price, price)) as max_price')
            ->first();

        return view('catalog', compact('products', 'categories', 'priceRange'));
    }
}
