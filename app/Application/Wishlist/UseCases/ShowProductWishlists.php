<?php

namespace App\Application\Wishlist\UseCases;

use App\Domain\Wishlist\Repositories\WishlistRepositoryInterface;
use App\Models\Product;

class ShowProductWishlists
{
    public function __construct(
        private readonly WishlistRepositoryInterface $repository
    ) {}

    public function execute(Product $product, ?string $search = null, int $perPage = 15): array
    {
        $product->load('primaryImage', 'category');

        $wishlists = $this->repository->getProductWishlists(
            productId: $product->id,
            search: $search,
            perPage: $perPage
        );

        $totalInterested = $this->repository->getProductWishlistCount($product->id);

        return [
            'product' => $product,
            'wishlists' => $wishlists,
            'totalInterested' => $totalInterested,
        ];
    }
}
