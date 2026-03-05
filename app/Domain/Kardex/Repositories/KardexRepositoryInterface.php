<?php

namespace App\Domain\Kardex\Repositories;

use Illuminate\Support\Collection;

interface KardexRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): mixed;

    public function paginateByProduct(int $productId, array $filters, int $perPage = 15): mixed;

    public function getMonthlyStats(): object;

    public function getProductStats(int $productId): object;

    public function getActiveProducts(): Collection;

    public function searchProducts(string $search, int $limit = 10): Collection;
}
