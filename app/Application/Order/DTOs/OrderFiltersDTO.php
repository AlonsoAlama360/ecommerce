<?php

namespace App\Application\Order\DTOs;

use Illuminate\Http\Request;

class OrderFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $status = null,
        public readonly ?string $source = null,
        public readonly ?string $paymentMethod = null,
        public readonly ?string $paymentStatus = null,
        public readonly ?string $dateFrom = null,
        public readonly ?string $dateTo = null,
        public readonly int $perPage = 10,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->get('search'),
            status: $request->get('status'),
            source: $request->get('source'),
            paymentMethod: $request->get('payment_method'),
            paymentStatus: $request->get('payment_status'),
            dateFrom: $request->get('date_from'),
            dateTo: $request->get('date_to'),
            perPage: (int) $request->get('per_page', 10),
        );
    }
}
