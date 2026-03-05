<?php

namespace App\Application\Complaint\UseCases;

use App\Application\Complaint\DTOs\CreateComplaintDTO;
use App\Domain\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Mail\Admin\NewComplaintNotificationMail;
use App\Models\Complaint;
use App\Services\AdminNotificationService;

class CreateComplaint
{
    public function __construct(
        private ComplaintRepositoryInterface $complaintRepository,
    ) {}

    public function execute(CreateComplaintDTO $dto): Complaint
    {
        $data = [
            'complaint_number' => Complaint::generateNumber(),
            'consumer_name' => $dto->consumerName,
            'consumer_document_type' => $dto->consumerDocumentType,
            'consumer_document_number' => $dto->consumerDocumentNumber,
            'consumer_email' => $dto->consumerEmail,
            'consumer_phone' => $dto->consumerPhone,
            'consumer_address' => $dto->consumerAddress,
            'representative_name' => $dto->representativeName,
            'representative_email' => $dto->representativeEmail,
            'product_type' => $dto->productType,
            'product_description' => $dto->productDescription,
            'order_number' => $dto->orderNumber,
            'complaint_type' => $dto->complaintType,
            'complaint_detail' => $dto->complaintDetail,
            'consumer_request' => $dto->consumerRequest,
        ];

        $complaint = $this->complaintRepository->create($data);

        AdminNotificationService::send('notify_new_complaint', new NewComplaintNotificationMail($complaint));

        return $complaint;
    }
}
