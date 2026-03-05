<?php

namespace App\Application\Catalog\UseCases;

use App\Application\Catalog\DTOs\CatalogFiltersDTO;
use App\Domain\Catalog\Repositories\CatalogRepositoryInterface;

class ListCatalog
{
    public function __construct(
        private CatalogRepositoryInterface $catalogRepository,
    ) {}

    public function execute(CatalogFiltersDTO $dto): array
    {
        $filters = [
            'categories' => $dto->categories,
            'price_min' => $dto->priceMin,
            'price_max' => $dto->priceMax,
            'in_stock' => $dto->inStock,
            'on_sale' => $dto->onSale,
            'sort' => $dto->sort,
        ];

        return [
            'products' => $this->catalogRepository->paginate($filters),
            'categories' => $this->catalogRepository->getCategories(),
            'priceRange' => $this->catalogRepository->getPriceRange(),
        ];
    }
}
