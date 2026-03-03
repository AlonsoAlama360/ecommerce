<?php

namespace App\Application\User\Ports;

interface PasswordResetInterface
{
    public function createToken(string $email): string;

    public function deleteToken(string $email): void;

    public function verifyToken(string $email, string $token): bool;

    public function isTokenExpired(string $email, int $expirationMinutes = 60): bool;

    public function buildResetUrl(string $token, string $email): string;
}
