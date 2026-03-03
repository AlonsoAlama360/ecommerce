<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('complaint_number', 'like', "%{$search}%")
                  ->orWhere('consumer_name', 'like', "%{$search}%")
                  ->orWhere('consumer_email', 'like', "%{$search}%")
                  ->orWhere('consumer_document_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->get('type')) {
            $query->where('complaint_type', $type);
        }

        $complaints = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Complaint::count(),
            'pendiente' => Complaint::where('status', 'pendiente')->count(),
            'en_proceso' => Complaint::where('status', 'en_proceso')->count(),
            'resuelto' => Complaint::where('status', 'resuelto')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'stats'));
    }

    public function show(Complaint $complaint)
    {
        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,en_proceso,resuelto',
            'provider_response' => 'nullable|string|max:3000',
        ]);

        if ($validated['status'] === 'resuelto' && !$complaint->response_date) {
            $validated['response_date'] = now();
        }

        $complaint->update($validated);

        return back()->with('success', 'Reclamación actualizada exitosamente.');
    }
}
