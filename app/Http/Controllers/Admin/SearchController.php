<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        return Cache::remember('admin_search:' . md5($q), 30, function () use ($q) {
            $like = "%{$q}%";
            $results = [];

            // Pedidos — solo columnas necesarias
            $orders = Order::select('id', 'order_number', 'customer_name', 'total')
                ->where(function ($query) use ($like) {
                    $query->where('order_number', 'like', $like)
                        ->orWhere('customer_name', 'like', $like)
                        ->orWhere('customer_email', 'like', $like);
                })
                ->latest()
                ->take(5)
                ->get();

            if ($orders->isNotEmpty()) {
                $results[] = [
                    'group' => 'Ventas',
                    'icon' => 'fa-receipt',
                    'items' => $orders->map(fn(Order $o) => [
                        'title' => $o->order_number,
                        'subtitle' => $o->customer_name . ' · S/ ' . number_format($o->total, 2),
                        'url' => route('admin.orders.index', ['search' => $o->order_number]),
                    ])->toArray(),
                ];
            }

            // Productos — solo columnas necesarias
            $products = Product::select('id', 'name', 'sku', 'price')
                ->where(function ($query) use ($like) {
                    $query->where('name', 'like', $like)
                        ->orWhere('sku', 'like', $like);
                })
                ->take(5)
                ->get();

            if ($products->isNotEmpty()) {
                $results[] = [
                    'group' => 'Productos',
                    'icon' => 'fa-box',
                    'items' => $products->map(fn(Product $p) => [
                        'title' => $p->name,
                        'subtitle' => ($p->sku ? "SKU: {$p->sku} · " : '') . 'S/ ' . number_format($p->price, 2),
                        'url' => route('admin.products.edit', $p),
                    ])->toArray(),
                ];
            }

            // Usuarios — solo columnas necesarias
            $users = User::select('id', 'first_name', 'last_name', 'email')
                ->where(function ($query) use ($like) {
                    $query->where('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhere('email', 'like', $like);
                })
                ->take(5)
                ->get();

            if ($users->isNotEmpty()) {
                $results[] = [
                    'group' => 'Usuarios',
                    'icon' => 'fa-users',
                    'items' => $users->map(fn(User $u) => [
                        'title' => $u->full_name,
                        'subtitle' => $u->email,
                        'url' => route('admin.users.edit', $u),
                    ])->toArray(),
                ];
            }

            // Proveedores — solo columnas necesarias
            $suppliers = Supplier::select('id', 'business_name', 'contact_name', 'ruc')
                ->where(function ($query) use ($like) {
                    $query->where('business_name', 'like', $like)
                        ->orWhere('contact_name', 'like', $like)
                        ->orWhere('ruc', 'like', $like);
                })
                ->take(5)
                ->get();

            if ($suppliers->isNotEmpty()) {
                $results[] = [
                    'group' => 'Proveedores',
                    'icon' => 'fa-truck-field',
                    'items' => $suppliers->map(fn(Supplier $s) => [
                        'title' => $s->business_name,
                        'subtitle' => $s->contact_name ?: $s->ruc,
                        'url' => route('admin.suppliers.edit', $s),
                    ])->toArray(),
                ];
            }

            // Compras — solo columnas necesarias, eager load supplier
            $purchases = Purchase::select('id', 'purchase_number', 'supplier_id', 'total')
                ->with('supplier:id,business_name')
                ->where('purchase_number', 'like', $like)
                ->latest()
                ->take(5)
                ->get();

            if ($purchases->isNotEmpty()) {
                $results[] = [
                    'group' => 'Compras',
                    'icon' => 'fa-cart-shopping',
                    'items' => $purchases->map(fn(Purchase $p) => [
                        'title' => $p->purchase_number,
                        'subtitle' => ($p->supplier?->business_name ?? '') . ' · S/ ' . number_format($p->total, 2),
                        'url' => route('admin.purchases.show', $p),
                    ])->toArray(),
                ];
            }

            return $results;
        });
    }
}
