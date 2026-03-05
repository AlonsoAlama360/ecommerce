<?php

namespace App\Application\Product\DTOs;

use Illuminate\Http\Request;

class ProductFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?int $category = null,
        public readonly ?string $status = null,
        public readonly ?string $featured = null,
        public readonly ?string $stock = null,
        public readonly int $perPage = 10,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->get('search'),
            category: $request->get('category') ? (int) $request->get('category') : null,
            status: $request->has('status') && $request->get('status') !== '' ? $request->get('status') : null,
            featured: $request->has('featured') && $request->get('featured') !== '' ? $request->get('featured') : null,
            stock: $request->has('stock') && $request->get('stock') !== '' ? $request->get('stock') : null,
            perPage: (int) $request->get('per_page', 10),
        );
    }
}
