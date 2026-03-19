@extends('admin.layouts.app')
@section('title', 'Ventas')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Ventas</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Todas las ventas</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-receipt text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Ventas Hoy</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($ordersToday) }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('d M Y') }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-calendar-day text-emerald-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Ingresos del Mes</p>
            <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($monthlyRevenue, 2) }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-violet-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-coins text-violet-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Pendientes</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($pendingOrders) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Requieren atención</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-clock text-amber-500"></i>
        </div>
    </div>
</div>

{{-- ==================== SEARCH FILTERS CARD ==================== --}}
@php
    $selectStyle = "background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;";
    $hasFilters = request('search') || request('status') || request('source') || request('payment_method') || request('payment_status') || request('date_from') || request('date_to');
    $filterCount = collect(['status','source','payment_method','payment_status','date_from','date_to'])->filter(fn($f) => request($f))->count();
@endphp
<div class="filter-collapsible bg-white rounded-xl border border-gray-200 mb-6">
    <button type="button" onclick="this.closest('.filter-collapsible').classList.toggle('open')"
        class="w-full flex items-center justify-between px-4 py-2.5 hover:bg-gray-50/50 transition-all group cursor-pointer">
        <div class="flex items-center gap-2.5">
            <i class="fas fa-sliders text-indigo-400 text-sm"></i>
            <span class="text-sm font-semibold text-gray-600">Filtros</span>
            @if($filterCount)
                <span class="bg-indigo-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">{{ $filterCount }}</span>
            @endif
        </div>
        <i class="fas fa-chevron-down text-gray-300 text-[10px] filter-chevron"></i>
    </button>
    <div class="filter-panel">
        <div class="border-t border-gray-100 mx-4"></div>
        <div class="p-4">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="filter-field">
                    <label>Estado</label>
                    <select id="filter_status" onchange="applyFilters()">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Order::STATUS_LABELS as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field">
                    <label>Origen</label>
                    <select id="filter_source" onchange="applyFilters()">
                        <option value="">Todos</option>
                        <option value="web" {{ request('source') === 'web' ? 'selected' : '' }}>Web</option>
                        <option value="admin" {{ request('source') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="filter-field">
                    <label>Método pago</label>
                    <select id="filter_payment_method" onchange="applyFilters()">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Order::PAYMENT_METHODS as $val => $label)
                        <option value="{{ $val }}" {{ request('payment_method') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-field">
                    <label>Estado pago</label>
                    <select id="filter_payment_status" onchange="applyFilters()">
                        <option value="">Todos</option>
                        @foreach(\App\Models\Order::PAYMENT_STATUS_LABELS as $val => $label)
                        <option value="{{ $val }}" {{ request('payment_status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-3">
                <div class="filter-field">
                    <label>Desde</label>
                    <input type="date" id="filter_date_from" value="{{ request('date_from') }}" onchange="applyFilters()">
                </div>
                <div class="filter-field">
                    <label>Hasta</label>
                    <input type="date" id="filter_date_to" value="{{ request('date_to') }}" onchange="applyFilters()">
                </div>
                <div class="flex items-end col-span-2 lg:col-span-2">
                    @if($hasFilters)
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                        <i class="fas fa-xmark"></i> Limpiar filtros
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== TABLE CARD ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- Table Toolbar --}}
    <div class="px-5 py-4 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <select id="filter_per_page" onchange="applyFilters()"
                    class="pl-3 pr-8 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer bg-white appearance-none"
                    style="{{ $selectStyle }}">
                    @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-3 flex-1 sm:flex-initial">
                <div class="relative flex-1 sm:flex-initial">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="filter_search" value="{{ request('search') }}"
                        placeholder="Buscar orden, cliente..."
                        onkeydown="if(event.key==='Enter'){event.preventDefault();applyFilters()}"
                        class="w-full sm:w-56 pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
                <button onclick="exportOrders()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition text-sm font-medium shadow-sm shadow-emerald-200 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-file-csv text-xs"></i>
                    <span class="hidden sm:inline">Exportar</span>
                </button>
                <button onclick="openCreateDrawer()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm shadow-indigo-200 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-plus text-xs"></i>
                    <span class="hidden sm:inline">Nueva Venta</span>
                    <span class="sm:hidden">Nueva</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"># Orden</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Origen</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pago</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3">
                        <span class="text-sm font-semibold text-indigo-600 font-mono">{{ $order->order_number }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $order->customer_name }}</p>
                            @if($order->customer_phone)
                            <p class="text-xs text-gray-400 truncate">{{ $order->customer_phone }}</p>
                            @elseif($order->customer_email)
                            <p class="text-xs text-gray-400 truncate">{{ $order->customer_email }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @if($order->source === 'web')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-cyan-50 text-cyan-600">
                            <i class="fas fa-globe mr-1.5 text-[9px]"></i>Web
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-violet-50 text-violet-600">
                            <i class="fas fa-user-shield mr-1.5 text-[9px]"></i>Admin
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $order->items->count() }} {{ $order->items->count() === 1 ? 'item' : 'items' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm font-bold text-gray-800">S/ {{ number_format($order->total, 2) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <p class="text-xs text-gray-600">{{ $order->payment_method_label }}</p>
                            @if($order->payment_status === 'pagado')
                            <span class="text-[10px] font-semibold text-emerald-500">Pagado</span>
                            @elseif($order->payment_status === 'pendiente')
                            <span class="text-[10px] font-semibold text-amber-500">Pendiente</span>
                            @else
                            <span class="text-[10px] font-semibold text-red-500">Fallido</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="relative" data-status-dropdown>
                            <button type="button" onclick="toggleStatusDropdown(this, {{ $order->id }})"
                                class="status-badge inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold cursor-pointer transition-all hover:shadow-sm {{ $order->status_color['bg'] }} {{ $order->status_color['text'] }}" data-current="{{ $order->status }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                                <span class="status-label">{{ $order->status_label }}</span>
                                <i class="fas fa-chevron-down text-[8px] opacity-50 ml-0.5"></i>
                            </button>
                            <div class="status-dropdown hidden absolute left-0 top-full mt-1 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 animate-fade-in">
                                @foreach(\App\Models\Order::STATUS_LABELS as $val => $label)
                                @php $c = \App\Models\Order::STATUS_COLORS[$val]; @endphp
                                <button type="button" onclick="selectStatus(this, {{ $order->id }}, '{{ $val }}')"
                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-xs hover:bg-gray-50 transition {{ $order->status === $val ? 'font-bold' : '' }}">
                                    <span class="w-2 h-2 rounded-full {{ $c['bg'] }} {{ $c['text'] }} border border-current/20 flex-shrink-0" style="background-color: currentColor; opacity: 0.6;"></span>
                                    <span class="flex-1 text-left text-gray-700">{{ $label }}</span>
                                    @if($order->status === $val)<i class="fas fa-check text-[9px] text-indigo-500"></i>@endif
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</span>
                        <p class="text-[10px] text-gray-400">{{ $order->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="viewOrder({{ $order->id }})"
                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition" title="Ver detalle">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            <button onclick="openEditDrawer({{ $order->id }})"
                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-md transition" title="Editar">
                                <i class="fas fa-pen-to-square text-sm"></i>
                            </button>
                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $order->order_number }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition" title="Eliminar">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-receipt text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-600 font-semibold mb-1">Sin ventas</h3>
                            <p class="text-sm text-gray-400 max-w-xs">No se encontraron ventas que coincidan con los filtros</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($orders as $order)
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-indigo-600 font-mono">{{ $order->order_number }}</span>
                <span class="text-sm font-bold text-gray-800">S/ {{ number_format($order->total, 2) }}</span>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <p class="font-semibold text-gray-800 text-sm flex-1 truncate">{{ $order->customer_name }}</p>
                @if($order->source === 'web')
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-cyan-50 text-cyan-600">Web</span>
                @else
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-violet-50 text-violet-600">Admin</span>
                @endif
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $order->status_color['bg'] }} {{ $order->status_color['text'] }}">
                    {{ $order->status_label }}
                </span>
                <span class="text-xs text-gray-400">{{ $order->payment_method_label }}</span>
                <span class="text-gray-300">|</span>
                <span class="text-xs text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex items-center justify-end gap-1 mt-2">
                <button onclick="viewOrder({{ $order->id }})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 rounded-md transition">
                    <i class="fas fa-eye text-sm"></i>
                </button>
                <button onclick="openEditDrawer({{ $order->id }})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-amber-600 rounded-md transition">
                    <i class="fas fa-pen-to-square text-sm"></i>
                </button>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $order->order_number }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-md transition">
                        <i class="fas fa-trash-can text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-receipt text-xl text-gray-300"></i>
            </div>
            <p class="text-sm text-gray-400">No se encontraron ventas</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Footer --}}
    @if($orders->total() > 0)
    <div class="px-5 py-3.5 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Mostrando {{ $orders->firstItem() }} a {{ $orders->lastItem() }} de {{ number_format($orders->total()) }} registros
            </p>
            @if($orders->hasPages())
            <nav class="flex items-center gap-1">
                @if($orders->currentPage() > 2)
                <a href="{{ $orders->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Primera" aria-label="Primera página">
                    <i class="fas fa-angles-left"></i>
                </a>
                @endif
                @if($orders->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                @else
                <a href="{{ $orders->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Página anterior"><i class="fas fa-chevron-left"></i></a>
                @endif
                @php
                    $current = $orders->currentPage();
                    $last = $orders->lastPage();
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
                @foreach($pages as $page)
                    @if($page === '...')
                    <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">...</span>
                    @elseif($page == $current)
                    <span class="w-8 h-8 flex items-center justify-center rounded-md bg-indigo-500 text-white text-xs font-semibold shadow-sm">{{ $page }}</span>
                    @else
                    <a href="{{ $orders->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                    @endif
                @endforeach
                @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Página siguiente"><i class="fas fa-chevron-right"></i></a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
                @endif
                @if($orders->currentPage() < $last - 1)
                <a href="{{ $orders->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Última" aria-label="Última página">
                    <i class="fas fa-angles-right"></i>
                </a>
                @endif
            </nav>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ==================== SHARED OVERLAY ==================== --}}
<div id="drawerOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] opacity-0 invisible transition-all duration-300" onclick="closeDrawer()"></div>

{{-- ==================== CREATE ORDER DRAWER ==================== --}}
<div id="createDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[600px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Nueva Venta</h2>
            <p class="text-sm text-gray-400 mt-0.5">Registrar una venta manual</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="{{ route('admin.orders.store') }}" id="createOrderForm" class="p-6">
            @csrf

            {{-- Client Section --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-user text-indigo-400 text-xs"></i> Datos del Cliente
                </h3>
                <div class="relative mb-3">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="clientSearch" placeholder="Buscar cliente existente..."
                        autocomplete="off"
                        class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    <div id="clientResults" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto hidden"></div>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Nombre completo <span class="text-red-400">*</span></label>
                        <input type="text" name="customer_name" id="create_customer_name" required
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Email</label>
                            <input type="email" name="customer_email" id="create_customer_email"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Teléfono</label>
                            <input type="text" name="customer_phone" id="create_customer_phone"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                    </div>
                    @if($shippingMode === 'both')
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Método de envío</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="toggleAdminShipping('create', 'agency')" id="createOptAgency"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg border-2 border-indigo-400 bg-indigo-50/50 text-sm font-medium transition">
                                <i class="fas fa-building text-indigo-500 text-xs"></i> Agencia
                            </button>
                            <button type="button" onclick="toggleAdminShipping('create', 'address')" id="createOptAddress"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg border-2 border-gray-200 text-sm font-medium transition">
                                <i class="fas fa-home text-gray-400 text-xs"></i> Domicilio
                            </button>
                        </div>
                    </div>
                    @endif

                    <div id="createAddressField" class="{{ $shippingMode === 'agency' ? 'hidden' : '' }} {{ $shippingMode === 'both' ? 'hidden' : '' }}">
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Dirección de envío</label>
                        <input type="text" name="shipping_address" id="create_shipping_address"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        <div id="savedAddressBadge" class="hidden mt-2 p-2.5 bg-emerald-50 border border-emerald-200 rounded-lg flex items-start gap-2">
                            <i class="fas fa-map-marker-alt text-emerald-500 text-xs mt-0.5"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-wider">Dirección guardada del cliente</p>
                                <p class="text-xs text-emerald-700 mt-0.5" id="savedAddressText"></p>
                            </div>
                            <button type="button" onclick="useSavedAddress()" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-800 bg-emerald-100 hover:bg-emerald-200 px-2 py-1 rounded transition flex-shrink-0">Usar esta</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 {{ $shippingMode === 'address' ? 'hidden' : '' }}" id="createAgencyFields">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Agencia de transporte</label>
                            <select name="shipping_agency" id="create_shipping_agency"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none">
                                @foreach($shippingAgencies as $agency)
                                    <option value="{{ $agency->name }}" {{ $loop->first ? 'selected' : '' }}>{{ $agency->name }}</option>
                                @endforeach
                                <option value="">Sin agencia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Dirección agencia</label>
                            <input type="text" name="shipping_agency_address" id="create_shipping_agency_address" placeholder="Nombre/dirección de sede"
                                list="createAgencyAddressList"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                            <datalist id="createAgencyAddressList"></datalist>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products Section --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-box text-violet-400 text-xs"></i> Productos
                </h3>
                <div class="relative mb-3">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="productSearch" placeholder="Buscar producto por nombre o SKU..."
                        autocomplete="off"
                        class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    <div id="productResults" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-60 overflow-y-auto hidden"></div>
                </div>

                {{-- Items List --}}
                <div id="orderItems" class="space-y-2">
                    {{-- Items will be added dynamically --}}
                </div>
                <div id="noItemsMsg" class="text-center py-6 border-2 border-dashed border-gray-200 rounded-lg">
                    <i class="fas fa-cart-plus text-gray-300 text-xl mb-2"></i>
                    <p class="text-sm text-gray-400">Busca y agrega productos</p>
                </div>

                {{-- Totals --}}
                <div id="orderTotals" class="mt-4 pt-4 border-t border-gray-100 hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-600">Total:</span>
                        <span id="orderTotalAmount" class="text-lg font-bold text-gray-900">S/ 0.00</span>
                    </div>
                </div>
            </div>

            {{-- Payment Section --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-credit-card text-emerald-400 text-xs"></i> Pago
                </h3>
                @php
                    $drawerSelectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
                @endphp
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Método de pago <span class="text-red-400">*</span></label>
                        <select name="payment_method" class="{{ $drawerSelectClass }}" style="{{ $selectStyle }}">
                            @foreach(\App\Models\Order::PAYMENT_METHODS as $val => $label)
                            <option value="{{ $val }}" {{ $val === 'yape_plin' ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado de pago <span class="text-red-400">*</span></label>
                        <select name="payment_status" class="{{ $drawerSelectClass }}" style="{{ $selectStyle }}">
                            @foreach(\App\Models\Order::PAYMENT_STATUS_LABELS as $val => $label)
                            <option value="{{ $val }}" {{ $val === 'pagado' ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Notas internas</label>
                <textarea name="admin_notes" rows="2" placeholder="Notas sobre esta venta..."
                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none"></textarea>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="submitOrder()" id="btnCreateOrder" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-check mr-1.5 text-xs"></i> <span>Crear Venta</span>
        </button>
    </div>
</div>

{{-- ==================== VIEW ORDER DRAWER ==================== --}}
<div id="viewDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[550px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Detalle de Venta</h2>
            <p class="text-sm text-gray-400 mt-0.5" id="viewOrderNumber"></p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-6" id="viewOrderContent">
        <div class="flex items-center justify-center py-20">
            <i class="fas fa-spinner fa-spin text-indigo-400 text-xl"></i>
        </div>
    </div>
</div>

{{-- ==================== EDIT ORDER DRAWER ==================== --}}
@php
    $drawerSelectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
    $drawerInputClass = "w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition";
@endphp
<div id="editDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[600px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Editar Venta</h2>
            <p class="text-sm text-gray-400 mt-0.5" id="editOrderNumber"></p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="" id="editOrderForm" class="p-6">
            @csrf
            @method('PUT')

            {{-- Order Info Header --}}
            <div class="flex items-center gap-3 mb-6 p-4 bg-indigo-50/50 rounded-xl">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-receipt text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800" id="editOrderTitle"></p>
                    <p class="text-xs text-gray-400" id="editOrderMeta"></p>
                </div>
            </div>

            {{-- Client Section --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-user text-indigo-400 text-xs"></i> Datos del Cliente
                </h3>
                <div class="relative mb-3">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="editClientSearch" placeholder="Buscar cliente existente..."
                        autocomplete="off"
                        class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    <div id="editClientResults" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto hidden"></div>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Nombre completo <span class="text-red-400">*</span></label>
                        <input type="text" name="customer_name" id="edit_customer_name" required
                            class="{{ $drawerInputClass }}">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Email</label>
                            <input type="email" name="customer_email" id="edit_customer_email"
                                class="{{ $drawerInputClass }}">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Teléfono</label>
                            <input type="text" name="customer_phone" id="edit_customer_phone"
                                class="{{ $drawerInputClass }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products Section --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-box text-violet-400 text-xs"></i> Productos
                </h3>
                <div class="relative mb-3">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="editProductSearch" placeholder="Buscar producto por nombre o SKU..."
                        autocomplete="off"
                        class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    <div id="editProductResults" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-60 overflow-y-auto hidden"></div>
                </div>

                {{-- Items List --}}
                <div id="editOrderItems" class="space-y-2"></div>
                <div id="editNoItemsMsg" class="text-center py-6 border-2 border-dashed border-gray-200 rounded-lg hidden">
                    <i class="fas fa-cart-plus text-gray-300 text-xl mb-2"></i>
                    <p class="text-sm text-gray-400">Busca y agrega productos</p>
                </div>

                {{-- Totals --}}
                <div id="editOrderTotals" class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-600">Total:</span>
                        <span id="editOrderTotal" class="text-lg font-bold text-gray-900">S/ 0.00</span>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-arrows-rotate text-blue-400 text-xs"></i> Estado de la venta
                </h3>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado</label>
                    <select name="status" id="edit_status" class="{{ $drawerSelectClass }}" style="{{ $selectStyle }}">
                        @foreach(\App\Models\Order::STATUS_LABELS as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Payment --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-credit-card text-emerald-400 text-xs"></i> Pago
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Método de pago</label>
                        <select name="payment_method" id="edit_payment_method" class="{{ $drawerSelectClass }}" style="{{ $selectStyle }}">
                            @foreach(\App\Models\Order::PAYMENT_METHODS as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado de pago</label>
                        <select name="payment_status" id="edit_payment_status" class="{{ $drawerSelectClass }}" style="{{ $selectStyle }}">
                            @foreach(\App\Models\Order::PAYMENT_STATUS_LABELS as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Shipping --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-truck text-amber-400 text-xs"></i> Envío
                </h3>

                @if($shippingMode === 'both')
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Método de envío</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="toggleAdminShipping('edit', 'agency')" id="editOptAgency"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg border-2 border-indigo-400 bg-indigo-50/50 text-sm font-medium transition">
                            <i class="fas fa-building text-indigo-500 text-xs"></i> Agencia
                        </button>
                        <button type="button" onclick="toggleAdminShipping('edit', 'address')" id="editOptAddress"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg border-2 border-gray-200 text-sm font-medium transition">
                            <i class="fas fa-home text-gray-400 text-xs"></i> Domicilio
                        </button>
                    </div>
                </div>
                @endif

                <div id="editAddressField" class="{{ $shippingMode === 'agency' ? 'hidden' : '' }} {{ $shippingMode === 'both' ? 'hidden' : '' }}">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Dirección de envío</label>
                    <input type="text" name="shipping_address" id="edit_shipping_address" placeholder="Dirección de entrega"
                        class="{{ $drawerInputClass }}">
                    <div id="editSavedAddressBadge" class="hidden mt-2 p-2.5 bg-emerald-50 border border-emerald-200 rounded-lg flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-emerald-500 text-xs mt-0.5"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-wider">Dirección guardada del cliente</p>
                            <p class="text-xs text-emerald-700 mt-0.5" id="editSavedAddressText"></p>
                        </div>
                        <button type="button" onclick="useEditSavedAddress()" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-800 bg-emerald-100 hover:bg-emerald-200 px-2 py-1 rounded transition flex-shrink-0">Usar esta</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 {{ $shippingMode === 'address' ? 'hidden' : '' }}" id="editAgencyFields">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Agencia de transporte</label>
                        <select name="shipping_agency" id="edit_shipping_agency"
                            class="{{ $drawerSelectClass }}">
                            @foreach($shippingAgencies as $agency)
                                <option value="{{ $agency->name }}">{{ $agency->name }}</option>
                            @endforeach
                            <option value="">Sin agencia</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Dirección agencia</label>
                        <input type="text" name="shipping_agency_address" id="edit_shipping_agency_address" placeholder="Nombre/dirección de sede"
                            list="editAgencyAddressList"
                            class="{{ $drawerInputClass }}">
                        <datalist id="editAgencyAddressList"></datalist>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-sticky-note text-gray-400 text-xs"></i> Notas
                </h3>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Notas internas</label>
                    <textarea name="admin_notes" id="edit_admin_notes" rows="3" placeholder="Notas sobre esta venta..."
                        class="{{ $drawerInputClass }} resize-none"></textarea>
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="pt-4 border-t border-gray-100">
                <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                    <span><i class="fas fa-calendar-alt mr-1"></i> Creado: <span id="editCreatedAt"></span></span>
                    <span><i class="fas fa-clock mr-1"></i> Actualizado: <span id="editUpdatedAt"></span></span>
                </div>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="submitEditOrder()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-check mr-1.5 text-xs"></i> Guardar Cambios
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const drawerOverlay = document.getElementById('drawerOverlay');
    const createDrawer = document.getElementById('createDrawer');
    const viewDrawer = document.getElementById('viewDrawer');
    const editDrawer = document.getElementById('editDrawer');
    let activeDrawer = null;
    let orderItemsList = [];
    let itemCounter = 0;
    let editItemsList = [];
    let editItemCounter = 0;
    let searchTimeout = null;

    // ==================== AGENCY ADDRESSES ====================
    const agenciesData = @json($shippingAgencies->mapWithKeys(fn($a) => [$a->name => $a->addresses->pluck('address')]));

    function updateAgencyDatalist(selectId, datalistId) {
        const select = document.getElementById(selectId);
        const datalist = document.getElementById(datalistId);
        if (!select || !datalist) return;
        const name = select.value;
        datalist.innerHTML = '';
        if (name && agenciesData[name]) {
            agenciesData[name].forEach(addr => {
                const opt = document.createElement('option');
                opt.value = addr;
                datalist.appendChild(opt);
            });
        }
    }

    const createAgencySelect = document.getElementById('create_shipping_agency');
    if (createAgencySelect) {
        createAgencySelect.addEventListener('change', function() {
            document.getElementById('create_shipping_agency_address').value = '';
            updateAgencyDatalist('create_shipping_agency', 'createAgencyAddressList');
        });
        updateAgencyDatalist('create_shipping_agency', 'createAgencyAddressList');
    }
    const editAgencySelect = document.getElementById('edit_shipping_agency');
    if (editAgencySelect) {
        editAgencySelect.addEventListener('change', function() {
            document.getElementById('edit_shipping_agency_address').value = '';
            updateAgencyDatalist('edit_shipping_agency', 'editAgencyAddressList');
        });
    }

    // ==================== ADMIN SHIPPING TOGGLE ====================
    function toggleAdminShipping(prefix, mode) {
        const addrField = document.getElementById(prefix + 'AddressField');
        const agencyField = document.getElementById(prefix + 'AgencyFields');
        const optAgency = document.getElementById(prefix + 'OptAgency');
        const optAddress = document.getElementById(prefix + 'OptAddress');

        if (mode === 'agency') {
            if (addrField) addrField.classList.add('hidden');
            if (agencyField) agencyField.classList.remove('hidden');
            if (optAgency) { optAgency.classList.add('border-indigo-400', 'bg-indigo-50/50'); optAgency.classList.remove('border-gray-200'); }
            if (optAddress) { optAddress.classList.remove('border-indigo-400', 'bg-indigo-50/50'); optAddress.classList.add('border-gray-200'); }
        } else {
            if (addrField) addrField.classList.remove('hidden');
            if (agencyField) agencyField.classList.add('hidden');
            if (optAddress) { optAddress.classList.add('border-indigo-400', 'bg-indigo-50/50'); optAddress.classList.remove('border-gray-200'); }
            if (optAgency) { optAgency.classList.remove('border-indigo-400', 'bg-indigo-50/50'); optAgency.classList.add('border-gray-200'); }
        }
    }

    // ==================== UNIFIED FILTERS ====================
    function applyFilters() {
        const params = new URLSearchParams();
        const fields = {
            search: document.getElementById('filter_search')?.value?.trim(),
            status: document.getElementById('filter_status')?.value,
            source: document.getElementById('filter_source')?.value,
            payment_method: document.getElementById('filter_payment_method')?.value,
            payment_status: document.getElementById('filter_payment_status')?.value,
            date_from: document.getElementById('filter_date_from')?.value,
            date_to: document.getElementById('filter_date_to')?.value,
            per_page: document.getElementById('filter_per_page')?.value,
        };

        for (const [key, val] of Object.entries(fields)) {
            if (val !== '' && val !== null && val !== undefined) {
                params.set(key, val);
            }
        }

        window.location.href = '{{ route("admin.orders.index") }}' + (params.toString() ? '?' + params.toString() : '');
    }

    // ==================== EXPORT ====================
    function exportOrders() {
        const params = new URLSearchParams();
        const fields = {
            search: document.getElementById('filter_search')?.value?.trim(),
            status: document.getElementById('filter_status')?.value,
            source: document.getElementById('filter_source')?.value,
            payment_method: document.getElementById('filter_payment_method')?.value,
            payment_status: document.getElementById('filter_payment_status')?.value,
            date_from: document.getElementById('filter_date_from')?.value,
            date_to: document.getElementById('filter_date_to')?.value,
        };

        for (const [key, val] of Object.entries(fields)) {
            if (val !== '' && val !== null && val !== undefined) {
                params.set(key, val);
            }
        }

        window.location.href = '{{ route("admin.orders.export") }}' + (params.toString() ? '?' + params.toString() : '');
    }

    // ==================== DRAWER SYSTEM ====================
    function showDrawer(drawer) {
        activeDrawer = drawer;
        drawerOverlay.classList.remove('opacity-0', 'invisible');
        drawerOverlay.classList.add('opacity-100', 'visible');
        drawer.classList.remove('translate-x-full');
        drawer.classList.add('translate-x-0');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawerOverlay.classList.add('opacity-0', 'invisible');
        drawerOverlay.classList.remove('opacity-100', 'visible');
        if (activeDrawer) {
            activeDrawer.classList.add('translate-x-full');
            activeDrawer.classList.remove('translate-x-0');
        }
        document.body.style.overflow = '';
        activeDrawer = null;
    }

    function openCreateDrawer() {
        // Reset form
        document.getElementById('createOrderForm').reset();
        orderItemsList = [];
        itemCounter = 0;
        selectedClientAddress = null;
        const badge = document.getElementById('savedAddressBadge');
        if (badge) badge.classList.add('hidden');
        renderOrderItems();
        showDrawer(createDrawer);
    }

    // ==================== CLIENT SEARCH ====================
    const clientSearchInput = document.getElementById('clientSearch');
    const clientResults = document.getElementById('clientResults');

    clientSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { clientResults.classList.add('hidden'); return; }

        searchTimeout = setTimeout(() => {
            fetch('{{ route("admin.orders.search-users") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(users => {
                    if (users.length === 0) {
                        clientResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Sin resultados</div>';
                    } else {
                        clientResults.innerHTML = users.map(u => `
                            <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition flex items-center gap-3"
                                 onclick="selectClient(${JSON.stringify(u).replace(/"/g, '&quot;')})">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0">
                                    ${u.name.substring(0,2).toUpperCase()}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">${u.name}</p>
                                    <p class="text-xs text-gray-400 truncate">${u.email || ''} ${u.phone ? '· ' + u.phone : ''}</p>
                                </div>
                            </div>
                        `).join('');
                    }
                    clientResults.classList.remove('hidden');
                });
        }, 300);
    });

    var selectedClientAddress = null;

    function selectClient(user) {
        document.getElementById('create_customer_name').value = user.name;
        document.getElementById('create_customer_email').value = user.email || '';
        document.getElementById('create_customer_phone').value = user.phone || '';
        clientResults.classList.add('hidden');
        clientSearchInput.value = '';

        // Show saved address if user has one
        var badge = document.getElementById('savedAddressBadge');
        if (user.full_address && badge) {
            selectedClientAddress = user.full_address;
            document.getElementById('savedAddressText').textContent = user.full_address;
            badge.classList.remove('hidden');
            const createShipAddr = document.getElementById('create_shipping_address');
            if (createShipAddr) createShipAddr.value = user.full_address;
        } else {
            selectedClientAddress = null;
            if (badge) badge.classList.add('hidden');
        }
    }

    function useSavedAddress() {
        const createShipAddr = document.getElementById('create_shipping_address');
        if (selectedClientAddress && createShipAddr) {
            createShipAddr.value = selectedClientAddress;
        }
    }

    document.addEventListener('click', function(e) {
        if (!clientSearchInput.contains(e.target) && !clientResults.contains(e.target)) {
            clientResults.classList.add('hidden');
        }
        if (!document.getElementById('productSearch').contains(e.target) && !document.getElementById('productResults').contains(e.target)) {
            document.getElementById('productResults').classList.add('hidden');
        }
        const ecs = document.getElementById('editClientSearch');
        const ecr = document.getElementById('editClientResults');
        if (!ecs.contains(e.target) && !ecr.contains(e.target)) {
            ecr.classList.add('hidden');
        }
        const eps = document.getElementById('editProductSearch');
        const epr = document.getElementById('editProductResults');
        if (!eps.contains(e.target) && !epr.contains(e.target)) {
            epr.classList.add('hidden');
        }
    });

    // ==================== PRODUCT SEARCH ====================
    const productSearchInput = document.getElementById('productSearch');
    const productResults = document.getElementById('productResults');

    productSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { productResults.classList.add('hidden'); return; }

        searchTimeout = setTimeout(() => {
            fetch('{{ route("admin.orders.search-products") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(products => {
                    if (products.length === 0) {
                        productResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Sin resultados</div>';
                    } else {
                        productResults.innerHTML = products.map(p => `
                            <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition flex items-center gap-3"
                                 onclick='addOrderItem(${JSON.stringify(p)})'>
                                ${p.image
                                    ? `<img src="${p.image}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">`
                                    : `<div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-300 text-xs"></i></div>`
                                }
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">${p.name}</p>
                                    <p class="text-xs text-gray-400">SKU: ${p.sku || '—'} · Stock: ${p.stock}</p>
                                </div>
                                <span class="text-sm font-bold text-gray-800 flex-shrink-0">S/ ${parseFloat(p.price).toFixed(2)}</span>
                            </div>
                        `).join('');
                    }
                    productResults.classList.remove('hidden');
                });
        }, 300);
    });

    // ==================== ORDER ITEMS MANAGEMENT ====================
    function addOrderItem(product) {
        // Check if already added
        const existing = orderItemsList.find(i => i.product_id === product.id);
        if (existing) {
            if (existing.quantity < product.stock) {
                existing.quantity++;
                renderOrderItems();
            }
            productResults.classList.add('hidden');
            productSearchInput.value = '';
            return;
        }

        orderItemsList.push({
            idx: itemCounter++,
            product_id: product.id,
            name: product.name,
            sku: product.sku,
            price: parseFloat(product.price),
            stock: product.stock,
            image: product.image,
            quantity: 1
        });

        renderOrderItems();
        productResults.classList.add('hidden');
        productSearchInput.value = '';
    }

    function removeOrderItem(idx) {
        orderItemsList = orderItemsList.filter(i => i.idx !== idx);
        renderOrderItems();
    }

    function updateItemQty(idx, qty) {
        const item = orderItemsList.find(i => i.idx === idx);
        if (!item) return;
        qty = parseInt(qty);
        if (qty < 1) qty = 1;
        if (qty > item.stock) qty = item.stock;
        item.quantity = qty;
        renderOrderItems();
    }

    function renderOrderItems() {
        const container = document.getElementById('orderItems');
        const noMsg = document.getElementById('noItemsMsg');
        const totalsDiv = document.getElementById('orderTotals');

        if (orderItemsList.length === 0) {
            container.innerHTML = '';
            noMsg.classList.remove('hidden');
            totalsDiv.classList.add('hidden');
            return;
        }

        noMsg.classList.add('hidden');
        totalsDiv.classList.remove('hidden');

        let total = 0;
        container.innerHTML = orderItemsList.map(item => {
            const lineTotal = item.price * item.quantity;
            total += lineTotal;
            return `
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        ${item.image
                            ? `<img src="${item.image}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">`
                            : `<div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-400 text-xs"></i></div>`
                        }
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 leading-snug">${item.name}</p>
                            <p class="text-xs text-gray-400 mt-0.5">S/ ${item.price.toFixed(2)} c/u</p>
                        </div>
                        <button type="button" onclick="removeOrderItem(${item.idx})"
                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition flex-shrink-0" aria-label="Eliminar producto">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <div class="flex items-center justify-between mt-2.5 pl-[52px]">
                        <div class="flex items-center gap-1.5">
                            <button type="button" onclick="updateItemQty(${item.idx}, ${item.quantity - 1})"
                                class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Disminuir cantidad">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" value="${item.quantity}" min="1" max="${item.stock}"
                                onchange="updateItemQty(${item.idx}, this.value)"
                                class="w-12 text-center text-sm border border-gray-200 rounded-md py-1 outline-none focus:ring-1 focus:ring-indigo-400">
                            <button type="button" onclick="updateItemQty(${item.idx}, ${item.quantity + 1})"
                                class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Aumentar cantidad">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <p class="text-sm font-bold text-gray-800">S/ ${lineTotal.toFixed(2)}</p>
                    </div>
                    <input type="hidden" name="items[${item.idx}][product_id]" value="${item.product_id}" form="createOrderForm">
                    <input type="hidden" name="items[${item.idx}][quantity]" value="${item.quantity}" form="createOrderForm">
                </div>
            `;
        }).join('');

        document.getElementById('orderTotalAmount').textContent = 'S/ ' + total.toFixed(2);
    }

    function submitOrder() {
        if (orderItemsList.length === 0) {
            showToast('Agrega al menos un producto', 'warning');
            return;
        }
        const name = document.getElementById('create_customer_name').value.trim();
        if (!name) {
            showToast('Ingresa el nombre del cliente', 'warning');
            return;
        }

        const btn = document.getElementById('btnCreateOrder');
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5 text-xs"></i> <span>Creando...</span>';

        document.getElementById('createOrderForm').submit();
    }

    // ==================== VIEW ORDER ====================
    function viewOrder(orderId) {
        document.getElementById('viewOrderContent').innerHTML = '<div class="flex items-center justify-center py-20"><i class="fas fa-spinner fa-spin text-indigo-400 text-xl"></i></div>';
        showDrawer(viewDrawer);

        fetch('/admin/orders/' + orderId)
            .then(r => r.json())
            .then(order => {
                document.getElementById('viewOrderNumber').textContent = order.order_number;

                const statusColors = {
                    pendiente: 'bg-amber-50 text-amber-600',
                    confirmado: 'bg-blue-50 text-blue-600',
                    en_preparacion: 'bg-indigo-50 text-indigo-600',
                    enviado: 'bg-violet-50 text-violet-600',
                    entregado: 'bg-emerald-50 text-emerald-600',
                    cancelado: 'bg-red-50 text-red-500'
                };
                const statusLabels = {
                    pendiente: 'Pendiente', confirmado: 'Confirmado', en_preparacion: 'En preparación',
                    enviado: 'Enviado', entregado: 'Entregado', cancelado: 'Cancelado'
                };
                const paymentMethods = {
                    efectivo: 'Efectivo', transferencia: 'Transferencia', yape_plin: 'Yape / Plin', tarjeta: 'Tarjeta'
                };
                const paymentStatusLabels = { pendiente: 'Pendiente', pagado: 'Pagado', fallido: 'Fallido' };
                const paymentStatusColors = { pendiente: 'text-amber-500', pagado: 'text-emerald-500', fallido: 'text-red-500' };

                const fmtDate = (d) => {
                    if (!d) return '—';
                    return new Date(d).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                };

                let html = `
                    <div class="space-y-6">
                        {{-- Status & Source --}}
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold ${statusColors[order.status] || 'bg-gray-50 text-gray-500'}">
                                ${statusLabels[order.status] || order.status}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold ${order.source === 'web' ? 'bg-cyan-50 text-cyan-600' : 'bg-violet-50 text-violet-600'}">
                                ${order.source === 'web' ? '<i class="fas fa-globe mr-1 text-[9px]"></i>Web' : '<i class="fas fa-user-shield mr-1 text-[9px]"></i>Admin'}
                            </span>
                            <span class="text-xs text-gray-400">${fmtDate(order.created_at)}</span>
                        </div>

                        {{-- Client Info --}}
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Cliente</h4>
                            <div class="space-y-1.5">
                                <p class="text-sm font-semibold text-gray-800">${order.customer_name}</p>
                                ${order.customer_email ? `<p class="text-sm text-gray-500"><i class="fas fa-envelope text-[10px] text-gray-400 mr-2"></i>${order.customer_email}</p>` : ''}
                                ${order.customer_phone ? `<p class="text-sm text-gray-500"><i class="fas fa-phone text-[10px] text-gray-400 mr-2"></i>${order.customer_phone}</p>` : ''}
                                ${order.shipping_address ? `<p class="text-sm text-gray-500"><i class="fas fa-location-dot text-[10px] text-gray-400 mr-2"></i>${order.shipping_address}</p>` : ''}
                                ${order.shipping_agency ? `<p class="text-sm text-gray-500"><i class="fas fa-truck text-[10px] text-gray-400 mr-2"></i>${order.shipping_agency}${order.shipping_agency_address ? ' — ' + order.shipping_agency_address : ''}</p>` : ''}
                            </div>
                        </div>

                        {{-- Items --}}
                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Productos</h4>
                            <div class="space-y-2">
                                ${(order.items || []).map(item => `
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                        ${item.product && item.product.primary_image
                                            ? `<img src="${item.product.primary_image.image_url}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">`
                                            : `<div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-400 text-xs"></i></div>`
                                        }
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-700 truncate">${item.product_name}</p>
                                            <p class="text-xs text-gray-400">SKU: ${item.product_sku || '—'} · ${item.quantity} × S/ ${parseFloat(item.unit_price).toFixed(2)}</p>
                                        </div>
                                        <span class="text-sm font-bold text-gray-800 flex-shrink-0">S/ ${parseFloat(item.line_total).toFixed(2)}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        {{-- Totals --}}
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Subtotal</span>
                                    <span class="text-gray-700">S/ ${parseFloat(order.subtotal).toFixed(2)}</span>
                                </div>
                                ${parseFloat(order.discount_amount) > 0 ? `
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Descuento</span>
                                    <span class="text-red-500">-S/ ${parseFloat(order.discount_amount).toFixed(2)}</span>
                                </div>` : ''}
                                ${parseFloat(order.shipping_cost) > 0 ? `
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Envío</span>
                                    <span class="text-gray-700">S/ ${parseFloat(order.shipping_cost).toFixed(2)}</span>
                                </div>` : ''}
                                <div class="flex justify-between text-sm font-bold pt-2 border-t border-gray-200">
                                    <span class="text-gray-800">Total</span>
                                    <span class="text-gray-900 text-base">S/ ${parseFloat(order.total).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Payment --}}
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Pago</h4>
                            <div class="flex items-center gap-4">
                                <span class="text-sm text-gray-700">${paymentMethods[order.payment_method] || order.payment_method}</span>
                                <span class="text-sm font-semibold ${paymentStatusColors[order.payment_status] || 'text-gray-500'}">${paymentStatusLabels[order.payment_status] || order.payment_status}</span>
                            </div>
                        </div>

                        ${order.admin_notes ? `
                        <div class="bg-amber-50 rounded-xl p-4">
                            <h4 class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-2">Notas internas</h4>
                            <p class="text-sm text-gray-700">${order.admin_notes}</p>
                        </div>` : ''}

                        ${order.creator ? `
                        <div class="text-xs text-gray-400 pt-2 border-t border-gray-100">
                            Creado por: ${order.creator.first_name} ${order.creator.last_name}
                        </div>` : ''}
                    </div>
                `;

                document.getElementById('viewOrderContent').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('viewOrderContent').innerHTML = '<div class="text-center py-20"><p class="text-sm text-red-500">Error al cargar la venta</p></div>';
            });
    }

    // ==================== EDIT CLIENT SEARCH ====================
    const editClientSearchInput = document.getElementById('editClientSearch');
    const editClientResults = document.getElementById('editClientResults');

    editClientSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { editClientResults.classList.add('hidden'); return; }

        searchTimeout = setTimeout(() => {
            fetch('{{ route("admin.orders.search-users") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(users => {
                    if (users.length === 0) {
                        editClientResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Sin resultados</div>';
                    } else {
                        editClientResults.innerHTML = users.map(u => `
                            <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition flex items-center gap-3"
                                 onclick="selectEditClient(${JSON.stringify(u).replace(/"/g, '&quot;')})">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0">
                                    ${u.name.substring(0,2).toUpperCase()}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">${u.name}</p>
                                    <p class="text-xs text-gray-400 truncate">${u.email || ''} ${u.phone ? '· ' + u.phone : ''}</p>
                                </div>
                            </div>
                        `).join('');
                    }
                    editClientResults.classList.remove('hidden');
                });
        }, 300);
    });

    var editSelectedClientAddress = null;

    function selectEditClient(user) {
        document.getElementById('edit_customer_name').value = user.name;
        document.getElementById('edit_customer_email').value = user.email || '';
        document.getElementById('edit_customer_phone').value = user.phone || '';
        editClientResults.classList.add('hidden');
        editClientSearchInput.value = '';

        var badge = document.getElementById('editSavedAddressBadge');
        if (user.full_address && badge) {
            editSelectedClientAddress = user.full_address;
            document.getElementById('editSavedAddressText').textContent = user.full_address;
            badge.classList.remove('hidden');
            const editShipAddr = document.getElementById('edit_shipping_address');
            if (editShipAddr) editShipAddr.value = user.full_address;
        } else {
            editSelectedClientAddress = null;
            if (badge) badge.classList.add('hidden');
        }
    }

    function useEditSavedAddress() {
        const editShipAddr = document.getElementById('edit_shipping_address');
        if (editSelectedClientAddress && editShipAddr) {
            editShipAddr.value = editSelectedClientAddress;
        }
    }

    // ==================== EDIT PRODUCT SEARCH ====================
    const editProductSearchInput = document.getElementById('editProductSearch');
    const editProductResults = document.getElementById('editProductResults');

    editProductSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { editProductResults.classList.add('hidden'); return; }

        searchTimeout = setTimeout(() => {
            fetch('{{ route("admin.orders.search-products") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(products => {
                    if (products.length === 0) {
                        editProductResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Sin resultados</div>';
                    } else {
                        editProductResults.innerHTML = products.map(p => `
                            <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition flex items-center gap-3"
                                 onclick='addEditItem(${JSON.stringify(p)})'>
                                ${p.image
                                    ? `<img src="${p.image}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">`
                                    : `<div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-300 text-xs"></i></div>`
                                }
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">${p.name}</p>
                                    <p class="text-xs text-gray-400">SKU: ${p.sku || '—'} · Stock: ${p.stock}</p>
                                </div>
                                <span class="text-sm font-bold text-gray-800 flex-shrink-0">S/ ${parseFloat(p.price).toFixed(2)}</span>
                            </div>
                        `).join('');
                    }
                    editProductResults.classList.remove('hidden');
                });
        }, 300);
    });

    // ==================== EDIT ITEMS MANAGEMENT ====================
    function addEditItem(product) {
        const existing = editItemsList.find(i => i.product_id === product.id);
        if (existing) {
            existing.quantity++;
            renderEditItems();
            editProductResults.classList.add('hidden');
            editProductSearchInput.value = '';
            return;
        }

        editItemsList.push({
            idx: editItemCounter++,
            product_id: product.id,
            name: product.name,
            sku: product.sku,
            price: parseFloat(product.price),
            stock: 9999,
            image: product.image,
            quantity: 1
        });

        renderEditItems();
        editProductResults.classList.add('hidden');
        editProductSearchInput.value = '';
    }

    function removeEditItem(idx) {
        editItemsList = editItemsList.filter(i => i.idx !== idx);
        renderEditItems();
    }

    function updateEditItemQty(idx, qty) {
        const item = editItemsList.find(i => i.idx === idx);
        if (!item) return;
        qty = parseInt(qty);
        if (qty < 1) qty = 1;
        item.quantity = qty;
        renderEditItems();
    }

    function renderEditItems() {
        const container = document.getElementById('editOrderItems');
        const noMsg = document.getElementById('editNoItemsMsg');
        const totalsDiv = document.getElementById('editOrderTotals');

        if (editItemsList.length === 0) {
            container.innerHTML = '';
            noMsg.classList.remove('hidden');
            totalsDiv.classList.add('hidden');
            return;
        }

        noMsg.classList.add('hidden');
        totalsDiv.classList.remove('hidden');

        let total = 0;
        container.innerHTML = editItemsList.map(item => {
            const lineTotal = item.price * item.quantity;
            total += lineTotal;
            return `
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    ${item.image
                        ? `<img src="${item.image}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">`
                        : `<div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-400 text-xs"></i></div>`
                    }
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">${item.name}</p>
                        <p class="text-xs text-gray-400">S/ ${item.price.toFixed(2)} c/u</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="updateEditItemQty(${item.idx}, ${item.quantity - 1})"
                            class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Disminuir cantidad">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" value="${item.quantity}" min="1"
                            onchange="updateEditItemQty(${item.idx}, this.value)"
                            class="w-12 text-center text-sm border border-gray-200 rounded-md py-1 outline-none focus:ring-1 focus:ring-indigo-400">
                        <button type="button" onclick="updateEditItemQty(${item.idx}, ${item.quantity + 1})"
                            class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Aumentar cantidad">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="text-right flex-shrink-0 w-20">
                        <p class="text-sm font-bold text-gray-800">S/ ${lineTotal.toFixed(2)}</p>
                    </div>
                    <button type="button" onclick="removeEditItem(${item.idx})"
                        class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition flex-shrink-0" aria-label="Eliminar producto">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    <input type="hidden" name="items[${item.idx}][product_id]" value="${item.product_id}">
                    <input type="hidden" name="items[${item.idx}][quantity]" value="${item.quantity}">
                </div>
            `;
        }).join('');

        document.getElementById('editOrderTotal').textContent = 'S/ ' + total.toFixed(2);
    }

    function submitEditOrder() {
        if (editItemsList.length === 0) {
            showToast('Agrega al menos un producto', 'warning');
            return;
        }
        const name = document.getElementById('edit_customer_name').value.trim();
        if (!name) {
            showToast('Ingresa el nombre del cliente', 'warning');
            return;
        }
        document.getElementById('editOrderForm').submit();
    }

    // ==================== EDIT ORDER ====================
    function openEditDrawer(orderId) {
        document.getElementById('editOrderItems').innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-indigo-400"></i></div>';
        document.getElementById('editNoItemsMsg').classList.add('hidden');
        editProductSearchInput.value = '';
        editClientSearchInput.value = '';
        showDrawer(editDrawer);

        fetch('/admin/orders/' + orderId)
            .then(r => r.json())
            .then(order => {
                // Set form action
                document.getElementById('editOrderForm').action = '/admin/orders/' + order.id;

                // Header info
                document.getElementById('editOrderNumber').textContent = order.order_number;
                document.getElementById('editOrderTitle').textContent = order.order_number;
                document.getElementById('editOrderMeta').textContent = (order.source === 'web' ? 'Web' : 'Admin') + ' · ' + new Date(order.created_at).toLocaleDateString('es-PE');

                // Client fields
                document.getElementById('edit_customer_name').value = order.customer_name || '';
                document.getElementById('edit_customer_email').value = order.customer_email || '';
                document.getElementById('edit_customer_phone').value = order.customer_phone || '';

                // Status
                document.getElementById('edit_status').value = order.status;

                // Payment
                document.getElementById('edit_payment_method').value = order.payment_method;
                document.getElementById('edit_payment_status').value = order.payment_status;

                // Shipping
                const editAddr = document.getElementById('edit_shipping_address');
                if (editAddr) editAddr.value = order.shipping_address || '';
                const editAgency = document.getElementById('edit_shipping_agency');
                if (editAgency) {
                    editAgency.value = order.shipping_agency || '';
                    updateAgencyDatalist('edit_shipping_agency', 'editAgencyAddressList');
                }
                const editAgencyAddr = document.getElementById('edit_shipping_agency_address');
                @if($shippingMode === 'both')
                // Auto-select the correct tab based on order data
                if (order.shipping_agency) {
                    toggleAdminShipping('edit', 'agency');
                } else {
                    toggleAdminShipping('edit', 'address');
                }
                @endif
                if (editAgencyAddr) editAgencyAddr.value = order.shipping_agency_address || '';

                // Notes
                document.getElementById('edit_admin_notes').value = order.admin_notes || '';

                // Load items into editable list
                editItemsList = [];
                editItemCounter = 0;
                (order.items || []).forEach(item => {
                    const img = (item.product && item.product.primary_image) ? item.product.primary_image.image_url : null;
                    editItemsList.push({
                        idx: editItemCounter++,
                        product_id: item.product_id,
                        name: item.product_name,
                        sku: item.product_sku,
                        price: parseFloat(item.unit_price),
                        stock: 9999,
                        image: img,
                        quantity: item.quantity
                    });
                });
                renderEditItems();

                // Dates
                const fmtDate = (d) => {
                    if (!d) return '—';
                    return new Date(d).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                };
                document.getElementById('editCreatedAt').textContent = fmtDate(order.created_at);
                document.getElementById('editUpdatedAt').textContent = fmtDate(order.updated_at);
            })
            .catch(() => {
                document.getElementById('editOrderItems').innerHTML = '<p class="text-sm text-red-500 text-center py-4">Error al cargar la venta</p>';
            });
    }

    // ==================== UPDATE STATUS AJAX ====================
    // Status dropdown
    const statusStyles = @json(\App\Models\Order::STATUS_COLORS);
    const statusLabels = @json(\App\Models\Order::STATUS_LABELS);

    function toggleStatusDropdown(btn, orderId) {
        // Close all other open dropdowns
        document.querySelectorAll('.status-dropdown').forEach(d => {
            if (d !== btn.nextElementSibling) d.classList.add('hidden');
        });
        btn.nextElementSibling.classList.toggle('hidden');
    }

    function selectStatus(optBtn, orderId, status) {
        const container = optBtn.closest('[data-status-dropdown]');
        const badge = container.querySelector('.status-badge');
        const dropdown = container.querySelector('.status-dropdown');
        dropdown.classList.add('hidden');

        // Optimistic UI update
        const style = statusStyles[status] || { bg: 'bg-gray-50', text: 'text-gray-500' };
        const oldClasses = badge.className;
        badge.className = 'status-badge inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold cursor-pointer transition-all hover:shadow-sm ' + style.bg + ' ' + style.text;
        badge.dataset.current = status;
        badge.querySelector('.status-label').textContent = statusLabels[status];

        // Update checkmarks in dropdown
        dropdown.querySelectorAll('button').forEach(b => {
            b.classList.remove('font-bold');
            const check = b.querySelector('.fa-check');
            if (check) check.remove();
        });
        optBtn.classList.add('font-bold');
        optBtn.insertAdjacentHTML('beforeend', '<i class="fas fa-check text-[9px] text-indigo-500"></i>');

        fetch('/admin/orders/' + orderId + '/status', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(r => r.json())
        .then(data => {
            showToast(data.message || 'Estado actualizado', 'success');
        })
        .catch(() => {
            showToast('Error al actualizar estado', 'error');
            badge.className = oldClasses;
        });
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[data-status-dropdown]')) {
            document.querySelectorAll('.status-dropdown').forEach(d => d.classList.add('hidden'));
        }
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDrawer();
    });

    // Auto-open drawer if validation errors
    @if($errors->any())
    openCreateDrawer();
    @endif

    // Auto-open order from notification link (?view=ID)
    const viewParam = new URLSearchParams(window.location.search).get('view');
    if (viewParam) {
        viewOrder(viewParam);
        history.replaceState(null, '', window.location.pathname + window.location.search.replace(/[?&]view=\d+/, '').replace(/^\?$/, ''));
    }
</script>
@endsection
