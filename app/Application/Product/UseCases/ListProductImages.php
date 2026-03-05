<?php

namespace App\Application\Product\UseCases;

use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Collection;

class ListProductImages
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(Product $product): Collection
    {
        return $this->productRepository->getImages($product);
    }
}
