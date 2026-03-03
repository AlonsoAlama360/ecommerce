<?php

namespace App\Infrastructure\User\Services;

use App\Application\User\Ports\PasswordResetInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaravelPasswordReset implements PasswordResetInterface
{
    public function createToken(string $email): string
    {
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => bcrypt($token),
            'created_at' => now(),
        ]);

        return $token;
    }

    public function deleteToken(string $email): void
    {
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }

    public function verifyToken(string $email, string $token): bool
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record) {
            return false;
        }

        return password_verify($token, $record->token);
    }

    public function isTokenExpired(string $email, int $expirationMinutes = 60): bool
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record) {
            return true;
        }

        return now()->diffInMinutes($record->created_at) > $expirationMinutes;
    }

    public function buildResetUrl(string $token, string $email): string
    {
        return url('/reset-password/' . $token . '?email=' . urlencode($email));
    }
}
