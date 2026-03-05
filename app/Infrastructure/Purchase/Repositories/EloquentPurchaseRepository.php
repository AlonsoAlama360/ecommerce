<?php

namespace App\Infrastructure\Purchase\Repositories;

use App\Domain\Purchase\Repositories\PurchaseRepositoryInterface;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentPurchaseRepository implements PurchaseRepositoryInterface
{
    public function findById(int $id): ?Purchase
    {
        return Purchase::find($id);
    }

    public function create(array $data): Purchase
    {
        return Purchase::create($data);
    }

    public function update(Purchase $purchase, array $data): Purchase
    {
        $purchase->update($data);
        return $purchase->fresh();
    }

    public function delete(Purchase $purchase): void
    {
        $purchase->delete();
    }

    public function paginate(array $filters, int $perPage = 10): mixed
    {
        $query = Purchase::with(['supplier', 'items']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('business_name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getStats(): object
    {
        return DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(created_at >= ?) as today,
                SUM(CASE WHEN created_at >= ? AND status != 'cancelado' THEN total ELSE 0 END) as monthly_spending,
                SUM(status = 'pendiente') as pending
            FROM purchases WHERE deleted_at IS NULL
        ", [today()->toDateTimeString(), now()->startOfMonth()->toDateTimeString()]);
    }

    public function getActiveSuppliers(): Collection
    {
        return Supplier::where('is_active', true)->orderBy('business_name')->get(['id', 'business_name']);
    }

    public function searchSuppliers(string $search, int $limit = 10): Collection
    {
        return Supplier::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('ruc', 'like', "%{$search}%");
            })
            ->take($limit)
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'business_name' => $s->business_name,
                'contact_name' => $s->contact_name,
                'ruc' => $s->ruc,
                'phone' => $s->phone,
            ]);
    }

    public function searchProducts(string $search, int $limit = 10): Collection
    {
        return Product::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })
            ->with('primaryImage')
            ->take($limit)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => $p->current_price,
                'stock' => $p->stock,
                'image' => $p->primaryImage?->image_url,
            ]);
    }
}
