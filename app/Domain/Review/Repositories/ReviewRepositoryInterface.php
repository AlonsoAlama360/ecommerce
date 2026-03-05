<?php

namespace App\Domain\Review\Repositories;

use App\Models\Review;

interface ReviewRepositoryInterface
{
    public function findById(int $id): ?Review;

    public function create(array $data): Review;

    public function update(Review $review, array $data): Review;

    public function delete(Review $review): void;

    public function paginate(array $filters, int $perPage = 15): mixed;

    public function getStats(): object;

    public function findByUserAndProduct(int $userId, int $productId): ?Review;
}
