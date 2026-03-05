<?php

namespace App\Http\Controllers;

use App\Application\Product\UseCases\ShowProduct;

class ProductController extends Controller
{
    public function show(string $slug, ShowProduct $showProduct)
    {
        $data = $showProduct->execute($slug);

        return view('product-detail', $data);
    }
}
