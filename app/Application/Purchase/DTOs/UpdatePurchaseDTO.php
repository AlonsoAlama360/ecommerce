<?php

namespace App\Application\Purchase\DTOs;

use Illuminate\Http\Request;

class UpdatePurchaseDTO
{
    public function __construct(
        public readonly ?int $supplierId = null,
        public readonly ?string $status = null,
        public readonly ?string $expectedDate = null,
        public readonly ?string $notes = null,
        public readonly ?string $shippingAddress = null,
        public readonly ?array $items = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            supplierId: $request->input('supplier_id') ? (int) $request->input('supplier_id') : null,
            status: $request->input('status'),
            expectedDate: $request->input('expected_date'),
            notes: $request->input('notes'),
            shippingAddress: $request->input('shipping_address'),
            items: $request->has('items') ? $request->input('items') : null,
        );
    }
}
