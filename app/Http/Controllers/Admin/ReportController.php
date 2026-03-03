<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Reports\CustomerReportService;
use App\Services\Reports\GeographicReportService;
use App\Services\Reports\InventoryReportService;
use App\Services\Reports\ProfitabilityReportService;
use App\Services\Reports\PurchaseReportService;
use App\Services\Reports\SalesReportService;
use App\Services\Reports\SatisfactionReportService;
use App\Services\Reports\ProductReportService;
use App\Services\Reports\TrendsReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private function getDateRange(Request $request): array
    {
        return [
            $request->input('from', now()->subDays(30)->toDateString()),
            $request->input('to', now()->toDateString()),
        ];
    }

    public function sales(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $data = (new SalesReportService)->getData($from, $to);

        return view('admin.reports.sales', compact('from', 'to') + $data);
    }

    public function products(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $data = (new ProductReportService)->getData($from, $to);

        return view('admin.reports.products', compact('from', 'to') + $data);
    }

    public function customers(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $data = (new CustomerReportService)->getData($from, $to);

        return view('admin.reports.customers', compact('from', 'to') + $data);
    }

    public function purchases(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $data = (new PurchaseReportService)->getData($from, $to);

        return view('admin.reports.purchases', compact('from', 'to') + $data);
    }

    public function profitability(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $data = (new ProfitabilityReportService)->getData($from, $to);

        return view('admin.reports.profitability', compact('from', 'to') + $data);
    }

    public function inventory(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $data = (new InventoryReportService)->getData($from, $to);

        return view('admin.reports.inventory', compact('from', 'to') + $data);
    }

    public function geographic(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $data = (new GeographicReportService)->getData($from, $to);

        return view('admin.reports.geographic', compact('from', 'to') + $data);
    }

    public function trends(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $data = (new TrendsReportService)->getData($from, $to);

        return view('admin.reports.trends', compact('from', 'to') + $data);
    }

    public function satisfaction(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $data = (new SatisfactionReportService)->getData($from, $to);

        return view('admin.reports.satisfaction', compact('from', 'to') + $data);
    }
}
