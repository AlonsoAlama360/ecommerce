@extends('admin.layouts.app')
@section('title', 'Kardex')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Movimientos Hoy</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($movementsToday) }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('d M Y') }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-arrow-right-arrow-left text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Entradas del Mes</p>
            <h3 class="text-2xl font-bold text-emerald-600">{{ number_format($entriesMonth) }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-arrow-down text-emerald-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Salidas del Mes</p>
            <h3 class="text-2xl font-bold text-red-500">{{ number_format($exitsMonth) }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-arrow-up text-red-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Ajustes del Mes</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ number_format($adjustmentsMonth) }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-sliders text-blue-500"></i>
        </div>
    </div>
</div>

{{-- ==================== SEARCH FILTERS CARD ==================== --}}
@php
    $selectStyle = "background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;";
    $selectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
    $inputClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition";
    $hasFilters = request('product_id') || request('type') || request('date_from') || request('date_to');
@endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="px-5 pt-5 pb-2">
        <h3 class="text-base font-semibold text-gray-800">Filtros de búsqueda</h3>
    </div>
    <div class="px-5 pb-5 mt-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Producto</label>
                <select id="filter_product_id" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Todos los productos</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }} ({{ $product->sku }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Tipo</label>
                <select id="filter_type" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Todos los tipos</option>
                    @foreach(\App\Models\StockMovement::TYPE_LABELS as $val => $label)
                    <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
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
            <a href="{{ route('admin.kardex.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
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
                    @foreach([15, 30, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" onclick="exportKardex(event)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition text-sm font-medium shadow-sm shadow-emerald-200 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-file-csv text-xs"></i>
                    <span class="hidden sm:inline">Exportar</span>
                </a>
                <button onclick="openAdjustDrawer()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm shadow-indigo-200 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-sliders text-xs"></i>
                    <span class="hidden sm:inline">Ajuste Manual</span>
                    <span class="sm:hidden">Ajuste</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-4 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Cantidad</th>
                    <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Referencia</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($movements as $movement)
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3">
                        <span class="text-sm text-gray-600">{{ $movement->created_at->format('d/m/Y') }}</span>
                        <p class="text-[10px] text-gray-400">{{ $movement->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.kardex.show', $movement->product_id) }}" class="flex items-center gap-3 group">
                            @if($movement->product?->primaryImage)
                            <img src="{{ $movement->product->primaryImage->image_url }}" class="w-9 h-9 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                            @else
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-image text-gray-300 text-xs"></i>
                            </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate group-hover:text-indigo-600 transition">{{ $movement->product?->name ?? 'Producto eliminado' }}</p>
                                <p class="text-xs text-gray-400">{{ $movement->product?->sku ?? '—' }}</p>
                            </div>
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        @php $tc = $movement->type_color; @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $tc['bg'] }} {{ $tc['text'] }}">
                            @if($movement->type === 'entrada')
                            <i class="fas fa-arrow-down mr-1.5 text-[9px]"></i>
                            @elseif($movement->type === 'salida')
                            <i class="fas fa-arrow-up mr-1.5 text-[9px]"></i>
                            @else
                            <i class="fas fa-sliders mr-1.5 text-[9px]"></i>
                            @endif
                            {{ $movement->type_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="text-sm font-bold {{ $movement->quantity > 0 ? 'text-emerald-600' : ($movement->quantity < 0 ? 'text-red-500' : 'text-gray-600') }}">
                            {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xs text-gray-400">{{ $movement->stock_before }}</span>
                        <i class="fas fa-arrow-right text-[8px] text-gray-300 mx-1"></i>
                        <span class="text-sm font-semibold text-gray-700">{{ $movement->stock_after }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($movement->reference_label)
                        <span class="text-xs font-mono text-indigo-600">{{ $movement->reference_label }}</span>
                        @elseif($movement->notes)
                        <span class="text-xs text-gray-400 truncate block max-w-[150px]">{{ $movement->notes }}</span>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($movement->creator)
                        <span class="text-xs text-gray-500">{{ $movement->creator->first_name }}</span>
                        @else
                        <span class="text-xs text-gray-300">Sistema</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-arrow-right-arrow-left text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-600 font-semibold mb-1">Sin movimientos</h3>
                            <p class="text-sm text-gray-400 max-w-xs">No se encontraron movimientos de stock</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($movements as $movement)
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <a href="{{ route('admin.kardex.show', $movement->product_id) }}" class="text-sm font-semibold text-gray-800 truncate flex-1 hover:text-indigo-600 transition">
                    {{ $movement->product?->name ?? 'Eliminado' }}
                </a>
                <span class="text-sm font-bold ml-2 {{ $movement->quantity > 0 ? 'text-emerald-600' : ($movement->quantity < 0 ? 'text-red-500' : 'text-gray-600') }}">
                    {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                </span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                @php $tc = $movement->type_color; @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $tc['bg'] }} {{ $tc['text'] }}">
                    {{ $movement->type_label }}
                </span>
                <span class="text-xs text-gray-400">{{ $movement->stock_before }} → {{ $movement->stock_after }}</span>
                <span class="text-gray-300">|</span>
                <span class="text-xs text-gray-400">{{ $movement->created_at->format('d/m/Y H:i') }}</span>
            </div>
            @if($movement->reference_label)
            <p class="text-xs font-mono text-indigo-600 mt-1">{{ $movement->reference_label }}</p>
            @endif
        </div>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-arrow-right-arrow-left text-xl text-gray-300"></i>
            </div>
            <p class="text-sm text-gray-400">No se encontraron movimientos</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Footer --}}
    @if($movements->total() > 0)
    <div class="px-5 py-3.5 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Mostrando {{ $movements->firstItem() }} a {{ $movements->lastItem() }} de {{ number_format($movements->total()) }} registros
            </p>
            @if($movements->hasPages())
            <nav class="flex items-center gap-1">
                @if($movements->currentPage() > 2)
                <a href="{{ $movements->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Primera">
                    <i class="fas fa-angles-left"></i>
                </a>
                @endif
                @if($movements->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                @else
                <a href="{{ $movements->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-left"></i></a>
                @endif
                @php
                    $current = $movements->currentPage();
                    $last = $movements->lastPage();
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
                    <a href="{{ $movements->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                    @endif
                @endforeach
                @if($movements->hasMorePages())
                <a href="{{ $movements->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-right"></i></a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
                @endif
                @if($movements->currentPage() < $last - 1)
                <a href="{{ $movements->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Última">
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

{{-- ==================== ADJUST STOCK DRAWER ==================== --}}
<div id="adjustDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[500px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Ajuste Manual de Stock</h2>
            <p class="text-sm text-gray-400 mt-0.5">Corregir el stock de un producto</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="{{ route('admin.kardex.adjust') }}" id="adjustForm" class="p-6">
            @csrf

            {{-- Product Search --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-box text-violet-400 text-xs"></i> Producto
                </h3>
                <div class="relative mb-3">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="adjustProductSearch" placeholder="Buscar producto por nombre o SKU..."
                        autocomplete="off"
                        class="w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    <div id="adjustProductResults" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-60 overflow-y-auto hidden"></div>
                </div>
                <div id="selectedProduct" class="hidden">
                    <div class="flex items-center gap-3 p-3 bg-violet-50/50 rounded-lg">
                        <div id="selectedProductImage" class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-image text-gray-400 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate" id="selectedProductName"></p>
                            <p class="text-xs text-gray-400" id="selectedProductSku"></p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs text-gray-400">Stock actual</p>
                            <p class="text-lg font-bold text-gray-800" id="selectedProductStock">0</p>
                        </div>
                        <button type="button" onclick="clearProduct()"
                            class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition flex-shrink-0">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <input type="hidden" name="product_id" id="adjust_product_id">
                </div>
            </div>

            {{-- New Stock --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-hashtag text-blue-400 text-xs"></i> Nuevo Stock
                </h3>
                <input type="number" name="new_stock" id="adjust_new_stock" min="0" required placeholder="Ingresa la nueva cantidad"
                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition text-lg font-semibold">
                <div id="stockDiffMsg" class="mt-2 text-sm font-medium hidden"></div>
            </div>

            {{-- Notes --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-sticky-note text-gray-400 text-xs"></i> Motivo
                </h3>
                <textarea name="notes" rows="2" placeholder="Razón del ajuste..."
                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none"></textarea>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="submitAdjust()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-check mr-1.5 text-xs"></i> Aplicar Ajuste
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const drawerOverlay = document.getElementById('drawerOverlay');
    const adjustDrawer = document.getElementById('adjustDrawer');
    let activeDrawer = null;
    let searchTimeout = null;
    let currentStock = 0;

    // ==================== FILTERS ====================
    function applyFilters() {
        const params = new URLSearchParams();
        const fields = {
            product_id: document.getElementById('filter_product_id')?.value,
            type: document.getElementById('filter_type')?.value,
            date_from: document.getElementById('filter_date_from')?.value,
            date_to: document.getElementById('filter_date_to')?.value,
            per_page: document.getElementById('filter_per_page')?.value,
        };

        for (const [key, val] of Object.entries(fields)) {
            if (val !== '' && val !== null && val !== undefined) {
                params.set(key, val);
            }
        }

        window.location.href = '{{ route("admin.kardex.index") }}' + (params.toString() ? '?' + params.toString() : '');
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

    function openAdjustDrawer() {
        document.getElementById('adjustForm').reset();
        clearProduct();
        document.getElementById('stockDiffMsg').classList.add('hidden');
        showDrawer(adjustDrawer);
    }

    // ==================== PRODUCT SEARCH ====================
    const adjustProductSearch = document.getElementById('adjustProductSearch');
    const adjustProductResults = document.getElementById('adjustProductResults');

    adjustProductSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { adjustProductResults.classList.add('hidden'); return; }

        searchTimeout = setTimeout(() => {
            fetch('{{ route("admin.kardex.search-products") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(products => {
                    if (products.length === 0) {
                        adjustProductResults.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">Sin resultados</div>';
                    } else {
                        adjustProductResults.innerHTML = products.map(p => `
                            <div class="px-4 py-2.5 hover:bg-gray-50 cursor-pointer transition flex items-center gap-3"
                                 onclick='selectProduct(${JSON.stringify(p)})'>
                                ${p.image
                                    ? `<img src="${p.image}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">`
                                    : `<div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-300 text-xs"></i></div>`
                                }
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">${p.name}</p>
                                    <p class="text-xs text-gray-400">SKU: ${p.sku || '—'} · Stock: ${p.stock}</p>
                                </div>
                            </div>
                        `).join('');
                    }
                    adjustProductResults.classList.remove('hidden');
                });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!adjustProductSearch.contains(e.target) && !adjustProductResults.contains(e.target)) {
            adjustProductResults.classList.add('hidden');
        }
    });

    function selectProduct(product) {
        document.getElementById('adjust_product_id').value = product.id;
        document.getElementById('selectedProductName').textContent = product.name;
        document.getElementById('selectedProductSku').textContent = 'SKU: ' + (product.sku || '—');
        document.getElementById('selectedProductStock').textContent = product.stock;
        currentStock = product.stock;

        const imgContainer = document.getElementById('selectedProductImage');
        if (product.image) {
            imgContainer.innerHTML = `<img src="${product.image}" class="w-12 h-12 rounded-lg object-cover">`;
        } else {
            imgContainer.innerHTML = '<i class="fas fa-image text-gray-400 text-xs"></i>';
        }

        document.getElementById('selectedProduct').classList.remove('hidden');
        adjustProductSearch.value = '';
        adjustProductSearch.classList.add('hidden');
        adjustProductResults.classList.add('hidden');
    }

    function clearProduct() {
        document.getElementById('adjust_product_id').value = '';
        document.getElementById('selectedProduct').classList.add('hidden');
        adjustProductSearch.value = '';
        adjustProductSearch.classList.remove('hidden');
        currentStock = 0;
    }

    // Stock diff preview
    document.getElementById('adjust_new_stock').addEventListener('input', function() {
        const msg = document.getElementById('stockDiffMsg');
        const val = parseInt(this.value);
        if (isNaN(val) || !document.getElementById('adjust_product_id').value) {
            msg.classList.add('hidden');
            return;
        }
        const diff = val - currentStock;
        msg.classList.remove('hidden');
        if (diff > 0) {
            msg.className = 'mt-2 text-sm font-medium text-emerald-600';
            msg.innerHTML = `<i class="fas fa-arrow-up text-xs mr-1"></i> +${diff} unidades`;
        } else if (diff < 0) {
            msg.className = 'mt-2 text-sm font-medium text-red-500';
            msg.innerHTML = `<i class="fas fa-arrow-down text-xs mr-1"></i> ${diff} unidades`;
        } else {
            msg.className = 'mt-2 text-sm font-medium text-gray-400';
            msg.innerHTML = 'Sin cambios';
        }
    });

    function submitAdjust() {
        const productId = document.getElementById('adjust_product_id')?.value;
        if (!productId) {
            showToast('Selecciona un producto', 'warning');
            return;
        }
        const newStock = document.getElementById('adjust_new_stock').value;
        if (newStock === '' || newStock === null) {
            showToast('Ingresa la nueva cantidad', 'warning');
            return;
        }
        document.getElementById('adjustForm').submit();
    }

    // ==================== EXPORT ====================
    function exportKardex(event) {
        event.preventDefault();
        const params = new URLSearchParams();
        const productId = document.getElementById('filter_product_id')?.value;
        const type = document.getElementById('filter_type')?.value;
        const dateFrom = document.getElementById('filter_date_from')?.value;
        const dateTo = document.getElementById('filter_date_to')?.value;

        if (productId) params.set('product_id', productId);
        if (type) params.set('type', type);
        if (dateFrom) params.set('date_from', dateFrom);
        if (dateTo) params.set('date_to', dateTo);

        window.location.href = '{{ route("admin.kardex.export") }}' + (params.toString() ? '?' + params.toString() : '');
    }

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDrawer();
    });
</script>
@endsection
