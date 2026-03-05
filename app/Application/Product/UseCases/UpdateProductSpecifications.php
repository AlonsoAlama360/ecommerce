<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTOs\UpdateSpecificationsDTO;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\Product;

class UpdateProductSpecifications
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(UpdateSpecificationsDTO $dto, Product $product): array
    {
        $this->productRepository->update($product, [
            'specifications' => $dto->specifications,
        ]);

        return $product->fresh()->specifications;
    }
}
