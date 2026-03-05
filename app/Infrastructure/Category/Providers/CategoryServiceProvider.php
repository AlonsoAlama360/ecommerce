<?php

namespace App\Infrastructure\Category\Providers;

use App\Domain\Category\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Category\Repositories\EloquentCategoryRepository;
use Illuminate\Support\ServiceProvider;

class CategoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
    }
}
