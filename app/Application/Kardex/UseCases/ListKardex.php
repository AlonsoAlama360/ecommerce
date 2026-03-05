<?php

namespace App\Application\Kardex\UseCases;

use App\Application\Kardex\DTOs\KardexFiltersDTO;
use App\Domain\Kardex\Repositories\KardexRepositoryInterface;

class ListKardex
{
    public function __construct(
        private KardexRepositoryInterface $kardexRepository,
    ) {}

    public function execute(KardexFiltersDTO $filters): array
    {
        $movements = $this->kardexRepository->paginate([
            'product_id' => $filters->productId,
            'type' => $filters->type,
            'date_from' => $filters->dateFrom,
            'date_to' => $filters->dateTo,
        ], $filters->perPage);

        $stats = $this->kardexRepository->getMonthlyStats();
        $products = $this->kardexRepository->getActiveProducts();

        return [
            'movements' => $movements,
            'movementsToday' => $stats->movementsToday,
            'entriesMonth' => $stats->entriesMonth,
            'exitsMonth' => $stats->exitsMonth,
            'adjustmentsMonth' => $stats->adjustmentsMonth,
            'products' => $products,
        ];
    }
}
