<?php

namespace App\Application\Wishlist\UseCases;

use App\Application\Wishlist\DTOs\WishlistFiltersDTO;
use App\Domain\Wishlist\Repositories\WishlistRepositoryInterface;

class ListAdminWishlists
{
    public function __construct(
        private readonly WishlistRepositoryInterface $repository
    ) {}

    public function execute(WishlistFiltersDTO $filters): array
    {
        $products = $this->repository->paginateProducts(
            filters: [
                'search' => $filters->search,
                'categoryId' => $filters->categoryId,
                'order' => $filters->order,
            ],
            perPage: $filters->perPage
        );

        $stats = $this->repository->getStats();
        $categories = $this->repository->getActiveCategories();

        return [
            'products' => $products,
            'totalItems' => $stats['totalItems'],
            'uniqueProducts' => $stats['uniqueProducts'],
            'uniqueClients' => $stats['uniqueClients'],
            'topProduct' => $stats['topProduct'],
            'categories' => $categories,
        ];
    }
}
