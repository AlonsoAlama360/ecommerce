<?php

namespace App\Infrastructure\Supplier\Repositories;

use App\Domain\Supplier\Repositories\SupplierRepositoryInterface;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentSupplierRepository implements SupplierRepositoryInterface
{
    public function findById(int $id): ?Supplier
    {
        return Supplier::find($id);
    }

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);
        return $supplier->fresh();
    }

    public function delete(Supplier $supplier): void
    {
        $supplier->delete();
    }

    public function paginate(array $filters, int $perPage = 10): mixed
    {
        $query = Supplier::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ruc', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== null) {
            $query->where('is_active', $filters['status']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getStats(): object
    {
        return DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(is_active = 1) as active,
                SUM(is_active = 0) as inactive,
                SUM(created_at >= ?) as new_week
            FROM suppliers WHERE deleted_at IS NULL
        ", [now()->subWeek()->toDateTimeString()]);
    }

    public function getDistinctCities(): Collection
    {
        return Supplier::whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->limit(100)
            ->pluck('city');
    }
}
