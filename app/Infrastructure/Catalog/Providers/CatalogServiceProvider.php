<?php

namespace App\Infrastructure\Catalog\Providers;

use App\Domain\Catalog\Repositories\CatalogRepositoryInterface;
use App\Infrastructure\Catalog\Repositories\EloquentCatalogRepository;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CatalogRepositoryInterface::class, EloquentCatalogRepository::class);
    }
}
