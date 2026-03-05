<?php

namespace App\Application\Purchase\UseCases;

use App\Domain\Purchase\Repositories\PurchaseRepositoryInterface;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class UpdatePurchaseStatus
{
    public function __construct(
        private PurchaseRepositoryInterface $purchaseRepository,
    ) {}

    public function execute(Purchase $purchase, string $newStatus): Purchase
    {
        return DB::transaction(function () use ($purchase, $newStatus) {
            $oldStatus = $purchase->status;

            $this->handleStockOnStatusChange($purchase, $oldStatus, $newStatus);

            $updateData = ['status' => $newStatus];
            if ($newStatus === 'recibido' && $oldStatus !== 'recibido') {
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
