<?php

namespace App\Http\Controllers\Admin;

use App\Application\Category\DTOs\CategoryFiltersDTO;
use App\Application\Category\DTOs\CreateCategoryDTO;
use App\Application\Category\DTOs\UpdateCategoryDTO;
use App\Application\Category\UseCases\CreateCategory;
use App\Application\Category\UseCases\DeleteCategory;
use App\Application\Category\UseCases\ListCategories;
use App\Application\Category\UseCases\UpdateCategory;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategories $listCategories)
    {
        $dto = CategoryFiltersDTO::fromRequest($request);
        $data = $listCategories->execute($dto);

        return view('admin.categories.index', $data);
    }

    public function create()
    {
        return redirect()->route('admin.categories.index');
    }

    public function store(Request $request, CreateCategory $createCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $dto = CreateCategoryDTO::fromRequest($request);
        $createCategory->execute($dto);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Category $category)
    {
        return redirect()->route('admin.categories.index');
    }

    public function update(Request $request, Category $category, UpdateCategory $updateCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $dto = UpdateCategoryDTO::fromRequest($request);
        $updateCategory->execute($dto, $category);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Category $category, DeleteCategory $deleteCategory)
    {
        $deleteCategory->execute($category);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
