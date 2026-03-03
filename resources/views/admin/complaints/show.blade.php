@extends('admin.layouts.app')
@section('title', 'Reclamación ' . $complaint->complaint_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.complaints.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
        <i class="fas fa-arrow-left mr-1"></i> Volver a reclamaciones
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Detalle principal -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Info del reclamo -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-file-alt text-indigo-500 mr-2"></i>
                    {{ $complaint->complaint_number }}
                </h2>
                @if($complaint->status === 'pendiente')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                        <i class="fas fa-clock mr-1"></i> Pendiente
                    </span>
                @elseif($complaint->status === 'en_proceso')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                        <i class="fas fa-spinner mr-1"></i> En proceso
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                        <i class="fas fa-check mr-1"></i> Resuelto
                    </span>
                @endif
            </div>

            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 mb-1">Fecha de registro</p>
                    <p class="text-gray-900 font-medium">{{ $complaint->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-1">Tipo</p>
                    @if($complaint->complaint_type === 'reclamo')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">Reclamo</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Queja</span>
                    @endif
                </div>
                <div>
                    <p class="text-gray-400 mb-1">Bien contratado</p>
                    <p class="text-gray-900 font-medium capitalize">{{ $complaint->product_type }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-1">N° Pedido</p>
                    <p class="text-gray-900 font-medium">{{ $complaint->order_number ?: 'No indicado' }}</p>
                </div>
            </div>
        </div>

        <!-- Descripción del producto -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Producto / Servicio</h3>
            <p class="text-gray-700 text-sm">{{ $complaint->product_description }}</p>
        </div>

        <!-- Detalle del reclamo -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Detalle de la reclamación</h3>
            <p class="text-gray-700 text-sm whitespace-pre-line">{{ $complaint->complaint_detail }}</p>
        </div>

        <!-- Pedido del consumidor -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Pedido del consumidor</h3>
            <p class="text-gray-700 text-sm whitespace-pre-line">{{ $complaint->consumer_request }}</p>
        </div>

        <!-- Respuesta del proveedor -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Respuesta del proveedor</h3>

            <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                        <option value="pendiente" {{ $complaint->status === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_proceso" {{ $complaint->status === 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                        <option value="resuelto" {{ $complaint->status === 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Respuesta</label>
                    <textarea name="provider_response" rows="5" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400" placeholder="Escribe la respuesta al consumidor...">{{ old('provider_response', $complaint->provider_response) }}</textarea>
                </div>

                @if($complaint->response_date)
                    <p class="text-xs text-gray-400">
                        <i class="fas fa-calendar mr-1"></i> Resuelto el {{ $complaint->response_date->format('d/m/Y') }}
                    </p>
                @endif

                <button type="submit" class="bg-indigo-500 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-600 transition">
                    <i class="fas fa-save mr-1"></i> Guardar respuesta
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar - Datos del consumidor -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">
                <i class="fas fa-user text-indigo-500 mr-2"></i> Consumidor
            </h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-400">Nombre</p>
                    <p class="text-gray-900 font-medium">{{ $complaint->consumer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Documento</p>
                    <p class="text-gray-900 font-medium">{{ $complaint->consumer_document_type }} {{ $complaint->consumer_document_number }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Email</p>
                    <p class="text-gray-900 font-medium">{{ $complaint->consumer_email }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Teléfono</p>
                    <p class="text-gray-900 font-medium">{{ $complaint->consumer_phone }}</p>
                </div>
                @if($complaint->consumer_address)
                    <div>
                        <p class="text-gray-400">Dirección</p>
                        <p class="text-gray-900 font-medium">{{ $complaint->consumer_address }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($complaint->representative_name)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user-tie text-indigo-500 mr-2"></i> Apoderado
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-400">Nombre</p>
                        <p class="text-gray-900 font-medium">{{ $complaint->representative_name }}</p>
                    </div>
                    @if($complaint->representative_email)
                        <div>
                            <p class="text-gray-400">Email</p>
                            <p class="text-gray-900 font-medium">{{ $complaint->representative_email }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Plazo legal -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
            <h3 class="font-semibold text-amber-800 mb-2 text-sm">
                <i class="fas fa-gavel mr-1"></i> Plazo legal
            </h3>
            <p class="text-xs text-amber-700">
                Según la normativa de INDECOPI, el proveedor tiene un plazo máximo de <strong>30 días calendario</strong> para dar respuesta al reclamo.
            </p>
            @php
                $daysElapsed = $complaint->created_at->diffInDays(now());
                $daysRemaining = max(0, 30 - $daysElapsed);
            @endphp
            <div class="mt-3 flex items-center gap-2">
                <div class="flex-1 bg-amber-200 rounded-full h-2">
                    <div class="bg-amber-500 h-2 rounded-full" style="width: {{ min(100, ($daysElapsed / 30) * 100) }}%"></div>
                </div>
                <span class="text-xs font-semibold {{ $daysRemaining <= 5 ? 'text-red-600' : 'text-amber-700' }}">
                    {{ $daysRemaining }} días restantes
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
