<?php

namespace App\Application\Subscriber\UseCases;

use App\Domain\Subscriber\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;

class ToggleSubscriberStatus
{
    public function __construct(
        private SubscriberRepositoryInterface $subscriberRepository,
    ) {}

    public function execute(Subscriber $subscriber): Subscriber
    {
        return $this->subscriberRepository->update($subscriber, [
            'is_active' => !$subscriber->is_active,
        ]);
    }
}
