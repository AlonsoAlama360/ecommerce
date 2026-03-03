<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class SalesReportService
{
    public function getData(string $from, string $to): array
    {
        $dateRange = [$from, $to . ' 23:59:59'];

        $ordersQuery = Order::whereBetween('created_at', $dateRange);
        $totalOrders = (clone $ordersQuery)->count();
        $cancelledOrders = (clone $ordersQuery)->where('status', 'cancelado')->count();
        $validQuery = (clone $ordersQuery)->where('status', '!=', 'cancelado');

        return [
            'totalRevenue' => (clone $validQuery)->sum('total'),
            'totalDiscount' => (clone $validQuery)->sum('discount_amount'),
            'totalOrders' => $totalOrders,
            'cancelledOrders' => $cancelledOrders,
            'avgTicket' => $totalOrders > 0 ? (clone $validQuery)->avg('total') : 0,
            'cancellationRate' => $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 1) : 0,
            'revenueByDay' => $this->getRevenueByDay($dateRange),
            'byPaymentMethod' => $this->getByPaymentMethod($dateRange),
            'byStatus' => $this->getByStatus($dateRange),
            'topProducts' => $this->getTopProducts($dateRange),
            'bySource' => $this->getBySource($dateRange),
        ];
    }

    private function getRevenueByDay(array $dateRange)
    {
        return Order::whereBetween('created_at', $dateRange)
            ->where('status', '!=', 'cancelado')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders_count'))
            ->groupBy('date')->orderBy('date')->get();
    }

    private function getByPaymentMethod(array $dateRange)
    {
        return Order::whereBetween('created_at', $dateRange)
            ->where('status', '!=', 'cancelado')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')->get();
    }

    private function getByStatus(array $dateRange)
    {
        return Order::whereBetween('created_at', $dateRange)
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('status')->get();
    }

    private function getTopProducts(array $dateRange)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', $dateRange)
            ->where('orders.status', '!=', 'cancelado')
            ->select(
                'order_items.product_id', 'order_items.product_name', 'order_items.product_sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.line_total) as total_revenue')
            )
            ->groupBy('order_items.product_id', 'order_items.product_name', 'order_items.product_sku')
            ->orderByDesc('total_quantity')->limit(10)->get();
    }

    private function getBySource(array $dateRange)
    {
        return Order::whereBetween('created_at', $dateRange)
            ->where('status', '!=', 'cancelado')
            ->select('source', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('source')->get();
    }
}
