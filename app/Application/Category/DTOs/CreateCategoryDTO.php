<?php

namespace App\Application\Category\DTOs;

use Illuminate\Http\Request;

class CreateCategoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly bool $isActive,
        public readonly ?string $slug = null,
        public readonly ?string $description = null,
        public readonly ?string $icon = null,
        public readonly ?string $imageUrl = null,
        public readonly int $sortOrder = 0,
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
            sortOrder: (int) ($request->input('sort_order') ?? 0),
        );
    }
}
