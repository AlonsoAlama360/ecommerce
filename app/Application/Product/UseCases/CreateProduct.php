<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTOs\CreateProductDTO;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Support\Str;

class CreateProduct
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ImageService $imageService,
    ) {}

    public function execute(CreateProductDTO $dto): Product
    {
        $slug = $dto->slug ?: Str::slug($dto->name);

        $product = $this->productRepository->create([
            'name' => $dto->name,
            'slug' => $slug,
            'category_id' => $dto->categoryId,
            'short_description' => $dto->shortDescription,
            'description' => $dto->description,
            'price' => $dto->price,
            'sale_price' => $dto->salePrice,
            'sku' => $dto->sku,
            'stock' => $dto->stock,
            'material' => $dto->material,
            'is_featured' => $dto->isFeatured,
            'is_active' => $dto->isActive,
        ]);

        $imageUrl = $dto->imageUrl;
        $thumbnailPath = null;

        if ($dto->imageFile) {
            $path = $dto->imageFile->store('products', 'public');
            $imageUrl = '/storage/' . $path;
            $thumbnailPath = $this->imageService->generateThumbnail($path);
        }

        if ($imageUrl) {
            $this->productRepository->createImage($product, [
                'image_url' => $imageUrl,
                'thumbnail_url' => $thumbnailPath,
                'is_primary' => true,
            ]);
        }

        return $product;
    }
}
