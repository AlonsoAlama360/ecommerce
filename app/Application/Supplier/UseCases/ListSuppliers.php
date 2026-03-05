<?php

namespace App\Application\Supplier\UseCases;

use App\Application\Supplier\DTOs\SupplierFiltersDTO;
use App\Domain\Supplier\Repositories\SupplierRepositoryInterface;

class ListSuppliers
{
    public function __construct(
        private SupplierRepositoryInterface $supplierRepository,
    ) {}

    public function execute(SupplierFiltersDTO $dto): array
    {
        $filters = [
            'search' => $dto->search,
            'status' => $dto->status,
            'city' => $dto->city,
        ];

        $suppliers = $this->supplierRepository->paginate($filters, $dto->perPage);
        $stats = $this->supplierRepository->getStats();
        $cities = $this->supplierRepository->getDistinctCities();

        return [
            'suppliers' => $suppliers,
            'totalSuppliers' => (int) $stats->total,
            'activeSuppliers' => (int) ($stats->active ?? 0),
            'inactiveSuppliers' => (int) ($stats->inactive ?? 0),
            'newSuppliersWeek' => (int) ($stats->new_week ?? 0),
            'cities' => $cities,
        ];
    }
}
