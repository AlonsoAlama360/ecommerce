<?php

namespace App\Services\Reports;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryReportService
{
    public function getData(string $from, string $to): array
    {
        $dateRange = [$from, $to . ' 23:59:59'];
        $days = max(1, now()->parse($from)->diffInDays(now()->parse($to)));

        $activeProducts = Product::where('is_active', true);
        $inventoryValue = (clone $activeProducts)->sum(DB::raw('stock * COALESCE(sale_price, price)'));

        $unitsSold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', $dateRange)
            ->where('orders.status', '!=', 'cancelado')
            ->sum('order_items.quantity');

        $avgStock = (clone $activeProducts)->avg('stock') ?: 1;
        $turnoverRate = $avgStock > 0 ? round($unitsSold / $avgStock, 2) : 0;

        $deadStock = $this->getDeadStock();

        return [
            'inventoryValue' => $inventoryValue,
            'totalActiveProducts' => (clone $activeProducts)->count(),
            'unitsSold' => $unitsSold,
            'turnoverRate' => $turnoverRate,
            'deadStockCount' => $deadStock->count(),
            'deadStock' => $deadStock,
            'movementsByType' => $this->getMovementsByType($dateRange),
            'topByValue' => $this->getTopByValue(),
            'fastMovers' => $this->getFastMovers($dateRange, $days),
            'slowMovers' => $this->getSlowMovers($dateRange, $days),
        ];
    }

    private function getDeadStock()
    {
        $recentSoldIds = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', now()->subDays(60))
            ->where('orders.status', '!=', 'cancelado')
            ->whereNotNull('order_items.product_id')
            ->distinct()
            ->pluck('order_items.product_id');

        return Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotIn('id', $recentSoldIds)
            ->with('primaryImage')
            ->select('products.*')
            ->selectSub(
                OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereColumn('order_items.product_id', 'products.id')
                    ->where('orders.status', '!=', 'cancelado')
                    ->select(DB::raw('MAX(orders.created_at)'))
                    ->limit(1),
                'last_sale_date'
            )
            ->orderBy('stock', 'desc')
            ->limit(15)
            ->get();
    }

    private function getMovementsByType(array $dateRange)
    {
        return StockMovement::whereBetween('created_at', $dateRange)
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(ABS(quantity)) as total_units'))
            ->groupBy('type')
            ->get();
    }

    private function getTopByValue()
    {
        return Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->select('name', 'sku', 'stock', DB::raw('COALESCE(sale_price, price) as unit_price'), DB::raw('stock * COALESCE(sale_price, price) as inventory_value'))
            ->orderByDesc('inventory_value')
            ->limit(10)
            ->get();
    }

    private function getFastMovers(array $dateRange, int $days)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', $dateRange)
            ->where('orders.status', '!=', 'cancelado')
            ->where('products.is_active', true)
            ->select(
                'products.name', 'products.sku', 'products.stock',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('ROUND(SUM(order_items.quantity) / ' . $days . ', 2) as daily_rate')
            )
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.stock')
            ->orderByDesc('units_sold')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $item->days_of_stock = $item->daily_rate > 0 ? round($item->stock / $item->daily_rate) : null;
                return $item;
            });
    }

    private function getSlowMovers(array $dateRange, int $days)
    {
        $soldIds = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', $dateRange)
            ->where('orders.status', '!=', 'cancelado')
            ->whereNotNull('order_items.product_id')
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as units_sold'))
            ->groupBy('order_items.product_id');

        return Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->leftJoinSub($soldIds, 'sales', fn($join) => $join->on('products.id', '=', 'sales.product_id'))
            ->select('products.name', 'products.sku', 'products.stock', DB::raw('COALESCE(sales.units_sold, 0) as units_sold'))
            ->orderBy('units_sold')
            ->limit(10)
            ->get()
            ->map(function ($item) use ($days) {
                $dailyRate = $days > 0 ? $item->units_sold / $days : 0;
                $item->daily_rate = round($dailyRate, 2);
                $item->days_of_stock = $dailyRate > 0 ? round($item->stock / $dailyRate) : null;
                return $item;
            });
    }
}
