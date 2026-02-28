@extends('admin.layouts.app')
@section('title', 'Lista de Deseos')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total en Wishlists</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalItems) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Productos guardados</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-rose-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-heart text-rose-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Productos Deseados</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($uniqueProducts) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Productos únicos</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-violet-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-box text-violet-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Clientes con Wishlist</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($uniqueClients) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Clientes interesados</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-users text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Más Deseado</p>
            @if($topProduct)
            <h3 class="text-lg font-bold text-gray-900 truncate max-w-[160px]" title="{{ $topProduct->name }}">{{ $topProduct->name }}</h3>
            <p class="text-xs text-emerald-500 font-semibold mt-1">{{ $topProduct->total }} veces</p>
            @else
            <h3 class="text-lg font-bold text-gray-400">—</h3>
            @endif
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-trophy text-amber-500"></i>
        </div>
    </div>
</div>

{{-- ==================== SEARCH FILTERS CARD ==================== --}}
@php
    $selectStyle = "background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;";
    $selectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
    $hasFilters = request('search') || request('category_id') || request('order');
@endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="px-5 pt-5 pb-2">
        <h3 class="text-base font-semibold text-gray-800">Filtros de búsqueda</h3>
    </div>
    <div class="px-5 pb-5 mt-3">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Buscar producto</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="filter_search" value="{{ request('search') }}" placeholder="Nombre o SKU..."
                        onkeydown="if(event.key==='Enter')applyFilters()"
                        class="w-full pl-9 pr-3 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Categoría</label>
                <select id="filter_category_id" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Ordenar por</label>
                <select id="filter_order" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="most_wished" {{ request('order', 'most_wished') === 'most_wished' ? 'selected' : '' }}>Más deseados</option>
                    <option value="recent" {{ request('order') === 'recent' ? 'selected' : '' }}>Más recientes</option>
                </select>
            </div>
        </div>
        @if($hasFilters)
        <div class="mt-4">
            <a href="{{ route('admin.wishlists.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
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
                <a href="#" onclick="exportWishlists(event)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition text-sm font-medium shadow-sm shadow-emerald-200 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-file-csv text-xs"></i>
                    <span class="hidden sm:inline">Exportar</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-4 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-heart text-rose-400 text-[10px] mr-1"></i> Deseado
                    </th>
                    <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Último</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            @if($product->primaryImage)
                            <img src="{{ $product->primaryImage->image_url }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                            @else
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-image text-gray-300 text-xs"></i>
                            </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $product->sku ?? '—' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-500">{{ $product->category?->name ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if($product->sale_price)
                        <span class="text-sm font-semibold text-emerald-600">S/ {{ number_format($product->sale_price, 2) }}</span>
                        <p class="text-xs text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</p>
                        @else
                        <span class="text-sm font-semibold text-gray-700">S/ {{ number_format($product->price, 2) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-bold bg-rose-50 text-rose-600">
                            <i class="fas fa-heart text-[9px]"></i>
                            {{ $product->wishlists_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($product->stock === 0)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-500">Agotado</span>
                        @elseif($product->stock <= 5)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-600">{{ $product->stock }}</span>
                        @else
                        <span class="text-sm text-gray-600">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs text-gray-400">{{ $product->last_wishlisted_at ? \Carbon\Carbon::parse($product->last_wishlisted_at)->format('d/m/Y') : '—' }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('admin.wishlists.show', $product) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition"
                           title="Ver clientes interesados">
                            <i class="fas fa-users text-[10px]"></i> Ver clientes
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-heart text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-600 font-semibold mb-1">Sin productos deseados</h3>
                            <p class="text-sm text-gray-400 max-w-xs">Ningún cliente ha agregado productos a su lista de deseos</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($products as $product)
        <a href="{{ route('admin.wishlists.show', $product) }}" class="block p-4 hover:bg-gray-50 transition">
            <div class="flex items-center gap-3 mb-2">
                @if($product->primaryImage)
                <img src="{{ $product->primaryImage->image_url }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                @else
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-image text-gray-300 text-xs"></i>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->category?->name ?? '—' }}</p>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-sm font-bold bg-rose-50 text-rose-600 flex-shrink-0">
                    <i class="fas fa-heart text-[9px]"></i> {{ $product->wishlists_count }}
                </span>
            </div>
            <div class="flex items-center gap-3 text-xs text-gray-400 ml-[52px]">
                <span>S/ {{ number_format($product->sale_price ?: $product->price, 2) }}</span>
                <span class="text-gray-300">|</span>
                <span>Stock: {{ $product->stock }}</span>
                <span class="text-gray-300">|</span>
                <span>{{ $product->last_wishlisted_at ? \Carbon\Carbon::parse($product->last_wishlisted_at)->format('d/m/Y') : '—' }}</span>
            </div>
        </a>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-heart text-xl text-gray-300"></i>
            </div>
            <p class="text-sm text-gray-400">Sin productos deseados</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Footer --}}
    @if($products->total() > 0)
    <div class="px-5 py-3.5 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }} de {{ number_format($products->total()) }} productos
            </p>
            @if($products->hasPages())
            <nav class="flex items-center gap-1">
                @if($products->currentPage() > 2)
                <a href="{{ $products->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Primera" aria-label="Primera página">
                    <i class="fas fa-angles-left"></i>
                </a>
                @endif
                @if($products->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                @else
                <a href="{{ $products->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Página anterior"><i class="fas fa-chevron-left"></i></a>
                @endif
                @php
                    $current = $products->currentPage();
                    $last = $products->lastPage();
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
                    <a href="{{ $products->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                    @endif
                @endforeach
                @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Página siguiente"><i class="fas fa-chevron-right"></i></a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
                @endif
                @if($products->currentPage() < $last - 1)
                <a href="{{ $products->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Última" aria-label="Última página">
                    <i class="fas fa-angles-right"></i>
                </a>
                @endif
            </nav>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function applyFilters() {
        const params = new URLSearchParams();
        const fields = {
            search: document.getElementById('filter_search')?.value,
            category_id: document.getElementById('filter_category_id')?.value,
            order: document.getElementById('filter_order')?.value,
            per_page: document.getElementById('filter_per_page')?.value,
        };

        for (const [key, val] of Object.entries(fields)) {
            if (val !== '' && val !== null && val !== undefined) {
                params.set(key, val);
            }
        }

        window.location.href = '{{ route("admin.wishlists.index") }}' + (params.toString() ? '?' + params.toString() : '');
    }

    function exportWishlists(event) {
        event.preventDefault();
        const params = new URLSearchParams();
        const search = document.getElementById('filter_search')?.value;
        const categoryId = document.getElementById('filter_category_id')?.value;

        if (search) params.set('search', search);
        if (categoryId) params.set('category_id', categoryId);

        window.location.href = '{{ route("admin.wishlists.export") }}' + (params.toString() ? '?' + params.toString() : '');
    }
</script>
@endsection
