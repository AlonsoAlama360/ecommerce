@extends('admin.layouts.app')
@section('title', 'Suscriptores')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Suscriptores</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalSubscribers) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Todos los registrados</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-envelope text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Activos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($activeSubscribers) }}</h3>
            <p class="text-xs mt-1">
                <span class="text-emerald-500 font-semibold">{{ $totalSubscribers > 0 ? round(($activeSubscribers / $totalSubscribers) * 100) : 0 }}%</span>
                <span class="text-gray-400">del total</span>
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check-circle text-emerald-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Inactivos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($inactiveSubscribers) }}</h3>
            <p class="text-xs mt-1">
                @if($inactiveSubscribers > 0)
                    <span class="text-red-500 font-semibold">Desactivados</span>
                @else
                    <span class="text-gray-400">Ninguno</span>
                @endif
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-times-circle text-red-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Nuevos esta semana</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($newThisWeek) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Últimos 7 días</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-plus text-blue-500"></i>
        </div>
    </div>
</div>

{{-- ==================== FILTERS & ACTIONS ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="p-4 flex flex-col sm:flex-row gap-3 items-center justify-between">
        <form method="GET" class="flex flex-col sm:flex-row gap-3 flex-1 flex-wrap">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por email..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
            </div>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 bg-white">
                <option value="">Todos los estados</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition">
                <i class="fas fa-search mr-1.5"></i>Filtrar
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.subscribers.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-1"></i>Limpiar
                </a>
            @endif
        </form>
        <a href="{{ route('admin.subscribers.export') }}" class="px-4 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition whitespace-nowrap">
            <i class="fas fa-file-csv mr-1.5"></i>Exportar CSV
        </a>
    </div>
</div>

{{-- ==================== ALERTS ==================== --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2">
        <i class="fas fa-check-circle"></i>{{ session('success') }}
    </div>
@endif

{{-- ==================== TABLE ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    {{-- Desktop --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="px-5 py-3.5 text-left font-semibold text-gray-600 text-xs uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3.5 text-center font-semibold text-gray-600 text-xs uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5 text-center font-semibold text-gray-600 text-xs uppercase tracking-wider">Fecha</th>
                    <th class="px-5 py-3.5 text-center font-semibold text-gray-600 text-xs uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($subscribers as $subscriber)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-xs font-bold">{{ strtoupper(substr($subscriber->email, 0, 1)) }}</span>
                                </div>
                                <span class="text-gray-900 font-medium">{{ $subscriber->email }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($subscriber->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <i class="fas fa-circle text-[6px] mr-1.5 text-emerald-500"></i>Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                                    <i class="fas fa-circle text-[6px] mr-1.5 text-red-500"></i>Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center text-gray-500 text-xs">
                            {{ $subscriber->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <form action="{{ route('admin.subscribers.toggle', $subscriber) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-md transition {{ $subscriber->is_active ? 'text-amber-500 hover:bg-amber-50' : 'text-emerald-500 hover:bg-emerald-50' }}"
                                        title="{{ $subscriber->is_active ? 'Desactivar' : 'Activar' }}">
                                        <i class="fas {{ $subscriber->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-sm"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar este suscriptor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition" title="Eliminar">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-envelope-open text-2xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-1">Sin suscriptores</p>
                                <p class="text-gray-400 text-xs">Los suscriptores del newsletter aparecerán aquí.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($subscribers as $subscriber)
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">{{ strtoupper(substr($subscriber->email, 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $subscriber->email }}</p>
                        <p class="text-xs text-gray-400">{{ $subscriber->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($subscriber->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700">Activo</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700">Inactivo</span>
                    @endif
                    <form action="{{ route('admin.subscribers.toggle', $subscriber) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-md transition {{ $subscriber->is_active ? 'text-amber-500' : 'text-emerald-500' }}">
                            <i class="fas {{ $subscriber->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST"
                        onsubmit="return confirm('¿Eliminar?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-md">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center">
                <i class="fas fa-envelope-open text-2xl text-gray-300 mb-2"></i>
                <p class="text-gray-400 text-sm">Sin suscriptores</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($subscribers->hasPages())
        <div class="border-t border-gray-100 px-5 py-3">
            {{ $subscribers->links() }}
        </div>
    @endif
</div>
@endsection
