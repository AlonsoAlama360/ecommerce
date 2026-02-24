<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'new_users_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'low_stock' => Product::whereBetween('stock', [1, 5])->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
        ];

        $recentProducts = Product::with(['category', 'primaryImage'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        $topCategories = Category::withCount('products')
            ->where('is_active', true)
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'recentUsers', 'topCategories'));
    }
}
