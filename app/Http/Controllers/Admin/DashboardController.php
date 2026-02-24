<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'new_users_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
