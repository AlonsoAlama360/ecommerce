<?php

namespace App\Services\Reports;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;

class PurchaseReportService
{
    public function getData(string $from, string $to): array
    {
        $dateRange = [$from, $to . ' 23:59:59'];

        $query = Purchase::whereBetween('created_at', $dateRange);
        $totalPurchases = (clone $query)->count();
        $cancelledPurchases = (clone $query)->where('status', 'cancelado')->count();
        $validQuery = (clone $query)->where('status', '!=', 'cancelado');

        return [
            'totalPurchases' => $totalPurchases,
            'cancelledPurchases' => $cancelledPurchases,
            'totalSpending' => (clone $validQuery)->sum('total'),
            'totalTax' => (clone $validQuery)->sum('tax_amount'),
            'avgPurchase' => $totalPurchases > 0 ? (clone $validQuery)->avg('total') : 0,
            'pendingPurchases' => (clone $query)->where('status', 'pendiente')->count(),
            'spendingByDay' => $this->getSpendingByDay($dateRange),
            'byStatus' => $this->getByStatus($dateRange),
            'topSuppliers' => $this->getTopSuppliers($dateRange),
            'topProductsPurchased' => $this->getTopProducts($dateRange),
            'recentPurchases' => Purchase::with('supplier')->whereBetween('created_at', $dateRange)->latest()->limit(10)->get(),
            'pendingDeliveries' => Purchase::with('supplier')->whereIn('status', ['aprobado', 'en_transito'])->orderBy('expected_date')->limit(10)->get(),
        ];
    }

    private function getSpendingByDay(array $dateRange)
    {
        return Purchase::whereBetween('created_at', $dateRange)
            ->where('status', '!=', 'cancelado')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as spending'), DB::raw('COUNT(*) as purchases_count'))
            ->groupBy('date')->orderBy('date')->get();
    }

    private function getByStatus(array $dateRange)
    {
        return Purchase::whereBetween('created_at', $dateRange)
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('status')->get();
    }

    private function getTopSuppliers(array $dateRange)
    {
        return Purchase::whereBetween('purchases.created_at', $dateRange)
            ->where('purchases.status', '!=', 'cancelado')
            ->whereNotNull('purchases.supplier_id')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->select('suppliers.id', 'suppliers.business_name', DB::raw('COUNT(*) as purchases_count'), DB::raw('SUM(purchases.total) as total_amount'))
            ->groupBy('suppliers.id', 'suppliers.business_name')
            ->orderByDesc('total_amount')->limit(10)->get();
    }

    private function getTopProducts(array $dateRange)
    {
        return PurchaseItem::join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->whereBetween('purchases.created_at', $dateRange)
            ->where('purchases.status', '!=', 'cancelado')
            ->select(
                'purchase_items.product_id', 'purchase_items.product_name', 'purchase_items.product_sku',
                DB::raw('SUM(purchase_items.quantity) as total_quantity'),
                DB::raw('SUM(purchase_items.line_total) as total_cost')
            )
            ->groupBy('purchase_items.product_id', 'purchase_items.product_name', 'purchase_items.product_sku')
            ->orderByDesc('total_quantity')->limit(10)->get();
    }
}
