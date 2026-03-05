<?php

namespace App\Application\Order\DTOs;

use Illuminate\Http\Request;

class UpdateOrderDTO
{
    public function __construct(
        public readonly ?string $status = null,
        public readonly ?string $paymentStatus = null,
        public readonly ?string $paymentMethod = null,
        public readonly ?string $adminNotes = null,
        public readonly ?string $shippingAddress = null,
        public readonly ?string $customerName = null,
        public readonly ?string $customerEmail = null,
        public readonly ?string $customerPhone = null,
        public readonly ?array $items = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            status: $request->input('status'),
            paymentStatus: $request->input('payment_status'),
            paymentMethod: $request->input('payment_method'),
            adminNotes: $request->input('admin_notes'),
            shippingAddress: $request->input('shipping_address'),
            customerName: $request->input('customer_name'),
            customerEmail: $request->input('customer_email'),
            customerPhone: $request->input('customer_phone'),
            items: $request->has('items') ? $request->input('items') : null,
        );
    }
}
