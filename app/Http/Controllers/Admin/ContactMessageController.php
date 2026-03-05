<?php

namespace App\Http\Controllers\Admin;

use App\Application\Contact\DTOs\ContactFiltersDTO;
use App\Application\Contact\UseCases\DeleteContactMessage;
use App\Application\Contact\UseCases\ListContactMessages;
use App\Application\Contact\UseCases\ShowContactMessage;
use App\Application\Contact\UseCases\UpdateContactMessage;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function __construct(
        private ListContactMessages $listContactMessages,
        private ShowContactMessage $showContactMessage,
        private UpdateContactMessage $updateContactMessage,
        private DeleteContactMessage $deleteContactMessage,
    ) {}

    public function index(Request $request)
    {
        $dto = ContactFiltersDTO::fromRequest($request);
        $result = $this->listContactMessages->execute($dto);

        return view('admin.contact-messages.index', [
            'messages' => $result['messages'],
            'stats' => $result['stats'],
        ]);
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage = $this->showContactMessage->execute($contactMessage);

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function update(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'status' => 'required|in:nuevo,leido,respondido',
            'admin_notes' => 'nullable|string|max:3000',
        ]);

        $this->updateContactMessage->execute($contactMessage, $validated);

        return back()->with('success', 'Mensaje actualizado exitosamente.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $this->deleteContactMessage->execute($contactMessage);

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Mensaje eliminado exitosamente.');
    }
}
