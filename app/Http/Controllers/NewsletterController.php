<?php

namespace App\Http\Controllers;

use App\Application\Subscriber\UseCases\Subscribe;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __construct(
        private Subscribe $subscribe,
    ) {}

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $result = $this->subscribe->execute($request->input('email'));

        return response()->json(['message' => $result['message']]);
    }
}
