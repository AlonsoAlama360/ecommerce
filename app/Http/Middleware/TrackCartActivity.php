<?php

namespace App\Http\Middleware;

use App\Models\AbandonedCart;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackCartActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$request->user()) {
            return $response;
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            AbandonedCart::where('user_id', $request->user()->id)
                ->whereNull('recovered_at')
                ->whereNull('email_sent_at')
                ->delete();
            return $response;
        }

        AbandonedCart::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'recovered_at' => null,
            ],
            [
                'cart_data' => $cart,
            ]
        );

        return $response;
    }
}
