<?php

namespace App\Infrastructure\User\Providers;

use App\Application\User\Ports\AuthServiceInterface;
use App\Application\User\Ports\MailServiceInterface;
use App\Application\User\Ports\PasswordResetInterface;
use App\Application\User\Ports\SocialAuthInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\User\Repositories\EloquentUserRepository;
use App\Infrastructure\User\Services\LaravelAuthService;
use App\Infrastructure\User\Services\LaravelMailService;
use App\Infrastructure\User\Services\LaravelPasswordReset;
use App\Infrastructure\User\Services\SocialiteAuthService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(AuthServiceInterface::class, LaravelAuthService::class);
        $this->app->bind(MailServiceInterface::class, LaravelMailService::class);
        $this->app->bind(SocialAuthInterface::class, SocialiteAuthService::class);
        $this->app->bind(PasswordResetInterface::class, LaravelPasswordReset::class);
    }
}
