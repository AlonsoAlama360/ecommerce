<?php

namespace App\Application\Purchase\UseCases;

use App\Application\Purchase\DTOs\PurchaseFiltersDTO;
use App\Domain\Purchase\Repositories\PurchaseRepositoryInterface;

class ListPurchases
{
    public function __construct(
        private PurchaseRepositoryInterface $purchaseRepository,
    ) {}

    public function execute(PurchaseFiltersDTO $dto): array
    {
        $filters = [
            'search' => $dto->search,
            'status' => $dto->status,
            'supplier_id' => $dto->supplierId,
            'date_from' => $dto->dateFrom,
            'date_to' => $dto->dateTo,
        ];

        $purchases = $this->purchaseRepository->paginate($filters, $dto->perPage);
        $stats = $this->purchaseRepository->getStats();
        $suppliers = $this->purchaseRepository->getActiveSuppliers();

        return [
            'purchases' => $purchases,
            'totalPurchases' => (int) $stats->total,
            'purchasesToday' => (int) ($stats->today ?? 0),
            'monthlySpending' => (float) ($stats->monthly_spending ?? 0),
            'pendingPurchases' => (int) ($stats->pending ?? 0),
            'suppliers' => $suppliers,
        ];
    }
}
