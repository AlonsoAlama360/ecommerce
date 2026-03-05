<?php

namespace App\Domain\Wishlist\Repositories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

interface WishlistRepositoryInterface
{
    public function paginateProducts(array $filters, int $perPage = 15): mixed;

    public function getStats(): array;

    public function getProductWishlists(int $productId, ?string $search = null, int $perPage = 15): mixed;

    public function getProductWishlistCount(int $productId): int;

    public function getUserWishlistProducts(User $user, int $perPage = 12): mixed;

    public function toggle(User $user, int $productId): string;

    public function getCount(User $user): array;

    public function getActiveCategories(): Collection;
}
