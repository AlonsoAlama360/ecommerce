<?php

namespace App\Application\Category\DTOs;

use Illuminate\Http\Request;

class UpdateCategoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly bool $isActive,
        public readonly ?string $slug = null,
        public readonly ?string $description = null,
        public readonly ?string $icon = null,
        public readonly ?string $imageUrl = null,
        public readonly ?int $sortOrder = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            isActive: $request->boolean('is_active'),
            slug: $request->input('slug'),
            description: $request->input('description'),
            icon: $request->input('icon'),
            imageUrl: $request->input('image_url'),
            sortOrder: $request->input('sort_order') !== null ? (int) $request->input('sort_order') : null,
        );
    }
}
