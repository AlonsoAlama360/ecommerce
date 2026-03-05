<?php

namespace App\Application\Purchase\UseCases;

use App\Models\Purchase;

class ShowPurchase
{
    public function execute(Purchase $purchase): Purchase
    {
        $purchase->load(['items.product.primaryImage', 'supplier', 'creator']);
        return $purchase;
    }
}
