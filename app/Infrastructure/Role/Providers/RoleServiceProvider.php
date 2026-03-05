<?php

namespace App\Infrastructure\Role\Providers;

use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Infrastructure\Role\Repositories\EloquentRoleRepository;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
    }
}
