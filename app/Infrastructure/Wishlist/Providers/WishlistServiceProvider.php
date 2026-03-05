<?php

namespace App\Infrastructure\Wishlist\Providers;

use App\Domain\Wishlist\Repositories\WishlistRepositoryInterface;
use App\Infrastructure\Wishlist\Repositories\EloquentWishlistRepository;
use Illuminate\Support\ServiceProvider;

class WishlistServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            WishlistRepositoryInterface::class,
            EloquentWishlistRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
