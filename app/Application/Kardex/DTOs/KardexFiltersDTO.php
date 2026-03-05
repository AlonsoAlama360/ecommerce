<?php

namespace App\Application\Kardex\DTOs;

use Illuminate\Http\Request;

class KardexFiltersDTO
{
    public function __construct(
        public readonly ?int $productId = null,
        public readonly ?string $type = null,
        public readonly ?string $dateFrom = null,
        public readonly ?string $dateTo = null,
        public readonly int $perPage = 15,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            productId: $request->get('product_id') ? (int) $request->get('product_id') : null,
            type: $request->get('type'),
            dateFrom: $request->get('date_from'),
            dateTo: $request->get('date_to'),
            perPage: (int) $request->get('per_page', 15),
        );
    }
}
