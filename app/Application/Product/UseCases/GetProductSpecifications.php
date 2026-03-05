<?php

namespace App\Application\Product\UseCases;

use App\Models\Product;

class GetProductSpecifications
{
    public function execute(Product $product): array
    {
        return $product->specifications ?? [];
    }
}
