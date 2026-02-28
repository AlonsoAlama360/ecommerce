<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterSubscribedMail;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = $request->input('email');

        $subscriber = Subscriber::where('email', $email)->first();

        if ($subscriber) {
            if ($subscriber->is_active) {
                return response()->json(['message' => 'Ya estás suscrito/a a nuestro newsletter.']);
            }
            $subscriber->update(['is_active' => true]);
        } else {
            Subscriber::create(['email' => $email]);
        }

        // Update newsletter flag if user exists
        User::where('email', $email)->update(['newsletter' => true]);

        try {
            Mail::to($email)->send(new NewsletterSubscribedMail($email));
        } catch (\Exception $e) {
            Log::error('Error enviando email newsletter: ' . $e->getMessage());
        }

        return response()->json(['message' => '¡Te has suscrito exitosamente! Revisa tu correo.']);
    }
}
