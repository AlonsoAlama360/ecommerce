<?php

namespace App\Infrastructure\User\Services;

use App\Application\User\Ports\MailServiceInterface;
use App\Mail\ResetPasswordMail;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class LaravelMailService implements MailServiceInterface
{
    public function sendWelcomeEmail(User $user): void
    {
        Mail::to($user)->queue(new WelcomeMail($user));
    }

    public function sendPasswordResetEmail(User $user, string $resetUrl): void
    {
        Mail::to($user->email)->queue(new ResetPasswordMail($user, $resetUrl));
    }
}
