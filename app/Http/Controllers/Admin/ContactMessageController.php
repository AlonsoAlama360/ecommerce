<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'nuevo' => ContactMessage::where('status', 'nuevo')->count(),
            'leido' => ContactMessage::where('status', 'leido')->count(),
            'respondido' => ContactMessage::where('status', 'respondido')->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'nuevo') {
            $contactMessage->update(['status' => 'leido']);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function update(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'status' => 'required|in:nuevo,leido,respondido',
            'admin_notes' => 'nullable|string|max:3000',
        ]);

        $contactMessage->update($validated);

        return back()->with('success', 'Mensaje actualizado exitosamente.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Mensaje eliminado exitosamente.');
    }
}
