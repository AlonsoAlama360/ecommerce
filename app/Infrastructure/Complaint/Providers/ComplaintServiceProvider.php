<?php

namespace App\Infrastructure\Complaint\Providers;

use App\Domain\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Infrastructure\Complaint\Repositories\EloquentComplaintRepository;
use Illuminate\Support\ServiceProvider;

class ComplaintServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ComplaintRepositoryInterface::class, EloquentComplaintRepository::class);
    }
}
