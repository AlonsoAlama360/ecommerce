<?php

namespace App\Infrastructure\Review\Providers;

use App\Domain\Review\Repositories\ReviewRepositoryInterface;
use App\Infrastructure\Review\Repositories\EloquentReviewRepository;
use Illuminate\Support\ServiceProvider;

class ReviewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ReviewRepositoryInterface::class, EloquentReviewRepository::class);
    }
}
