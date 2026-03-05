<?php

namespace App\Application\Category\UseCases;

use App\Application\Category\DTOs\CreateCategoryDTO;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CreateCategory
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function execute(CreateCategoryDTO $dto): Category
    {
        $category = $this->categoryRepository->create([
            'name' => $dto->name,
            'slug' => $dto->slug ?: Str::slug($dto->name),
            'description' => $dto->description,
            'icon' => $dto->icon,
            'image_url' => $dto->imageUrl,
            'is_active' => $dto->isActive,
            'sort_order' => $dto->sortOrder,
        ]);

        Cache::forget('nav_categories');

        return $category;
    }
}
