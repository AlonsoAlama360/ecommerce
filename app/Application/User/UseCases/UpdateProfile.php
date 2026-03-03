<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UpdateProfileDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;

class UpdateProfile
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(User $user, UpdateProfileDTO $dto): User
    {
        return $this->userRepository->update($user, [
            'first_name' => $dto->firstName,
            'last_name' => $dto->lastName,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'document_type' => $dto->documentType,
            'document_number' => $dto->documentNumber,
            'department_id' => $dto->departmentId,
            'province_id' => $dto->provinceId,
            'district_id' => $dto->districtId,
            'address' => $dto->address,
            'address_reference' => $dto->addressReference,
            'newsletter' => $dto->newsletter,
        ]);
    }
}
