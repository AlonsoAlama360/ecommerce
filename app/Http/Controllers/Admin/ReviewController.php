<?php

namespace App\Http\Controllers\Admin;

use App\Application\Review\DTOs\ReviewFiltersDTO;
use App\Application\Review\UseCases\ApproveReview;
use App\Application\Review\UseCases\DeleteReview;
use App\Application\Review\UseCases\ListReviews;
use App\Application\Review\UseCases\RejectReview;
use App\Application\Review\UseCases\ToggleFeaturedReview;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request, ListReviews $listReviews)
    {
        $dto = ReviewFiltersDTO::fromRequest($request);
        $data = $listReviews->execute($dto);

        return view('admin.reviews.index', $data);
    }

    public function approve(Review $review, ApproveReview $approveReview)
    {
        $approveReview->execute($review);
        return back()->with('success', 'Reseña aprobada correctamente.');
    }

    public function reject(Review $review, RejectReview $rejectReview)
    {
        $rejectReview->execute($review);
        return back()->with('success', 'Reseña rechazada.');
    }

    public function toggleFeatured(Review $review, ToggleFeaturedReview $toggleFeatured)
    {
        $toggleFeatured->execute($review);

        $message = $review->fresh()->is_featured
            ? 'Reseña marcada para mostrar en el inicio.'
            : 'Reseña removida del inicio.';

        return back()->with('success', $message);
    }

    public function destroy(Review $review, DeleteReview $deleteReview)
    {
        $deleteReview->execute($review);
        return back()->with('success', 'Reseña eliminada correctamente.');
    }
}
