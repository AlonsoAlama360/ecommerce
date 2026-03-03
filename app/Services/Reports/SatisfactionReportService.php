<?php

namespace App\Services\Reports;

use App\Models\Complaint;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class SatisfactionReportService
{
    public function getData(string $from, string $to): array
    {
        $dateRange = [$from, $to . ' 23:59:59'];

        $totalReviews = Review::whereBetween('created_at', $dateRange)->count();
        $approvedReviews = Review::whereBetween('created_at', $dateRange)->where('is_approved', true)->count();
        $avgRating = Review::whereBetween('created_at', $dateRange)->where('is_approved', true)->avg('rating') ?? 0;

        $totalOrders = Order::whereBetween('created_at', $dateRange)->count();
        $totalComplaints = Complaint::whereBetween('created_at', $dateRange)->count();

        $avgResolution = Complaint::whereNotNull('response_date')
            ->whereBetween('created_at', $dateRange)
            ->select(DB::raw('AVG(DATEDIFF(response_date, created_at)) as avg_days'))
            ->value('avg_days') ?? 0;

        return [
            'avgRating' => round($avgRating, 1),
            'totalReviews' => $totalReviews,
            'approvalRate' => $totalReviews > 0 ? round(($approvedReviews / $totalReviews) * 100, 1) : 0,
            'complaintRate' => $totalOrders > 0 ? round(($totalComplaints / $totalOrders) * 100, 1) : 0,
            'totalComplaints' => $totalComplaints,
            'avgResolutionDays' => round($avgResolution, 1),
            'ratingDistribution' => $this->getRatingDistribution($dateRange),
            'ratingTrend' => $this->getRatingTrend($dateRange),
            'complaintTypes' => $this->getComplaintTypes($dateRange),
            'pendingComplaints' => $this->getPendingComplaints(),
            'contactVolume' => $this->getContactVolume($dateRange),
        ];
    }

    private function getRatingDistribution(array $dateRange)
    {
        $results = Review::whereBetween('created_at', $dateRange)
            ->where('is_approved', true)
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->pluck('count', 'rating');

        // Ensure all 5 ratings are present
        return collect(range(1, 5))->mapWithKeys(fn($r) => [$r => $results[$r] ?? 0]);
    }

    private function getRatingTrend(array $dateRange)
    {
        return Review::whereBetween('created_at', $dateRange)
            ->where('is_approved', true)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    private function getComplaintTypes(array $dateRange)
    {
        return Complaint::whereBetween('created_at', $dateRange)
            ->select('complaint_type', DB::raw('COUNT(*) as count'))
            ->groupBy('complaint_type')
            ->orderByDesc('count')
            ->get();
    }

    private function getPendingComplaints()
    {
        return Complaint::whereNull('response_date')
            ->where('created_at', '<=', now()->subDays(7))
            ->select('complaint_number', 'complaint_type', 'created_at', 'status',
                DB::raw('DATEDIFF(NOW(), created_at) as days_open'))
            ->orderByDesc('days_open')
            ->limit(10)
            ->get();
    }

    private function getContactVolume(array $dateRange)
    {
        return ContactMessage::whereBetween('created_at', $dateRange)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
