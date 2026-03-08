<?php

namespace App\Infrastructure\Order\Repositories;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findById(int $id): ?Order
    {
        return Order::find($id);
    }

    public function create(array $data): Order
    {
        $order = new Order();
        $order->forceFill($data)->save();
        return $order;
    }

    public function update(Order $order, array $data): Order
    {
        $order->forceFill($data)->save();
        return $order->fresh();
    }

    public function delete(Order $order): void
    {
        $order->delete();
    }

    public function paginate(array $filters, int $perPage = 10): mixed
    {
        $query = Order::with(['user', 'items']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getStats(): object
    {
        return DB::selectOne("
            SELECT
                COUNT(*) as total,
                SUM(created_at >= ?) as today,
                SUM(CASE WHEN created_at >= ? AND status != 'cancelado' THEN total ELSE 0 END) as monthly_revenue,
                SUM(status = 'pendiente') as pending
            FROM orders WHERE deleted_at IS NULL
        ", [today()->toDateTimeString(), now()->startOfMonth()->toDateTimeString()]);
    }

    public function getCustomerOrders(int $userId, ?string $status = null, int $perPage = 10): mixed
    {
        $query = Order::where('user_id', $userId)
            ->with('items.product.primaryImage')
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    public function getCustomerStatusCounts(int $userId): Collection
    {
        return Order::where('user_id', $userId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    public function searchProducts(string $search, int $limit = 10): Collection
    {
        return Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })
            ->with('primaryImage')
            ->take($limit)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => $p->current_price,
                'stock' => $p->stock,
                'image' => $p->primaryImage?->image_url,
            ]);
    }

    public function searchUsers(string $search, int $limit = 10): Collection
    {
        return User::where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            })
            ->where('is_active', true)
            ->with(['department', 'province', 'district'])
            ->take($limit)
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->full_name,
                'email' => $u->email,
                'phone' => $u->phone,
                'full_address' => $u->full_address,
                'address' => $u->address,
                'address_reference' => $u->address_reference,
            ]);
    }

    public function exportQuery(array $filters): mixed
    {
        $query = Order::with(['user', 'items.product']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }
        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }
        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        return $query->latest();
    }
}
