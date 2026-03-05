<?php

namespace App\Domain\Purchase\Repositories;

use App\Models\Purchase;
use Illuminate\Support\Collection;

interface PurchaseRepositoryInterface
{
    public function findById(int $id): ?Purchase;

    public function create(array $data): Purchase;

    public function update(Purchase $purchase, array $data): Purchase;

    public function delete(Purchase $purchase): void;

    public function paginate(array $filters, int $perPage = 10): mixed;

    public function getStats(): object;

    public function getActiveSuppliers(): Collection;

    public function searchSuppliers(string $search, int $limit = 10): Collection;

    public function searchProducts(string $search, int $limit = 10): Collection;
}
