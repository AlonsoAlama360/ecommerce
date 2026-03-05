<?php

namespace App\Application\Category\UseCases;

use App\Application\Category\DTOs\CategoryFiltersDTO;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class ListCategories
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function execute(CategoryFiltersDTO $dto): array
    {
        $filters = [
            'search' => $dto->search,
            'status' => $dto->status,
        ];

        $categories = $this->categoryRepository->paginate($filters, $dto->perPage);
        $stats = $this->categoryRepository->getStats();

        return [
            'categories' => $categories,
            'totalCategories' => (int) $stats->total,
            'activeCategories' => (int) ($stats->active ?? 0),
            'inactiveCategories' => (int) ($stats->inactive ?? 0),
            'totalProducts' => (int) $stats->total_products,
        ];
    }
}
