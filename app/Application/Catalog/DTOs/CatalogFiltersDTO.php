<?php

namespace App\Application\Catalog\DTOs;

use Illuminate\Http\Request;

class CatalogFiltersDTO
{
    public function __construct(
        public readonly ?array $categories = null,
        public readonly ?float $priceMin = null,
        public readonly ?float $priceMax = null,
        public readonly bool $inStock = false,
        public readonly bool $onSale = false,
        public readonly string $sort = 'relevant',
    ) {}

    public static function fromRequest(Request $request): self
    {
        $categories = null;
        if ($request->filled('categories')) {
            $categories = is_array($request->categories)
                ? $request->categories
                : explode(',', $request->categories);
        }

        return new self(
            categories: $categories,
            priceMin: $request->filled('price_min') ? (float) $request->price_min : null,
            priceMax: $request->filled('price_max') ? (float) $request->price_max : null,
            inStock: $request->boolean('in_stock'),
            onSale: $request->boolean('on_sale'),
            sort: $request->get('sort', 'relevant'),
        );
    }
}
