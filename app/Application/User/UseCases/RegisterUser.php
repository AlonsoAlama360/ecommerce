<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\RegisterUserDTO;
use App\Application\User\Ports\AuthServiceInterface;
use App\Application\User\Ports\MailServiceInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class RegisterUser
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AuthServiceInterface $authService,
        private MailServiceInterface $mailService,
    ) {}

    public function execute(RegisterUserDTO $dto): User
    {
        $user = $this->userRepository->create([
            'first_name' => $dto->firstName,
            'last_name' => $dto->lastName,
            'email' => $dto->email,
            'password' => $dto->password,
            'newsletter' => $dto->newsletter,
            'auth_provider' => 'form',
        ]);

        $this->mailService->sendWelcomeEmail($user);
        $this->authService->login($user);

        return $user;
    }
}
