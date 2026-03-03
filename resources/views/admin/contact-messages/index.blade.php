@extends('admin.layouts.app')
@section('title', 'Mensajes de Contacto')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Mensajes</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Todos los recibidos</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-envelope text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Nuevos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['nuevo']) }}</h3>
            <p class="text-xs mt-1">
                @if($stats['nuevo'] > 0)
                    <span class="text-amber-500 font-semibold">Sin leer</span>
                @else
                    <span class="text-gray-400">Ninguno</span>
                @endif
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-bell text-amber-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Leídos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['leido']) }}</h3>
            <p class="text-xs mt-1">
                <span class="text-blue-500 font-semibold">Pendientes de respuesta</span>
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-envelope-open text-blue-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Respondidos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['respondido']) }}</h3>
            <p class="text-xs mt-1">
                <span class="text-emerald-500 font-semibold">Completados</span>
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check-circle text-emerald-500"></i>
        </div>
    </div>
</div>

{{-- ==================== FILTERS ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="p-4 flex flex-col sm:flex-row gap-3 items-center justify-between">
        <form method="GET" class="flex flex-col sm:flex-row gap-3 items-center flex-1">
            <div class="relative flex-1 min-w-0">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, email, asunto o N° pedido..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
            </div>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                <option value="">Todos los estados</option>
                <option value="nuevo" {{ request('status') == 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                <option value="leido" {{ request('status') == 'leido' ? 'selected' : '' }}>Leído</option>
                <option value="respondido" {{ request('status') == 'respondido' ? 'selected' : '' }}>Respondido</option>
            </select>
            <button type="submit" class="bg-indigo-500 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-600 transition">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
        </form>
    </div>
</div>

{{-- ==================== TABLE ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    @if(session('success'))
        <div class="m-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/80 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Remitente</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Asunto</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pedido</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($messages as $msg)
                    <tr class="hover:bg-gray-50/50 transition {{ $msg->status === 'nuevo' ? 'bg-amber-50/30' : '' }}">
                        <td class="px-5 py-4">
                            @if($msg->status === 'nuevo')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <i class="fas fa-circle text-[6px] mr-1.5"></i> Nuevo
                                </span>
                            @elseif($msg->status === 'leido')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    <i class="fas fa-envelope-open text-[10px] mr-1.5"></i> Leído
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <i class="fas fa-check text-[10px] mr-1.5"></i> Respondido
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $msg->created_at->format('d/m/Y') }}
                            <span class="text-gray-400 text-xs block">{{ $msg->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-medium text-gray-900 {{ $msg->status === 'nuevo' ? 'font-semibold' : '' }}">{{ $msg->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $msg->email }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-gray-700 {{ $msg->status === 'nuevo' ? 'font-semibold text-gray-900' : '' }}">{{ $msg->subject }}</p>
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $msg->order_number ?: '—' }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.contact-messages.show', $msg) }}" class="text-indigo-500 hover:text-indigo-700 transition" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.contact-messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('¿Eliminar este mensaje?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition" title="Eliminar">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            <i class="fas fa-envelope text-3xl mb-3 block"></i>
                            <p>No hay mensajes de contacto</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($messages->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $messages->links() }}
        </div>
    @endif
</div>
@endsection
