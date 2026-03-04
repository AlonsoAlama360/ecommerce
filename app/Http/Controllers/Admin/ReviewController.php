<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user:id,first_name,last_name', 'product:id,name,slug'])
            ->latest();

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        $rs = \DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(is_approved = 1) as approved,
                SUM(is_approved = 0) as pending,
                AVG(CASE WHEN is_approved = 1 THEN rating ELSE NULL END) as avg_rating,
                SUM(is_featured = 1) as featured
            FROM reviews
        ");
        $totalReviews = (int) $rs->total;
        $approvedReviews = (int) ($rs->approved ?? 0);
        $pendingReviews = (int) ($rs->pending ?? 0);
        $averageRating = round((float) ($rs->avg_rating ?? 0), 1);
        $featuredCount = (int) ($rs->featured ?? 0);

        return view('admin.reviews.index', compact(
            'reviews', 'totalReviews', 'approvedReviews', 'pendingReviews', 'averageRating', 'featuredCount'
        ));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Reseña aprobada correctamente.');
    }

    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);
        return back()->with('success', 'Reseña rechazada.');
    }

    public function toggleFeatured(Review $review)
    {
        $review->update(['is_featured' => !$review->is_featured]);

        $message = $review->is_featured
            ? 'Reseña marcada para mostrar en el inicio.'
            : 'Reseña removida del inicio.';

        return back()->with('success', $message);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Reseña eliminada correctamente.');
    }
}
