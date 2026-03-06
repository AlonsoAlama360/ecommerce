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
                @if($totalSubscribers > 0)
                    <span class="text-emerald-500 font-semibold">{{ round(($activeSubscribers / $totalSubscribers) * 100) }}%</span>
                    <span class="text-gray-400">del total</span>
                @else
                    <span class="text-gray-400">Sin datos</span>
                @endif
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
                @if($totalSubscribers > 0)
                    <span class="text-red-500 font-semibold">{{ round(($inactiveSubscribers / $totalSubscribers) * 100) }}%</span>
                    <span class="text-gray-400">del total</span>
                @else
                    <span class="text-gray-400">Sin datos</span>
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
            <p class="text-xs text-gray-400 mt-1">Ultimos 7 dias</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-plus text-blue-500"></i>
        </div>
    </div>
</div>

{{-- ==================== SEARCH FILTERS CARD ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="px-5 pt-5 pb-2">
        <h3 class="text-base font-semibold text-gray-800">Filtros de busqueda</h3>
    </div>
    @php
        $selectStyle = "background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;";
        $selectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
        $hasFilters = request('search') || (request('status') !== null && request('status') !== '');
    @endphp
    <form method="GET" id="filterForm" class="px-5 pb-5 mt-3">
        @if(request('per_page'))<input type="hidden" name="per_page" value="{{ request('per_page') }}">@endif
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado</label>
                <select name="status" onchange="this.form.submit()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
        </div>
        @if($hasFilters)
            <div class="mt-3">
                <a href="{{ route('admin.subscribers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-rotate-left text-xs"></i> Limpiar filtros
                </a>
            </div>
        @endif
    </form>
</div>

{{-- ==================== TABLE CARD ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- Table Toolbar --}}
    <div class="px-5 py-4 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                    @if(request('status') !== null && request('status') !== '')<input type="hidden" name="status" value="{{ request('status') }}">@endif
                    <select name="per_page" onchange="this.form.submit()"
                            class="pl-3 pr-8 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer bg-white appearance-none"
                            style="{{ $selectStyle }}">
                        @foreach([10, 15, 25, 50] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="flex items-center gap-3 flex-1 sm:flex-initial">
                <form method="GET" class="flex-1 sm:flex-initial">
                    @if(request('status') !== null && request('status') !== '')<input type="hidden" name="status" value="{{ request('status') }}">@endif
                    @if(request('per_page'))<input type="hidden" name="per_page" value="{{ request('per_page') }}">@endif
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar por email..."
                               class="w-full sm:w-64 pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                </form>
                <a href="{{ route('admin.subscribers.export') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition whitespace-nowrap">
                    <i class="fas fa-file-csv text-xs"></i>
                    <span class="hidden sm:inline">Exportar CSV</span>
                    <span class="sm:hidden">CSV</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mx-5 mt-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Suscriptor</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha de suscripcion</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($subscribers as $subscriber)
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                {{ strtoupper(substr($subscriber->email, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $subscriber->email }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        @if($subscriber->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600">
                                <i class="fas fa-circle text-[6px] mr-1.5 text-emerald-500"></i> Activo
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-500">
                                <i class="fas fa-circle text-[6px] mr-1.5 text-red-500"></i> Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="text-sm text-gray-500">{{ $subscriber->created_at->format('d/m/Y') }}</span>
                        <span class="text-xs text-gray-400 block">{{ $subscriber->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1">
                            <form action="{{ route('admin.subscribers.toggle', $subscriber) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-md transition {{ $subscriber->is_active ? 'text-amber-500 hover:bg-amber-50' : 'text-emerald-500 hover:bg-emerald-50' }}"
                                    title="{{ $subscriber->is_active ? 'Desactivar' : 'Activar' }}">
                                    <i class="fas {{ $subscriber->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar este suscriptor?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition" title="Eliminar" aria-label="Eliminar">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-envelope-open text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-600 font-semibold mb-1">Sin suscriptores</h3>
                            <p class="text-sm text-gray-400 max-w-xs">Los suscriptores del newsletter apareceran aqui</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($subscribers as $subscriber)
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($subscriber->email, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $subscriber->email }}</p>
                    <p class="text-xs text-gray-400">{{ $subscriber->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="flex items-center gap-0.5">
                    <form action="{{ route('admin.subscribers.toggle', $subscriber) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-md transition {{ $subscriber->is_active ? 'text-amber-500' : 'text-emerald-500' }}">
                            <i class="fas {{ $subscriber->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST"
                        onsubmit="return confirm('¿Eliminar?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-md transition" aria-label="Eliminar">
                            <i class="fas fa-trash-can text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="mt-2 ml-[52px] flex items-center gap-2 flex-wrap">
                @if($subscriber->is_active)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-600">
                        <i class="fas fa-circle text-[5px] mr-1 text-emerald-500"></i> Activo
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-red-500">
                        <i class="fas fa-circle text-[5px] mr-1 text-red-500"></i> Inactivo
                    </span>
                @endif
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-envelope-open text-xl text-gray-300"></i>
            </div>
            <p class="text-sm text-gray-400">Sin suscriptores</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Footer --}}
    @if($subscribers->total() > 0)
    <div class="px-5 py-3.5 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Mostrando {{ $subscribers->firstItem() }} a {{ $subscribers->lastItem() }} de {{ number_format($subscribers->total()) }} registros
            </p>
            @if($subscribers->hasPages())
            @php
                $current = $subscribers->currentPage();
                $last = $subscribers->lastPage();
                $pages = [];
                if ($last <= 5) { $pages = range(1, $last); }
                else {
                    $pages[] = 1;
                    if ($current > 3) $pages[] = '...';
                    for ($i = max(2, $current - 1); $i <= min($last - 1, $current + 1); $i++) { $pages[] = $i; }
                    if ($current < $last - 2) $pages[] = '...';
                    $pages[] = $last;
                }
            @endphp
            <nav class="flex items-center gap-1">
                @if($current > 2)
                    <a href="{{ $subscribers->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Primera"><i class="fas fa-angles-left"></i></a>
                @endif
                @if($subscribers->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $subscribers->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-left"></i></a>
                @endif
                @foreach($pages as $page)
                    @if($page === '...')
                        <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">...</span>
                    @elseif($page == $current)
                        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-indigo-500 text-white text-xs font-semibold shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $subscribers->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                    @endif
                @endforeach
                @if($subscribers->hasMorePages())
                    <a href="{{ $subscribers->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
                @endif
                @if($current < $last - 1)
                    <a href="{{ $subscribers->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Ultima"><i class="fas fa-angles-right"></i></a>
                @endif
            </nav>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
