<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with([
                'images' => fn($q) => $q->orderBy('sort_order'),
                'category:id,name,slug',
            ])
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->select('id', 'category_id', 'name', 'slug', 'price', 'sale_price')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('product-detail', compact('product', 'relatedProducts'));
    }
}
