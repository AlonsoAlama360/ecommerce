<?php

namespace App\Application\Wishlist\UseCases;

use App\Domain\Wishlist\Repositories\WishlistRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListUserWishlist
{
    public function __construct(
        private readonly WishlistRepositoryInterface $repository
    ) {}

    public function execute(User $user, int $perPage = 12): LengthAwarePaginator
    {
        return $this->repository->getUserWishlistProducts($user, $perPage);
    }
}
