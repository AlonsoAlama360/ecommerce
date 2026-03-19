<?php

namespace App\Application\Contact\UseCases;

use App\Application\Contact\DTOs\CreateContactDTO;
use App\Domain\Contact\Repositories\ContactRepositoryInterface;
use App\Mail\Admin\NewContactNotificationMail;
use App\Models\ContactMessage;
use App\Notifications\Admin\NewContactNotification;
use App\Services\AdminNotificationService;

class CreateContactMessage
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
    ) {}

    public function execute(CreateContactDTO $dto): ContactMessage
    {
        $data = [
            'name' => $dto->name,
            'email' => $dto->email,
            'subject' => $dto->subject,
            'message' => $dto->message,
            'order_number' => $dto->orderNumber,
        ];

        $contact = $this->contactRepository->create($data);

        AdminNotificationService::send('notify_new_contact', new NewContactNotificationMail($contact));
        AdminNotificationService::notify('new_contact', new NewContactNotification($contact));

        return $contact;
    }
}
