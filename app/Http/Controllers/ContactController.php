<?php

namespace App\Http\Controllers;

use App\Application\Contact\DTOs\CreateContactDTO;
use App\Application\Contact\UseCases\CreateContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        private CreateContactMessage $createContactMessage,
    ) {}

    public function show()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:3000',
            'order_number' => 'nullable|string|max:50',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Ingresa un email válido.',
            'subject.required' => 'El asunto es obligatorio.',
            'message.required' => 'El mensaje es obligatorio.',
        ]);

        $dto = CreateContactDTO::fromRequest($request);
        $this->createContactMessage->execute($dto);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Tu mensaje ha sido enviado exitosamente. Te responderemos a la brevedad.']);
        }

        return back()->with('success', 'Tu mensaje ha sido enviado exitosamente. Te responderemos a la brevedad.');
    }
}
