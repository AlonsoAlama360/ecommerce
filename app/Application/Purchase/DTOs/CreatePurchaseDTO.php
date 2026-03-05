<?php

namespace App\Application\Purchase\DTOs;

use Illuminate\Http\Request;

class CreatePurchaseDTO
{
    public function __construct(
        public readonly int $supplierId,
        public readonly array $items,
        public readonly ?string $expectedDate = null,
        public readonly ?string $notes = null,
        public readonly int $createdBy = 0,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            supplierId: (int) $request->input('supplier_id'),
            items: $request->input('items', []),
            expectedDate: $request->input('expected_date'),
            notes: $request->input('notes'),
            createdBy: auth()->id(),
        );
    }
}
