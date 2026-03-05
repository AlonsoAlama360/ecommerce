<?php

namespace App\Application\Supplier\UseCases;

use App\Domain\Supplier\Repositories\SupplierRepositoryInterface;
use App\Models\Supplier;

class DeleteSupplier
{
    public function __construct(
        private SupplierRepositoryInterface $supplierRepository,
    ) {}

    public function execute(Supplier $supplier): void
    {
        $this->supplierRepository->delete($supplier);
    }
}
