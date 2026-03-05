<?php

namespace App\Application\Product\DTOs;

class UpdateSpecificationsDTO
{
    public function __construct(
        public readonly array $specifications,
    ) {}
}
