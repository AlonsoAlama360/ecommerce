<?php

namespace App\Application\Review\DTOs;

use Illuminate\Http\Request;

class ReviewFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $status = null,
        public readonly ?int $rating = null,
        public readonly ?bool $featured = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->filled('search') ? $request->search : null,
            status: $request->filled('status') ? $request->status : null,
            rating: $request->filled('rating') ? (int) $request->rating : null,
            featured: $request->filled('featured') ? true : null,
        );
    }
}
