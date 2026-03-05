<?php

namespace App\Application\Product\UseCases;

use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\ProductImage;

class UpdateProductImage
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(Product $product, ProductImage $image, array $data): ProductImage
    {
        if (!empty($data['is_primary'])) {
            $this->productRepository->setImageAsPrimary($product, $image);
            unset($data['is_primary']);
        }

        if (!empty($data)) {
            return $this->productRepository->updateImage($image, $data);
        }

        return $image->fresh();
    }
}
