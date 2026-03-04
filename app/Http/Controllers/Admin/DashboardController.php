<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Review;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $monthStart = now()->startOfMonth()->toDateTimeString();
        $prevMonthStart = now()->subMonth()->startOfMonth()->toDateTimeString();
        $prevMonthEnd = now()->subMonth()->endOfMonth()->toDateTimeString();
        $todayStart = today()->toDateTimeString();
        $weekAgo = now()->subWeek()->toDateTimeString();
        $sevenDaysAgo = now()->subDays(6)->startOfDay()->toDateTimeString();

        // --- Stats: single raw query for orders + products + users (cached 5 min) ---
        $stats = Cache::remember('dashboard_stats', 300, function () use ($monthStart, $prevMonthStart, $prevMonthEnd, $todayStart, $weekAgo) {

            // Orders aggregation: 1 query instead of 10
            $orderStats = DB::selectOne("
                SELECT
                    SUM(CASE WHEN created_at >= ? AND status != 'cancelado' THEN total ELSE 0 END) as monthly_revenue,
                    SUM(CASE WHEN created_at >= ? AND created_at <= ? AND status != 'cancelado' THEN total ELSE 0 END) as prev_month_revenue,
                    SUM(CASE WHEN created_at >= ? AND status != 'cancelado' THEN 1 ELSE 0 END) as monthly_orders,
                    SUM(CASE WHEN created_at >= ? AND created_at <= ? AND status != 'cancelado' THEN 1 ELSE 0 END) as prev_month_orders,
                    SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as orders_today,
                    SUM(CASE WHEN created_at >= ? AND status != 'cancelado' THEN total ELSE 0 END) as revenue_today,
                    SUM(CASE WHEN status = 'pendiente' THEN 1 ELSE 0 END) as pending_orders,
                    AVG(CASE WHEN created_at >= ? AND status != 'cancelado' THEN total ELSE NULL END) as avg_ticket
                FROM orders WHERE deleted_at IS NULL
            ", [$monthStart, $prevMonthStart, $prevMonthEnd, $monthStart, $prevMonthStart, $prevMonthEnd, $todayStart, $todayStart, $monthStart]);

            // Products aggregation: 1 query instead of 4
            $productStats = DB::selectOne("
                SELECT
                    COUNT(*) as total_products,
                    SUM(is_active = 1) as active_products,
                    SUM(stock = 0 AND is_active = 1) as out_of_stock,
                    SUM(stock BETWEEN 1 AND 5 AND is_active = 1) as low_stock
                FROM products WHERE deleted_at IS NULL
            ");

            // Counts: 1 query for small tables
            $counts = DB::selectOne("
                SELECT
                    (SELECT COUNT(*) FROM users) as total_users,
                    (SELECT COUNT(*) FROM users WHERE created_at >= ?) as new_users_week,
                    (SELECT COUNT(*) FROM categories) as total_categories,
                    (SELECT SUM(is_active = 1) FROM categories) as active_categories,
                    (SELECT COUNT(*) FROM suppliers) as total_suppliers,
                    (SELECT SUM(total) FROM purchases WHERE created_at >= ? AND status != 'cancelado') as monthly_spending,
                    (SELECT COUNT(*) FROM complaints WHERE response_date IS NULL) as pending_complaints,
                    (SELECT COUNT(*) FROM contact_messages WHERE status = 'nuevo') as unread_messages,
                    (SELECT AVG(rating) FROM reviews WHERE is_approved = 1) as avg_rating,
                    (SELECT COUNT(*) FROM reviews WHERE is_approved = 1) as total_reviews,
                    (SELECT COUNT(*) FROM wishlists) as total_wishlists
            ", [$weekAgo, $monthStart]);

            $monthlyRevenue = (float) ($orderStats->monthly_revenue ?? 0);
            $prevMonthRevenue = (float) ($orderStats->prev_month_revenue ?? 0);
            $monthlyOrders = (int) ($orderStats->monthly_orders ?? 0);
            $prevMonthOrders = (int) ($orderStats->prev_month_orders ?? 0);

            return [
                'monthly_revenue' => $monthlyRevenue,
                'prev_month_revenue' => $prevMonthRevenue,
                'revenue_growth' => $prevMonthRevenue > 0
                    ? round((($monthlyRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 1)
                    : ($monthlyRevenue > 0 ? 100 : 0),
                'monthly_orders' => $monthlyOrders,
                'prev_month_orders' => $prevMonthOrders,
                'orders_growth' => $prevMonthOrders > 0
                    ? round((($monthlyOrders - $prevMonthOrders) / $prevMonthOrders) * 100, 1)
                    : ($monthlyOrders > 0 ? 100 : 0),
                'orders_today' => (int) ($orderStats->orders_today ?? 0),
                'revenue_today' => (float) ($orderStats->revenue_today ?? 0),
                'pending_orders' => (int) ($orderStats->pending_orders ?? 0),
                'avg_ticket' => (float) ($orderStats->avg_ticket ?? 0),
                'total_users' => (int) $counts->total_users,
                'new_users_week' => (int) $counts->new_users_week,
                'total_products' => (int) $productStats->total_products,
                'active_products' => (int) $productStats->active_products,
                'out_of_stock' => (int) $productStats->out_of_stock,
                'low_stock' => (int) $productStats->low_stock,
                'total_categories' => (int) $counts->total_categories,
                'active_categories' => (int) ($counts->active_categories ?? 0),
                'total_suppliers' => (int) $counts->total_suppliers,
                'monthly_spending' => (float) ($counts->monthly_spending ?? 0),
                'pending_complaints' => (int) $counts->pending_complaints,
                'unread_messages' => (int) $counts->unread_messages,
                'avg_rating' => (float) ($counts->avg_rating ?? 0),
                'total_reviews' => (int) $counts->total_reviews,
                'total_wishlists' => (int) $counts->total_wishlists,
            ];
        });

        // --- Revenue last 7 days (chart) - cached 10 min ---
        $chartData = Cache::remember('dashboard_chart', 600, function () use ($sevenDaysAgo) {
            $revenueLast7Days = Order::where('created_at', '>=', $sevenDaysAgo)
                ->where('status', '!=', 'cancelado')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

            $data = collect();
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $data->push([
                    'date' => $date,
                    'revenue' => (float) ($revenueLast7Days[$date]->revenue ?? 0),
                    'orders' => (int) ($revenueLast7Days[$date]->orders ?? 0),
                ]);
            }
            return $data;
        });

        // --- Top selling products this month - cached 10 min ---
        $topSellingProducts = Cache::remember('dashboard_top_products', 600, function () use ($monthStart) {
            return DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.created_at', '>=', $monthStart)
                ->where('orders.status', '!=', 'cancelado')
                ->whereNull('orders.deleted_at')
                ->select('order_items.product_name', 'order_items.product_sku',
                    DB::raw('SUM(order_items.quantity) as total_qty'),
                    DB::raw('SUM(order_items.line_total) as total_revenue'))
                ->groupBy('order_items.product_name', 'order_items.product_sku')
                ->orderByDesc('total_revenue')
                ->limit(5)
                ->get();
        });

        // --- Recent data (no cache, these are fast with indexes + LIMIT) ---
        $recentOrders = Order::with('user:id,first_name,last_name,email')->latest()->take(7)->get();

        $pendingPurchases = Purchase::whereIn('status', ['pendiente', 'aprobado', 'en_transito'])
            ->with('supplier:id,business_name')
            ->latest()
            ->take(5)
            ->get();

        $recentReviews = Review::with(['user:id,first_name,last_name', 'product:id,name,slug'])
            ->where('is_approved', true)
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('is_active', true)
            ->where('stock', '<=', 5)
            ->with('primaryImage:id,product_id,image_url')
            ->orderBy('stock')
            ->take(5)
            ->get();

        $topCategories = Category::withCount('products')
            ->where('is_active', true)
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'chartData', 'topSellingProducts', 'recentOrders',
            'pendingPurchases', 'recentReviews', 'lowStockProducts', 'topCategories'
        ));
    }
}
