<?php

namespace App\Infrastructure\Supplier\Providers;

use App\Domain\Supplier\Repositories\SupplierRepositoryInterface;
use App\Infrastructure\Supplier\Repositories\EloquentSupplierRepository;
use Illuminate\Support\ServiceProvider;

class SupplierServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SupplierRepositoryInterface::class, EloquentSupplierRepository::class);
    }
}
