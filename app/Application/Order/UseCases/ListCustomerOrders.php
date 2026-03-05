<?php

namespace App\Application\Order\UseCases;

use App\Domain\Order\Repositories\OrderRepositoryInterface;

class ListCustomerOrders
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(int $userId, ?string $status = null): array
    {
        $orders = $this->orderRepository->getCustomerOrders($userId, $status);
        $statusCounts = $this->orderRepository->getCustomerStatusCounts($userId);

        return compact('orders', 'statusCounts');
    }
}
