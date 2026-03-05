<?php

namespace App\Application\Review\UseCases;

use App\Application\Review\DTOs\CreateReviewDTO;
use App\Domain\Review\Repositories\ReviewRepositoryInterface;
use App\Mail\Admin\NewReviewNotificationMail;
use App\Models\Review;
use App\Services\AdminNotificationService;

class CreateReview
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
    ) {}

    public function execute(CreateReviewDTO $dto): Review|string
    {
        $existing = $this->reviewRepository->findByUserAndProduct($dto->userId, $dto->productId);

        if ($existing) {
            return 'Ya has dejado una reseña para este producto.';
        }

        $review = $this->reviewRepository->create([
            'user_id' => $dto->userId,
            'product_id' => $dto->productId,
            'rating' => $dto->rating,
            'title' => $dto->title,
            'comment' => $dto->comment,
            'is_approved' => true,
        ]);

        $review->load(['user', 'product']);
        AdminNotificationService::send('notify_new_review', new NewReviewNotificationMail($review));

        return $review;
    }
}
