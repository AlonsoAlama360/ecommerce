<?php

namespace App\Domain\Product\Repositories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;

    public function create(array $data): Product;

    public function update(Product $product, array $data): Product;

    public function delete(Product $product): void;

    public function paginate(array $filters, int $perPage = 10): mixed;

    public function exportQuery(array $filters): mixed;

    public function getStats(): object;

    public function findBySlugWithRelations(string $slug): ?Product;

    public function getRelatedProducts(Product $product, int $limit = 4): Collection;

    // Image methods
    public function getImages(Product $product): Collection;

    public function createImage(Product $product, array $data): ProductImage;

    public function updateImage(ProductImage $image, array $data): ProductImage;

    public function deleteImage(ProductImage $image): void;

    public function reorderImages(Product $product, array $order): void;

    public function setImageAsPrimary(Product $product, ProductImage $image): void;
}
