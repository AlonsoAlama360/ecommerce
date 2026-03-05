<?php

namespace App\Application\Order\DTOs;

use Illuminate\Http\Request;

class CreateOrderDTO
{
    public function __construct(
        public readonly string $customerName,
        public readonly string $paymentMethod,
        public readonly string $paymentStatus,
        public readonly array $items,
        public readonly ?string $customerEmail = null,
        public readonly ?string $customerPhone = null,
        public readonly ?string $shippingAddress = null,
        public readonly ?string $adminNotes = null,
        public readonly int $createdBy = 0,
        public readonly string $source = 'admin',
        public readonly ?string $paymentReference = null,
        public readonly ?string $customerNotes = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            customerName: $request->input('customer_name'),
            paymentMethod: $request->input('payment_method'),
            paymentStatus: $request->input('payment_status'),
            items: $request->input('items', []),
            customerEmail: $request->input('customer_email'),
            customerPhone: $request->input('customer_phone'),
            shippingAddress: $request->input('shipping_address'),
            adminNotes: $request->input('admin_notes'),
            createdBy: auth()->id(),
        );
    }
}
