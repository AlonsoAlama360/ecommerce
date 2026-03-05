<?php

namespace App\Application\Purchase\UseCases;

use App\Application\Purchase\DTOs\UpdatePurchaseDTO;
use App\Domain\Purchase\Repositories\PurchaseRepositoryInterface;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class UpdatePurchase
{
    public function __construct(
        private PurchaseRepositoryInterface $purchaseRepository,
    ) {}

    public function execute(UpdatePurchaseDTO $dto, Purchase $purchase): Purchase
    {
        return DB::transaction(function () use ($dto, $purchase) {
            $oldStatus = $purchase->status;
            $newStatus = $dto->status ?? $oldStatus;

            $updateData = [];

            if ($dto->items !== null) {
                if ($oldStatus === 'recibido') {
                    foreach ($purchase->items as $oldItem) {
                        if ($oldItem->product_id) {
                            $oldProduct = Product::find($oldItem->product_id);
                            if ($oldProduct) {
                                StockService::decrement($oldProduct, $oldItem->quantity, $purchase, "Reversión edición compra {$purchase->purchase_number}");
                            }
                        }
                    }
                }

                $purchase->items()->delete();

                $subtotal = 0;
                foreach ($dto->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $lineTotal = $item['unit_cost'] * $item['quantity'];
                    $subtotal += $lineTotal;

                    $purchase->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'quantity' => $item['quantity'],
                        'unit_cost' => $item['unit_cost'],
                        'line_total' => $lineTotal,
                    ]);
                }

                $updateData['subtotal'] = $subtotal;
                $updateData['total'] = $subtotal + ($purchase->tax_amount ?? 0);

                if ($newStatus === 'recibido') {
                    $purchase->load('items');
                    foreach ($purchase->items as $newItem) {
                        if ($newItem->product_id) {
                            $newProduct = Product::find($newItem->product_id);
                            if ($newProduct) {
                                StockService::increment($newProduct, $newItem->quantity, $purchase, "Compra {$purchase->purchase_number} recibida (edición)");
                            }
                        }
                    }
                    $updateData['received_date'] = now()->toDateString();
                }
            } else {
                $this->handleStockOnStatusChange($purchase, $oldStatus, $newStatus);
            }

            if ($dto->supplierId !== null) {
                $updateData['supplier_id'] = $dto->supplierId;
            }
            $updateData['status'] = $newStatus;
            $updateData['expected_date'] = $dto->expectedDate;
            $updateData['notes'] = $dto->notes;
            if ($dto->shippingAddress !== null) {
                $updateData['shipping_address'] = $dto->shippingAddress;
            }

            if ($newStatus === 'recibido' && $oldStatus !== 'recibido' && !isset($updateData['received_date'])) {
                $updateData['received_date'] = now()->toDateString();
            }

            return $this->purchaseRepository->update($purchase, $updateData);
        });
    }

    private function handleStockOnStatusChange(Purchase $purchase, string $oldStatus, string $newStatus): void
    {
        if ($oldStatus === $newStatus) return;

        $purchase->load('items');

        if ($newStatus === 'recibido' && $oldStatus !== 'recibido') {
            foreach ($purchase->items as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        StockService::increment($product, $item->quantity, $purchase, "Compra {$purchase->purchase_number} recibida");
                    }
                }
            }
        }

        if ($oldStatus === 'recibido' && $newStatus !== 'recibido') {
            foreach ($purchase->items as $item) {
                if ($item->product_id) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        StockService::decrement($product, $item->quantity, $purchase, "Reversión compra {$purchase->purchase_number}");
                    }
                }
            }
        }
    }
}
