<?php

namespace App\Application\Purchase\DTOs;

use Illuminate\Http\Request;

class PurchaseFiltersDTO
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $status = null,
        public readonly ?int $supplierId = null,
        public readonly ?string $dateFrom = null,
        public readonly ?string $dateTo = null,
        public readonly int $perPage = 10,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            search: $request->get('search'),
            status: $request->get('status'),
            supplierId: $request->get('supplier_id') ? (int) $request->get('supplier_id') : null,
            dateFrom: $request->get('date_from'),
            dateTo: $request->get('date_to'),
            perPage: (int) $request->get('per_page', 10),
        );
    }
}
