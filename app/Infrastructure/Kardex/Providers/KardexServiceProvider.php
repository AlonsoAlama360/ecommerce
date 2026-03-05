<?php

namespace App\Infrastructure\Kardex\Providers;

use App\Domain\Kardex\Repositories\KardexRepositoryInterface;
use App\Infrastructure\Kardex\Repositories\EloquentKardexRepository;
use Illuminate\Support\ServiceProvider;

class KardexServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(KardexRepositoryInterface::class, EloquentKardexRepository::class);
    }
}
