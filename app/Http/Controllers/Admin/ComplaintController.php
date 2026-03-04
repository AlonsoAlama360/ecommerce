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

        $perPage = $request->get('per_page', 15);
        $complaints = $query->latest()->paginate($perPage)->withQueryString();

        $cs = \DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(status = 'pendiente') as pendiente,
                SUM(status = 'en_proceso') as en_proceso,
                SUM(status = 'resuelto') as resuelto
            FROM complaints
        ");
        $stats = [
            'total' => (int) $cs->total,
            'pendiente' => (int) ($cs->pendiente ?? 0),
            'en_proceso' => (int) ($cs->en_proceso ?? 0),
            'resuelto' => (int) ($cs->resuelto ?? 0),
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
