<?php

namespace App\Application\Category\UseCases;

use App\Application\Category\DTOs\UpdateCategoryDTO;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UpdateCategory
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function execute(UpdateCategoryDTO $dto, Category $category): Category
    {
        $data = [
            'name' => $dto->name,
            'slug' => $dto->slug ?: Str::slug($dto->name),
            'description' => $dto->description,
            'icon' => $dto->icon,
            'image_url' => $dto->imageUrl,
            'is_active' => $dto->isActive,
        ];

        if ($dto->sortOrder !== null) {
            $data['sort_order'] = $dto->sortOrder;
        }

        $category = $this->categoryRepository->update($category, $data);

        Cache::forget('nav_categories');

        return $category;
    }
}
