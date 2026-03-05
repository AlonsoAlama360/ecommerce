<?php

namespace App\Application\Contact\DTOs;

use Illuminate\Http\Request;

class CreateContactDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $subject,
        public readonly string $message,
        public readonly ?string $orderNumber = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            subject: $request->input('subject'),
            message: $request->input('message'),
            orderNumber: $request->input('order_number'),
        );
    }
}
