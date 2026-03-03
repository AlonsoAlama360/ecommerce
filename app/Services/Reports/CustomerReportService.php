<?php

namespace App\Services\Reports;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerReportService
{
    public function getData(string $from, string $to): array
    {
        $dateRange = [$from, $to . ' 23:59:59'];

        return [
            'totalCustomers' => User::where('role', 'cliente')->count(),
            'newCustomers' => User::where('role', 'cliente')->whereBetween('created_at', $dateRange)->count(),
            'withOrders' => User::where('role', 'cliente')->whereHas('orders')->count(),
            'newsletterSubs' => User::where('newsletter', true)->count(),
            'registrationsByDay' => $this->getRegistrationsByDay($dateRange),
            'topBuyers' => $this->getTopBuyers($from, $to),
            'customersWithMultipleOrders' => $this->getRecurringCount(),
            'customersWithOneOrder' => $this->getSingleOrderCount(),
            'topDepartments' => $this->getTopDepartments(),
        ];
    }

    private function getRegistrationsByDay(array $dateRange)
    {
        return User::where('role', 'cliente')
            ->whereBetween('created_at', $dateRange)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')->orderBy('date')->get();
    }

    private function getTopBuyers(string $from, string $to)
    {
        $dateRange = [$from, $to . ' 23:59:59'];
        $orderFilter = fn($q) => $q->whereBetween('created_at', $dateRange)->where('status', '!=', 'cancelado');

        return User::where('role', 'cliente')
            ->whereHas('orders', $orderFilter)
            ->withCount(['orders' => $orderFilter])
            ->withSum(['orders' => $orderFilter], 'total')
            ->orderByDesc('orders_sum_total')->limit(10)->get();
    }

    private function getRecurringCount(): int
    {
        return User::where('role', 'cliente')
            ->whereHas('orders', fn($q) => $q->where('status', '!=', 'cancelado'), '>=', 2)
            ->count();
    }

    private function getSingleOrderCount(): int
    {
        return User::where('role', 'cliente')
            ->whereHas('orders', fn($q) => $q->where('status', '!=', 'cancelado'), '=', 1)
            ->count();
    }

    private function getTopDepartments()
    {
        return User::where('role', 'cliente')
            ->whereNotNull('department_id')
            ->join('departments', 'users.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('COUNT(*) as count'))
            ->groupBy('departments.name')
            ->orderByDesc('count')->limit(10)->get();
    }
}
