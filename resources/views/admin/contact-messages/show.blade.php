@extends('admin.layouts.app')
@section('title', 'Mensaje de ' . $contactMessage->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
        <i class="fas fa-arrow-left mr-1"></i> Volver a mensajes
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    </div>
@endif

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Mensaje -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-envelope text-indigo-500 mr-2"></i>
                    {{ $contactMessage->subject }}
                </h2>
                @if($contactMessage->status === 'nuevo')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                        <i class="fas fa-circle text-[6px] mr-1.5"></i> Nuevo
                    </span>
                @elseif($contactMessage->status === 'leido')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                        <i class="fas fa-envelope-open mr-1"></i> Leído
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                        <i class="fas fa-check mr-1"></i> Respondido
                    </span>
                @endif
            </div>

            <div class="text-sm text-gray-400 mb-4">
                <i class="fas fa-calendar mr-1"></i> {{ $contactMessage->created_at->format('d/m/Y H:i') }}
                · {{ $contactMessage->created_at->diffForHumans() }}
            </div>

            <div class="bg-gray-50 rounded-xl p-5 text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $contactMessage->message }}</div>
        </div>

        <!-- Notas y respuesta -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Gestión del mensaje</h3>

            <form action="{{ route('admin.contact-messages.update', $contactMessage) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                        <option value="nuevo" {{ $contactMessage->status === 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                        <option value="leido" {{ $contactMessage->status === 'leido' ? 'selected' : '' }}>Leído</option>
                        <option value="respondido" {{ $contactMessage->status === 'respondido' ? 'selected' : '' }}>Respondido</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas internas</label>
                    <textarea name="admin_notes" rows="4" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400" placeholder="Notas sobre la gestión de este mensaje (solo visible para admins)...">{{ old('admin_notes', $contactMessage->admin_notes) }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-indigo-500 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-600 transition">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                    <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ urlencode($contactMessage->subject) }}" class="border border-gray-200 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        <i class="fas fa-reply mr-1"></i> Responder por email
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">
                <i class="fas fa-user text-indigo-500 mr-2"></i> Remitente
            </h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-400">Nombre</p>
                    <p class="text-gray-900 font-medium">{{ $contactMessage->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Email</p>
                    <a href="mailto:{{ $contactMessage->email }}" class="text-indigo-500 hover:text-indigo-700 font-medium transition">{{ $contactMessage->email }}</a>
                </div>
                @if($contactMessage->phone)
                    <div>
                        <p class="text-gray-400">Teléfono</p>
                        <p class="text-gray-900 font-medium">{{ $contactMessage->phone }}</p>
                    </div>
                @endif
                @if($contactMessage->order_number)
                    <div>
                        <p class="text-gray-400">N° Pedido</p>
                        <p class="text-gray-900 font-medium font-mono">{{ $contactMessage->order_number }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Acciones</h3>
            <div class="space-y-2">
                <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ urlencode($contactMessage->subject) }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition py-1.5">
                    <i class="fas fa-reply text-gray-400 w-4"></i> Responder por email
                </a>
                @if($contactMessage->phone)
                    <a href="https://wa.me/51{{ preg_replace('/\D/', '', $contactMessage->phone) }}" target="_blank" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition py-1.5">
                        <i class="fab fa-whatsapp text-gray-400 w-4"></i> Escribir por WhatsApp
                    </a>
                @endif
                <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" onsubmit="return confirm('¿Eliminar este mensaje?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 text-sm text-red-500 hover:text-red-700 transition py-1.5">
                        <i class="fas fa-trash w-4 text-xs"></i> Eliminar mensaje
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
