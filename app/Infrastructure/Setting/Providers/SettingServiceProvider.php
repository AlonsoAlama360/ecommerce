<?php

namespace App\Infrastructure\Setting\Providers;

use App\Domain\Setting\Repositories\SettingRepositoryInterface;
use App\Infrastructure\Setting\Repositories\EloquentSettingRepository;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SettingRepositoryInterface::class, EloquentSettingRepository::class);
    }
}
