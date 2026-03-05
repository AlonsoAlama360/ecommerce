<?php

namespace App\Http\Controllers\Admin;

use App\Application\Complaint\DTOs\ComplaintFiltersDTO;
use App\Application\Complaint\UseCases\ListComplaints;
use App\Application\Complaint\UseCases\UpdateComplaint;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function __construct(
        private ListComplaints $listComplaints,
        private UpdateComplaint $updateComplaint,
    ) {}

    public function index(Request $request)
    {
        $dto = ComplaintFiltersDTO::fromRequest($request);
        $result = $this->listComplaints->execute($dto);

        return view('admin.complaints.index', [
            'complaints' => $result['complaints'],
            'stats' => $result['stats'],
        ]);
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

        $this->updateComplaint->execute($complaint, $validated);

        return back()->with('success', 'Reclamación actualizada exitosamente.');
    }
}
