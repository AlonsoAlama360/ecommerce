<?php

namespace App\Application\Product\UseCases;

use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\ProductImage;

class DeleteProductImage
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(ProductImage $image): void
    {
        $this->productRepository->deleteImage($image);
    }
}
