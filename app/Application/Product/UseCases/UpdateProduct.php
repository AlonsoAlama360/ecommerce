<?php

namespace App\Application\Product\UseCases;

use App\Application\Product\DTOs\UpdateProductDTO;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Mail\Admin\LowStockImmediateAlertMail;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\AdminNotificationService;
use App\Services\ImageService;
use Illuminate\Support\Str;

class UpdateProduct
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ImageService $imageService,
    ) {}

    public function execute(UpdateProductDTO $dto, Product $product): Product
    {
        $slug = $dto->slug ?: Str::slug($dto->name);
        $stockBefore = $product->stock;

        $product = $this->productRepository->update($product, [
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
            $primary = $product->primaryImage;
            if ($primary) {
                $this->productRepository->updateImage($primary, [
                    'image_url' => $imageUrl,
                    'thumbnail_url' => $thumbnailPath,
                ]);
            } else {
                $this->productRepository->createImage($product, [
                    'image_url' => $imageUrl,
                    'thumbnail_url' => $thumbnailPath,
                    'is_primary' => true,
                ]);
            }
        }

        // Verificar si el stock cruzó el umbral o llegó a 0
        if ($dto->stock !== null && $dto->stock != $stockBefore) {
            $threshold = (int) SiteSetting::get('low_stock_threshold', 5);
            $crossedThreshold = $stockBefore > $threshold && $dto->stock <= $threshold;
            $reachedZero = $stockBefore > 0 && $dto->stock === 0;

            if ($crossedThreshold || $reachedZero) {
                $product->load('category');
                AdminNotificationService::send(
                    'notify_low_stock',
                    new LowStockImmediateAlertMail($product, $threshold)
                );
            }
        }

        return $product;
    }
}
