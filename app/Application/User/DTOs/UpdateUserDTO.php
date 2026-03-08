<?php

namespace App\Application\User\DTOs;

class UpdateUserDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $password,
        public readonly string $role,
        public readonly bool $isActive,
        public readonly bool $newsletter,
        public readonly ?string $phone = null,
        public readonly ?string $documentType = null,
        public readonly ?string $documentNumber = null,
        public readonly ?int $departmentId = null,
        public readonly ?int $provinceId = null,
        public readonly ?int $districtId = null,
        public readonly ?string $address = null,
        public readonly ?string $addressReference = null,
    ) {}
}
