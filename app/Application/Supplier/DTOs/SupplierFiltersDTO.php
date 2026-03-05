<?php

namespace App\Application\Supplier\DTOs;

use Illuminate\Http\Request;

class SupplierFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $status = null,
        public readonly ?string $city = null,
        public readonly int $perPage = 10,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->get('search'),
            status: $request->has('status') && $request->get('status') !== '' ? $request->get('status') : null,
            city: $request->get('city'),
            perPage: (int) $request->get('per_page', 10),
        );
    }
}
