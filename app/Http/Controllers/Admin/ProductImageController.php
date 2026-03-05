<?php

namespace App\Http\Controllers\Admin;

use App\Application\Product\DTOs\CreateProductImageDTO;
use App\Application\Product\UseCases\CreateProductImage;
use App\Application\Product\UseCases\DeleteProductImage;
use App\Application\Product\UseCases\ListProductImages;
use App\Application\Product\UseCases\ReorderProductImages;
use App\Application\Product\UseCases\UpdateProductImage;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function index(Product $product, ListProductImages $listImages)
    {
        return response()->json($listImages->execute($product));
    }

    public function store(Request $request, Product $product, CreateProductImage $createImage, ImageService $imageService)
    {
        $rules = [
            'alt_text' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ];

        if ($request->hasFile('image_file')) {
            $rules['image_file'] = 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048';
        } else {
            $rules['image_url'] = 'required|url|max:500';
        }

        $validated = $request->validate($rules);

        $imageUrl = $validated['image_url'] ?? null;
        $thumbnailUrl = null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $imageUrl = '/storage/' . $path;
            $thumbnailUrl = $imageService->generateThumbnail($path);
        }

        $dto = new CreateProductImageDTO(
            imageUrl: $imageUrl,
            isPrimary: $request->boolean('is_primary'),
            thumbnailUrl: $thumbnailUrl,
            altText: $validated['alt_text'] ?? null,
        );

        $image = $createImage->execute($dto, $product);

        return response()->json($image, 201);
    }

    public function update(Request $request, Product $product, ProductImage $image, UpdateProductImage $updateImage)
    {
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $result = $updateImage->execute($product, $image, $validated);

        return response()->json($result);
    }

    public function destroy(Product $product, ProductImage $image, DeleteProductImage $deleteImage)
    {
        $deleteImage->execute($image);

        return response()->json(['message' => 'Imagen eliminada']);
    }

    public function reorder(Request $request, Product $product, ReorderProductImages $reorderImages)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:product_images,id',
        ]);

        $reorderImages->execute($product, $validated['order']);

        return response()->json(['message' => 'Orden actualizado']);
    }
}
