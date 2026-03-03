<?php

namespace App\Application\User\DTOs;

class RegisterUserDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $password,
        public readonly bool $newsletter = false,
    ) {}
}
