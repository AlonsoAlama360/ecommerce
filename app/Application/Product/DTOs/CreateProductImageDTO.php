<?php

namespace App\Application\Product\DTOs;

class CreateProductImageDTO
{
    public function __construct(
        public readonly string $imageUrl,
        public readonly bool $isPrimary,
        public readonly ?string $thumbnailUrl = null,
        public readonly ?string $altText = null,
    ) {}
}
