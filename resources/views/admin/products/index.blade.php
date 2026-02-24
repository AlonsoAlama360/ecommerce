@extends('admin.layouts.app')
@section('title', 'Productos')

@php
$stockBadge = function($stock) {
if ($stock === 0) return ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'label' => 'Agotado'];
if ($stock <= 5) return ['bg'=> 'bg-amber-50', 'text' => 'text-amber-600', 'label' => 'Bajo: ' . $stock];
    return ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'label' => $stock . ' uds'];
    };
    @endphp

    @section('content')
    {{-- ==================== STAT CARDS ==================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Productos</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalProducts) }}</h3>
                <p class="text-xs text-gray-400 mt-1">Registrados en la plataforma</p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-box text-indigo-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Productos Activos</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($activeProducts) }}</h3>
                <p class="text-xs mt-1">
                    @if($totalProducts > 0)
                    <span class="text-emerald-500 font-semibold">{{ round(($activeProducts / $totalProducts) * 100) }}%</span>
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
                <p class="text-sm text-gray-500 mb-1">Destacados</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($featuredProducts) }}</h3>
                <p class="text-xs mt-1">
                    @if($totalProducts > 0)
                    <span class="text-amber-500 font-semibold">{{ round(($featuredProducts / $totalProducts) * 100) }}%</span>
                    <span class="text-gray-400">del total</span>
                    @else
                    <span class="text-gray-400">Sin datos</span>
                    @endif
                </p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-star text-amber-500"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Sin Stock</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($outOfStock) }}</h3>
                <p class="text-xs mt-1">
                    @if($totalProducts > 0)
                    <span class="text-red-500 font-semibold">{{ round(($outOfStock / $totalProducts) * 100) }}%</span>
                    <span class="text-gray-400">del total</span>
                    @else
                    <span class="text-gray-400">Sin datos</span>
                    @endif
                </p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-box-open text-red-500"></i>
            </div>
        </div>
    </div>

    {{-- ==================== SEARCH FILTERS CARD ==================== --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
        <div class="px-5 pt-5 pb-2">
            <h3 class="text-base font-semibold text-gray-800">Filtros de búsqueda</h3>
        </div>
        @php
        $selectStyle = "background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;";
        $selectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
        $hasFilters = request('search') || request('category') || (request('status') !== null && request('status') !== '') || (request('featured') !== null && request('featured') !== '') || (request('stock') !== null && request('stock') !== '');
        @endphp
        <div class="px-5 pb-5 mt-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Categoría</label>
                    <select id="filter_category" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado</label>
                    <select id="filter_status" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                        <option value="">Seleccionar estado</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Stock</label>
                    <select id="filter_stock" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                        <option value="">Todo el stock</option>
                        <option value="in" {{ request('stock') === 'in' ? 'selected' : '' }}>En stock</option>
                        <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Stock bajo (1-5)</option>
                        <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Agotado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Destacado</label>
                    <select id="filter_featured" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                        <option value="">Todos</option>
                        <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Destacados</option>
                        <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>No destacados</option>
                    </select>
                </div>
            </div>
            @if($hasFilters)
            <div class="mt-3">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
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
                    {{-- Per page --}}
                    <select id="filter_per_page" onchange="applyFilters()"
                        class="pl-3 pr-8 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer bg-white appearance-none"
                        style="{{ $selectStyle }}">
                        @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-3 flex-1 sm:flex-initial">
                    {{-- Search --}}
                    <div class="relative flex-1 sm:flex-initial">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" id="filter_search" value="{{ request('search') }}"
                            placeholder="Buscar producto"
                            onkeydown="if(event.key==='Enter'){event.preventDefault();applyFilters()}"
                            class="w-full sm:w-56 pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                    {{-- Export --}}
                    <button type="button" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-up-from-bracket text-xs"></i> Exportar
                    </button>
                    {{-- Add New --}}
                    <button onclick="openCreateDrawer()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm shadow-indigo-200 cursor-pointer whitespace-nowrap">
                        <i class="fas fa-plus text-xs"></i>
                        <span class="hidden sm:inline">Nuevo Producto</span>
                        <span class="sm:hidden">Nuevo</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="w-12 px-5 py-3.5">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Producto</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products as $product)
                    @php $sBadge = $stockBadge($product->stock); @endphp
                    <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                        <td class="px-5 py-3">
                            <input type="checkbox" class="row-check w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($product->primaryImage)
                                <img src="{{ $product->primaryImage->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-image text-gray-400 text-xs"></i>
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $product->name }}</p>
                                        @if($product->is_featured)
                                        <i class="fas fa-star text-amber-400 text-[10px]" title="Destacado"></i>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 truncate">SKU: {{ $product->sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-600">
                                {{ $product->category->name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                @if($product->sale_price)
                                <p class="text-sm font-semibold text-gray-800">S/ {{ number_format($product->sale_price, 2) }}</p>
                                <p class="text-xs text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</p>
                                @else
                                <p class="text-sm font-semibold text-gray-800">S/ {{ number_format($product->price, 2) }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $sBadge['bg'] }} {{ $sBadge['text'] }}">
                                {{ $sBadge['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($product->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600">Active</span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-500">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="openSpecsDrawer({{ $product->id }}, '{{ $product->name }}')"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-md transition" title="Especificaciones">
                                    <i class="fas fa-list-check text-sm"></i>
                                </button>
                                <button onclick="openImagesDrawer({{ $product->id }}, '{{ $product->name }}')"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-violet-600 hover:bg-violet-50 rounded-md transition" title="Imágenes">
                                    <i class="fas fa-images text-sm"></i>
                                </button>
                                <button data-product="{{ json_encode($product->only('id','name','slug','category_id','short_description','description','price','sale_price','sku','stock','material','is_featured','is_active','created_at','updated_at') + ['image_url' => $product->primaryImage?->image_url]) }}"
                                    onclick="openEditDrawer(JSON.parse(this.dataset.product))"
                                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition" title="Editar">
                                    <i class="fas fa-pen-to-square text-sm"></i>
                                </button>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $product->name }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition" title="Eliminar">
                                        <i class="fas fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                    <i class="fas fa-box text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-gray-600 font-semibold mb-1">Sin resultados</h3>
                                <p class="text-sm text-gray-400 max-w-xs">No se encontraron productos que coincidan con los filtros</p>
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
            @php $sBadge = $stockBadge($product->stock); @endphp
            <div class="p-4">
                <div class="flex items-center gap-3">
                    @if($product->primaryImage)
                    <img src="{{ $product->primaryImage->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover border border-gray-100 flex-shrink-0">
                    @else
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-image text-gray-400 text-sm"></i>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $product->name }}</p>
                            @if($product->is_featured)
                            <i class="fas fa-star text-amber-400 text-[10px]"></i>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 truncate">SKU: {{ $product->sku }}</p>
                    </div>
                    <div class="flex items-center gap-0.5">
                        <button onclick="openSpecsDrawer({{ $product->id }}, '{{ $product->name }}')"
                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-teal-600 rounded-md transition">
                            <i class="fas fa-list-check text-sm"></i>
                        </button>
                        <button onclick="openImagesDrawer({{ $product->id }}, '{{ $product->name }}')"
                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-violet-600 rounded-md transition">
                            <i class="fas fa-images text-sm"></i>
                        </button>
                        <button data-product="{{ json_encode($product->only('id','name','slug','category_id','short_description','description','price','sale_price','sku','stock','material','is_featured','is_active','created_at','updated_at') + ['image_url' => $product->primaryImage?->image_url]) }}"
                            onclick="openEditDrawer(JSON.parse(this.dataset.product))"
                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 rounded-md transition">
                            <i class="fas fa-pen-to-square text-sm"></i>
                        </button>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $product->name }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-md transition">
                                <i class="fas fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="mt-2 ml-[60px] flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-gray-500 font-medium">S/ {{ number_format($product->current_price, 2) }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-xs {{ $sBadge['text'] }} font-semibold">{{ $sBadge['label'] }}</span>
                    <span class="text-gray-300">|</span>
                    @if($product->is_active)
                    <span class="text-xs font-semibold text-emerald-600">Active</span>
                    @else
                    <span class="text-xs font-semibold text-red-500">Inactive</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-10 text-center">
                <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-box text-xl text-gray-300"></i>
                </div>
                <p class="text-sm text-gray-400">No se encontraron productos</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination Footer --}}
        @if($products->total() > 0)
        <div class="px-5 py-3.5 border-t border-gray-100">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm text-gray-500">
                    Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }} de {{ number_format($products->total()) }} registros
                </p>
                @if($products->hasPages())
                <nav class="flex items-center gap-1">
                    @if($products->currentPage() > 2)
                    <a href="{{ $products->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Primera">
                        <i class="fas fa-angles-left"></i>
                    </a>
                    @endif
                    @if($products->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                    @else
                    <a href="{{ $products->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    @php
                    $current = $products->currentPage();
                    $last = $products->lastPage();
                    $pages = [];
                    if ($last <= 5) { $pages=range(1, $last); }
                        else {
                        $pages[]=1;
                        if ($current> 3) $pages[] = '...';
                        for ($i = max(2, $current - 1); $i <= min($last - 1, $current + 1); $i++) { $pages[]=$i; }
                            if ($current < $last - 2) $pages[]='...' ;
                            $pages[]=$last;
                            }
                            @endphp
                            @foreach($pages as $page)
                            @if($page==='...' )
                            <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">...</span>
                            @elseif($page == $current)
                            <span class="w-8 h-8 flex items-center justify-center rounded-md bg-indigo-500 text-white text-xs font-semibold shadow-sm">{{ $page }}</span>
                            @else
                            <a href="{{ $products->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                            @endif
                            @endforeach
                            @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-right"></i></a>
                            @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
                            @endif
                            @if($products->currentPage() < $last - 1)
                                <a href="{{ $products->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Ultima">
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

    {{-- ==================== CREATE DRAWER ==================== --}}
    <div id="createDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[520px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Nuevo Producto</h2>
                <p class="text-sm text-gray-400 mt-0.5">Completa los datos del nuevo producto</p>
            </div>
            <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <form method="POST" action="{{ route('admin.products.store') }}" id="createProductForm" class="p-6" enctype="multipart/form-data">
                @csrf
                {{-- Image Preview --}}
                <div class="flex justify-center mb-6">
                    <div id="createImgPreviewWrap" class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200 overflow-hidden">
                        <i class="fas fa-box text-2xl text-white" id="createImgPlaceholder"></i>
                        <img id="createImgPreview" src="" alt="" class="w-full h-full object-cover hidden">
                    </div>
                </div>

                <div class="space-y-4">
                    {{-- Name --}}
                    <div>
                        <label for="create_name" class="block text-xs font-medium text-gray-500 mb-1.5">Nombre <span class="text-red-400">*</span></label>
                        <input type="text" name="name" id="create_name" value="{{ old('name') }}" required
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('name') border-red-400 @enderror">
                        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- SKU --}}
                        <div>
                            <label for="create_sku" class="block text-xs font-medium text-gray-500 mb-1.5">SKU <span class="text-red-400">*</span></label>
                            <input type="text" name="sku" id="create_sku" value="{{ old('sku') }}" required
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('sku') border-red-400 @enderror">
                            @error('sku')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        {{-- Slug --}}
                        <div>
                            <label for="create_slug" class="block text-xs font-medium text-gray-500 mb-1.5">Slug</label>
                            <input type="text" name="slug" id="create_slug" value="{{ old('slug') }}" placeholder="Auto-generado"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('slug') border-red-400 @enderror">
                            @error('slug')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="create_category_id" class="block text-xs font-medium text-gray-500 mb-1.5">Categoría <span class="text-red-400">*</span></label>
                        <select name="category_id" id="create_category_id" required
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none @error('category_id') border-red-400 @enderror"
                            style="{{ $selectStyle }}">
                            <option value="">Seleccionar categoría</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Short Description --}}
                    <div>
                        <label for="create_short_description" class="block text-xs font-medium text-gray-500 mb-1.5">Descripción corta</label>
                        <input type="text" name="short_description" id="create_short_description" value="{{ old('short_description') }}" placeholder="Breve descripción del producto"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('short_description') border-red-400 @enderror">
                        @error('short_description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="create_description" class="block text-xs font-medium text-gray-500 mb-1.5">Descripción</label>
                        <textarea name="description" id="create_description" rows="3" placeholder="Descripción detallada..."
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Price --}}
                        <div>
                            <label for="create_price" class="block text-xs font-medium text-gray-500 mb-1.5">Precio <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">S/</span>
                                <input type="number" name="price" id="create_price" value="{{ old('price') }}" step="0.01" min="0" required
                                    class="w-full pl-9 pr-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('price') border-red-400 @enderror">
                            </div>
                            @error('price')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        {{-- Sale Price --}}
                        <div>
                            <label for="create_sale_price" class="block text-xs font-medium text-gray-500 mb-1.5">Precio oferta</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">S/</span>
                                <input type="number" name="sale_price" id="create_sale_price" value="{{ old('sale_price') }}" step="0.01" min="0"
                                    class="w-full pl-9 pr-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('sale_price') border-red-400 @enderror">
                            </div>
                            @error('sale_price')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Stock --}}
                        <div>
                            <label for="create_stock" class="block text-xs font-medium text-gray-500 mb-1.5">Stock <span class="text-red-400">*</span></label>
                            <input type="number" name="stock" id="create_stock" value="{{ old('stock', 0) }}" min="0" required
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('stock') border-red-400 @enderror">
                            @error('stock')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        {{-- Material --}}
                        <div>
                            <label for="create_material" class="block text-xs font-medium text-gray-500 mb-1.5">Material</label>
                            <input type="text" name="material" id="create_material" value="{{ old('material') }}" placeholder="Ej: Plata 925"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('material') border-red-400 @enderror">
                            @error('material')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Image Principal --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Imagen principal</label>
                        <div class="flex rounded-lg bg-gray-200/70 p-0.5 mb-3">
                            <button type="button" onclick="switchCreateImgTab('url')" id="createImgTabUrl"
                                class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition bg-white text-gray-800 shadow-sm">
                                <i class="fas fa-link mr-1.5"></i>URL
                            </button>
                            <button type="button" onclick="switchCreateImgTab('file')" id="createImgTabFile"
                                class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition text-gray-500 hover:text-gray-700">
                                <i class="fas fa-upload mr-1.5"></i>Subir archivo
                            </button>
                        </div>
                        <div id="createImgInputUrl">
                            <input type="url" name="image_url" id="create_image_url" value="{{ old('image_url') }}" placeholder="https://ejemplo.com/imagen.jpg"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('image_url') border-red-400 @enderror">
                            @error('image_url')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div id="createImgInputFile" class="hidden">
                            <div id="createImgDropZone"
                                class="relative border-2 border-dashed border-gray-300 rounded-xl p-5 text-center bg-white hover:border-indigo-400 hover:bg-indigo-50/30 transition-colors cursor-pointer"
                                onclick="document.getElementById('create_image_file').click()">
                                <input type="file" name="image_file" id="create_image_file" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden"
                                    onchange="previewCreateFile(this)">
                                <div id="createFileContent">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-cloud-arrow-up text-indigo-500"></i>
                                    </div>
                                    <p class="text-xs text-gray-600 font-medium">Arrastra o haz clic para seleccionar</p>
                                    <p class="text-[10px] text-gray-300 mt-1">JPG, PNG, WebP, GIF — Máx. 2MB</p>
                                </div>
                                <div id="createFilePreviewWrap" class="hidden">
                                    <img id="createFilePreviewImg" src="" alt="" class="h-20 mx-auto rounded-lg object-contain">
                                    <p id="createFileName" class="text-xs text-gray-500 mt-2 truncate"></p>
                                    <button type="button" onclick="event.stopPropagation(); clearCreateFile()"
                                        class="mt-1 inline-flex items-center gap-1 px-3 py-1 text-xs text-red-500 hover:bg-red-50 rounded-lg transition">
                                        <i class="fas fa-times text-[10px]"></i> Quitar
                                    </button>
                                </div>
                            </div>
                            @error('image_file')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Toggles --}}
                    <div class="pt-3 border-t border-gray-100 space-y-4">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Producto activo</span>
                                <p class="text-xs text-gray-400">Visible en la tienda</p>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:bg-indigo-500 transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Destacado</span>
                                <p class="text-xs text-gray-400">Aparece en secciones destacadas</p>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-amber-500/20 rounded-full peer peer-checked:bg-amber-500 transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
            <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
                Cancelar
            </button>
            <button onclick="document.getElementById('createProductForm').submit()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
                <i class="fas fa-plus mr-1.5 text-xs"></i> Crear Producto
            </button>
        </div>
    </div>

    {{-- ==================== EDIT DRAWER ==================== --}}
    <div id="editDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[520px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Editar Producto</h2>
                <p class="text-sm text-gray-400 mt-0.5" id="editDrawerSubtitle">Modifica los datos del producto</p>
            </div>
            <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <form method="POST" action="" id="editProductForm" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                {{-- Image Preview --}}
                <div class="flex justify-center mb-6">
                    <div id="editImgPreviewWrap" class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200 overflow-hidden">
                        <i class="fas fa-box text-2xl text-white" id="editImgPlaceholder"></i>
                        <img id="editImgPreview" src="" alt="" class="w-full h-full object-cover hidden">
                    </div>
                </div>

                <div class="space-y-4">
                    {{-- Name --}}
                    <div>
                        <label for="edit_name" class="block text-xs font-medium text-gray-500 mb-1.5">Nombre <span class="text-red-400">*</span></label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- SKU --}}
                        <div>
                            <label for="edit_sku" class="block text-xs font-medium text-gray-500 mb-1.5">SKU <span class="text-red-400">*</span></label>
                            <input type="text" name="sku" id="edit_sku" required
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                        {{-- Slug --}}
                        <div>
                            <label for="edit_slug" class="block text-xs font-medium text-gray-500 mb-1.5">Slug</label>
                            <input type="text" name="slug" id="edit_slug" placeholder="Auto-generado"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="edit_category_id" class="block text-xs font-medium text-gray-500 mb-1.5">Categoría <span class="text-red-400">*</span></label>
                        <select name="category_id" id="edit_category_id" required
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none"
                            style="{{ $selectStyle }}">
                            <option value="">Seleccionar categoría</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Short Description --}}
                    <div>
                        <label for="edit_short_description" class="block text-xs font-medium text-gray-500 mb-1.5">Descripción corta</label>
                        <input type="text" name="short_description" id="edit_short_description" placeholder="Breve descripción del producto"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="edit_description" class="block text-xs font-medium text-gray-500 mb-1.5">Descripción</label>
                        <textarea name="description" id="edit_description" rows="3" placeholder="Descripción detallada..."
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Price --}}
                        <div>
                            <label for="edit_price" class="block text-xs font-medium text-gray-500 mb-1.5">Precio <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">S/</span>
                                <input type="number" name="price" id="edit_price" step="0.01" min="0" required
                                    class="w-full pl-9 pr-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                            </div>
                        </div>
                        {{-- Sale Price --}}
                        <div>
                            <label for="edit_sale_price" class="block text-xs font-medium text-gray-500 mb-1.5">Precio oferta</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">S/</span>
                                <input type="number" name="sale_price" id="edit_sale_price" step="0.01" min="0"
                                    class="w-full pl-9 pr-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Stock --}}
                        <div>
                            <label for="edit_stock" class="block text-xs font-medium text-gray-500 mb-1.5">Stock <span class="text-red-400">*</span></label>
                            <input type="number" name="stock" id="edit_stock" min="0" required
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                        {{-- Material --}}
                        <div>
                            <label for="edit_material" class="block text-xs font-medium text-gray-500 mb-1.5">Material</label>
                            <input type="text" name="material" id="edit_material" placeholder="Ej: Plata 925"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                    </div>

                    {{-- Image Principal --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Imagen principal</label>
                        <div class="flex rounded-lg bg-gray-200/70 p-0.5 mb-3">
                            <button type="button" onclick="switchEditImgTab('url')" id="editImgTabUrl"
                                class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition bg-white text-gray-800 shadow-sm">
                                <i class="fas fa-link mr-1.5"></i>URL
                            </button>
                            <button type="button" onclick="switchEditImgTab('file')" id="editImgTabFile"
                                class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition text-gray-500 hover:text-gray-700">
                                <i class="fas fa-upload mr-1.5"></i>Subir archivo
                            </button>
                        </div>
                        <div id="editImgInputUrl">
                            <input type="url" name="image_url" id="edit_image_url" placeholder="https://ejemplo.com/imagen.jpg"
                                class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                        <div id="editImgInputFile" class="hidden">
                            <div id="editImgDropZone"
                                class="relative border-2 border-dashed border-gray-300 rounded-xl p-5 text-center bg-white hover:border-indigo-400 hover:bg-indigo-50/30 transition-colors cursor-pointer"
                                onclick="document.getElementById('edit_image_file').click()">
                                <input type="file" name="image_file" id="edit_image_file" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden"
                                    onchange="previewEditFile(this)">
                                <div id="editFileContent">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-cloud-arrow-up text-indigo-500"></i>
                                    </div>
                                    <p class="text-xs text-gray-600 font-medium">Arrastra o haz clic para seleccionar</p>
                                    <p class="text-[10px] text-gray-300 mt-1">JPG, PNG, WebP, GIF — Máx. 2MB</p>
                                </div>
                                <div id="editFilePreviewWrap" class="hidden">
                                    <img id="editFilePreviewImg" src="" alt="" class="h-20 mx-auto rounded-lg object-contain">
                                    <p id="editFileName" class="text-xs text-gray-500 mt-2 truncate"></p>
                                    <button type="button" onclick="event.stopPropagation(); clearEditFile()"
                                        class="mt-1 inline-flex items-center gap-1 px-3 py-1 text-xs text-red-500 hover:bg-red-50 rounded-lg transition">
                                        <i class="fas fa-times text-[10px]"></i> Quitar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Toggles --}}
                    <div class="pt-3 border-t border-gray-100 space-y-4">
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Producto activo</span>
                                <p class="text-xs text-gray-400">Visible en la tienda</p>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" id="edit_is_active">
                                <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:bg-indigo-500 transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <div>
                                <span class="text-sm font-medium text-gray-700">Destacado</span>
                                <p class="text-xs text-gray-400">Aparece en secciones destacadas</p>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" class="sr-only peer" id="edit_is_featured">
                                <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-amber-500/20 rounded-full peer peer-checked:bg-amber-500 transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-gray-100">
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
            <button onclick="document.getElementById('editProductForm').submit()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
                <i class="fas fa-check mr-1.5 text-xs"></i> Guardar Cambios
            </button>
        </div>
    </div>

    {{-- ==================== IMAGES DRAWER ==================== --}}
    <div id="imagesDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[560px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Imágenes del Producto</h2>
                <p class="text-sm text-gray-400 mt-0.5" id="imagesDrawerSubtitle"></p>
            </div>
            <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div class="p-6">
                {{-- Add Image Form --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Agregar imagen</h4>

                    {{-- Tabs URL / Archivo --}}
                    <div class="flex rounded-lg bg-gray-200/70 p-0.5 mb-4">
                        <button type="button" onclick="switchImageTab('url')" id="imgTabUrl"
                            class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition bg-white text-gray-800 shadow-sm">
                            <i class="fas fa-link mr-1.5"></i>URL
                        </button>
                        <button type="button" onclick="switchImageTab('file')" id="imgTabFile"
                            class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition text-gray-500 hover:text-gray-700">
                            <i class="fas fa-upload mr-1.5"></i>Subir archivo
                        </button>
                    </div>

                    <div class="space-y-3">
                        {{-- URL input --}}
                        <div id="imgInputUrl">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">URL de imagen <span class="text-red-400">*</span></label>
                            <input type="url" id="img_new_url" placeholder="https://ejemplo.com/imagen.jpg"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>

                        {{-- File input --}}
                        <div id="imgInputFile" class="hidden">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Archivo de imagen <span class="text-red-400">*</span></label>
                            <div id="imgDropZone"
                                class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center bg-white hover:border-indigo-400 hover:bg-indigo-50/30 transition-colors cursor-pointer"
                                onclick="document.getElementById('img_new_file').click()">
                                <input type="file" id="img_new_file" accept="image/jpeg,image/png,image/webp,image/gif" class="hidden"
                                    onchange="previewFileImage(this)">
                                <div id="imgDropContent">
                                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-cloud-arrow-up text-indigo-500 text-lg"></i>
                                    </div>
                                    <p class="text-sm text-gray-600 font-medium">Arrastra tu imagen aquí</p>
                                    <p class="text-xs text-gray-400 mt-1">o haz clic para seleccionar</p>
                                    <p class="text-[10px] text-gray-300 mt-2">JPG, PNG, WebP, GIF — Máx. 2MB</p>
                                </div>
                                <div id="imgFilePreviewWrap" class="hidden">
                                    <img id="imgFilePreviewImg" src="" alt="" class="h-24 mx-auto rounded-lg object-contain">
                                    <p id="imgFileName" class="text-xs text-gray-500 mt-2 truncate"></p>
                                    <button type="button" onclick="event.stopPropagation(); clearFileImage()"
                                        class="mt-2 inline-flex items-center gap-1 px-3 py-1 text-xs text-red-500 hover:bg-red-50 rounded-lg transition">
                                        <i class="fas fa-times text-[10px]"></i> Quitar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Texto alternativo</label>
                            <input type="text" id="img_new_alt" placeholder="Descripción de la imagen"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="img_new_primary" class="w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500">
                                <span class="text-xs text-gray-600">Marcar como principal</span>
                            </label>
                            <button onclick="addImage()" id="imgAddBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm shadow-indigo-200">
                                <i class="fas fa-plus text-xs"></i> Agregar
                            </button>
                        </div>
                        {{-- URL Preview --}}
                        <div id="imgNewPreview" class="hidden">
                            <img id="imgNewPreviewImg" src="" alt="" class="h-20 w-full object-contain rounded-lg border border-gray-200 bg-white">
                        </div>
                    </div>
                </div>

                {{-- Images List --}}
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-700">Imágenes (<span id="imagesCount">0</span>)</h4>
                    <div id="imagesLoading" class="hidden">
                        <i class="fas fa-spinner fa-spin text-indigo-500 text-sm"></i>
                    </div>
                </div>
                <div id="imagesList" class="space-y-3">
                    {{-- Populated via JS --}}
                </div>
                <div id="imagesEmpty" class="hidden py-10 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-images text-xl text-gray-300"></i>
                    </div>
                    <p class="text-sm text-gray-400">Este producto no tiene imágenes</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== SPECIFICATIONS DRAWER ==================== --}}
    <div id="specsDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[520px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Especificaciones</h2>
                <p class="text-sm text-gray-400 mt-0.5" id="specsDrawerSubtitle"></p>
            </div>
            <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div class="p-6">
                {{-- Add Spec Form --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Agregar especificación</h4>
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Clave <span class="text-red-400">*</span></label>
                            <input type="text" id="spec_new_key" placeholder="Ej: Material"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Valor <span class="text-red-400">*</span></label>
                            <input type="text" id="spec_new_value" placeholder="Ej: Plata 925"
                                onkeydown="if(event.key==='Enter'){event.preventDefault();addSpec()}"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                        </div>
                        <div class="flex items-end">
                            <button onclick="addSpec()" class="px-4 py-2.5 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm shadow-indigo-200">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Specs List --}}
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-700">Especificaciones (<span id="specsCount">0</span>)</h4>
                    <div id="specsLoading" class="hidden">
                        <i class="fas fa-spinner fa-spin text-indigo-500 text-sm"></i>
                    </div>
                </div>
                <div id="specsList" class="space-y-2">
                    {{-- Populated via JS --}}
                </div>
                <div id="specsEmpty" class="hidden py-10 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-list-check text-xl text-gray-300"></i>
                    </div>
                    <p class="text-sm text-gray-400">Este producto no tiene especificaciones</p>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script>
        const drawerOverlay = document.getElementById('drawerOverlay');
        const createDrawer = document.getElementById('createDrawer');
        const editDrawer = document.getElementById('editDrawer');
        const imagesDrawer = document.getElementById('imagesDrawer');
        const specsDrawer = document.getElementById('specsDrawer');
        let activeDrawer = null;

        // ==================== UNIFIED FILTERS ====================
        function applyFilters() {
            const params = new URLSearchParams();
            const fields = {
                search: document.getElementById('filter_search')?.value?.trim(),
                category: document.getElementById('filter_category')?.value,
                status: document.getElementById('filter_status')?.value,
                stock: document.getElementById('filter_stock')?.value,
                featured: document.getElementById('filter_featured')?.value,
                per_page: document.getElementById('filter_per_page')?.value,
            };

            for (const [key, val] of Object.entries(fields)) {
                if (val !== '' && val !== null && val !== undefined) {
                    params.set(key, val);
                }
            }

            window.location.href = '{{ route("admin.products.index") }}' + (params.toString() ? '?' + params.toString() : '');
        }

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
            switchCreateImgTab('url');
            clearCreateFile();
            showDrawer(createDrawer);
            setTimeout(() => document.getElementById('create_name').focus(), 300);
        }

        function openEditDrawer(product) {
            document.getElementById('edit_name').value = product.name || '';
            document.getElementById('edit_sku').value = product.sku || '';
            document.getElementById('edit_slug').value = product.slug || '';
            document.getElementById('edit_category_id').value = product.category_id || '';
            document.getElementById('edit_short_description').value = product.short_description || '';
            document.getElementById('edit_description').value = product.description || '';
            document.getElementById('edit_price').value = product.price || '';
            document.getElementById('edit_sale_price').value = product.sale_price || '';
            document.getElementById('edit_stock').value = product.stock ?? 0;
            document.getElementById('edit_material').value = product.material || '';
            document.getElementById('edit_image_url').value = product.image_url || '';

            document.getElementById('editProductForm').action = '/admin/products/' + product.id;
            document.getElementById('editDrawerSubtitle').textContent = product.name;
            switchEditImgTab('url');
            clearEditFile();

            document.getElementById('edit_is_active').checked = product.is_active;
            document.getElementById('edit_is_featured').checked = product.is_featured;

            // Image preview
            const img = document.getElementById('editImgPreview');
            const placeholder = document.getElementById('editImgPlaceholder');
            if (product.image_url) {
                img.src = product.image_url;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            // Dates
            const fmtDate = (d) => {
                if (!d) return '—';
                const dt = new Date(d);
                return dt.toLocaleDateString('es-PE', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };
            document.getElementById('editCreatedAt').textContent = fmtDate(product.created_at);
            document.getElementById('editUpdatedAt').textContent = fmtDate(product.updated_at);

            showDrawer(editDrawer);
            setTimeout(() => document.getElementById('edit_name').focus(), 300);
        }

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDrawer();
        });

        // ==================== CREATE DRAWER: Image tabs & preview ====================
        function switchCreateImgTab(tab) {
            const urlTab = document.getElementById('createImgTabUrl');
            const fileTab = document.getElementById('createImgTabFile');
            const urlInput = document.getElementById('createImgInputUrl');
            const fileInput = document.getElementById('createImgInputFile');
            if (tab === 'url') {
                urlTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition bg-white text-gray-800 shadow-sm';
                fileTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition text-gray-500 hover:text-gray-700';
                urlInput.classList.remove('hidden');
                fileInput.classList.add('hidden');
            } else {
                fileTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition bg-white text-gray-800 shadow-sm';
                urlTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition text-gray-500 hover:text-gray-700';
                fileInput.classList.remove('hidden');
                urlInput.classList.add('hidden');
            }
        }

        function previewCreateFile(input) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('createFilePreviewImg').src = e.target.result;
                document.getElementById('createFileName').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                document.getElementById('createFileContent').classList.add('hidden');
                document.getElementById('createFilePreviewWrap').classList.remove('hidden');
                // Update header preview
                const img = document.getElementById('createImgPreview');
                const placeholder = document.getElementById('createImgPlaceholder');
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        function clearCreateFile() {
            document.getElementById('create_image_file').value = '';
            document.getElementById('createFileContent').classList.remove('hidden');
            document.getElementById('createFilePreviewWrap').classList.add('hidden');
        }

        // Image URL preview for create
        const createImgInput = document.getElementById('create_image_url');
        createImgInput.addEventListener('change', function() {
            const img = document.getElementById('createImgPreview');
            const placeholder = document.getElementById('createImgPlaceholder');
            if (this.value) {
                img.src = this.value;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                img.onerror = () => { img.classList.add('hidden'); placeholder.classList.remove('hidden'); };
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        });

        // Drag & drop for create
        (function() {
            const dz = document.getElementById('createImgDropZone');
            if (!dz) return;
            ['dragenter','dragover'].forEach(e => dz.addEventListener(e, ev => { ev.preventDefault(); dz.classList.add('border-indigo-400','bg-indigo-50/40'); }));
            ['dragleave','drop'].forEach(e => dz.addEventListener(e, ev => { ev.preventDefault(); dz.classList.remove('border-indigo-400','bg-indigo-50/40'); }));
            dz.addEventListener('drop', e => {
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    const input = document.getElementById('create_image_file');
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                    previewCreateFile(input);
                }
            });
        })();

        // ==================== EDIT DRAWER: Image tabs & preview ====================
        function switchEditImgTab(tab) {
            const urlTab = document.getElementById('editImgTabUrl');
            const fileTab = document.getElementById('editImgTabFile');
            const urlInput = document.getElementById('editImgInputUrl');
            const fileInput = document.getElementById('editImgInputFile');
            if (tab === 'url') {
                urlTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition bg-white text-gray-800 shadow-sm';
                fileTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition text-gray-500 hover:text-gray-700';
                urlInput.classList.remove('hidden');
                fileInput.classList.add('hidden');
            } else {
                fileTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition bg-white text-gray-800 shadow-sm';
                urlTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition text-gray-500 hover:text-gray-700';
                fileInput.classList.remove('hidden');
                urlInput.classList.add('hidden');
            }
        }

        function previewEditFile(input) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('editFilePreviewImg').src = e.target.result;
                document.getElementById('editFileName').textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                document.getElementById('editFileContent').classList.add('hidden');
                document.getElementById('editFilePreviewWrap').classList.remove('hidden');
                // Update header preview
                const img = document.getElementById('editImgPreview');
                const placeholder = document.getElementById('editImgPlaceholder');
                img.src = e.target.result;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        function clearEditFile() {
            document.getElementById('edit_image_file').value = '';
            document.getElementById('editFileContent').classList.remove('hidden');
            document.getElementById('editFilePreviewWrap').classList.add('hidden');
        }

        // Image URL preview for edit
        const editImgInput = document.getElementById('edit_image_url');
        editImgInput.addEventListener('change', function() {
            const img = document.getElementById('editImgPreview');
            const placeholder = document.getElementById('editImgPlaceholder');
            if (this.value) {
                img.src = this.value;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                img.onerror = () => { img.classList.add('hidden'); placeholder.classList.remove('hidden'); };
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        });

        // Drag & drop for edit
        (function() {
            const dz = document.getElementById('editImgDropZone');
            if (!dz) return;
            ['dragenter','dragover'].forEach(e => dz.addEventListener(e, ev => { ev.preventDefault(); dz.classList.add('border-indigo-400','bg-indigo-50/40'); }));
            ['dragleave','drop'].forEach(e => dz.addEventListener(e, ev => { ev.preventDefault(); dz.classList.remove('border-indigo-400','bg-indigo-50/40'); }));
            dz.addEventListener('drop', e => {
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    const input = document.getElementById('edit_image_file');
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                    previewEditFile(input);
                }
            });
        })();

        // Select all checkboxes
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
            });
            document.querySelectorAll('.row-check').forEach(cb => {
                cb.addEventListener('change', () => {
                    const all = document.querySelectorAll('.row-check');
                    const checked = document.querySelectorAll('.row-check:checked');
                    selectAll.checked = all.length === checked.length;
                    selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
                });
            });
        }

        // ==================== IMAGES MANAGEMENT ====================
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentProductId = null;

        let currentImageTab = 'url';

        function switchImageTab(tab) {
            currentImageTab = tab;
            const urlTab = document.getElementById('imgTabUrl');
            const fileTab = document.getElementById('imgTabFile');
            const urlInput = document.getElementById('imgInputUrl');
            const fileInput = document.getElementById('imgInputFile');

            if (tab === 'url') {
                urlTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition bg-white text-gray-800 shadow-sm';
                fileTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition text-gray-500 hover:text-gray-700';
                urlInput.classList.remove('hidden');
                fileInput.classList.add('hidden');
            } else {
                fileTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition bg-white text-gray-800 shadow-sm';
                urlTab.className = 'flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition text-gray-500 hover:text-gray-700';
                fileInput.classList.remove('hidden');
                urlInput.classList.add('hidden');
            }
        }

        function previewFileImage(input) {
            const file = input.files[0];
            if (!file) return;

            const content = document.getElementById('imgDropContent');
            const previewWrap = document.getElementById('imgFilePreviewWrap');
            const previewImg = document.getElementById('imgFilePreviewImg');
            const fileName = document.getElementById('imgFileName');

            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                content.classList.add('hidden');
                previewWrap.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function clearFileImage() {
            document.getElementById('img_new_file').value = '';
            document.getElementById('imgDropContent').classList.remove('hidden');
            document.getElementById('imgFilePreviewWrap').classList.add('hidden');
        }

        // Drag & drop
        document.addEventListener('DOMContentLoaded', () => {
            const dropZone = document.getElementById('imgDropZone');
            if (!dropZone) return;

            ['dragenter', 'dragover'].forEach(evt => {
                dropZone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    dropZone.classList.add('border-indigo-400', 'bg-indigo-50/40');
                });
            });
            ['dragleave', 'drop'].forEach(evt => {
                dropZone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('border-indigo-400', 'bg-indigo-50/40');
                });
            });
            dropZone.addEventListener('drop', (e) => {
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    const input = document.getElementById('img_new_file');
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                    previewFileImage(input);
                }
            });
        });

        function openImagesDrawer(productId, productName) {
            currentProductId = productId;
            document.getElementById('imagesDrawerSubtitle').textContent = productName;
            document.getElementById('img_new_url').value = '';
            document.getElementById('img_new_alt').value = '';
            document.getElementById('img_new_primary').checked = false;
            document.getElementById('imgNewPreview').classList.add('hidden');
            clearFileImage();
            switchImageTab('url');
            showDrawer(imagesDrawer);
            loadImages();
        }

        async function loadImages() {
            const loading = document.getElementById('imagesLoading');
            const list = document.getElementById('imagesList');
            const empty = document.getElementById('imagesEmpty');
            const count = document.getElementById('imagesCount');

            loading.classList.remove('hidden');
            list.innerHTML = '';

            try {
                const res = await fetch(`/admin/products/${currentProductId}/images`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const images = await res.json();
                count.textContent = images.length;

                if (images.length === 0) {
                    empty.classList.remove('hidden');
                    list.classList.add('hidden');
                } else {
                    empty.classList.add('hidden');
                    list.classList.remove('hidden');
                    images.forEach((img, i) => {
                        list.appendChild(createImageCard(img, i, images.length));
                    });
                }
            } catch (e) {
                list.innerHTML = '<p class="text-sm text-red-500 text-center py-4">Error al cargar imágenes</p>';
            } finally {
                loading.classList.add('hidden');
            }
        }

        function createImageCard(img, index, total) {
            const card = document.createElement('div');
            card.className = 'group flex items-start gap-3 p-3 bg-white rounded-xl border border-gray-100 hover:border-gray-200 hover:shadow-sm transition-all';
            card.dataset.imageId = img.id;

            card.innerHTML = `
            <div class="relative flex-shrink-0">
                <img src="${img.image_url}" alt="${img.alt_text || ''}" class="w-20 h-20 rounded-lg object-cover border border-gray-100"
                     onerror="this.src=''; this.classList.add('bg-gray-100'); this.alt='Error'">
                ${img.is_primary ? '<span class="absolute -top-1.5 -left-1.5 w-5 h-5 bg-amber-400 rounded-full flex items-center justify-center shadow-sm"><i class="fas fa-star text-white text-[8px]"></i></span>' : ''}
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <p class="text-xs text-gray-500 truncate mb-1" title="${img.image_url}">${img.image_url}</p>
                <p class="text-xs text-gray-400 mb-2">${img.alt_text || '<span class="italic">Sin texto alt</span>'}</p>
                <div class="flex items-center gap-1.5 flex-wrap">
                    ${img.is_primary
                        ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-600"><i class="fas fa-star mr-1 text-[8px]"></i>Principal</span>'
                        : `<button onclick="setPrimary(${img.id})" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-500 hover:bg-amber-50 hover:text-amber-600 transition cursor-pointer"><i class="far fa-star mr-1 text-[8px]"></i>Hacer principal</button>`
                    }
                    <button onclick="deleteImage(${img.id})" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-500 hover:bg-red-50 hover:text-red-600 transition cursor-pointer">
                        <i class="fas fa-trash-can mr-1 text-[8px]"></i>Eliminar
                    </button>
                </div>
            </div>
            <div class="flex flex-col gap-0.5 pt-1">
                ${index > 0 ? `<button onclick="moveImage(${img.id}, 'up')" class="w-6 h-6 flex items-center justify-center text-gray-300 hover:text-gray-600 hover:bg-gray-100 rounded transition text-[10px]"><i class="fas fa-chevron-up"></i></button>` : '<div class="w-6 h-6"></div>'}
                <span class="text-[10px] text-gray-300 text-center">${index + 1}</span>
                ${index < total - 1 ? `<button onclick="moveImage(${img.id}, 'down')" class="w-6 h-6 flex items-center justify-center text-gray-300 hover:text-gray-600 hover:bg-gray-100 rounded transition text-[10px]"><i class="fas fa-chevron-down"></i></button>` : '<div class="w-6 h-6"></div>'}
            </div>
        `;

            return card;
        }

        async function addImage() {
            const alt = document.getElementById('img_new_alt').value.trim();
            const primary = document.getElementById('img_new_primary').checked;
            const btn = document.getElementById('imgAddBtn');

            let body, headers;

            if (currentImageTab === 'file') {
                const fileInput = document.getElementById('img_new_file');
                const file = fileInput.files[0];
                if (!file) {
                    fileInput.click();
                    return;
                }

                const formData = new FormData();
                formData.append('image_file', file);
                if (alt) formData.append('alt_text', alt);
                formData.append('is_primary', primary ? '1' : '0');

                body = formData;
                headers = {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                };
            } else {
                const url = document.getElementById('img_new_url').value.trim();
                if (!url) {
                    document.getElementById('img_new_url').focus();
                    return;
                }

                body = JSON.stringify({
                    image_url: url,
                    alt_text: alt || null,
                    is_primary: primary
                });
                headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                };
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Subiendo...';

            try {
                const res = await fetch(`/admin/products/${currentProductId}/images`, {
                    method: 'POST',
                    headers,
                    body
                });

                if (!res.ok) {
                    const err = await res.json();
                    alert(err.message || 'Error al agregar imagen');
                    return;
                }

                document.getElementById('img_new_url').value = '';
                document.getElementById('img_new_alt').value = '';
                document.getElementById('img_new_primary').checked = false;
                document.getElementById('imgNewPreview').classList.add('hidden');
                clearFileImage();
                loadImages();
            } catch (e) {
                alert('Error de conexión');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plus text-xs"></i> Agregar';
            }
        }

        async function setPrimary(imageId) {
            try {
                await fetch(`/admin/products/${currentProductId}/images/${imageId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        is_primary: true
                    })
                });
                loadImages();
            } catch (e) {
                alert('Error de conexión');
            }
        }

        async function deleteImage(imageId) {
            if (!confirm('¿Eliminar esta imagen?')) return;

            try {
                await fetch(`/admin/products/${currentProductId}/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                loadImages();
            } catch (e) {
                alert('Error de conexión');
            }
        }

        async function moveImage(imageId, direction) {
            const cards = [...document.querySelectorAll('#imagesList [data-image-id]')];
            const ids = cards.map(c => parseInt(c.dataset.imageId));
            const idx = ids.indexOf(imageId);

            if (direction === 'up' && idx > 0) {
                [ids[idx], ids[idx - 1]] = [ids[idx - 1], ids[idx]];
            } else if (direction === 'down' && idx < ids.length - 1) {
                [ids[idx], ids[idx + 1]] = [ids[idx + 1], ids[idx]];
            } else {
                return;
            }

            try {
                await fetch(`/admin/products/${currentProductId}/images/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        order: ids
                    })
                });
                loadImages();
            } catch (e) {
                alert('Error de conexión');
            }
        }

        // New image URL preview
        document.getElementById('img_new_url').addEventListener('input', function() {
            const preview = document.getElementById('imgNewPreview');
            const img = document.getElementById('imgNewPreviewImg');
            if (this.value.trim()) {
                img.src = this.value.trim();
                preview.classList.remove('hidden');
                img.onerror = () => preview.classList.add('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });

        // ==================== SPECIFICATIONS MANAGEMENT ====================
        let currentSpecsProductId = null;
        let currentSpecs = {};

        function openSpecsDrawer(productId, productName) {
            currentSpecsProductId = productId;
            document.getElementById('specsDrawerSubtitle').textContent = productName;
            document.getElementById('spec_new_key').value = '';
            document.getElementById('spec_new_value').value = '';
            showDrawer(specsDrawer);
            loadSpecs();
        }

        async function loadSpecs() {
            const loading = document.getElementById('specsLoading');
            const list = document.getElementById('specsList');
            const empty = document.getElementById('specsEmpty');
            const count = document.getElementById('specsCount');

            loading.classList.remove('hidden');
            list.innerHTML = '';

            try {
                const res = await fetch(`/admin/products/${currentSpecsProductId}/specifications`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                currentSpecs = await res.json();
                const entries = Object.entries(currentSpecs);
                count.textContent = entries.length;

                if (entries.length === 0) {
                    empty.classList.remove('hidden');
                    list.classList.add('hidden');
                } else {
                    empty.classList.add('hidden');
                    list.classList.remove('hidden');
                    entries.forEach(([key, value]) => {
                        list.appendChild(createSpecRow(key, value));
                    });
                }
            } catch (e) {
                list.innerHTML = '<p class="text-sm text-red-500 text-center py-4">Error al cargar especificaciones</p>';
            } finally {
                loading.classList.add('hidden');
            }
        }

        function createSpecRow(key, value) {
            const row = document.createElement('div');
            row.className = 'group flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-100 hover:border-gray-200 hover:shadow-sm transition-all';
            row.dataset.specKey = key;

            row.innerHTML = `
            <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-tag text-teal-500 text-xs"></i>
            </div>
            <div class="flex-1 min-w-0 spec-display">
                <p class="text-sm font-medium text-gray-800">${escapeHtml(key)}</p>
                <p class="text-xs text-gray-400 truncate">${escapeHtml(value)}</p>
            </div>
            <div class="flex-1 min-w-0 spec-edit hidden">
                <input type="text" value="${escapeAttr(key)}" class="spec-edit-key w-full px-2 py-1 border border-gray-200 rounded text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 mb-1">
                <input type="text" value="${escapeAttr(value)}" class="spec-edit-value w-full px-2 py-1 border border-gray-200 rounded text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                       onkeydown="if(event.key==='Enter'){event.preventDefault();saveSpecEdit(this.closest('[data-spec-key]'))}">
            </div>
            <div class="flex items-center gap-0.5 spec-display">
                <button onclick="startEditSpec(this.closest('[data-spec-key]'))" class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition" title="Editar">
                    <i class="fas fa-pen text-[10px]"></i>
                </button>
                <button onclick="deleteSpec('${escapeAttr(key)}')" class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-red-600 hover:bg-red-50 rounded-md transition" title="Eliminar">
                    <i class="fas fa-trash-can text-[10px]"></i>
                </button>
            </div>
            <div class="flex items-center gap-0.5 spec-edit hidden">
                <button onclick="saveSpecEdit(this.closest('[data-spec-key]'))" class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-emerald-600 hover:bg-emerald-50 rounded-md transition" title="Guardar">
                    <i class="fas fa-check text-[10px]"></i>
                </button>
                <button onclick="cancelEditSpec(this.closest('[data-spec-key]'))" class="w-7 h-7 flex items-center justify-center text-gray-300 hover:text-gray-600 hover:bg-gray-100 rounded-md transition" title="Cancelar">
                    <i class="fas fa-times text-[10px]"></i>
                </button>
            </div>
        `;

            return row;
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function escapeAttr(str) {
            return str.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        function startEditSpec(row) {
            row.querySelectorAll('.spec-display').forEach(el => el.classList.add('hidden'));
            row.querySelectorAll('.spec-edit').forEach(el => el.classList.remove('hidden'));
            row.querySelector('.spec-edit-key').focus();
        }

        function cancelEditSpec(row) {
            row.querySelectorAll('.spec-display').forEach(el => el.classList.remove('hidden'));
            row.querySelectorAll('.spec-edit').forEach(el => el.classList.add('hidden'));
        }

        async function saveSpecEdit(row) {
            const oldKey = row.dataset.specKey;
            const newKey = row.querySelector('.spec-edit-key').value.trim();
            const newValue = row.querySelector('.spec-edit-value').value.trim();

            if (!newKey || !newValue) return;

            const updated = {
                ...currentSpecs
            };
            if (newKey !== oldKey) {
                delete updated[oldKey];
            }
            updated[newKey] = newValue;

            await saveSpecs(updated);
        }

        async function addSpec() {
            const keyInput = document.getElementById('spec_new_key');
            const valueInput = document.getElementById('spec_new_value');
            const key = keyInput.value.trim();
            const value = valueInput.value.trim();

            if (!key) {
                keyInput.focus();
                return;
            }
            if (!value) {
                valueInput.focus();
                return;
            }

            const updated = {
                ...currentSpecs
            };
            updated[key] = value;

            await saveSpecs(updated);
            keyInput.value = '';
            valueInput.value = '';
            keyInput.focus();
        }

        async function deleteSpec(key) {
            const updated = {
                ...currentSpecs
            };
            delete updated[key];
            await saveSpecs(updated);
        }

        async function saveSpecs(specs) {
            const loading = document.getElementById('specsLoading');
            loading.classList.remove('hidden');

            try {
                const res = await fetch(`/admin/products/${currentSpecsProductId}/specifications`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        specifications: specs
                    })
                });

                if (!res.ok) {
                    const err = await res.json();
                    alert(err.message || 'Error al guardar');
                    return;
                }

                loadSpecs();
            } catch (e) {
                alert('Error de conexión');
            }
        }

        // Auto-open if validation errors
        @if($errors->any() && old('_method') === 'PUT')
        openEditDrawer({
            id: '{{ old("_product_id") }}',
            name: '{{ old("name") }}',
            sku: '{{ old("sku") }}',
            slug: '{{ old("slug") }}',
            category_id: '{{ old("category_id") }}',
            short_description: '{{ old("short_description") }}',
            description: '{{ old("description") }}',
            price: '{{ old("price") }}',
            sale_price: '{{ old("sale_price") }}',
            stock: {{ old('stock', 0) }},
            material: '{{ old("material") }}',
            image_url: '{{ old("image_url") }}',
            is_active: {{ old('is_active', 1) }},
            is_featured: {{ old('is_featured', 0) }},
            created_at: null,
            updated_at: null
        });
        @elseif($errors->any())
        openCreateDrawer();
        @endif
    </script>
    @endsection