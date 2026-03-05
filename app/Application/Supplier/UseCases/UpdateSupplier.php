<?php

namespace App\Application\Supplier\UseCases;

use App\Application\Supplier\DTOs\UpdateSupplierDTO;
use App\Domain\Supplier\Repositories\SupplierRepositoryInterface;
use App\Models\Supplier;

class UpdateSupplier
{
    public function __construct(
        private SupplierRepositoryInterface $supplierRepository,
    ) {}

    public function execute(UpdateSupplierDTO $dto, Supplier $supplier): Supplier
    {
        return $this->supplierRepository->update($supplier, [
            'business_name' => $dto->businessName,
            'contact_name' => $dto->contactName,
            'email' => $dto->email,
            'phone' => $dto->phone,
            'ruc' => $dto->ruc,
            'address' => $dto->address,
            'city' => $dto->city,
            'notes' => $dto->notes,
            'is_active' => $dto->isActive,
        ]);
    }
}
