<?php

namespace App\Application\User\Ports;

use App\Models\User;

interface MailServiceInterface
{
    public function sendWelcomeEmail(User $user): void;

    public function sendPasswordResetEmail(User $user, string $resetUrl): void;
}
