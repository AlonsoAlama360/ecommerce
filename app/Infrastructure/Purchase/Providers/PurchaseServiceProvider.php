<?php

namespace App\Infrastructure\Purchase\Providers;

use App\Domain\Purchase\Repositories\PurchaseRepositoryInterface;
use App\Infrastructure\Purchase\Repositories\EloquentPurchaseRepository;
use Illuminate\Support\ServiceProvider;

class PurchaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PurchaseRepositoryInterface::class, EloquentPurchaseRepository::class);
    }
}
