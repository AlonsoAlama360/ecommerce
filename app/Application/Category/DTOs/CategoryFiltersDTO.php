<?php

namespace App\Application\Category\DTOs;

use Illuminate\Http\Request;

class CategoryFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $status = null,
        public readonly int $perPage = 20,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->get('search'),
            status: $request->has('status') && $request->get('status') !== '' ? $request->get('status') : null,
            perPage: (int) $request->get('per_page', 20),
        );
    }
}
