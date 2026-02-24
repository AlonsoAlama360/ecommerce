<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->get('category')) {
            $query->where('category_id', $categoryId);
        }

        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('is_active', $request->get('status'));
        }

        if ($request->has('featured') && $request->get('featured') !== '') {
            $query->where('is_featured', $request->get('featured'));
        }

        if ($request->has('stock') && $request->get('stock') !== '') {
            if ($request->get('stock') === 'out') {
                $query->where('stock', 0);
            } elseif ($request->get('stock') === 'low') {
                $query->whereBetween('stock', [1, 5]);
            } elseif ($request->get('stock') === 'in') {
                $query->where('stock', '>', 5);
            }
        }

        $perPage = $request->get('per_page', 10);
        $products = $query->latest()->paginate($perPage)->withQueryString();

        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $outOfStock = Product::where('stock', 0)->count();

        $categories = Category::ordered()->get();

        return view('admin.products.index', compact(
            'products', 'totalProducts', 'activeProducts', 'featuredProducts', 'outOfStock', 'categories'
        ));
    }

    public function create()
    {
        return redirect()->route('admin.products.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:5000',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:50|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'material' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'image_url' => 'nullable|url|max:500',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $imageUrl = $validated['image_url'] ?? null;
        unset($validated['image_url']);

        $product = Product::create($validated);

        if ($imageUrl) {
            $product->images()->create([
                'image_url' => $imageUrl,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product)
    {
        return redirect()->route('admin.products.index');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:5000',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'stock' => 'required|integer|min:0',
            'material' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'image_url' => 'nullable|url|max:500',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);

        $imageUrl = $validated['image_url'] ?? null;
        unset($validated['image_url']);

        $product->update($validated);

        if ($imageUrl) {
            $primary = $product->primaryImage;
            if ($primary) {
                $primary->update(['image_url' => $imageUrl]);
            } else {
                $product->images()->create([
                    'image_url' => $imageUrl,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
