<?php

namespace App\Application\Subscriber\UseCases;

use App\Domain\Subscriber\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;

class DeleteSubscriber
{
    public function __construct(
        private SubscriberRepositoryInterface $subscriberRepository,
    ) {}

    public function execute(Subscriber $subscriber): void
    {
        $this->subscriberRepository->delete($subscriber);
    }
}
