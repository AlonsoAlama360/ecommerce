<?php

namespace App\Infrastructure\Review\Repositories;

use App\Domain\Review\Repositories\ReviewRepositoryInterface;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class EloquentReviewRepository implements ReviewRepositoryInterface
{
    public function findById(int $id): ?Review
    {
        return Review::find($id);
    }

    public function create(array $data): Review
    {
        return Review::create($data);
    }

    public function update(Review $review, array $data): Review
    {
        $review->update($data);
        return $review->fresh();
    }

    public function delete(Review $review): void
    {
        $review->delete();
    }

    public function paginate(array $filters, int $perPage = 15): mixed
    {
        $query = Review::with(['user:id,first_name,last_name', 'product:id,name,slug'])
            ->latest();

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'approved') {
                $query->where('is_approved', true);
            } elseif ($filters['status'] === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['featured'])) {
            $query->where('is_featured', true);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getStats(): object
    {
        return DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(is_approved = 1) as approved,
                SUM(is_approved = 0) as pending,
                AVG(CASE WHEN is_approved = 1 THEN rating ELSE NULL END) as avg_rating,
                SUM(is_featured = 1) as featured
            FROM reviews
        ");
    }

    public function findByUserAndProduct(int $userId, int $productId): ?Review
    {
        return Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }
}
