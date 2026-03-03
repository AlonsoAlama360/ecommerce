@extends('admin.layouts.app')
@section('title', 'Libro de Reclamaciones')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Reclamaciones</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Todas las registradas</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-book text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Pendientes</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['pendiente']) }}</h3>
            <p class="text-xs mt-1">
                @if($stats['pendiente'] > 0)
                    <span class="text-amber-500 font-semibold">Requieren atención</span>
                @else
                    <span class="text-gray-400">Ninguna</span>
                @endif
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-clock text-amber-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">En Proceso</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['en_proceso']) }}</h3>
            <p class="text-xs mt-1">
                <span class="text-blue-500 font-semibold">En revisión</span>
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-spinner text-blue-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Resueltos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['resuelto']) }}</h3>
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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por N°, nombre, email o documento..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
            </div>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="en_proceso" {{ request('status') == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                <option value="resuelto" {{ request('status') == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
            </select>
            <select name="type" class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                <option value="">Todos los tipos</option>
                <option value="reclamo" {{ request('type') == 'reclamo' ? 'selected' : '' }}>Reclamo</option>
                <option value="queja" {{ request('type') == 'queja' ? 'selected' : '' }}>Queja</option>
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
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">N° Reclamo</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Consumidor</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($complaints as $complaint)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-5 py-4">
                            <span class="font-mono font-semibold text-gray-900">{{ $complaint->complaint_number }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $complaint->created_at->format('d/m/Y') }}
                            <span class="text-gray-400 text-xs block">{{ $complaint->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-medium text-gray-900">{{ $complaint->consumer_name }}</p>
                            <p class="text-gray-400 text-xs">{{ $complaint->consumer_email }}</p>
                        </td>
                        <td class="px-5 py-4">
                            @if($complaint->complaint_type === 'reclamo')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">Reclamo</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Queja</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($complaint->status === 'pendiente')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <i class="fas fa-clock mr-1 text-[10px]"></i> Pendiente
                                </span>
                            @elseif($complaint->status === 'en_proceso')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    <i class="fas fa-spinner mr-1 text-[10px]"></i> En proceso
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <i class="fas fa-check mr-1 text-[10px]"></i> Resuelto
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('admin.complaints.show', $complaint) }}" class="text-indigo-500 hover:text-indigo-700 transition" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            <i class="fas fa-book text-3xl mb-3 block"></i>
                            <p>No hay reclamaciones registradas</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($complaints->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $complaints->links() }}
        </div>
    @endif
</div>
@endsection
