@extends('admin.layouts.app')
@section('title', 'Compras')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Compras</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalPurchases) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Todas las compras</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-cart-shopping text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Compras Hoy</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($purchasesToday) }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('d M Y') }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-calendar-day text-emerald-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Gasto del Mes</p>
            <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($monthlySpending, 2) }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-violet-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-coins text-violet-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Pendientes</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($pendingPurchases) }}</h3>
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
    $selectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
    $inputClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition";
    $hasFilters = request('search') || request('status') || request('supplier_id') || request('date_from') || request('date_to');
@endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="px-5 pt-5 pb-2">
        <h3 class="text-base font-semibold text-gray-800">Filtros de búsqueda</h3>
    </div>
    <div class="px-5 pb-5 mt-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado</label>
                <select id="filter_status" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Todos los estados</option>
                    @foreach(\App\Models\Purchase::STATUS_LABELS as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Proveedor</label>
                <select id="filter_supplier_id" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Todos los proveedores</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->business_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Desde</label>
                <input type="date" id="filter_date_from" value="{{ request('date_from') }}" onchange="applyFilters()" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Hasta</label>
                <input type="date" id="filter_date_to" value="{{ request('date_to') }}" onchange="applyFilters()" class="{{ $inputClass }}">
            </div>
        </div>
        @if($hasFilters)
        <div class="mt-4">
            <a href="{{ route('admin.purchases.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-rotate-left text-xs"></i> Limpiar filtros
            </a>
        </div>
        @endif
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
                        placeholder="Buscar compra, proveedor..."
                        onkeydown="if(event.key==='Enter'){event.preventDefault();applyFilters()}"
                        class="w-full sm:w-56 pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
                <button onclick="openCreateDrawer()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm shadow-indigo-200 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-plus text-xs"></i>
                    <span class="hidden sm:inline">Nueva Compra</span>
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
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"># Compra</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Proveedor</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">F. Esperada</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($purchases as $purchase)
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3">
                        <span class="text-sm font-semibold text-indigo-600 font-mono">{{ $purchase->purchase_number }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $purchase->supplier->business_name ?? 'Sin proveedor' }}</p>
                            @if($purchase->supplier?->contact_name)
                            <p class="text-xs text-gray-400 truncate">{{ $purchase->supplier->contact_name }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $purchase->items->count() }} {{ $purchase->items->count() === 1 ? 'item' : 'items' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm font-bold text-gray-800">S/ {{ number_format($purchase->total, 2) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <select onchange="updatePurchaseStatus({{ $purchase->id }}, this.value)"
                            class="px-2.5 py-1 rounded-md text-xs font-semibold border-0 cursor-pointer focus:ring-2 focus:ring-indigo-500/20 {{ $purchase->status_color['bg'] }} {{ $purchase->status_color['text'] }}"
                            style="{{ $selectStyle }} background-size: 0.75rem; padding-right: 1.5rem;">
                            @foreach(\App\Models\Purchase::STATUS_LABELS as $val => $label)
                            <option value="{{ $val }}" {{ $purchase->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        @if($purchase->expected_date)
                        <span class="text-sm text-gray-500">{{ $purchase->expected_date->format('d/m/Y') }}</span>
                        @else
                        <span class="text-sm text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-500">{{ $purchase->created_at->format('d/m/Y') }}</span>
                        <p class="text-[10px] text-gray-400">{{ $purchase->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="viewPurchase({{ $purchase->id }})"
                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition" title="Ver detalle">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            <button onclick="openEditDrawer({{ $purchase->id }})"
                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-md transition" title="Editar">
                                <i class="fas fa-pen-to-square text-sm"></i>
                            </button>
                            <form action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $purchase->purchase_number }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition" title="Eliminar">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-cart-shopping text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-600 font-semibold mb-1">Sin compras</h3>
                            <p class="text-sm text-gray-400 max-w-xs">No se encontraron compras que coincidan con los filtros</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($purchases as $purchase)
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-indigo-600 font-mono">{{ $purchase->purchase_number }}</span>
                <span class="text-sm font-bold text-gray-800">S/ {{ number_format($purchase->total, 2) }}</span>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <p class="font-semibold text-gray-800 text-sm flex-1 truncate">{{ $purchase->supplier->business_name ?? 'Sin proveedor' }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $purchase->status_color['bg'] }} {{ $purchase->status_color['text'] }}">
                    {{ $purchase->status_label }}
                </span>
                @if($purchase->expected_date)
                <span class="text-xs text-gray-400">Esperado: {{ $purchase->expected_date->format('d/m/Y') }}</span>
                @endif
                <span class="text-gray-300">|</span>
                <span class="text-xs text-gray-400">{{ $purchase->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex items-center justify-end gap-1 mt-2">
                <button onclick="viewPurchase({{ $purchase->id }})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 rounded-md transition">
                    <i class="fas fa-eye text-sm"></i>
                </button>
                <button onclick="openEditDrawer({{ $purchase->id }})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-amber-600 rounded-md transition">
                    <i class="fas fa-pen-to-square text-sm"></i>
                </button>
                <form action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $purchase->purchase_number }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-md transition">
                        <i class="fas fa-trash-can text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-cart-shopping text-xl text-gray-300"></i>
            </div>
            <p class="text-sm text-gray-400">No se encontraron compras</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Footer --}}
    @if($purchases->total() > 0)
    <div class="px-5 py-3.5 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Mostrando {{ $purchases->firstItem() }} a {{ $purchases->lastItem() }} de {{ number_format($purchases->total()) }} registros
            </p>
            @if($purchases->hasPages())
            <nav class="flex items-center gap-1">
                @if($purchases->currentPage() > 2)
                <a href="{{ $purchases->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Primera">
                    <i class="fas fa-angles-left"></i>
                </a>
                @endif
                @if($purchases->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                @else
                <a href="{{ $purchases->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-left"></i></a>
                @endif
                @php
                    $current = $purchases->currentPage();
                    $last = $purchases->lastPage();
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
                    <a href="{{ $purchases->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                    @endif
                @endforeach
                @if($purchases->hasMorePages())
                <a href="{{ $purchases->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-right"></i></a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
                @endif
                @if($purchases->currentPage() < $last - 1)
                <a href="{{ $purchases->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Última">
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

{{-- ==================== CREATE PURCHASE DRAWER ==================== --}}
<div id="createDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[600px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Nueva Compra</h2>
            <p class="text-sm text-gray-400 mt-0.5">Registrar compra a proveedor</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="{{ route('admin.purchases.store') }}" id="createPurchaseForm" class="p-6">
            @csrf

            {{-- Supplier Section --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-building text-indigo-400 text-xs"></i> Proveedor
                </h3>
                <div class="relative mb-3">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="supplierSearch" placeholder="Buscar proveedor por nombre o RUC..."
                        autocomplete="off"
                        class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    <div id="supplierResults" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto hidden"></div>
                </div>
                <div id="selectedSupplier" class="hidden">
                    <div class="flex items-center gap-3 p-3 bg-indigo-50/50 rounded-lg">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-building text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate" id="selectedSupplierName"></p>
                            <p class="text-xs text-gray-400 truncate" id="selectedSupplierInfo"></p>
                        </div>
                        <button type="button" onclick="clearSupplier()"
                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition flex-shrink-0">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <input type="hidden" name="supplier_id" id="create_supplier_id">
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
                <div id="purchaseItems" class="space-y-2">
                    {{-- Items will be added dynamically --}}
                </div>
                <div id="noItemsMsg" class="text-center py-6 border-2 border-dashed border-gray-200 rounded-lg">
                    <i class="fas fa-cart-plus text-gray-300 text-xl mb-2"></i>
                    <p class="text-sm text-gray-400">Busca y agrega productos</p>
                </div>

                {{-- Totals --}}
                <div id="purchaseTotals" class="mt-4 pt-4 border-t border-gray-100 hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-600">Total:</span>
                        <span id="purchaseTotalAmount" class="text-lg font-bold text-gray-900">S/ 0.00</span>
                    </div>
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar text-emerald-400 text-xs"></i> Información adicional
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Fecha esperada de entrega</label>
                        <input type="date" name="expected_date"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Notas / observaciones</label>
                        <textarea name="notes" rows="2" placeholder="Notas sobre esta compra..."
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none"></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="submitPurchase()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-check mr-1.5 text-xs"></i> Crear Compra
        </button>
    </div>
</div>

{{-- ==================== VIEW PURCHASE DRAWER ==================== --}}
<div id="viewDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[550px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Detalle de Compra</h2>
            <p class="text-sm text-gray-400 mt-0.5" id="viewPurchaseNumber"></p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-6" id="viewPurchaseContent">
        <div class="flex items-center justify-center py-20">
            <i class="fas fa-spinner fa-spin text-indigo-400 text-xl"></i>
        </div>
    </div>
</div>

{{-- ==================== EDIT PURCHASE DRAWER ==================== --}}
@php
    $drawerSelectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
    $drawerInputClass = "w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition";
@endphp
<div id="editDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[600px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Editar Compra</h2>
            <p class="text-sm text-gray-400 mt-0.5" id="editPurchaseNumber"></p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="" id="editPurchaseForm" class="p-6">
            @csrf
            @method('PUT')

            {{-- Purchase Info Header --}}
            <div class="flex items-center gap-3 mb-6 p-4 bg-indigo-50/50 rounded-xl">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-cart-shopping text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800" id="editPurchaseTitle"></p>
                    <p class="text-xs text-gray-400" id="editPurchaseMeta"></p>
                </div>
            </div>

            {{-- Supplier Section --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-building text-indigo-400 text-xs"></i> Proveedor
                </h3>
                <div class="relative mb-3">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="editSupplierSearch" placeholder="Buscar proveedor..."
                        autocomplete="off"
                        class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    <div id="editSupplierResults" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto hidden"></div>
                </div>
                <div id="editSelectedSupplier" class="hidden">
                    <div class="flex items-center gap-3 p-3 bg-indigo-50/50 rounded-lg">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-building text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate" id="editSelectedSupplierName"></p>
                            <p class="text-xs text-gray-400 truncate" id="editSelectedSupplierInfo"></p>
                        </div>
                        <button type="button" onclick="clearEditSupplier()"
                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition flex-shrink-0">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <input type="hidden" name="supplier_id" id="edit_supplier_id">
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
                <div id="editPurchaseItems" class="space-y-2"></div>
                <div id="editNoItemsMsg" class="text-center py-6 border-2 border-dashed border-gray-200 rounded-lg hidden">
                    <i class="fas fa-cart-plus text-gray-300 text-xl mb-2"></i>
                    <p class="text-sm text-gray-400">Busca y agrega productos</p>
                </div>

                {{-- Totals --}}
                <div id="editPurchaseTotals" class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-600">Total:</span>
                        <span id="editPurchaseTotal" class="text-lg font-bold text-gray-900">S/ 0.00</span>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-arrows-rotate text-blue-400 text-xs"></i> Estado
                </h3>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado de la compra</label>
                    <select name="status" id="edit_status" class="{{ $drawerSelectClass }}" style="{{ $selectStyle }}">
                        @foreach(\App\Models\Purchase::STATUS_LABELS as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar text-emerald-400 text-xs"></i> Información adicional
                </h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Fecha esperada de entrega</label>
                        <input type="date" name="expected_date" id="edit_expected_date" class="{{ $drawerInputClass }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Notas / observaciones</label>
                        <textarea name="notes" id="edit_notes" rows="3" placeholder="Notas sobre esta compra..."
                            class="{{ $drawerInputClass }} resize-none"></textarea>
                    </div>
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
        <button onclick="submitEditPurchase()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
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
    let purchaseItemsList = [];
    let itemCounter = 0;
    let editItemsList = [];
    let editItemCounter = 0;
    let searchTimeout = null;

    // ==================== UNIFIED FILTERS ====================
    function applyFilters() {
        const params = new URLSearchParams();
        const fields = {
            search: document.getElementById('filter_search')?.value?.trim(),
            status: document.getElementById('filter_status')?.value,
            supplier_id: document.getElementById('filter_supplier_id')?.value,
            date_from: document.getElementById('filter_date_from')?.value,
            date_to: document.getElementById('filter_date_to')?.value,
            per_page: document.getElementById('filter_per_page')?.value,
        };

        for (const [key, val] of Object.entries(fields)) {
            if (val !== '' && val !== null && val !== undefined) {
                params.set(key, val);
            }
        }

        window.location.href = '{{ route("admin.purchases.index") }}' + (params.toString() ? '?' + params.toString() : '');
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
        document.getElementById('createPurchaseForm').reset();
        purchaseItemsList = [];
        itemCounter = 0;
        renderPurchaseItems();
        clearSupplier();
        showDrawer(createDrawer);
    }

    // ==================== SUPPLIER SEARCH (CREATE) ====================
    const supplierSearchInput = document.getElementById('supplierSearch');
    const supplierResults = document.getElementById('supplierResults');

    supplierSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { supplierResults.classList.add('hidden'); return; }

        searchTimeout = setTimeout(() => {
            fetch('{{ route("admin.purchases.search-suppliers") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(suppliers => {
                    if (suppliers.length === 0) {
                        supplierResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Sin resultados</div>';
                    } else {
                        supplierResults.innerHTML = suppliers.map(s => `
                            <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition flex items-center gap-3"
                                 onclick="selectSupplier(${JSON.stringify(s).replace(/"/g, '&quot;')})">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0">
                                    ${s.business_name.substring(0,2).toUpperCase()}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">${s.business_name}</p>
                                    <p class="text-xs text-gray-400 truncate">${s.ruc || ''} ${s.contact_name ? '· ' + s.contact_name : ''}</p>
                                </div>
                            </div>
                        `).join('');
                    }
                    supplierResults.classList.remove('hidden');
                });
        }, 300);
    });

    function selectSupplier(supplier) {
        document.getElementById('create_supplier_id').value = supplier.id;
        document.getElementById('selectedSupplierName').textContent = supplier.business_name;
        document.getElementById('selectedSupplierInfo').textContent = (supplier.ruc || '') + (supplier.contact_name ? ' · ' + supplier.contact_name : '');
        document.getElementById('selectedSupplier').classList.remove('hidden');
        supplierSearchInput.value = '';
        supplierSearchInput.classList.add('hidden');
        supplierResults.classList.add('hidden');
    }

    function clearSupplier() {
        document.getElementById('create_supplier_id').value = '';
        document.getElementById('selectedSupplier').classList.add('hidden');
        supplierSearchInput.value = '';
        supplierSearchInput.classList.remove('hidden');
    }

    // ==================== PRODUCT SEARCH (CREATE) ====================
    const productSearchInput = document.getElementById('productSearch');
    const productResults = document.getElementById('productResults');

    productSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { productResults.classList.add('hidden'); return; }

        searchTimeout = setTimeout(() => {
            fetch('{{ route("admin.purchases.search-products") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(products => {
                    if (products.length === 0) {
                        productResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Sin resultados</div>';
                    } else {
                        productResults.innerHTML = products.map(p => `
                            <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition flex items-center gap-3"
                                 onclick='addPurchaseItem(${JSON.stringify(p)})'>
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

    // Click-outside handlers
    document.addEventListener('click', function(e) {
        if (!supplierSearchInput.contains(e.target) && !supplierResults.contains(e.target)) {
            supplierResults.classList.add('hidden');
        }
        if (!productSearchInput.contains(e.target) && !productResults.contains(e.target)) {
            productResults.classList.add('hidden');
        }
        const ess = document.getElementById('editSupplierSearch');
        const esr = document.getElementById('editSupplierResults');
        if (!ess.contains(e.target) && !esr.contains(e.target)) {
            esr.classList.add('hidden');
        }
        const eps = document.getElementById('editProductSearch');
        const epr = document.getElementById('editProductResults');
        if (!eps.contains(e.target) && !epr.contains(e.target)) {
            epr.classList.add('hidden');
        }
    });

    // ==================== PURCHASE ITEMS MANAGEMENT (CREATE) ====================
    function addPurchaseItem(product) {
        const existing = purchaseItemsList.find(i => i.product_id === product.id);
        if (existing) {
            existing.quantity++;
            renderPurchaseItems();
            productResults.classList.add('hidden');
            productSearchInput.value = '';
            return;
        }

        purchaseItemsList.push({
            idx: itemCounter++,
            product_id: product.id,
            name: product.name,
            sku: product.sku,
            price: parseFloat(product.price),
            stock: product.stock,
            image: product.image,
            unit_cost: parseFloat(product.price),
            quantity: 1
        });

        renderPurchaseItems();
        productResults.classList.add('hidden');
        productSearchInput.value = '';
    }

    function removePurchaseItem(idx) {
        purchaseItemsList = purchaseItemsList.filter(i => i.idx !== idx);
        renderPurchaseItems();
    }

    function updateItemQty(idx, qty) {
        const item = purchaseItemsList.find(i => i.idx === idx);
        if (!item) return;
        qty = parseInt(qty);
        if (qty < 1) qty = 1;
        item.quantity = qty;
        renderPurchaseItems();
    }

    function updateItemCost(idx, cost) {
        const item = purchaseItemsList.find(i => i.idx === idx);
        if (!item) return;
        cost = parseFloat(cost);
        if (isNaN(cost) || cost < 0) cost = 0;
        item.unit_cost = cost;
        renderPurchaseItems();
    }

    function renderPurchaseItems() {
        const container = document.getElementById('purchaseItems');
        const noMsg = document.getElementById('noItemsMsg');
        const totalsDiv = document.getElementById('purchaseTotals');

        if (purchaseItemsList.length === 0) {
            container.innerHTML = '';
            noMsg.classList.remove('hidden');
            totalsDiv.classList.add('hidden');
            return;
        }

        noMsg.classList.add('hidden');
        totalsDiv.classList.remove('hidden');

        let total = 0;
        container.innerHTML = purchaseItemsList.map(item => {
            const lineTotal = item.unit_cost * item.quantity;
            total += lineTotal;
            return `
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3 mb-2">
                        ${item.image
                            ? `<img src="${item.image}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">`
                            : `<div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-400 text-xs"></i></div>`
                        }
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 truncate">${item.name}</p>
                            <p class="text-xs text-gray-400">SKU: ${item.sku || '—'}</p>
                        </div>
                        <div class="text-right flex-shrink-0 w-24">
                            <p class="text-sm font-bold text-gray-800">S/ ${lineTotal.toFixed(2)}</p>
                        </div>
                        <button type="button" onclick="removePurchaseItem(${item.idx})"
                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition flex-shrink-0">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-3 ml-[52px]">
                        <div class="flex items-center gap-1.5">
                            <label class="text-xs text-gray-400">Cant:</label>
                            <button type="button" onclick="updateItemQty(${item.idx}, ${item.quantity - 1})"
                                class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition text-xs">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" value="${item.quantity}" min="1"
                                onchange="updateItemQty(${item.idx}, this.value)"
                                class="w-14 text-center text-sm border border-gray-200 rounded-md py-1 outline-none focus:ring-1 focus:ring-indigo-400">
                            <button type="button" onclick="updateItemQty(${item.idx}, ${item.quantity + 1})"
                                class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition text-xs">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <label class="text-xs text-gray-400">Costo:</label>
                            <div class="relative">
                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-xs text-gray-400">S/</span>
                                <input type="number" step="0.01" min="0" value="${item.unit_cost.toFixed(2)}"
                                    onchange="updateItemCost(${item.idx}, this.value)"
                                    class="w-24 pl-7 pr-2 text-sm border border-gray-200 rounded-md py-1 outline-none focus:ring-1 focus:ring-indigo-400 text-right">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="items[${item.idx}][product_id]" value="${item.product_id}" form="createPurchaseForm">
                    <input type="hidden" name="items[${item.idx}][quantity]" value="${item.quantity}" form="createPurchaseForm">
                    <input type="hidden" name="items[${item.idx}][unit_cost]" value="${item.unit_cost.toFixed(2)}" form="createPurchaseForm">
                </div>
            `;
        }).join('');

        document.getElementById('purchaseTotalAmount').textContent = 'S/ ' + total.toFixed(2);
    }

    function submitPurchase() {
        if (purchaseItemsList.length === 0) {
            showToast('Agrega al menos un producto', 'warning');
            return;
        }
        const supplierId = document.getElementById('create_supplier_id')?.value;
        if (!supplierId) {
            showToast('Selecciona un proveedor', 'warning');
            return;
        }
        document.getElementById('createPurchaseForm').submit();
    }

    // ==================== VIEW PURCHASE ====================
    function viewPurchase(purchaseId) {
        document.getElementById('viewPurchaseContent').innerHTML = '<div class="flex items-center justify-center py-20"><i class="fas fa-spinner fa-spin text-indigo-400 text-xl"></i></div>';
        showDrawer(viewDrawer);

        fetch('/admin/purchases/' + purchaseId)
            .then(r => r.json())
            .then(purchase => {
                document.getElementById('viewPurchaseNumber').textContent = purchase.purchase_number;

                const statusColors = {
                    pendiente: 'bg-amber-50 text-amber-600',
                    aprobado: 'bg-blue-50 text-blue-600',
                    en_transito: 'bg-violet-50 text-violet-600',
                    recibido: 'bg-emerald-50 text-emerald-600',
                    cancelado: 'bg-red-50 text-red-500'
                };
                const statusLabels = {
                    pendiente: 'Pendiente', aprobado: 'Aprobado', en_transito: 'En tránsito',
                    recibido: 'Recibido', cancelado: 'Cancelado'
                };

                const fmtDate = (d) => {
                    if (!d) return '—';
                    return new Date(d).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                };
                const fmtDateShort = (d) => {
                    if (!d) return '—';
                    return new Date(d).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' });
                };

                let html = `
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold ${statusColors[purchase.status] || 'bg-gray-50 text-gray-500'}">
                                ${statusLabels[purchase.status] || purchase.status}
                            </span>
                            <span class="text-xs text-gray-400">${fmtDate(purchase.created_at)}</span>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Proveedor</h4>
                            <div class="space-y-1.5">
                                <p class="text-sm font-semibold text-gray-800">${purchase.supplier ? purchase.supplier.business_name : 'Sin proveedor'}</p>
                                ${purchase.supplier?.contact_name ? `<p class="text-sm text-gray-500"><i class="fas fa-user text-[10px] text-gray-400 mr-2"></i>${purchase.supplier.contact_name}</p>` : ''}
                                ${purchase.supplier?.phone ? `<p class="text-sm text-gray-500"><i class="fas fa-phone text-[10px] text-gray-400 mr-2"></i>${purchase.supplier.phone}</p>` : ''}
                                ${purchase.supplier?.ruc ? `<p class="text-sm text-gray-500"><i class="fas fa-id-card text-[10px] text-gray-400 mr-2"></i>RUC: ${purchase.supplier.ruc}</p>` : ''}
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Productos</h4>
                            <div class="space-y-2">
                                ${(purchase.items || []).map(item => `
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                        ${item.product && item.product.primary_image
                                            ? `<img src="${item.product.primary_image.image_url}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">`
                                            : `<div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-400 text-xs"></i></div>`
                                        }
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-700 truncate">${item.product_name}</p>
                                            <p class="text-xs text-gray-400">SKU: ${item.product_sku || '—'} · ${item.quantity} × S/ ${parseFloat(item.unit_cost).toFixed(2)}</p>
                                        </div>
                                        <span class="text-sm font-bold text-gray-800 flex-shrink-0">S/ ${parseFloat(item.line_total).toFixed(2)}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Subtotal</span>
                                    <span class="text-gray-700">S/ ${parseFloat(purchase.subtotal).toFixed(2)}</span>
                                </div>
                                ${parseFloat(purchase.tax_amount) > 0 ? `
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Impuestos</span>
                                    <span class="text-gray-700">S/ ${parseFloat(purchase.tax_amount).toFixed(2)}</span>
                                </div>` : ''}
                                <div class="flex justify-between text-sm font-bold pt-2 border-t border-gray-200">
                                    <span class="text-gray-800">Total</span>
                                    <span class="text-gray-900 text-base">S/ ${parseFloat(purchase.total).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Fechas</h4>
                            <div class="space-y-1.5">
                                ${purchase.expected_date ? `<p class="text-sm text-gray-500"><i class="fas fa-calendar text-[10px] text-gray-400 mr-2"></i>Esperada: ${fmtDateShort(purchase.expected_date)}</p>` : ''}
                                ${purchase.received_date ? `<p class="text-sm text-gray-500"><i class="fas fa-check-circle text-[10px] text-emerald-400 mr-2"></i>Recibida: ${fmtDateShort(purchase.received_date)}</p>` : ''}
                            </div>
                        </div>

                        ${purchase.notes ? `
                        <div class="bg-amber-50 rounded-xl p-4">
                            <h4 class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-2">Notas</h4>
                            <p class="text-sm text-gray-700">${purchase.notes}</p>
                        </div>` : ''}

                        ${purchase.creator ? `
                        <div class="text-xs text-gray-400 pt-2 border-t border-gray-100">
                            Creado por: ${purchase.creator.first_name} ${purchase.creator.last_name}
                        </div>` : ''}
                    </div>
                `;

                document.getElementById('viewPurchaseContent').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('viewPurchaseContent').innerHTML = '<div class="text-center py-20"><p class="text-sm text-red-500">Error al cargar la compra</p></div>';
            });
    }

    // ==================== EDIT SUPPLIER SEARCH ====================
    const editSupplierSearchInput = document.getElementById('editSupplierSearch');
    const editSupplierResults = document.getElementById('editSupplierResults');

    editSupplierSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { editSupplierResults.classList.add('hidden'); return; }

        searchTimeout = setTimeout(() => {
            fetch('{{ route("admin.purchases.search-suppliers") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(suppliers => {
                    if (suppliers.length === 0) {
                        editSupplierResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Sin resultados</div>';
                    } else {
                        editSupplierResults.innerHTML = suppliers.map(s => `
                            <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition flex items-center gap-3"
                                 onclick="selectEditSupplier(${JSON.stringify(s).replace(/"/g, '&quot;')})">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0">
                                    ${s.business_name.substring(0,2).toUpperCase()}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">${s.business_name}</p>
                                    <p class="text-xs text-gray-400 truncate">${s.ruc || ''} ${s.contact_name ? '· ' + s.contact_name : ''}</p>
                                </div>
                            </div>
                        `).join('');
                    }
                    editSupplierResults.classList.remove('hidden');
                });
        }, 300);
    });

    function selectEditSupplier(supplier) {
        document.getElementById('edit_supplier_id').value = supplier.id;
        document.getElementById('editSelectedSupplierName').textContent = supplier.business_name;
        document.getElementById('editSelectedSupplierInfo').textContent = (supplier.ruc || '') + (supplier.contact_name ? ' · ' + supplier.contact_name : '');
        document.getElementById('editSelectedSupplier').classList.remove('hidden');
        editSupplierSearchInput.value = '';
        editSupplierSearchInput.classList.add('hidden');
        editSupplierResults.classList.add('hidden');
    }

    function clearEditSupplier() {
        document.getElementById('edit_supplier_id').value = '';
        document.getElementById('editSelectedSupplier').classList.add('hidden');
        editSupplierSearchInput.value = '';
        editSupplierSearchInput.classList.remove('hidden');
    }

    // ==================== EDIT PRODUCT SEARCH ====================
    const editProductSearchInput = document.getElementById('editProductSearch');
    const editProductResults = document.getElementById('editProductResults');

    editProductSearchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { editProductResults.classList.add('hidden'); return; }

        searchTimeout = setTimeout(() => {
            fetch('{{ route("admin.purchases.search-products") }}?q=' + encodeURIComponent(q))
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
            image: product.image,
            unit_cost: parseFloat(product.price),
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

    function updateEditItemCost(idx, cost) {
        const item = editItemsList.find(i => i.idx === idx);
        if (!item) return;
        cost = parseFloat(cost);
        if (isNaN(cost) || cost < 0) cost = 0;
        item.unit_cost = cost;
        renderEditItems();
    }

    function renderEditItems() {
        const container = document.getElementById('editPurchaseItems');
        const noMsg = document.getElementById('editNoItemsMsg');
        const totalsDiv = document.getElementById('editPurchaseTotals');

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
            const lineTotal = item.unit_cost * item.quantity;
            total += lineTotal;
            return `
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3 mb-2">
                        ${item.image
                            ? `<img src="${item.image}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">`
                            : `<div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-400 text-xs"></i></div>`
                        }
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-700 truncate">${item.name}</p>
                            <p class="text-xs text-gray-400">SKU: ${item.sku || '—'}</p>
                        </div>
                        <div class="text-right flex-shrink-0 w-24">
                            <p class="text-sm font-bold text-gray-800">S/ ${lineTotal.toFixed(2)}</p>
                        </div>
                        <button type="button" onclick="removeEditItem(${item.idx})"
                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition flex-shrink-0">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-3 ml-[52px]">
                        <div class="flex items-center gap-1.5">
                            <label class="text-xs text-gray-400">Cant:</label>
                            <button type="button" onclick="updateEditItemQty(${item.idx}, ${item.quantity - 1})"
                                class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition text-xs">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" value="${item.quantity}" min="1"
                                onchange="updateEditItemQty(${item.idx}, this.value)"
                                class="w-14 text-center text-sm border border-gray-200 rounded-md py-1 outline-none focus:ring-1 focus:ring-indigo-400">
                            <button type="button" onclick="updateEditItemQty(${item.idx}, ${item.quantity + 1})"
                                class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-100 transition text-xs">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <label class="text-xs text-gray-400">Costo:</label>
                            <div class="relative">
                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-xs text-gray-400">S/</span>
                                <input type="number" step="0.01" min="0" value="${item.unit_cost.toFixed(2)}"
                                    onchange="updateEditItemCost(${item.idx}, this.value)"
                                    class="w-24 pl-7 pr-2 text-sm border border-gray-200 rounded-md py-1 outline-none focus:ring-1 focus:ring-indigo-400 text-right">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="items[${item.idx}][product_id]" value="${item.product_id}">
                    <input type="hidden" name="items[${item.idx}][quantity]" value="${item.quantity}">
                    <input type="hidden" name="items[${item.idx}][unit_cost]" value="${item.unit_cost.toFixed(2)}">
                </div>
            `;
        }).join('');

        document.getElementById('editPurchaseTotal').textContent = 'S/ ' + total.toFixed(2);
    }

    function submitEditPurchase() {
        if (editItemsList.length === 0) {
            showToast('Agrega al menos un producto', 'warning');
            return;
        }
        const supplierId = document.getElementById('edit_supplier_id')?.value;
        if (!supplierId) {
            showToast('Selecciona un proveedor', 'warning');
            return;
        }
        document.getElementById('editPurchaseForm').submit();
    }

    // ==================== EDIT PURCHASE ====================
    function openEditDrawer(purchaseId) {
        document.getElementById('editPurchaseItems').innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-indigo-400"></i></div>';
        document.getElementById('editNoItemsMsg').classList.add('hidden');
        editProductSearchInput.value = '';
        editSupplierSearchInput.value = '';
        showDrawer(editDrawer);

        fetch('/admin/purchases/' + purchaseId)
            .then(r => r.json())
            .then(purchase => {
                // Set form action
                document.getElementById('editPurchaseForm').action = '/admin/purchases/' + purchase.id;

                // Header info
                document.getElementById('editPurchaseNumber').textContent = purchase.purchase_number;
                document.getElementById('editPurchaseTitle').textContent = purchase.purchase_number;
                document.getElementById('editPurchaseMeta').textContent = new Date(purchase.created_at).toLocaleDateString('es-PE');

                // Supplier
                if (purchase.supplier) {
                    selectEditSupplier({
                        id: purchase.supplier.id,
                        business_name: purchase.supplier.business_name,
                        contact_name: purchase.supplier.contact_name || '',
                        ruc: purchase.supplier.ruc || '',
                    });
                } else {
                    clearEditSupplier();
                }

                // Status
                document.getElementById('edit_status').value = purchase.status;

                // Dates
                document.getElementById('edit_expected_date').value = purchase.expected_date ? purchase.expected_date.substring(0, 10) : '';

                // Notes
                document.getElementById('edit_notes').value = purchase.notes || '';

                // Load items into editable list
                editItemsList = [];
                editItemCounter = 0;
                (purchase.items || []).forEach(item => {
                    const img = (item.product && item.product.primary_image) ? item.product.primary_image.image_url : null;
                    editItemsList.push({
                        idx: editItemCounter++,
                        product_id: item.product_id,
                        name: item.product_name,
                        sku: item.product_sku,
                        price: parseFloat(item.unit_cost),
                        image: img,
                        unit_cost: parseFloat(item.unit_cost),
                        quantity: item.quantity
                    });
                });
                renderEditItems();

                // Timestamps
                const fmtDate = (d) => {
                    if (!d) return '—';
                    return new Date(d).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                };
                document.getElementById('editCreatedAt').textContent = fmtDate(purchase.created_at);
                document.getElementById('editUpdatedAt').textContent = fmtDate(purchase.updated_at);
            })
            .catch(() => {
                document.getElementById('editPurchaseItems').innerHTML = '<p class="text-sm text-red-500 text-center py-4">Error al cargar la compra</p>';
            });
    }

    // ==================== UPDATE STATUS AJAX ====================
    function updatePurchaseStatus(purchaseId, status) {
        fetch('/admin/purchases/' + purchaseId + '/status', {
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
        });
    }

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDrawer();
    });

    // Auto-open drawer if validation errors
    @if($errors->any())
    openCreateDrawer();
    @endif
</script>
@endsection
