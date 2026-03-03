<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TrendsReportService
{
    public function getData(string $from, string $to): array
    {
        $dateRange = [$from, $to . ' 23:59:59'];

        $hourly = $this->getHourlyPattern($dateRange);
        $daily = $this->getDailyPattern($dateRange);
        $comparison = $this->getMonthComparison();

        $peakHour = $hourly->sortByDesc('orders_count')->first();
        $peakDay = $daily->sortByDesc('orders_count')->first();

        return [
            'hourlyPattern' => $hourly,
            'dailyPattern' => $daily,
            'comparison' => $comparison,
            'peakHour' => $peakHour,
            'peakDay' => $peakDay,
            'revenueGrowth' => $comparison['revenueGrowth'],
            'ordersGrowth' => $comparison['ordersGrowth'],
        ];
    }

    private function getHourlyPattern(array $dateRange)
    {
        return Order::whereBetween('created_at', $dateRange)
            ->where('status', '!=', 'cancelado')
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('AVG(total) as avg_ticket')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }

    private function getDailyPattern(array $dateRange)
    {
        $dayNames = ['', 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return Order::whereBetween('created_at', $dateRange)
            ->where('status', '!=', 'cancelado')
            ->select(
                DB::raw('DAYOFWEEK(created_at) as day_number'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('AVG(total) as avg_ticket')
            )
            ->groupBy('day_number')
            ->orderBy('day_number')
            ->get()
            ->map(function ($item) use ($dayNames) {
                $item->day_name = $dayNames[$item->day_number] ?? '';
                return $item;
            });
    }

    private function getMonthComparison(): array
    {
        $currentStart = now()->startOfMonth();
        $currentEnd = now();
        $previousStart = now()->subMonth()->startOfMonth();
        $previousEnd = now()->subMonth()->endOfMonth();

        $current = $this->getMonthMetrics($currentStart, $currentEnd);
        $previous = $this->getMonthMetrics($previousStart, $previousEnd);

        $revenueGrowth = $previous['revenue'] > 0
            ? round((($current['revenue'] - $previous['revenue']) / $previous['revenue']) * 100, 1)
            : ($current['revenue'] > 0 ? 100 : 0);

        $ordersGrowth = $previous['orders'] > 0
            ? round((($current['orders'] - $previous['orders']) / $previous['orders']) * 100, 1)
            : ($current['orders'] > 0 ? 100 : 0);

        return [
            'current' => $current,
            'previous' => $previous,
            'currentLabel' => $currentStart->translatedFormat('F Y'),
            'previousLabel' => $previousStart->translatedFormat('F Y'),
            'revenueGrowth' => $revenueGrowth,
            'ordersGrowth' => $ordersGrowth,
        ];
    }

    private function getMonthMetrics($start, $end): array
    {
        $query = Order::whereBetween('created_at', [$start, $end->endOfDay()])
            ->where('status', '!=', 'cancelado');

        return [
            'revenue' => (clone $query)->sum('total'),
            'orders' => (clone $query)->count(),
            'avgTicket' => (clone $query)->avg('total') ?? 0,
            'newCustomers' => User::where('role', 'cliente')
                ->whereBetween('created_at', [$start, $end->endOfDay()])->count(),
        ];
    }
}
