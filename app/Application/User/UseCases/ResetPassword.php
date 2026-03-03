<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\ResetPasswordDTO;
use App\Application\User\Ports\PasswordResetInterface;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\Repositories\UserRepositoryInterface;

class ResetPassword
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordResetInterface $passwordReset,
    ) {}

    public function execute(ResetPasswordDTO $dto): void
    {
        if ($this->passwordReset->isTokenExpired($dto->email)) {
            $this->passwordReset->deleteToken($dto->email);
            throw new \InvalidArgumentException('Este enlace ha expirado. Solicita uno nuevo.');
        }

        if (!$this->passwordReset->verifyToken($dto->email, $dto->token)) {
            throw new \InvalidArgumentException('Este enlace de recuperación no es válido.');
        }

        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->userRepository->update($user, [
            'password' => bcrypt($dto->password),
        ]);

        $this->passwordReset->deleteToken($dto->email);
    }
}
