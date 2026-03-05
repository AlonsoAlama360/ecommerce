<?php

namespace App\Application\Wishlist\UseCases;

use App\Domain\Wishlist\Repositories\WishlistRepositoryInterface;
use App\Models\User;

class GetWishlistCount
{
    public function __construct(
        private readonly WishlistRepositoryInterface $repository
    ) {}

    public function execute(?User $user): array
    {
        if (!$user) {
            return ['count' => 0, 'ids' => []];
        }

        return $this->repository->getCount($user);
    }
}
