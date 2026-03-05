<?php

namespace App\Domain\Catalog\Repositories;

use Illuminate\Support\Collection;

interface CatalogRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 12): mixed;

    public function getCategories(): Collection;

    public function getPriceRange(): object;

    public function search(string $query, int $limit = 6): array;
}
