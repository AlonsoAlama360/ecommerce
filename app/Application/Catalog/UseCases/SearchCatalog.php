<?php

namespace App\Application\Catalog\UseCases;

use App\Domain\Catalog\Repositories\CatalogRepositoryInterface;

class SearchCatalog
{
    public function __construct(
        private CatalogRepositoryInterface $catalogRepository,
    ) {}

    public function execute(string $query): array
    {
        if (strlen(trim($query)) < 2) {
            return ['products' => [], 'categories' => []];
        }

        return $this->catalogRepository->search(trim($query));
    }
}
