<?php

namespace App\Application\Contact\DTOs;

use Illuminate\Http\Request;

class ContactFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $status = null,
        public readonly int $perPage = 15,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->get('search'),
            status: $request->has('status') && $request->get('status') !== '' ? $request->get('status') : null,
            perPage: (int) $request->get('per_page', 15),
        );
    }
}
