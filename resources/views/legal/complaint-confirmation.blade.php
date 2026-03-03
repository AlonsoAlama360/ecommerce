@extends('layouts.app')

@section('title', 'Reclamación Registrada - Arixna')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 text-center">

            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>

            <h1 class="font-serif text-2xl md:text-3xl font-bold text-gray-900 mb-3">Reclamación registrada</h1>
            <p class="text-gray-600 mb-8">Tu reclamo ha sido recibido exitosamente. Nos comunicaremos contigo en un plazo máximo de 30 días calendario.</p>

            <div class="bg-gray-50 rounded-xl p-6 text-left space-y-3 mb-8">
                <div class="flex justify-between">
                    <span class="text-gray-500">N° de Reclamo:</span>
                    <span class="font-semibold text-gray-900">{{ $complaint->complaint_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Fecha:</span>
                    <span class="text-gray-900">{{ $complaint->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tipo:</span>
                    <span class="text-gray-900 capitalize">{{ $complaint->complaint_type }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Estado:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Reclamante:</span>
                    <span class="text-gray-900">{{ $complaint->consumer_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email:</span>
                    <span class="text-gray-900">{{ $complaint->consumer_email }}</span>
                </div>
            </div>

            <p class="text-sm text-gray-500 mb-6">Guarda el número de reclamo <strong>{{ $complaint->complaint_number }}</strong> para futuras consultas.</p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" class="bg-gray-900 text-white px-8 py-3 rounded-full font-medium hover:bg-gray-800 transition">
                    Volver al inicio
                </a>
                <a href="{{ route('complaint.create') }}" class="border border-gray-300 text-gray-700 px-8 py-3 rounded-full font-medium hover:bg-gray-50 transition">
                    Nuevo reclamo
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
