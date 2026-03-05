<?php

namespace App\Http\Controllers\Admin;

use App\Application\Product\DTOs\CreateProductDTO;
use App\Application\Product\DTOs\ProductFiltersDTO;
use App\Application\Product\DTOs\UpdateProductDTO;
use App\Application\Product\DTOs\UpdateSpecificationsDTO;
use App\Application\Product\UseCases\CreateProduct;
use App\Application\Product\UseCases\DeleteProduct;
use App\Application\Product\UseCases\GetProductSpecifications;
use App\Application\Product\UseCases\ListProducts;
use App\Application\Product\UseCases\UpdateProduct;
use App\Application\Product\UseCases\UpdateProductSpecifications;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request, ListProducts $listProducts)
    {
        $dto = ProductFiltersDTO::fromRequest($request);
        $data = $listProducts->execute($dto);

        return view('admin.products.index', $data);
    }

    public function create()
    {
        return redirect()->route('admin.products.index');
    }

    public function store(Request $request, CreateProduct $createProduct)
    {
        $request->validate([
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
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $dto = CreateProductDTO::fromRequest($request);
        $createProduct->execute($dto);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product)
    {
        return redirect()->route('admin.products.index');
    }

    public function update(Request $request, Product $product, UpdateProduct $updateProduct)
    {
        $request->validate([
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
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $dto = UpdateProductDTO::fromRequest($request);
        $updateProduct->execute($dto, $product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product, DeleteProduct $deleteProduct)
    {
        $deleteProduct->execute($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    public function specifications(Product $product, GetProductSpecifications $getSpecifications)
    {
        return response()->json($getSpecifications->execute($product));
    }

    public function updateSpecifications(Request $request, Product $product, UpdateProductSpecifications $updateSpecifications)
    {
        $validated = $request->validate([
            'specifications' => 'present|array',
            'specifications.*' => 'string|max:500',
        ]);

        $dto = new UpdateSpecificationsDTO(specifications: $validated['specifications']);
        $result = $updateSpecifications->execute($dto, $product);

        return response()->json($result);
    }
}
