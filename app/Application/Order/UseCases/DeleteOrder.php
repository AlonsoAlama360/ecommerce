<?php

namespace App\Application\Order\UseCases;

use App\Domain\Order\Repositories\OrderRepositoryInterface;
use App\Models\Order;

class DeleteOrder
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(Order $order): void
    {
        $this->orderRepository->delete($order);
    }
}
