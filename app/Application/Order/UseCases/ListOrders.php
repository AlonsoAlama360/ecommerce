<?php

namespace App\Application\Order\UseCases;

use App\Application\Order\DTOs\OrderFiltersDTO;
use App\Domain\Order\Repositories\OrderRepositoryInterface;

class ListOrders
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(OrderFiltersDTO $dto): array
    {
        $filters = [
            'search' => $dto->search,
            'status' => $dto->status,
            'source' => $dto->source,
            'payment_method' => $dto->paymentMethod,
            'payment_status' => $dto->paymentStatus,
            'date_from' => $dto->dateFrom,
            'date_to' => $dto->dateTo,
        ];

        $orders = $this->orderRepository->paginate($filters, $dto->perPage);
        $stats = $this->orderRepository->getStats();

        return [
            'orders' => $orders,
            'totalOrders' => (int) $stats->total,
            'ordersToday' => (int) ($stats->today ?? 0),
            'monthlyRevenue' => (float) ($stats->monthly_revenue ?? 0),
            'pendingOrders' => (int) ($stats->pending ?? 0),
        ];
    }
}
