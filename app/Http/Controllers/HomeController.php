<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->inStock()
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->select('id', 'category_id', 'name', 'slug', 'price', 'sale_price')
            ->limit(8)
            ->get();

        $categories = Category::active()
            ->ordered()
            ->select('id', 'name', 'slug', 'icon')
            ->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
