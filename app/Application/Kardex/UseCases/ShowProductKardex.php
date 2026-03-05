<?php

namespace App\Application\Kardex\UseCases;

use App\Application\Kardex\DTOs\KardexFiltersDTO;
use App\Domain\Kardex\Repositories\KardexRepositoryInterface;
use App\Models\Product;

class ShowProductKardex
{
    public function __construct(
        private KardexRepositoryInterface $kardexRepository,
    ) {}

    public function execute(Product $product, KardexFiltersDTO $filters): array
    {
        $product->load('primaryImage', 'category');

        $movements = $this->kardexRepository->paginateByProduct(
            $product->id,
            [
                'type' => $filters->type,
                'date_from' => $filters->dateFrom,
                'date_to' => $filters->dateTo,
            ],
            $filters->perPage
        );

        $stats = $this->kardexRepository->getProductStats($product->id);

        return [
            'product' => $product,
            'movements' => $movements,
            'totalEntries' => $stats->totalEntries,
            'totalExits' => $stats->totalExits,
            'totalAdjustments' => $stats->totalAdjustments,
            'totalMovements' => $stats->totalMovements,
        ];
    }
}
