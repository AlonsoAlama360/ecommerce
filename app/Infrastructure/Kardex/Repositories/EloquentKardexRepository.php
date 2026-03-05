<?php

namespace App\Infrastructure\Kardex\Repositories;

use App\Domain\Kardex\Repositories\KardexRepositoryInterface;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentKardexRepository implements KardexRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 15): mixed
    {
        $query = StockMovement::with(['product.primaryImage', 'creator']);

        if (!empty($filters['product_id'])) {
            $query->byProduct($filters['product_id']);
        }

        if (!empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (!empty($filters['date_from']) || !empty($filters['date_to'])) {
            $query->byDateRange($filters['date_from'], $filters['date_to']);
        }

        return $query->latest('created_at')->paginate($perPage)->withQueryString();
    }

    public function paginateByProduct(int $productId, array $filters, int $perPage = 15): mixed
    {
        $query = StockMovement::with('creator')->byProduct($productId);

        if (!empty($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (!empty($filters['date_from']) || !empty($filters['date_to'])) {
            $query->byDateRange($filters['date_from'], $filters['date_to']);
        }

        return $query->latest('created_at')->paginate($perPage)->withQueryString();
    }

    public function getMonthlyStats(): object
    {
        $todayStr = today()->toDateTimeString();
        $monthStr = now()->startOfMonth()->toDateTimeString();

        $result = DB::selectOne(
            "SELECT
                SUM(created_at >= ?) as today,
                SUM(type = 'entrada' AND created_at >= ?) as entries_month,
                SUM(type = 'salida' AND created_at >= ?) as exits_month,
                SUM(type = 'ajuste' AND created_at >= ?) as adjustments_month
            FROM stock_movements",
            [$todayStr, $monthStr, $monthStr, $monthStr]
        );

        return (object) [
            'movementsToday' => (int) ($result->today ?? 0),
            'entriesMonth' => (int) ($result->entries_month ?? 0),
            'exitsMonth' => (int) ($result->exits_month ?? 0),
            'adjustmentsMonth' => (int) ($result->adjustments_month ?? 0),
        ];
    }

    public function getProductStats(int $productId): object
    {
        $result = DB::selectOne(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN type = 'entrada' THEN quantity ELSE 0 END) as entries,
                SUM(CASE WHEN type = 'salida' THEN quantity ELSE 0 END) as exits,
                SUM(type = 'ajuste') as adjustments
            FROM stock_movements
            WHERE product_id = ?",
            [$productId]
        );

        return (object) [
            'totalMovements' => (int) ($result->total ?? 0),
            'totalEntries' => (int) ($result->entries ?? 0),
            'totalExits' => (int) ($result->exits ?? 0),
            'totalAdjustments' => (int) ($result->adjustments ?? 0),
        ];
    }

    public function getActiveProducts(): Collection
    {
        return Product::where('is_active', true)
            ->orderBy('name')
            ->limit(200)
            ->get(['id', 'name', 'sku']);
    }

    public function searchProducts(string $search, int $limit = 10): Collection
    {
        return Product::where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->with('primaryImage')
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name', 'sku', 'stock']);
    }
}
