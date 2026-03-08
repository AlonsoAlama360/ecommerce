<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UpdateUserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class UpdateUser
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(User $user, UpdateUserDTO $dto): User
    {
        $data = [
            'first_name' => $dto->firstName,
            'last_name' => $dto->lastName,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'role' => $dto->role,
            'is_active' => $dto->isActive,
            'newsletter' => $dto->newsletter,
            'document_type' => $dto->documentType,
            'document_number' => $dto->documentNumber,
        ];

        if ($dto->password) {
            $data['password'] = $dto->password;
        }

        return $this->userRepository->update($user, $data);
    }
}
