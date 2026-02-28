@extends('admin.layouts.app')
@section('title', 'Clientes Interesados - ' . $product->name)

@section('content')
{{-- ==================== PRODUCT HEADER ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.wishlists.index') }}" class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition flex-shrink-0" aria-label="Volver a lista de deseos">
            <i class="fas fa-arrow-left text-gray-500 text-sm"></i>
        </a>
        @if($product->primaryImage)
        <img src="{{ $product->primaryImage->image_url }}" class="w-14 h-14 rounded-xl object-cover border border-gray-100 flex-shrink-0">
        @else
        <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-image text-gray-300"></i>
        </div>
        @endif
        <div class="flex-1 min-w-0">
            <h1 class="text-lg font-bold text-gray-900 truncate">{{ $product->name }}</h1>
            <p class="text-sm text-gray-400">SKU: {{ $product->sku ?? '—' }} · {{ $product->category?->name ?? '—' }}</p>
        </div>
        <div class="text-right flex-shrink-0 hidden sm:block">
            <p class="text-xs text-gray-400">Precio</p>
            @if($product->sale_price)
            <p class="text-xl font-bold text-emerald-600">S/ {{ number_format($product->sale_price, 2) }}</p>
            @else
            <p class="text-xl font-bold text-gray-900">S/ {{ number_format($product->price, 2) }}</p>
            @endif
        </div>
        <div class="text-center flex-shrink-0 hidden sm:block ml-2">
            <p class="text-xs text-gray-400">Stock</p>
            <p class="text-xl font-bold {{ $product->stock === 0 ? 'text-red-500' : ($product->stock <= 5 ? 'text-amber-500' : 'text-gray-900') }}">{{ $product->stock }}</p>
        </div>
        <div class="text-center flex-shrink-0 ml-2">
            <p class="text-xs text-gray-400">Interesados</p>
            <p class="text-xl font-bold text-rose-500">
                <i class="fas fa-heart text-sm mr-0.5"></i> {{ $totalInterested }}
            </p>
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
                    style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                    @foreach([15, 30, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="filter_search" value="{{ request('search') }}" placeholder="Buscar cliente..."
                        onkeydown="if(event.key==='Enter')applyFilters()"
                        class="w-full sm:w-52 pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
                @if(request('search'))
                <a href="{{ route('admin.wishlists.show', $product) }}" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Limpiar búsqueda" aria-label="Limpiar búsqueda">
                    <i class="fas fa-rotate-left text-xs"></i>
                </a>
                @endif
                <a href="#" onclick="exportClients(event)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition text-sm font-medium shadow-sm shadow-emerald-200 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-file-csv text-xs"></i>
                    <span class="hidden sm:inline">Exportar Clientes</span>
                    <span class="sm:hidden">CSV</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Agregado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($wishlists as $wishlist)
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            @php
                                $gradients = ['from-indigo-400 to-indigo-600', 'from-emerald-400 to-emerald-600', 'from-violet-400 to-violet-600', 'from-amber-400 to-amber-600', 'from-rose-400 to-rose-600'];
                                $gradient = $gradients[($wishlist->user_id ?? 0) % count($gradients)];
                            @endphp
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                @if($wishlist->user)
                                {{ strtoupper(substr($wishlist->user->first_name, 0, 1)) }}{{ strtoupper(substr($wishlist->user->last_name, 0, 1)) }}
                                @else
                                ??
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $wishlist->user ? $wishlist->user->first_name . ' ' . $wishlist->user->last_name : 'Usuario eliminado' }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-500">{{ $wishlist->user?->email ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-500">{{ $wishlist->user?->phone ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-500">{{ $wishlist->created_at ? \Carbon\Carbon::parse($wishlist->created_at)->format('d/m/Y H:i') : '—' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-users text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-600 font-semibold mb-1">Sin clientes interesados</h3>
                            <p class="text-sm text-gray-400 max-w-xs">Ningún cliente ha agregado este producto a su lista de deseos</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($wishlists as $wishlist)
        <div class="p-4">
            <div class="flex items-center gap-3 mb-1">
                @php
                    $gradient = $gradients[($wishlist->user_id ?? 0) % count($gradients)];
                @endphp
                <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                    @if($wishlist->user)
                    {{ strtoupper(substr($wishlist->user->first_name, 0, 1)) }}{{ strtoupper(substr($wishlist->user->last_name, 0, 1)) }}
                    @else
                    ??
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">
                        {{ $wishlist->user ? $wishlist->user->first_name . ' ' . $wishlist->user->last_name : 'Eliminado' }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $wishlist->user?->email ?? '—' }}</p>
                </div>
                <span class="text-xs text-gray-400 flex-shrink-0">{{ $wishlist->created_at ? \Carbon\Carbon::parse($wishlist->created_at)->format('d/m/Y') : '—' }}</span>
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-users text-xl text-gray-300"></i>
            </div>
            <p class="text-sm text-gray-400">Sin clientes interesados</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Footer --}}
    @if($wishlists->total() > 0)
    <div class="px-5 py-3.5 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Mostrando {{ $wishlists->firstItem() }} a {{ $wishlists->lastItem() }} de {{ number_format($wishlists->total()) }} clientes
            </p>
            @if($wishlists->hasPages())
            <nav class="flex items-center gap-1">
                @if($wishlists->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                @else
                <a href="{{ $wishlists->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Página anterior"><i class="fas fa-chevron-left"></i></a>
                @endif
                @php
                    $current = $wishlists->currentPage();
                    $last = $wishlists->lastPage();
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
                    <a href="{{ $wishlists->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                    @endif
                @endforeach
                @if($wishlists->hasMorePages())
                <a href="{{ $wishlists->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" aria-label="Página siguiente"><i class="fas fa-chevron-right"></i></a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
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
        const search = document.getElementById('filter_search')?.value;
        const perPage = document.getElementById('filter_per_page')?.value;

        if (search) params.set('search', search);
        if (perPage && perPage !== '15') params.set('per_page', perPage);

        window.location.href = '{{ route("admin.wishlists.show", $product) }}' + (params.toString() ? '?' + params.toString() : '');
    }

    function exportClients(event) {
        event.preventDefault();
        const params = new URLSearchParams();
        const search = document.getElementById('filter_search')?.value;

        if (search) params.set('search', search);

        window.location.href = '{{ route("admin.wishlists.export-product", $product) }}' + (params.toString() ? '?' + params.toString() : '');
    }
</script>
@endsection
