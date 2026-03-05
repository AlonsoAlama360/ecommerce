<?php

namespace App\Http\Controllers;

use App\Application\Review\DTOs\CreateReviewDTO;
use App\Application\Review\UseCases\CreateReview;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product, CreateReview $createReview)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Selecciona una calificación.',
            'rating.min' => 'La calificación mínima es 1 estrella.',
            'rating.max' => 'La calificación máxima es 5 estrellas.',
            'comment.required' => 'Escribe un comentario.',
            'comment.min' => 'El comentario debe tener al menos 10 caracteres.',
            'comment.max' => 'El comentario no puede exceder 1000 caracteres.',
        ]);

        $dto = new CreateReviewDTO(
            userId: auth()->id(),
            productId: $product->id,
            rating: $validated['rating'],
            comment: $validated['comment'],
            title: $validated['title'] ?? null,
        );

        $result = $createReview->execute($dto);

        if (is_string($result)) {
            return back()->with('review_error', $result);
        }

        return back()->with('review_success', '¡Gracias por tu reseña!');
    }
}
