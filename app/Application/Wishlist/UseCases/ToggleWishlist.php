<?php

namespace App\Application\Wishlist\UseCases;

use App\Domain\Wishlist\Repositories\WishlistRepositoryInterface;
use App\Models\User;

class ToggleWishlist
{
    public function __construct(
        private readonly WishlistRepositoryInterface $repository
    ) {}

    public function execute(User $user, int $productId): array
    {
        $status = $this->repository->toggle($user, $productId);
        $count = $this->repository->getCount($user)['count'];

        return [
            'status' => $status,
            'count' => $count,
        ];
    }
}
