<?php

namespace App\Infrastructure\User\Services;

use App\Application\User\Ports\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LaravelAuthService implements AuthServiceInterface
{
    public function attempt(string $email, string $password, bool $remember = false): bool
    {
        return Auth::attempt(['email' => $email, 'password' => $password], $remember);
    }

    public function login(User $user, bool $remember = false): void
    {
        Auth::login($user, $remember);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function user(): ?User
    {
        return Auth::user();
    }

    public function id(): ?int
    {
        return Auth::id();
    }

    public function check(): bool
    {
        return Auth::check();
    }

    public function regenerateSession(): void
    {
        session()->regenerate();
    }

    public function invalidateSession(): void
    {
        session()->invalidate();
        session()->regenerateToken();
    }
}
