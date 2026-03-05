<?php

namespace App\Domain\Supplier\Repositories;

use App\Models\Supplier;
use Illuminate\Support\Collection;

interface SupplierRepositoryInterface
{
    public function findById(int $id): ?Supplier;

    public function create(array $data): Supplier;

    public function update(Supplier $supplier, array $data): Supplier;

    public function delete(Supplier $supplier): void;

    public function paginate(array $filters, int $perPage = 10): mixed;

    public function getStats(): object;

    public function getDistinctCities(): Collection;
}
