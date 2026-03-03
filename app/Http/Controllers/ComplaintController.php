<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function create()
    {
        return view('legal.complaint');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'consumer_name' => 'required|string|max:255',
            'consumer_document_type' => 'required|in:DNI,CE,Pasaporte',
            'consumer_document_number' => 'required|string|max:20',
            'consumer_email' => 'required|email|max:255',
            'consumer_phone' => 'required|string|max:20',
            'consumer_address' => 'nullable|string|max:255',
            'representative_name' => 'nullable|string|max:255',
            'representative_email' => 'nullable|email|max:255',
            'product_type' => 'required|in:producto,servicio',
            'product_description' => 'required|string|max:500',
            'order_number' => 'nullable|string|max:50',
            'complaint_type' => 'required|in:reclamo,queja',
            'complaint_detail' => 'required|string|max:3000',
            'consumer_request' => 'required|string|max:3000',
        ], [
            'consumer_name.required' => 'El nombre es obligatorio.',
            'consumer_document_type.required' => 'Selecciona un tipo de documento.',
            'consumer_document_number.required' => 'El número de documento es obligatorio.',
            'consumer_email.required' => 'El email es obligatorio.',
            'consumer_email.email' => 'Ingresa un email válido.',
            'consumer_phone.required' => 'El teléfono es obligatorio.',
            'product_type.required' => 'Selecciona el tipo de bien.',
            'product_description.required' => 'Describe el producto o servicio.',
            'complaint_type.required' => 'Selecciona el tipo de reclamo.',
            'complaint_detail.required' => 'Describe el detalle de tu reclamo.',
            'consumer_request.required' => 'Indica qué solución solicitas.',
        ]);

        $validated['complaint_number'] = Complaint::generateNumber();

        $complaint = Complaint::create($validated);

        return redirect()->route('complaint.confirmation', $complaint)
            ->with('success', 'Tu reclamo ha sido registrado exitosamente.');
    }

    public function confirmation(Complaint $complaint)
    {
        return view('legal.complaint-confirmation', compact('complaint'));
    }
}
