<?php

namespace App\Application\Product\DTOs;

use Illuminate\Http\Request;

class UpdateProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $sku,
        public readonly int $categoryId,
        public readonly float $price,
        public readonly int $stock,
        public readonly bool $isFeatured,
        public readonly bool $isActive,
        public readonly ?string $slug = null,
        public readonly ?string $shortDescription = null,
        public readonly ?string $description = null,
        public readonly ?float $salePrice = null,
        public readonly ?string $material = null,
        public readonly ?string $imageUrl = null,
        public readonly mixed $imageFile = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            sku: $request->input('sku'),
            categoryId: (int) $request->input('category_id'),
            price: (float) $request->input('price'),
            stock: (int) $request->input('stock'),
            isFeatured: $request->boolean('is_featured'),
            isActive: $request->boolean('is_active'),
            slug: $request->input('slug'),
            shortDescription: $request->input('short_description'),
            description: $request->input('description'),
            salePrice: $request->input('sale_price') ? (float) $request->input('sale_price') : null,
            material: $request->input('material'),
            imageUrl: $request->input('image_url'),
            imageFile: $request->file('image_file'),
        );
    }
}
