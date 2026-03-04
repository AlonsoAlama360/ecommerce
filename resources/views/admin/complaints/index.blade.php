@extends('admin.layouts.app')
@section('title', 'Libro de Reclamaciones')

@php
    $statusBadges = [
        'pendiente' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'icon' => 'fa-clock', 'label' => 'Pendiente'],
        'en_proceso' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'icon' => 'fa-spinner', 'label' => 'En proceso'],
        'resuelto' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon' => 'fa-check', 'label' => 'Resuelto'],
    ];
    $typeBadges = [
        'reclamo' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'label' => 'Reclamo'],
        'queja' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'label' => 'Queja'],
    ];
@endphp

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
                    <span class="text-amber-500 font-semibold">Requieren atencion</span>
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
                <span class="text-blue-500 font-semibold">En revision</span>
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

{{-- ==================== SEARCH FILTERS CARD ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="px-5 pt-5 pb-2">
        <h3 class="text-base font-semibold text-gray-800">Filtros de busqueda</h3>
    </div>
    @php
        $selectStyle = "background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;";
        $selectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
        $hasFilters = request('search') || request('status') || request('type');
    @endphp
    <form method="GET" id="filterForm" class="px-5 pb-5 mt-3">
        @if(request('per_page'))<input type="hidden" name="per_page" value="{{ request('per_page') }}">@endif
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado</label>
                <select name="status" onchange="this.form.submit()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_proceso" {{ request('status') == 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                    <option value="resuelto" {{ request('status') == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Tipo</label>
                <select name="type" onchange="this.form.submit()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Todos los tipos</option>
                    <option value="reclamo" {{ request('type') == 'reclamo' ? 'selected' : '' }}>Reclamo</option>
                    <option value="queja" {{ request('type') == 'queja' ? 'selected' : '' }}>Queja</option>
                </select>
            </div>
        </div>
        @if($hasFilters)
            <div class="mt-3">
                <a href="{{ route('admin.complaints.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
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
                    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                    @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
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
                    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                    @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
                    @if(request('per_page'))<input type="hidden" name="per_page" value="{{ request('per_page') }}">@endif
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar reclamacion..."
                               class="w-full sm:w-64 pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                </form>
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
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">N° Reclamo</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Consumidor</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($complaints as $complaint)
                @php
                    $sBadge = $statusBadges[$complaint->status] ?? $statusBadges['pendiente'];
                    $tBadge = $typeBadges[$complaint->complaint_type] ?? $typeBadges['reclamo'];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3.5">
                        <span class="font-mono font-semibold text-gray-900 text-sm">{{ $complaint->complaint_number }}</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                {{ strtoupper(substr($complaint->consumer_name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $complaint->consumer_name)[1] ?? '', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-800 text-sm">{{ $complaint->consumer_name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $complaint->consumer_email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $tBadge['bg'] }} {{ $tBadge['text'] }}">
                            {{ $tBadge['label'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $sBadge['bg'] }} {{ $sBadge['text'] }}">
                            <i class="fas {{ $sBadge['icon'] }} text-[8px] mr-1.5"></i> {{ $sBadge['label'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="text-sm text-gray-500">{{ $complaint->created_at->format('d/m/Y') }}</span>
                        <span class="text-xs text-gray-400 block">{{ $complaint->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('admin.complaints.show', $complaint) }}" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition" title="Ver detalle">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-book text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-600 font-semibold mb-1">Sin reclamaciones</h3>
                            <p class="text-sm text-gray-400 max-w-xs">No se encontraron reclamaciones que coincidan con los filtros</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($complaints as $complaint)
        @php
            $sBadge = $statusBadges[$complaint->status] ?? $statusBadges['pendiente'];
            $tBadge = $typeBadges[$complaint->complaint_type] ?? $typeBadges['reclamo'];
        @endphp
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($complaint->consumer_name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $complaint->consumer_name)[1] ?? '', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ $complaint->consumer_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $complaint->consumer_email }}</p>
                </div>
                <a href="{{ route('admin.complaints.show', $complaint) }}" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 rounded-md transition">
                    <i class="fas fa-eye text-sm"></i>
                </a>
            </div>
            <div class="mt-2 ml-[52px] flex items-center gap-2 flex-wrap">
                <span class="text-xs font-mono font-semibold text-gray-600">{{ $complaint->complaint_number }}</span>
                <span class="text-gray-300">|</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $tBadge['bg'] }} {{ $tBadge['text'] }}">{{ $tBadge['label'] }}</span>
                <span class="text-gray-300">|</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $sBadge['bg'] }} {{ $sBadge['text'] }}">
                    <i class="fas {{ $sBadge['icon'] }} text-[6px] mr-1"></i> {{ $sBadge['label'] }}
                </span>
                <span class="text-gray-300">|</span>
                <span class="text-xs text-gray-400">{{ $complaint->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-book text-xl text-gray-300"></i>
            </div>
            <p class="text-sm text-gray-400">No se encontraron reclamaciones</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Footer --}}
    @if($complaints->total() > 0)
    <div class="px-5 py-3.5 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Mostrando {{ $complaints->firstItem() }} a {{ $complaints->lastItem() }} de {{ number_format($complaints->total()) }} registros
            </p>
            @if($complaints->hasPages())
            @php
                $current = $complaints->currentPage();
                $last = $complaints->lastPage();
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
                    <a href="{{ $complaints->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Primera"><i class="fas fa-angles-left"></i></a>
                @endif
                @if($complaints->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $complaints->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-left"></i></a>
                @endif
                @foreach($pages as $page)
                    @if($page === '...')
                        <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">...</span>
                    @elseif($page == $current)
                        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-indigo-500 text-white text-xs font-semibold shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $complaints->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                    @endif
                @endforeach
                @if($complaints->hasMorePages())
                    <a href="{{ $complaints->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
                @endif
                @if($current < $last - 1)
                    <a href="{{ $complaints->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Ultima"><i class="fas fa-angles-right"></i></a>
                @endif
            </nav>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
