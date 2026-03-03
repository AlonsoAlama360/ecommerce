<?php

namespace App\Application\User\DTOs;

class SocialUserDTO
{
    public function __construct(
        public readonly string $provider,
        public readonly string $providerId,
        public readonly string $name,
        public readonly ?string $email,
    ) {}
}
