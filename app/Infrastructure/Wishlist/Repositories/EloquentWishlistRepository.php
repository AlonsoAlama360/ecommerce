<?php

namespace App\Infrastructure\Wishlist\Repositories;

use App\Domain\Wishlist\Repositories\WishlistRepositoryInterface;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentWishlistRepository implements WishlistRepositoryInterface
{
    public function paginateProducts(array $filters, int $perPage = 15): mixed
    {
        $query = Product::withCount('wishlists')
            ->addSelect([
                'last_wishlisted_at' => Wishlist::select(DB::raw('MAX(created_at)'))
                    ->whereColumn('product_id', 'products.id'),
            ])
            ->whereHas('wishlists');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.sku', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['categoryId'])) {
            $query->where('products.category_id', $filters['categoryId']);
        }

        $orderBy = $filters['order'] ?? 'most_wished';
        if ($orderBy === 'recent') {
            $query->orderByDesc('last_wishlisted_at');
        } else {
            $query->orderByDesc('wishlists_count');
        }

        return $query->with(['primaryImage', 'category'])
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getStats(): array
    {
        $totalItems = Wishlist::count();
        $uniqueProducts = Wishlist::distinct('product_id')->count('product_id');
        $uniqueClients = Wishlist::distinct('user_id')->count('user_id');

        $topProduct = Product::select('products.id', 'products.name')
            ->join('wishlists', 'products.id', '=', 'wishlists.product_id')
            ->selectRaw('COUNT(wishlists.id) as total')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->first();

        return [
            'totalItems' => $totalItems,
            'uniqueProducts' => $uniqueProducts,
            'uniqueClients' => $uniqueClients,
            'topProduct' => $topProduct,
        ];
    }

    public function getProductWishlists(int $productId, ?string $search = null, int $perPage = 15): mixed
    {
        $query = Wishlist::with('user')
            ->where('product_id', $productId);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->latest('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getProductWishlistCount(int $productId): int
    {
        return Wishlist::where('product_id', $productId)->count();
    }

    public function getUserWishlistProducts(User $user, int $perPage = 12): mixed
    {
        return $user->wishlistProducts()
            ->active()
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->with('category:id,name,slug')
            ->select('products.id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'is_featured')
            ->orderByDesc('wishlists.created_at')
            ->paginate($perPage);
    }

    public function toggle(User $user, int $productId): string
    {
        $exists = $user->wishlistProducts()->where('product_id', $productId)->exists();

        if ($exists) {
            $user->wishlistProducts()->detach($productId);
            return 'removed';
        } else {
            $user->wishlistProducts()->attach($productId);
            return 'added';
        }
    }

    public function getCount(User $user): array
    {
        return [
            'count' => $user->wishlistProducts()->count(),
            'ids' => $user->wishlistProducts()->pluck('product_id')->toArray(),
        ];
    }

    public function getActiveCategories(): Collection
    {
        return Category::where('is_active', true)->orderBy('name')->get(['id', 'name']);
    }
}
