<?php

namespace App\Application\Subscriber\UseCases;

use App\Application\Subscriber\DTOs\SubscriberFiltersDTO;
use App\Domain\Subscriber\Repositories\SubscriberRepositoryInterface;

class ListSubscribers
{
    public function __construct(
        private SubscriberRepositoryInterface $subscriberRepository,
    ) {}

    public function execute(SubscriberFiltersDTO $dto): array
    {
        $filters = [
            'search' => $dto->search,
            'status' => $dto->status,
        ];

        $subscribers = $this->subscriberRepository->paginate($filters, $dto->perPage);
        $stats = $this->subscriberRepository->getStats();

        return [
            'subscribers' => $subscribers,
            'totalSubscribers' => (int) $stats->total,
            'activeSubscribers' => (int) ($stats->active ?? 0),
            'inactiveSubscribers' => (int) ($stats->inactive ?? 0),
            'newThisWeek' => (int) ($stats->new_week ?? 0),
        ];
    }
}
