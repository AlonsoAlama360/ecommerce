<?php

namespace App\Application\Supplier\UseCases;

use App\Application\Supplier\DTOs\CreateSupplierDTO;
use App\Domain\Supplier\Repositories\SupplierRepositoryInterface;
use App\Models\Supplier;

class CreateSupplier
{
    public function __construct(
        private SupplierRepositoryInterface $supplierRepository,
    ) {}

    public function execute(CreateSupplierDTO $dto): Supplier
    {
        return $this->supplierRepository->create([
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
