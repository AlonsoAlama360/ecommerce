<?php

namespace App\Application\Order\UseCases;

use App\Models\Order;

class ShowOrder
{
    public function execute(Order $order): Order
    {
        $order->load(['items.product.primaryImage', 'user', 'creator']);
        return $order;
    }
}
