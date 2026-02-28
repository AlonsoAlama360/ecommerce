<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryProductController extends Controller
{
    public function index(string $slug)
    {
        $products = Cache::remember("cat_products_{$slug}", 1800, function () use ($slug) {
            $category = Category::where('slug', $slug)->firstOrFail();

            return $category->activeProducts()
                ->with('primaryImage:id,product_id,image_url,alt_text')
                ->select('id', 'category_id', 'name', 'slug', 'price', 'sale_price')
                ->limit(4)
                ->get()
                ->map(fn($p) => [
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => $p->current_price,
                    'image' => $p->primaryImage?->image_url ?? '',
                ]);
        });

        return response()->json($products);
    }
}
