<?php

namespace App\Application\User\UseCases;

use App\Application\User\Ports\MailServiceInterface;
use App\Application\User\Ports\PasswordResetInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;

class SendPasswordReset
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordResetInterface $passwordReset,
        private MailServiceInterface $mailService,
    ) {}

    public function execute(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return; // No revelar si el email existe
        }

        $this->passwordReset->deleteToken($email);
        $token = $this->passwordReset->createToken($email);
        $resetUrl = $this->passwordReset->buildResetUrl($token, $email);

        $this->mailService->sendPasswordResetEmail($user, $resetUrl);
    }
}
