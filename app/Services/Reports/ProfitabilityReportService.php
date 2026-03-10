<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;

class ProfitabilityReportService
{
    public function getData(string $from, string $to): array
    {
        $dateRange = [$from, $to . ' 23:59:59'];

        $totalRevenue = Order::whereBetween('created_at', $dateRange)
            ->where('status', '!=', 'cancelado')->sum('total');

        $costMap = $this->getCostMap();
        $revenueVsCostByMonth = $this->getRevenueVsCostByMonth($dateRange, $costMap);
        $totalCost = collect($revenueVsCostByMonth)->sum('cost');
        $grossProfit = $totalRevenue - $totalCost;
        $productProfitability = $this->getProductProfitability($dateRange, $costMap);

        return [
            'totalRevenue' => $totalRevenue,
            'totalCost' => $totalCost,
            'grossProfit' => $grossProfit,
            'profitMargin' => $totalRevenue > 0 ? round(($grossProfit / $totalRevenue) * 100, 1) : 0,
            'revenueVsCostByMonth' => $revenueVsCostByMonth,
            'categoryProfitability' => $this->getCategoryProfitability($dateRange),
            'productProfitability' => $productProfitability,
        ];
    }

    private function getCostMap(): \Illuminate\Support\Collection
    {
        $lastCosts = PurchaseItem::join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->where('purchases.status', '!=', 'cancelado')
            ->whereNotNull('purchase_items.product_id')
            ->select('purchase_items.product_id', DB::raw('MAX(purchases.created_at) as last_purchase'))
            ->groupBy('purchase_items.product_id');

        return PurchaseItem::join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->joinSub($lastCosts, 'latest', function ($join) {
                $join->on('purchase_items.product_id', '=', 'latest.product_id')
                    ->on('purchases.created_at', '=', 'latest.last_purchase');
            })
            ->pluck('purchase_items.unit_cost', 'purchase_items.product_id');
    }

    private function getProductProfitability(array $dateRange, \Illuminate\Support\Collection $costMap)
    {
        $sales = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', $dateRange)
            ->where('orders.status', '!=', 'cancelado')
            ->whereNotNull('order_items.product_id')
            ->select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('SUM(order_items.line_total) as total_revenue'),
                DB::raw('AVG(order_items.unit_price) as avg_sale_price')
            )
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return $sales->map(function ($item) use ($costMap) {
            $unitCost = $costMap[$item->product_id] ?? ($item->avg_sale_price * 0.6);
            $totalCost = $unitCost * $item->units_sold;
            $profit = $item->total_revenue - $totalCost;

            $item->unit_cost = $unitCost;
            $item->total_cost = $totalCost;
            $item->profit = $profit;
            $item->margin = $item->total_revenue > 0 ? round(($profit / $item->total_revenue) * 100, 1) : 0;

            return $item;
        });
    }

    private function getRevenueVsCostByMonth(array $dateRange, \Illuminate\Support\Collection $costMap)
    {
        // Revenue by month
        $revenueByMonth = Order::whereBetween('created_at', $dateRange)
            ->where('status', '!=', 'cancelado')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(total) as revenue'))
            ->groupBy('month')->orderBy('month')->get()->keyBy('month');

        // COGS by month (cost of goods SOLD, not purchased)
        $salesByMonth = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', $dateRange)
            ->where('orders.status', '!=', 'cancelado')
            ->select(
                DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as month"),
                'order_items.product_id',
                DB::raw('SUM(order_items.quantity) as qty'),
                DB::raw('AVG(order_items.unit_price) as avg_price')
            )
            ->groupBy('month', 'order_items.product_id')
            ->get();

        $cogsByMonth = [];
        foreach ($salesByMonth as $row) {
            $unitCost = $costMap[$row->product_id] ?? ($row->avg_price * 0.6);
            $cogsByMonth[$row->month] = ($cogsByMonth[$row->month] ?? 0) + ($unitCost * $row->qty);
        }

        $months = $revenueByMonth->keys()->merge(collect(array_keys($cogsByMonth)))->unique()->sort()->values();

        return $months->map(fn($m) => [
            'month' => $m,
            'revenue' => (float) ($revenueByMonth[$m]->revenue ?? 0),
            'cost' => round($cogsByMonth[$m] ?? 0, 2),
        ]);
    }

    private function getCategoryProfitability(array $dateRange)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', $dateRange)
            ->where('orders.status', '!=', 'cancelado')
            ->select(
                'categories.name as category',
                DB::raw('SUM(order_items.line_total) as revenue'),
                DB::raw('SUM(order_items.quantity) as units_sold')
            )
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();
    }
}
