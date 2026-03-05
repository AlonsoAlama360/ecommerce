<?php

namespace App\Application\Purchase\UseCases;

use App\Application\Purchase\DTOs\CreatePurchaseDTO;
use App\Domain\Purchase\Repositories\PurchaseRepositoryInterface;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class CreatePurchase
{
    public function __construct(
        private PurchaseRepositoryInterface $purchaseRepository,
    ) {}

    public function execute(CreatePurchaseDTO $dto): Purchase
    {
        return DB::transaction(function () use ($dto) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($dto->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $lineTotal = $item['unit_cost'] * $item['quantity'];
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $lineTotal,
                ];
            }

            $purchase = $this->purchaseRepository->create([
                'supplier_id' => $dto->supplierId,
                'status' => 'pendiente',
                'subtotal' => $subtotal,
                'tax_amount' => 0,
                'total' => $subtotal,
                'expected_date' => $dto->expectedDate,
                'notes' => $dto->notes,
                'created_by' => $dto->createdBy,
            ]);

            foreach ($itemsData as $itemData) {
                $purchase->items()->create($itemData);
            }

            return $purchase;
        });
    }
}
