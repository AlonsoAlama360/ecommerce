<?php

namespace App\Application\Supplier\DTOs;

use Illuminate\Http\Request;

class UpdateSupplierDTO
{
    public function __construct(
        public readonly string $businessName,
        public readonly string $contactName,
        public readonly bool $isActive,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $ruc = null,
        public readonly ?string $address = null,
        public readonly ?string $city = null,
        public readonly ?string $notes = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            businessName: $request->input('business_name'),
            contactName: $request->input('contact_name'),
            isActive: $request->boolean('is_active'),
            email: $request->input('email'),
            phone: $request->input('phone'),
            ruc: $request->input('ruc'),
            address: $request->input('address'),
            city: $request->input('city'),
            notes: $request->input('notes'),
        );
    }
}
