<?php

namespace App\Services\Reports;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductReportService
{
    public function getData(string $from, string $to): array
    {
        $dateRange = [$from, $to . ' 23:59:59'];

        return [
            'totalProducts' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
            'outOfStock' => Product::where('stock', 0)->where('is_active', true)->count(),
            'lowStock' => Product::whereBetween('stock', [1, 5])->where('is_active', true)->count(),
            'lowStockProducts' => $this->getLowStockProducts(),
            'bestSellers' => $this->getBestSellers($dateRange),
            'mostWished' => $this->getMostWished(),
            'bestRated' => $this->getBestRated(),
            'noSalesProducts' => $this->getNoSalesProducts($dateRange),
        ];
    }

    private function getLowStockProducts()
    {
        return Product::with('primaryImage')
            ->where('is_active', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')->get();
    }

    private function getBestSellers(array $dateRange)
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

    private function getMostWished()
    {
        return Product::withCount('wishlists')
            ->having('wishlists_count', '>', 0)
            ->orderByDesc('wishlists_count')->limit(10)->get();
    }

    private function getBestRated()
    {
        return Product::withCount(['reviews' => fn($q) => $q->where('is_approved', true)])
            ->withAvg(['reviews' => fn($q) => $q->where('is_approved', true)], 'rating')
            ->having('reviews_count', '>=', 3)
            ->orderByDesc('reviews_avg_rating')->limit(10)->get();
    }

    private function getNoSalesProducts(array $dateRange)
    {
        $soldIds = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', $dateRange)
            ->where('orders.status', '!=', 'cancelado')
            ->whereNotNull('order_items.product_id')
            ->distinct()
            ->pluck('order_items.product_id');

        return Product::where('is_active', true)
            ->whereNotIn('id', $soldIds)
            ->with('primaryImage')->limit(10)->get();
    }
}
