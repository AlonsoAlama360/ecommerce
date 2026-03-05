<?php

namespace App\Infrastructure\Order\Providers;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Infrastructure\Order\Repositories\EloquentOrderRepository;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
    }
}
