<?php

namespace App\Application\Kardex\DTOs;

use Illuminate\Http\Request;

class AdjustStockDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $newStock,
        public readonly ?string $notes = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            productId: (int) $request->validated('product_id'),
            newStock: (int) $request->validated('new_stock'),
            notes: $request->validated('notes') ?? 'Ajuste manual de stock',
        );
    }
}
