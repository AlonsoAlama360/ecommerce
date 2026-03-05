<?php

namespace App\Application\Wishlist\DTOs;

use Illuminate\Http\Request;

class WishlistFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?int $categoryId = null,
        public readonly string $order = 'most_wished',
        public readonly int $perPage = 15,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->get('search'),
            categoryId: $request->get('category_id') ? (int) $request->get('category_id') : null,
            order: $request->get('order', 'most_wished'),
            perPage: (int) $request->get('per_page', 15),
        );
    }
}
