<?php

namespace App\Domain\Category\Repositories;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function findById(int $id): ?Category;

    public function create(array $data): Category;

    public function update(Category $category, array $data): Category;

    public function delete(Category $category): void;

    public function paginate(array $filters, int $perPage = 20): mixed;

    public function getStats(): object;
}
