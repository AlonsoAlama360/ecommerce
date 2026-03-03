<?php

namespace App\Application\User\DTOs;

class UpdateProfileDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly ?string $documentType,
        public readonly ?string $documentNumber,
        public readonly ?int $departmentId,
        public readonly ?int $provinceId,
        public readonly ?int $districtId,
        public readonly ?string $address,
        public readonly ?string $addressReference,
        public readonly bool $newsletter,
    ) {}
}
