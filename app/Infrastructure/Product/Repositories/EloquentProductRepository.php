<?php

namespace App\Infrastructure\Product\Repositories;

use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function paginate(array $filters, int $perPage = 10): mixed
    {
        $query = Product::with(['category', 'primaryImage']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (isset($filters['status']) && $filters['status'] !== null) {
            $query->where('is_active', $filters['status']);
        }

        if (isset($filters['featured']) && $filters['featured'] !== null) {
            $query->where('is_featured', $filters['featured']);
        }

        if (!empty($filters['stock'])) {
            match ($filters['stock']) {
                'out' => $query->where('stock', 0),
                'low' => $query->whereBetween('stock', [1, 5]),
                'in' => $query->where('stock', '>', 5),
                default => null,
            };
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function getStats(): object
    {
        return DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(is_active = 1) as active,
                SUM(is_featured = 1) as featured,
                SUM(stock = 0) as out_of_stock
            FROM products WHERE deleted_at IS NULL
        ");
    }

    public function findBySlugWithRelations(string $slug): ?Product
    {
        return Product::active()
            ->where('slug', $slug)
            ->with([
                'images' => fn($q) => $q->orderBy('sort_order'),
                'category:id,name,slug',
            ])
            ->first();
    }

    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        return Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage:id,product_id,image_url,thumbnail_url,alt_text')
            ->select('id', 'category_id', 'name', 'slug', 'price', 'sale_price')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    // Image methods

    public function getImages(Product $product): Collection
    {
        return $product->images()->orderBy('sort_order')->get();
    }

    public function createImage(Product $product, array $data): ProductImage
    {
        $data['sort_order'] = ($product->images()->max('sort_order') ?? -1) + 1;

        if (!empty($data['is_primary'])) {
            $product->images()->update(['is_primary' => false]);
        }

        if ($product->images()->count() === 0) {
            $data['is_primary'] = true;
        }

        return $product->images()->create($data);
    }

    public function updateImage(ProductImage $image, array $data): ProductImage
    {
        $image->update($data);
        return $image->fresh();
    }

    public function deleteImage(ProductImage $image): void
    {
        $wasPrimary = $image->is_primary;
        $product = $image->product;
        $image->delete();

        if ($wasPrimary) {
            $first = $product->images()->orderBy('sort_order')->first();
            if ($first) {
                $first->update(['is_primary' => true]);
            }
        }
    }

    public function reorderImages(Product $product, array $order): void
    {
        foreach ($order as $index => $id) {
            ProductImage::where('id', $id)
                ->where('product_id', $product->id)
                ->update(['sort_order' => $index]);
        }
    }

    public function setImageAsPrimary(Product $product, ProductImage $image): void
    {
        $product->images()->where('id', '!=', $image->id)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
    }
}
