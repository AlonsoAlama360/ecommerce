<?php

namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdatePassword
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \InvalidArgumentException('La contraseña actual es incorrecta.');
        }

        $this->userRepository->update($user, [
            'password' => $newPassword,
        ]);
    }
}
