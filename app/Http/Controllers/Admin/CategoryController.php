<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('is_active', $request->get('status'));
        }

        $perPage = $request->get('per_page', 20);
        $categories = $query->ordered()->paginate($perPage)->withQueryString();

        $stats = \DB::selectOne("
            SELECT
                (SELECT COUNT(*) FROM categories) as total,
                (SELECT SUM(is_active = 1) FROM categories) as active,
                (SELECT SUM(is_active = 0) FROM categories) as inactive,
                (SELECT COUNT(*) FROM products WHERE deleted_at IS NULL) as total_products
        ");

        $totalCategories = (int) $stats->total;
        $activeCategories = (int) ($stats->active ?? 0);
        $inactiveCategories = (int) ($stats->inactive ?? 0);
        $totalProducts = (int) $stats->total_products;

        return view('admin.categories.index', compact(
            'categories', 'totalCategories', 'activeCategories', 'inactiveCategories', 'totalProducts'
        ));
    }

    public function create()
    {
        return redirect()->route('admin.categories.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Category::create($validated);
        Cache::forget('nav_categories');

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Category $category)
    {
        return redirect()->route('admin.categories.index');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'image_url' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $category->update($validated);
        Cache::forget('nav_categories');

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        Cache::forget('nav_categories');

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
