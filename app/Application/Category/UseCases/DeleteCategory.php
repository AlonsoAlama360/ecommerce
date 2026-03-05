<?php

namespace App\Application\Category\UseCases;

use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class DeleteCategory
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function execute(Category $category): void
    {
        $this->categoryRepository->delete($category);
        Cache::forget('nav_categories');
    }
}
