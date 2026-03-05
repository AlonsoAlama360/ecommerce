<?php

namespace App\Application\Review\DTOs;

class CreateReviewDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $productId,
        public readonly int $rating,
        public readonly string $comment,
        public readonly ?string $title = null,
    ) {}
}
