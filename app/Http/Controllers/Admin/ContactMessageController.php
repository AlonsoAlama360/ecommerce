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

        $perPage = $request->get('per_page', 15);
        $messages = $query->latest()->paginate($perPage)->withQueryString();

        $ms = \DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(status = 'nuevo') as nuevo,
                SUM(status = 'leido') as leido,
                SUM(status = 'respondido') as respondido
            FROM contact_messages
        ");
        $stats = [
            'total' => (int) $ms->total,
            'nuevo' => (int) ($ms->nuevo ?? 0),
            'leido' => (int) ($ms->leido ?? 0),
            'respondido' => (int) ($ms->respondido ?? 0),
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
