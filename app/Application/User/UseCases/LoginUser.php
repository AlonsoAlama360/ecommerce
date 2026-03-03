<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\LoginDTO;
use App\Application\User\Ports\AuthServiceInterface;
use App\Domain\User\Exceptions\InvalidCredentialsException;

class LoginUser
{
    public function __construct(
        private AuthServiceInterface $authService,
    ) {}

    public function execute(LoginDTO $dto): void
    {
        if (!$this->authService->attempt($dto->email, $dto->password, $dto->remember)) {
            throw new InvalidCredentialsException();
        }

        $this->authService->regenerateSession();
    }
}
