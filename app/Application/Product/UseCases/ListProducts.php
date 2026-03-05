<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTOs\ProductFiltersDTO;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\Category;

class ListProducts
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(ProductFiltersDTO $dto): array
    {
        $filters = [
            'search' => $dto->search,
            'category' => $dto->category,
            'status' => $dto->status,
            'featured' => $dto->featured,
            'stock' => $dto->stock,
        ];

        $products = $this->productRepository->paginate($filters, $dto->perPage);
        $stats = $this->productRepository->getStats();
        $categories = Category::ordered()->get();

        return [
            'products' => $products,
            'totalProducts' => (int) $stats->total,
            'activeProducts' => (int) ($stats->active ?? 0),
            'featuredProducts' => (int) ($stats->featured ?? 0),
            'outOfStock' => (int) ($stats->out_of_stock ?? 0),
            'categories' => $categories,
        ];
    }
}
