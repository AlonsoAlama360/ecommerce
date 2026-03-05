<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTOs\CreateProductImageDTO;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\ProductImage;

class CreateProductImage
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(CreateProductImageDTO $dto, Product $product): ProductImage
    {
        return $this->productRepository->createImage($product, [
            'image_url' => $dto->imageUrl,
            'thumbnail_url' => $dto->thumbnailUrl,
            'alt_text' => $dto->altText,
            'is_primary' => $dto->isPrimary,
        ]);
    }
}
