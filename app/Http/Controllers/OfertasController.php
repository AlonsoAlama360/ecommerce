<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class OfertasController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()
            ->onSale()
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

        // Filtro por rango de descuento (único de ofertas)
        if ($request->filled('discount_min')) {
            $min = (int) $request->discount_min;
            $query->whereRaw(
                'ROUND(((price - sale_price) / price) * 100) >= ?',
                [$min]
            );
        }

        if ($request->filled('discount_max')) {
            $max = (int) $request->discount_max;
            $query->whereRaw(
                'ROUND(((price - sale_price) / price) * 100) <= ?',
                [$max]
            );
        }

        // Filtro por rango de precio (sobre sale_price)
        if ($request->filled('price_min')) {
            $min = (float) $request->price_min;
            $query->where('sale_price', '>=', $min);
        }

        if ($request->filled('price_max')) {
            $max = (float) $request->price_max;
            $query->where('sale_price', '<=', $max);
        }

        // Filtro de disponibilidad
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        // Ordenamiento - por defecto mayor descuento
        switch ($request->get('sort', 'discount_desc')) {
            case 'discount_desc':
                $query->orderByRaw('ROUND(((price - sale_price) / price) * 100) DESC');
                break;
            case 'discount_asc':
                $query->orderByRaw('ROUND(((price - sale_price) / price) * 100) ASC');
                break;
            case 'savings_desc':
                $query->orderByRaw('(price - sale_price) DESC');
                break;
            case 'price_asc':
                $query->orderByRaw('sale_price ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('sale_price DESC');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->orderByRaw('ROUND(((price - sale_price) / price) * 100) DESC');
                break;
        }

        $products = $query->select([
            'id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'is_featured',
        ])->paginate(12)->withQueryString();

        // Categorías que tienen al menos un producto en oferta
        $categories = Category::active()
            ->ordered()
            ->select('id', 'name', 'slug', 'icon')
            ->withCount(['products as on_sale_count' => function ($q) {
                $q->where('is_active', true)
                  ->whereNotNull('sale_price')
                  ->whereColumn('sale_price', '<', 'price');
            }])
            ->having('on_sale_count', '>', 0)
            ->get();

        // Estadísticas para el hero banner
        $stats = Product::active()->onSale()->selectRaw('
            COUNT(*) as total_offers,
            MAX(ROUND(((price - sale_price) / price) * 100)) as max_discount,
            MIN(sale_price) as min_price
        ')->first();

        // Conteo por tier de descuento para chips
        $discountTiers = Product::active()->onSale()->selectRaw("
            CASE
                WHEN ROUND(((price - sale_price) / price) * 100) >= 50 THEN '50+'
                WHEN ROUND(((price - sale_price) / price) * 100) >= 30 THEN '30-49'
                WHEN ROUND(((price - sale_price) / price) * 100) >= 15 THEN '15-29'
                ELSE '1-14'
            END as tier,
            COUNT(*) as count
        ")->groupBy('tier')->pluck('count', 'tier');

        // Rango de precios para el slider
        $priceRange = Product::active()->onSale()->selectRaw('
            MIN(sale_price) as min_price, MAX(sale_price) as max_price
        ')->first();

        return view('ofertas', compact(
            'products', 'categories', 'stats', 'discountTiers', 'priceRange'
        ));
    }
}
