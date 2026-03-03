<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Review;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Core stats ---
        $monthStart = now()->startOfMonth();
        $prevMonthStart = now()->subMonth()->startOfMonth();
        $prevMonthEnd = now()->subMonth()->endOfMonth();

        $monthlyRevenue = Order::where('created_at', '>=', $monthStart)
            ->where('status', '!=', 'cancelado')->sum('total');
        $prevMonthRevenue = Order::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
            ->where('status', '!=', 'cancelado')->sum('total');

        $monthlyOrders = Order::where('created_at', '>=', $monthStart)
            ->where('status', '!=', 'cancelado')->count();
        $prevMonthOrders = Order::whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
            ->where('status', '!=', 'cancelado')->count();

        $stats = [
            'monthly_revenue' => $monthlyRevenue,
            'prev_month_revenue' => $prevMonthRevenue,
            'revenue_growth' => $prevMonthRevenue > 0 ? round((($monthlyRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 1) : ($monthlyRevenue > 0 ? 100 : 0),
            'monthly_orders' => $monthlyOrders,
            'prev_month_orders' => $prevMonthOrders,
            'orders_growth' => $prevMonthOrders > 0 ? round((($monthlyOrders - $prevMonthOrders) / $prevMonthOrders) * 100, 1) : ($monthlyOrders > 0 ? 100 : 0),
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'revenue_today' => Order::whereDate('created_at', today())->where('status', '!=', 'cancelado')->sum('total'),
            'pending_orders' => Order::where('status', 'pendiente')->count(),
            'avg_ticket' => Order::where('created_at', '>=', $monthStart)->where('status', '!=', 'cancelado')->avg('total') ?? 0,
            'total_users' => User::count(),
            'new_users_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'out_of_stock' => Product::where('stock', 0)->where('is_active', true)->count(),
            'low_stock' => Product::whereBetween('stock', [1, 5])->where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'total_suppliers' => Supplier::count(),
            'monthly_spending' => Purchase::where('created_at', '>=', $monthStart)
                ->where('status', '!=', 'cancelado')->sum('total'),
            'pending_complaints' => Complaint::whereNull('response_date')->count(),
            'unread_messages' => ContactMessage::where('status', 'nuevo')->count(),
            'avg_rating' => Review::where('is_approved', true)->avg('rating') ?? 0,
            'total_reviews' => Review::where('is_approved', true)->count(),
            'total_wishlists' => Wishlist::count(),
        ];

        // --- Revenue last 7 days (chart) ---
        $revenueLast7Days = Order::where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->where('status', '!=', 'cancelado')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartData->push([
                'date' => $date,
                'revenue' => (float) ($revenueLast7Days[$date]->revenue ?? 0),
                'orders' => (int) ($revenueLast7Days[$date]->orders ?? 0),
            ]);
        }

        // --- Top selling products this month ---
        $topSellingProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $monthStart)
            ->where('orders.status', '!=', 'cancelado')
            ->select('order_items.product_name', 'order_items.product_sku',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.line_total) as total_revenue'))
            ->groupBy('order_items.product_name', 'order_items.product_sku')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // --- Recent orders ---
        $recentOrders = Order::with('user')->latest()->take(7)->get();

        // --- Pending purchases ---
        $pendingPurchases = Purchase::whereIn('status', ['pendiente', 'aprobado', 'en_transito'])
            ->with('supplier')
            ->latest()
            ->take(5)
            ->get();

        // --- Recent reviews ---
        $recentReviews = Review::with(['user', 'product'])
            ->where('is_approved', true)
            ->latest()
            ->take(5)
            ->get();

        // --- Low stock products ---
        $lowStockProducts = Product::where('is_active', true)
            ->where('stock', '<=', 5)
            ->with('primaryImage')
            ->orderBy('stock')
            ->take(5)
            ->get();

        // --- Top categories ---
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
