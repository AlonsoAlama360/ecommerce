<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GeographicReportService
{
    public function getData(string $from, string $to): array
    {
        $dateRange = [$from, $to . ' 23:59:59'];

        $departmentSales = $this->getDepartmentSales($dateRange);
        $totalRevenue = $departmentSales->sum('revenue');

        return [
            'departmentSales' => $departmentSales,
            'totalRevenue' => $totalRevenue,
            'departmentsWithOrders' => $departmentSales->count(),
            'avgTicketNational' => Order::whereBetween('created_at', $dateRange)
                ->where('status', '!=', 'cancelado')->avg('total') ?? 0,
            'topDepartment' => $departmentSales->first(),
            'customerDistribution' => $this->getCustomerDistribution(),
        ];
    }

    private function getDepartmentSales(array $dateRange)
    {
        return Order::join('users', 'orders.user_id', '=', 'users.id')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->whereBetween('orders.created_at', $dateRange)
            ->where('orders.status', '!=', 'cancelado')
            ->whereNotNull('users.department_id')
            ->select(
                'departments.name as department',
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('COUNT(DISTINCT orders.user_id) as customers_count'),
                DB::raw('SUM(orders.total) as revenue'),
                DB::raw('AVG(orders.total) as avg_ticket')
            )
            ->groupBy('departments.name')
            ->orderByDesc('revenue')
            ->get();
    }

    private function getCustomerDistribution()
    {
        return User::where('role', 'cliente')
            ->whereNotNull('department_id')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->select('departments.name as department', DB::raw('COUNT(*) as count'))
            ->groupBy('departments.name')
            ->orderByDesc('count')
            ->get();
    }
}
