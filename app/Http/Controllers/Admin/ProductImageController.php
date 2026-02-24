<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function index(Product $product)
    {
        $images = $product->images()->orderBy('sort_order')->get();

        return response()->json($images);
    }

    public function store(Request $request, Product $product)
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

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image_url'] = '/storage/' . $path;
            unset($validated['image_file']);
        }

        $validated['is_primary'] = $request->boolean('is_primary');
        $validated['sort_order'] = ($product->images()->max('sort_order') ?? -1) + 1;

        if ($validated['is_primary']) {
            $product->images()->update(['is_primary' => false]);
        }

        if ($product->images()->count() === 0) {
            $validated['is_primary'] = true;
        }

        $image = $product->images()->create($validated);

        return response()->json($image, 201);
    }

    public function update(Request $request, Product $product, ProductImage $image)
    {
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
        ]);

        if ($request->has('is_primary') && $request->boolean('is_primary')) {
            $product->images()->where('id', '!=', $image->id)->update(['is_primary' => false]);
            $validated['is_primary'] = true;
        }

        $image->update($validated);

        return response()->json($image);
    }

    public function destroy(Product $product, ProductImage $image)
    {
        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $first = $product->images()->orderBy('sort_order')->first();
            if ($first) {
                $first->update(['is_primary' => true]);
            }
        }

        return response()->json(['message' => 'Imagen eliminada']);
    }

    public function reorder(Request $request, Product $product)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:product_images,id',
        ]);

        foreach ($validated['order'] as $index => $id) {
            ProductImage::where('id', $id)->where('product_id', $product->id)->update(['sort_order' => $index]);
        }

        return response()->json(['message' => 'Orden actualizado']);
    }
}
