<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Role;

class User
{
    public function __construct(
        public readonly ?int $id,
        public string $firstName,
        public string $lastName,
        public Email $email,
        public ?string $phone,
        public Role $role,
        public bool $isActive,
        public bool $newsletter,
        public ?string $documentType,
        public ?string $documentNumber,
        public ?int $departmentId,
        public ?int $provinceId,
        public ?int $districtId,
        public ?string $address,
        public ?string $addressReference,
        public ?string $authProvider,
        public ?string $googleId = null,
        public ?string $facebookId = null,
    ) {}

    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function hasAdminAccess(): bool
    {
        return $this->role->hasAdminAccess();
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }
}
