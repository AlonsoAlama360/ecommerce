<?php

namespace App\Infrastructure\Subscriber\Providers;

use App\Domain\Subscriber\Repositories\SubscriberRepositoryInterface;
use App\Infrastructure\Subscriber\Repositories\EloquentSubscriberRepository;
use Illuminate\Support\ServiceProvider;

class SubscriberServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubscriberRepositoryInterface::class, EloquentSubscriberRepository::class);
    }
}
