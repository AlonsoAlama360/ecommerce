<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class CreateUser
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(CreateUserDTO $dto): User
    {
        return $this->userRepository->create([
            'first_name' => $dto->firstName,
            'last_name' => $dto->lastName,
            'email' => $dto->email,
            'password' => $dto->password,
            'phone' => $dto->phone,
            'role' => $dto->role,
            'is_active' => $dto->isActive,
            'newsletter' => $dto->newsletter,
            'document_type' => $dto->documentType,
            'document_number' => $dto->documentNumber,
            'department_id' => $dto->departmentId,
            'province_id' => $dto->provinceId,
            'district_id' => $dto->districtId,
            'address' => $dto->address,
            'address_reference' => $dto->addressReference,
        ]);
    }
}
