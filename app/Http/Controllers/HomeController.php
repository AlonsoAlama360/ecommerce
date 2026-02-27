<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->inStock()
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->select('id', 'category_id', 'name', 'slug', 'short_description', 'price', 'sale_price', 'stock')
            ->limit(8)
            ->get();

        $newArrivals = Product::active()
            ->inStock()
            ->with(['primaryImage:id,product_id,image_url,alt_text', 'category:id,name'])
            ->select('id', 'category_id', 'name', 'slug', 'short_description', 'price', 'sale_price', 'stock')
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::active()
            ->ordered()
            ->select('id', 'name', 'slug', 'icon')
            ->get();

        $reviews = Review::approved()
            ->featured()
            ->with(['user:id,first_name,last_name', 'product:id,name,slug'])
            ->latest()
            ->limit(6)
            ->get();

        return view('home', compact('featuredProducts', 'newArrivals', 'categories', 'reviews'));
    }
}
