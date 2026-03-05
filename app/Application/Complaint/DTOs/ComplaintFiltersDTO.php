<?php

namespace App\Application\Complaint\DTOs;

use Illuminate\Http\Request;

class ComplaintFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $status = null,
        public readonly ?string $type = null,
        public readonly int $perPage = 15,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->get('search'),
            status: $request->has('status') && $request->get('status') !== '' ? $request->get('status') : null,
            type: $request->has('type') && $request->get('type') !== '' ? $request->get('type') : null,
            perPage: (int) $request->get('per_page', 15),
        );
    }
}
