<?php

namespace App\Infrastructure\Catalog\Repositories;

use App\Domain\Catalog\Repositories\CatalogRepositoryInterface;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class EloquentCatalogRepository implements CatalogRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 12): mixed
    {
        $query = Product::active()
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->with('category:id,name,slug');

        if (!empty($filters['categories'])) {
            $slugs = $filters['categories'];
            $query->whereHas('category', function ($q) use ($slugs) {
                $q->whereIn('slug', $slugs);
            });
        }

        if (!empty($filters['price_min'])) {
            $min = $filters['price_min'];
            $query->where(function ($q) use ($min) {
                $q->whereNotNull('sale_price')->where('sale_price', '>=', $min)
                  ->orWhereNull('sale_price')->where('price', '>=', $min);
            });
        }

        if (!empty($filters['price_max'])) {
            $max = $filters['price_max'];
            $query->where(function ($q) use ($max) {
                $q->whereNotNull('sale_price')->where('sale_price', '<=', $max)
                  ->orWhereNull('sale_price')->where('price', '<=', $max);
            });
        }

        if (!empty($filters['in_stock'])) {
            $query->inStock();
        }

        if (!empty($filters['on_sale'])) {
            $query->whereNotNull('sale_price')
                  ->whereColumn('sale_price', '<', 'price');
        }

        match ($filters['sort'] ?? 'relevant') {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'newest' => $query->latest(),
            default => $query->orderByDesc('is_featured')->latest(),
        };

        return $query->select([
            'id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'stock', 'is_featured',
        ])->paginate($perPage)->withQueryString();
    }

    public function getCategories(): Collection
    {
        return Category::active()
            ->ordered()
            ->select('id', 'name', 'slug', 'icon')
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }])
            ->get();
    }

    public function getPriceRange(): object
    {
        return Cache::remember('catalog_price_range', 1800, function () {
            return Product::active()
                ->selectRaw('MIN(COALESCE(sale_price, price)) as min_price, MAX(COALESCE(sale_price, price)) as max_price')
                ->first();
        });
    }

    public function search(string $query, int $limit = 6): array
    {
        $products = Product::active()
            ->with('primaryImage:id,product_id,image_url,alt_text')
            ->with('category:id,name,slug')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhere('material', 'like', "%{$query}%")
                  ->orWhereHas('category', function ($cq) use ($query) {
                      $cq->where('name', 'like', "%{$query}%");
                  });
            })
            ->select('id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'stock')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->current_price,
                    'original_price' => $product->sale_price ? $product->price : null,
                    'category' => $product->category?->name,
                    'image' => $product->primaryImage?->image_url,
                    'url' => route('product.show', $product->slug),
                ];
            });

        $categories = Category::active()
            ->where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'slug', 'icon')
            ->limit(3)
            ->get()
            ->map(function ($cat) {
                return [
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'icon' => $cat->icon,
                    'url' => route('catalog', ['categories' => [$cat->slug]]),
                ];
            });

        return [
            'products' => $products,
            'categories' => $categories,
        ];
    }
}
