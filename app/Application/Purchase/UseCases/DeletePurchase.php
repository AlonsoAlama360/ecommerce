<?php

namespace App\Application\Purchase\UseCases;

use App\Domain\Purchase\Repositories\PurchaseRepositoryInterface;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class DeletePurchase
{
    public function __construct(
        private PurchaseRepositoryInterface $purchaseRepository,
    ) {}

    public function execute(Purchase $purchase): void
    {
        DB::transaction(function () use ($purchase) {
            if ($purchase->status === 'recibido') {
                foreach ($purchase->items as $item) {
                    if ($item->product_id) {
                        $product = Product::find($item->product_id);
                        if ($product) {
                            StockService::decrement($product, $item->quantity, $purchase, "Eliminación compra {$purchase->purchase_number}");
                        }
                    }
                }
            }

            $this->purchaseRepository->delete($purchase);
        });
    }
}
