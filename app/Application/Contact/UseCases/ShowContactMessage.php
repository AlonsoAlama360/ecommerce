<?php

namespace App\Application\Contact\UseCases;

use App\Domain\Contact\Repositories\ContactRepositoryInterface;
use App\Models\ContactMessage;

class ShowContactMessage
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
    ) {}

    public function execute(ContactMessage $contactMessage): ContactMessage
    {
        if ($contactMessage->status === 'nuevo') {
            $this->contactRepository->update($contactMessage, ['status' => 'leido']);
        }

        return $contactMessage;
    }
}
