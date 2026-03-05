<?php

namespace App\Application\Complaint\DTOs;

use Illuminate\Http\Request;

class CreateComplaintDTO
{
    public function __construct(
        public readonly string $consumerName,
        public readonly string $consumerDocumentType,
        public readonly string $consumerDocumentNumber,
        public readonly string $consumerEmail,
        public readonly string $consumerPhone,
        public readonly string $productType,
        public readonly string $productDescription,
        public readonly string $complaintType,
        public readonly string $complaintDetail,
        public readonly string $consumerRequest,
        public readonly ?string $consumerAddress = null,
        public readonly ?string $representativeName = null,
        public readonly ?string $representativeEmail = null,
        public readonly ?string $orderNumber = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            consumerName: $request->input('consumer_name'),
            consumerDocumentType: $request->input('consumer_document_type'),
            consumerDocumentNumber: $request->input('consumer_document_number'),
            consumerEmail: $request->input('consumer_email'),
            consumerPhone: $request->input('consumer_phone'),
            productType: $request->input('product_type'),
            productDescription: $request->input('product_description'),
            complaintType: $request->input('complaint_type'),
            complaintDetail: $request->input('complaint_detail'),
            consumerRequest: $request->input('consumer_request'),
            consumerAddress: $request->input('consumer_address'),
            representativeName: $request->input('representative_name'),
            representativeEmail: $request->input('representative_email'),
            orderNumber: $request->input('order_number'),
        );
    }
}
