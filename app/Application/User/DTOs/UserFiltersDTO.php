<?php

namespace App\Application\User\DTOs;

class UserFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $role = null,
        public readonly ?string $status = null,
        public readonly ?string $dateFrom = null,
        public readonly ?string $dateTo = null,
        public readonly int $perPage = 10,
    ) {}
}
