<?php

namespace App\Application\User\Ports;

use App\Models\User;

interface AuthServiceInterface
{
    public function attempt(string $email, string $password, bool $remember = false): bool;

    public function login(User $user, bool $remember = false): void;

    public function logout(): void;

    public function user(): ?User;

    public function id(): ?int;

    public function check(): bool;

    public function regenerateSession(): void;

    public function invalidateSession(): void;
}
