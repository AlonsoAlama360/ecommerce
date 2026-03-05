<?php

namespace App\Infrastructure\Product\Providers;

use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Product\Repositories\EloquentProductRepository;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
    }
}
