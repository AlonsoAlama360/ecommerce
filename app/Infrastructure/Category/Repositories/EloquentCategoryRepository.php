<?php

namespace App\Infrastructure\Category\Repositories;

use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    public function paginate(array $filters, int $perPage = 20): mixed
    {
        $query = Category::withCount('products');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] !== null) {
            $query->where('is_active', $filters['status']);
        }

        return $query->ordered()->paginate($perPage)->withQueryString();
    }

    public function getStats(): object
    {
        return DB::selectOne("
            SELECT
                (SELECT COUNT(*) FROM categories) as total,
                (SELECT SUM(is_active = 1) FROM categories) as active,
                (SELECT SUM(is_active = 0) FROM categories) as inactive,
                (SELECT COUNT(*) FROM products WHERE deleted_at IS NULL) as total_products
        ");
    }
}
