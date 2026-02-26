@extends('layouts.app')

@section('title', 'Catálogo - Romantic Gifts')

@section('styles')
    /* ── Animations ── */
    @keyframes fadeInUp {
        from { opacity:0; transform:translateY(30px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .anim-fade-up { animation: fadeInUp .6s ease both; }

    .reveal-catalog {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity .5s ease, transform .5s ease;
    }
    .reveal-catalog.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Filter Sidebar ── */
    .filter-sidebar {
        scrollbar-width: thin;
        scrollbar-color: #D4A574 transparent;
    }
    .filter-sidebar::-webkit-scrollbar { width: 4px; }
    .filter-sidebar::-webkit-scrollbar-thumb { background: #D4A574; border-radius: 4px; }

    .filter-chip {
        transition: all .25s ease;
    }
    .filter-chip:hover {
        border-color: #D4A574;
        background: #FAF8F5;
    }
    .filter-chip.active {
        background: linear-gradient(135deg, #D4A574, #C39563);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(212,165,116,0.3);
    }
    .filter-chip.active i { color: white !important; }

    /* ── Price Range ── */
    .price-range-slider {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 6px;
        border-radius: 5px;
        background: linear-gradient(90deg, #D4A574, #e5e7eb);
        outline: none;
        transition: background .2s;
    }
    .price-range-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: linear-gradient(135deg, #D4A574, #C39563);
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(212,165,116,0.4);
        border: 3px solid white;
        transition: transform .2s;
    }
    .price-range-slider::-webkit-slider-thumb:hover {
        transform: scale(1.15);
    }
    .price-range-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: linear-gradient(135deg, #D4A574, #C39563);
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(212,165,116,0.4);
    }

    /* ── Mobile Filter Panel ── */
    .catalog-filters-mobile {
        transform: translateX(-100%);
        transition: transform .35s cubic-bezier(.4,0,.2,1);
    }
    .catalog-filters-mobile.active {
        transform: translateX(0);
    }

    /* ── Product Cards ── */
    .product-card-catalog {
        transition: all .4s cubic-bezier(.25,.8,.25,1);
    }
    .product-card-catalog:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 50px rgba(0,0,0,.1);
    }
    .product-card-catalog .product-img {
        transition: transform .7s cubic-bezier(.25,.8,.25,1);
    }
    .product-card-catalog:hover .product-img {
        transform: scale(1.08);
    }
    .product-card-catalog .quick-actions {
        opacity: 0;
        transform: translateY(10px);
        transition: all .3s ease;
    }
    .product-card-catalog:hover .quick-actions {
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Sort Dropdown ── */
    .sort-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23D4A574' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        -webkit-appearance: none;
        appearance: none;
        padding-right: 36px;
    }

    /* ── Pagination ── */
    .pagination-btn {
        transition: all .25s ease;
    }
    .pagination-btn:hover:not(.active):not(.disabled) {
        background: #D4A574;
        color: white;
        border-color: #D4A574;
        transform: translateY(-1px);
    }
    .pagination-btn.active {
        background: linear-gradient(135deg, #D4A574, #C39563);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(212,165,116,0.35);
    }

    /* ── Active Filter Tags ── */
    .filter-tag {
        background: linear-gradient(135deg, #D4A574, #C39563);
        transition: all .2s ease;
    }
    .filter-tag:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(212,165,116,0.3);
    }

    /* ── View Toggle ── */
    .view-btn.active {
        background: #D4A574;
        color: white;
    }
@endsection

@section('content')
    {{-- ═══════════════════ HERO BANNER ═══════════════════ --}}
    <section class="relative bg-gradient-to-br from-[#F5E6D3] via-[#FAF8F5] to-[#E8D4C0] overflow-hidden">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-10 right-10 w-64 h-64 bg-[#D4A574]/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 left-10 w-48 h-48 bg-[#E8B4A8]/20 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14 relative z-10">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="anim-fade-up">
                    {{-- Breadcrumb --}}
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                        <a href="{{ route('home') }}" class="hover:text-[#D4A574] transition">
                            <i class="fas fa-home text-xs"></i> Inicio
                        </a>
                        <i class="fas fa-chevron-right text-[8px]"></i>
                        <span class="text-gray-900 font-medium">Catálogo</span>
                    </div>
                    <h1 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-2">
                        Nuestra Colección
                    </h1>
                    <p class="text-gray-600 text-lg">
                        {{ $products->total() }} productos esperan por ti
                    </p>
                </div>

                {{-- Sort & View Controls --}}
                <div class="flex items-center gap-3 anim-fade-up" style="animation-delay:.15s">
                    <div class="hidden sm:flex items-center bg-white rounded-xl border border-gray-200 p-1 gap-0.5">
                        <button type="button" class="view-btn active w-9 h-9 rounded-lg flex items-center justify-center text-sm transition" data-view="grid" title="Vista cuadrícula">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="view-btn w-9 h-9 rounded-lg flex items-center justify-center text-sm text-gray-400 hover:text-gray-700 transition" data-view="list" title="Vista lista">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ CATALOG CONTENT ═══════════════════ --}}
    <section class="py-8 lg:py-10 bg-[#FAF8F5] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form id="catalogForm" method="GET" action="{{ route('catalog') }}">
                <div class="flex flex-col lg:flex-row gap-8">

                    {{-- ═══ SIDEBAR FILTERS ═══ --}}
                    <aside class="lg:w-[280px] flex-shrink-0">
                        {{-- Mobile Filter Toggle --}}
                        <button type="button" class="lg:hidden w-full bg-white text-gray-900 py-3.5 rounded-2xl mb-5 flex items-center justify-center gap-3 border border-gray-200 hover:border-[#D4A574] hover:shadow-md transition-all font-medium" id="catalogMobileFilterBtn">
                            <i class="fas fa-sliders-h text-[#D4A574]"></i>
                            Filtros y Ordenar
                            @if(request()->hasAny(['categories', 'price_min', 'price_max', 'in_stock', 'on_sale']))
                                <span class="bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">!</span>
                            @endif
                        </button>

                        {{-- Filters Container --}}
                        <div class="catalog-filters-mobile filter-sidebar lg:transform-none fixed lg:static inset-y-0 left-0 w-[320px] lg:w-auto bg-white lg:bg-transparent z-40 overflow-y-auto lg:overflow-visible shadow-2xl lg:shadow-none" id="catalogFiltersContainer">
                            {{-- Mobile header --}}
                            <div class="lg:hidden flex items-center justify-between px-6 py-5 border-b border-gray-100">
                                <h2 class="font-serif text-xl font-bold text-gray-900">Filtros</h2>
                                <button type="button" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition" id="catalogCloseFiltersBtn">
                                    <i class="fas fa-times text-gray-600"></i>
                                </button>
                            </div>

                            <div class="p-6 lg:p-0 space-y-0">
                                {{-- Filter Card Wrapper --}}
                                <div class="bg-white lg:rounded-2xl lg:shadow-sm lg:border lg:border-gray-100 lg:overflow-hidden">

                                    {{-- Header --}}
                                    <div class="hidden lg:flex items-center justify-between p-5 border-b border-gray-100 bg-gradient-to-r from-[#FAF8F5] to-white">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gradient-to-br from-[#D4A574] to-[#C39563] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-sliders-h text-white text-xs"></i>
                                            </div>
                                            <h2 class="font-bold text-gray-900">Filtros</h2>
                                        </div>
                                        <a href="{{ route('catalog') }}" class="text-xs text-[#D4A574] hover:text-[#C39563] font-semibold transition">Limpiar</a>
                                    </div>

                                    {{-- Sort (inside filters for mobile) --}}
                                    <div class="p-5 border-b border-gray-100">
                                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Ordenar por</h3>
                                        <select name="sort" class="sort-select w-full px-4 py-3 bg-[#FAF8F5] border border-gray-200 rounded-xl focus:outline-none focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 text-sm font-medium text-gray-700" id="sortSelect">
                                            <option value="relevant" {{ request('sort', 'relevant') === 'relevant' ? 'selected' : '' }}>Más Relevantes</option>
                                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Más Nuevos</option>
                                        </select>
                                    </div>

                                    {{-- Categories --}}
                                    <div class="p-5 border-b border-gray-100">
                                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Categoría</h3>
                                        <div class="space-y-2">
                                            @foreach($categories as $category)
                                                @php
                                                    $selectedCats = request()->get('categories', []);
                                                    if (is_string($selectedCats)) $selectedCats = explode(',', $selectedCats);
                                                    $isChecked = in_array($category->slug, $selectedCats);
                                                @endphp
                                                <label class="flex items-center cursor-pointer group">
                                                    <input type="checkbox"
                                                           name="categories[]"
                                                           value="{{ $category->slug }}"
                                                           class="category-filter hidden"
                                                           {{ $isChecked ? 'checked' : '' }}>
                                                    <span class="filter-chip flex-1 py-2.5 px-4 border border-gray-200 rounded-xl flex items-center justify-between text-sm {{ $isChecked ? 'active' : '' }}">
                                                        <span class="flex items-center gap-2">
                                                            <i class="{{ $category->icon }} text-sm {{ $isChecked ? '' : 'text-[#D4A574]' }}"></i>
                                                            <span class="font-medium">{{ $category->name }}</span>
                                                        </span>
                                                        <span class="text-xs {{ $isChecked ? 'text-white/70' : 'text-gray-400' }} font-medium">{{ $category->products_count }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Price Range --}}
                                    <div class="p-5 border-b border-gray-100">
                                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Rango de Precio</h3>
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-1 relative">
                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">S/</span>
                                                    <input type="number"
                                                           name="price_min"
                                                           value="{{ request('price_min', '') }}"
                                                           min="0" step="0.01"
                                                           class="w-full pl-9 pr-3 py-2.5 bg-[#FAF8F5] border border-gray-200 rounded-xl focus:outline-none focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 text-sm"
                                                           placeholder="{{ floor($priceRange->min_price ?? 0) }}"
                                                           id="priceMin">
                                                </div>
                                                <span class="text-gray-300 text-sm">—</span>
                                                <div class="flex-1 relative">
                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">S/</span>
                                                    <input type="number"
                                                           name="price_max"
                                                           value="{{ request('price_max', '') }}"
                                                           min="0" step="0.01"
                                                           class="w-full pl-9 pr-3 py-2.5 bg-[#FAF8F5] border border-gray-200 rounded-xl focus:outline-none focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 text-sm"
                                                           placeholder="{{ ceil($priceRange->max_price ?? 500) }}"
                                                           id="priceMax">
                                                </div>
                                            </div>
                                            <input type="range"
                                                   min="{{ floor($priceRange->min_price ?? 0) }}"
                                                   max="{{ ceil($priceRange->max_price ?? 500) }}"
                                                   value="{{ request('price_max', ceil($priceRange->max_price ?? 500)) }}"
                                                   class="price-range-slider w-full"
                                                   id="priceSlider">
                                            <div class="flex justify-between text-xs text-gray-400 font-medium">
                                                <span>S/ {{ floor($priceRange->min_price ?? 0) }}</span>
                                                <span>S/ {{ ceil($priceRange->max_price ?? 500) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Availability --}}
                                    <div class="p-5">
                                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Disponibilidad</h3>
                                        <div class="space-y-2.5">
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <div class="relative">
                                                    <input type="checkbox"
                                                           name="in_stock" value="1"
                                                           class="availability-filter peer sr-only"
                                                           {{ request()->boolean('in_stock') ? 'checked' : '' }}>
                                                    <div class="w-10 h-6 bg-gray-200 rounded-full peer-checked:bg-gradient-to-r peer-checked:from-[#D4A574] peer-checked:to-[#C39563] transition-all"></div>
                                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm peer-checked:translate-x-4 transition-transform"></div>
                                                </div>
                                                <span class="text-sm text-gray-700 font-medium group-hover:text-gray-900">En Stock</span>
                                            </label>
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <div class="relative">
                                                    <input type="checkbox"
                                                           name="on_sale" value="1"
                                                           class="availability-filter peer sr-only"
                                                           {{ request()->boolean('on_sale') ? 'checked' : '' }}>
                                                    <div class="w-10 h-6 bg-gray-200 rounded-full peer-checked:bg-gradient-to-r peer-checked:from-[#D4A574] peer-checked:to-[#C39563] transition-all"></div>
                                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm peer-checked:translate-x-4 transition-transform"></div>
                                                </div>
                                                <span class="text-sm text-gray-700 font-medium group-hover:text-gray-900">En Oferta</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Mobile Apply Button --}}
                                <button type="submit" class="lg:hidden w-full mt-5 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white py-3.5 rounded-2xl font-semibold hover:shadow-lg transition-all">
                                    Aplicar Filtros
                                </button>

                                {{-- Mobile Clear --}}
                                <a href="{{ route('catalog') }}" class="lg:hidden block text-center mt-3 text-sm text-gray-500 hover:text-gray-900 font-medium">
                                    Limpiar todos los filtros
                                </a>
                            </div>
                        </div>
                    </aside>

                    {{-- ═══ PRODUCTS AREA ═══ --}}
                    <div class="flex-1 min-w-0">

                        {{-- Active Filters --}}
                        @if(request()->hasAny(['categories', 'price_min', 'price_max', 'in_stock', 'on_sale']))
                            <div class="flex flex-wrap items-center gap-2 mb-6 reveal-catalog">
                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider mr-1">Filtros:</span>
                                @if(request()->filled('categories'))
                                    @php
                                        $selectedCats = request()->get('categories', []);
                                        if (is_string($selectedCats)) $selectedCats = explode(',', $selectedCats);
                                    @endphp
                                    @foreach($categories->whereIn('slug', $selectedCats) as $cat)
                                        <span class="filter-tag inline-flex items-center gap-1.5 text-white text-xs px-3 py-1.5 rounded-full font-medium cursor-default">
                                            <i class="{{ $cat->icon }} text-[10px]"></i>
                                            {{ $cat->name }}
                                        </span>
                                    @endforeach
                                @endif
                                @if(request()->filled('price_min') || request()->filled('price_max'))
                                    <span class="filter-tag inline-flex items-center gap-1.5 text-white text-xs px-3 py-1.5 rounded-full font-medium cursor-default">
                                        <i class="fas fa-tag text-[10px]"></i>
                                        S/ {{ request('price_min', floor($priceRange->min_price ?? 0)) }} — S/ {{ request('price_max', ceil($priceRange->max_price ?? 500)) }}
                                    </span>
                                @endif
                                @if(request()->boolean('in_stock'))
                                    <span class="filter-tag inline-flex items-center gap-1.5 text-white text-xs px-3 py-1.5 rounded-full font-medium cursor-default">
                                        <i class="fas fa-check text-[10px]"></i> En Stock
                                    </span>
                                @endif
                                @if(request()->boolean('on_sale'))
                                    <span class="filter-tag inline-flex items-center gap-1.5 text-white text-xs px-3 py-1.5 rounded-full font-medium cursor-default">
                                        <i class="fas fa-percent text-[10px]"></i> En Oferta
                                    </span>
                                @endif
                                <a href="{{ route('catalog') }}" class="text-xs text-gray-400 hover:text-[#D4A574] font-medium transition ml-1 flex items-center gap-1">
                                    <i class="fas fa-times text-[9px]"></i> Limpiar
                                </a>
                            </div>
                        @endif

                        {{-- Results info --}}
                        <div class="flex items-center justify-between mb-6 reveal-catalog">
                            <p class="text-sm text-gray-500">
                                Mostrando <span class="font-semibold text-gray-900">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</span> de <span class="font-semibold text-gray-900">{{ $products->total() }}</span> productos
                            </p>
                        </div>

                        @if($products->isEmpty())
                            {{-- Empty State --}}
                            <div class="text-center py-24 reveal-catalog">
                                <div class="w-24 h-24 bg-gradient-to-br from-[#D4A574]/10 to-[#C39563]/10 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-search text-4xl text-[#D4A574]/40"></i>
                                </div>
                                <h3 class="font-serif text-2xl font-bold text-gray-900 mb-3">No encontramos productos</h3>
                                <p class="text-gray-500 mb-8 max-w-sm mx-auto">Intenta cambiar los filtros o explorar otras categorías para descubrir lo que buscas.</p>
                                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-8 py-3.5 rounded-full hover:shadow-lg hover:scale-105 transition-all font-semibold">
                                    <i class="fas fa-redo text-sm"></i> Ver todos los productos
                                </a>
                            </div>
                        @else
                            {{-- Products Grid --}}
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-12" id="productsGrid">
                                @foreach($products as $index => $product)
                                    <div class="product-card-catalog bg-white rounded-2xl overflow-hidden border border-gray-100/80 group reveal-catalog" style="transition-delay: {{ min($index * 50, 300) }}ms">
                                        <a href="{{ route('product.show', $product->slug) }}" class="block relative overflow-hidden">
                                            <img src="{{ $product->primaryImage?->image_url ?? 'https://via.placeholder.com/400x300?text=Sin+Imagen' }}"
                                                 alt="{{ $product->primaryImage?->alt_text ?? $product->name }}"
                                                 class="product-img w-full h-52 sm:h-72 lg:h-80 object-cover"
                                                 loading="lazy">

                                            {{-- Overlay --}}
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                                            {{-- Badges --}}
                                            @if($product->discount_percentage)
                                                <div class="absolute top-3 left-3 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                                    -{{ $product->discount_percentage }}%
                                                </div>
                                            @endif

                                            {{-- Wishlist --}}
                                            <button type="button" class="wishlist-btn absolute top-3 right-3 w-9 h-9 sm:w-10 sm:h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-md" data-product-id="{{ $product->id }}">
                                                <i class="far fa-heart text-xs sm:text-sm"></i>
                                            </button>

                                            {{-- Low Stock --}}
                                            @if($product->stock <= 5 && $product->stock > 0)
                                                <div class="absolute bottom-3 left-3 bg-amber-500/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold flex items-center gap-1">
                                                    <i class="fas fa-fire text-[9px]"></i> ¡Últimas {{ $product->stock }} uds!
                                                </div>
                                            @endif

                                            {{-- Quick Add (desktop hover) --}}
                                            <div class="quick-actions absolute bottom-3 right-3 hidden sm:block">
                                                <button type="button"
                                                        class="add-to-cart-btn bg-white/95 backdrop-blur-sm text-gray-900 hover:bg-[#D4A574] hover:text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-all duration-300"
                                                        data-product-id="{{ $product->id }}"
                                                        title="Agregar al carrito">
                                                    <i class="fas fa-shopping-bag text-sm"></i>
                                                </button>
                                            </div>
                                        </a>

                                        <div class="p-4 sm:p-5">
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <p class="text-[10px] sm:text-xs text-[#D4A574] font-semibold uppercase tracking-wider">{{ $product->category?->name }}</p>
                                                @if($product->sale_price && $product->sale_price < $product->price)
                                                    <span class="text-[10px] bg-rose-50 text-rose-500 px-1.5 py-0.5 rounded font-bold">OFERTA</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('product.show', $product->slug) }}" class="block">
                                                <h3 class="font-semibold text-sm sm:text-base mb-2 line-clamp-2 group-hover:text-[#D4A574] transition-colors duration-200 leading-snug">{{ $product->name }}</h3>
                                            </a>
                                            <div class="flex items-end gap-2 mb-3 sm:mb-4">
                                                <span class="text-lg sm:text-xl font-bold text-gray-900 leading-none">S/ {{ number_format($product->current_price, 2) }}</span>
                                                @if($product->sale_price && $product->sale_price < $product->price)
                                                    <span class="text-xs text-gray-400 line-through leading-none pb-0.5">S/ {{ number_format($product->price, 2) }}</span>
                                                @endif
                                            </div>

                                            {{-- Mobile Add to Cart --}}
                                            <button type="button"
                                                    class="add-to-cart-btn sm:hidden w-full bg-gray-900 text-white py-2.5 rounded-full hover:bg-[#D4A574] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 font-medium text-xs"
                                                    data-product-id="{{ $product->id }}">
                                                <i class="fas fa-shopping-bag text-xs"></i> Agregar
                                            </button>
                                            <button type="button"
                                                    class="add-to-cart-btn hidden sm:flex w-full bg-gray-900 text-white py-3 rounded-full hover:bg-[#D4A574] active:scale-[0.98] transition-all duration-300 items-center justify-center gap-2 font-medium text-sm"
                                                    data-product-id="{{ $product->id }}">
                                                <i class="fas fa-shopping-bag text-xs"></i> Agregar al Carrito
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Pagination --}}
                            @if($products->hasPages())
                                <div class="flex items-center justify-center gap-1.5 sm:gap-2 reveal-catalog">
                                    @if($products->onFirstPage())
                                        <span class="pagination-btn disabled w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center text-gray-300 cursor-not-allowed">
                                            <i class="fas fa-chevron-left text-xs"></i>
                                        </span>
                                    @else
                                        <a href="{{ $products->previousPageUrl() }}" class="pagination-btn w-10 h-10 border border-gray-200 bg-white rounded-xl flex items-center justify-center text-gray-600">
                                            <i class="fas fa-chevron-left text-xs"></i>
                                        </a>
                                    @endif

                                    @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                                        @if($page == $products->currentPage())
                                            <span class="pagination-btn active w-10 h-10 rounded-xl flex items-center justify-center font-semibold text-sm">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="pagination-btn w-10 h-10 border border-gray-200 bg-white rounded-xl flex items-center justify-center text-sm text-gray-600">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if($products->currentPage() + 2 < $products->lastPage())
                                        <span class="px-1 text-gray-300">...</span>
                                        <a href="{{ $products->url($products->lastPage()) }}" class="pagination-btn w-10 h-10 border border-gray-200 bg-white rounded-xl flex items-center justify-center text-sm text-gray-600">{{ $products->lastPage() }}</a>
                                    @endif

                                    @if($products->hasMorePages())
                                        <a href="{{ $products->nextPageUrl() }}" class="pagination-btn w-10 h-10 border border-gray-200 bg-white rounded-xl flex items-center justify-center text-gray-600">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </a>
                                    @else
                                        <span class="pagination-btn disabled w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center text-gray-300 cursor-not-allowed">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </span>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- Mobile Filter Overlay --}}
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden transition-opacity" id="catalogFilterOverlay"></div>
@endsection

@section('scripts')
<script>
    // ── Scroll Reveal ──
    (function() {
        var els = document.querySelectorAll('.reveal-catalog');
        function check() {
            els.forEach(function(el) {
                if (el.getBoundingClientRect().top < window.innerHeight - 60) {
                    el.classList.add('visible');
                }
            });
        }
        window.addEventListener('scroll', check);
        check();
    })();

    // ── Mobile Filters ──
    var catalogMobileFilterBtn = document.getElementById('catalogMobileFilterBtn');
    var catalogFiltersContainer = document.getElementById('catalogFiltersContainer');
    var catalogCloseFiltersBtn = document.getElementById('catalogCloseFiltersBtn');
    var catalogFilterOverlay = document.getElementById('catalogFilterOverlay');

    catalogMobileFilterBtn.addEventListener('click', function() {
        catalogFiltersContainer.classList.add('active');
        catalogFilterOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    catalogCloseFiltersBtn.addEventListener('click', closeFilters);
    catalogFilterOverlay.addEventListener('click', closeFilters);

    function closeFilters() {
        catalogFiltersContainer.classList.remove('active');
        catalogFilterOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ── Form Logic ──
    var catalogForm = document.getElementById('catalogForm');
    var sortSelect = document.getElementById('sortSelect');
    var priceSlider = document.getElementById('priceSlider');
    var priceMax = document.getElementById('priceMax');
    var priceMin = document.getElementById('priceMin');

    // Category checkboxes
    document.querySelectorAll('.category-filter').forEach(function(cb) {
        cb.addEventListener('change', function() {
            var chip = this.nextElementSibling;
            chip.classList.toggle('active', this.checked);
            if (window.innerWidth >= 1024) catalogForm.submit();
        });
    });

    // Availability toggles
    document.querySelectorAll('.availability-filter').forEach(function(cb) {
        cb.addEventListener('change', function() {
            if (window.innerWidth >= 1024) catalogForm.submit();
        });
    });

    // Sort select
    sortSelect.addEventListener('change', function() { catalogForm.submit(); });

    // Price slider
    priceSlider.addEventListener('input', function() { priceMax.value = this.value; });
    priceSlider.addEventListener('change', function() {
        priceMax.value = this.value;
        if (window.innerWidth >= 1024) catalogForm.submit();
    });

    // Price inputs
    [priceMin, priceMax].forEach(function(input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (window.innerWidth >= 1024) catalogForm.submit();
            }
        });
        input.addEventListener('change', function() {
            if (this === priceMax) priceSlider.value = this.value;
            if (window.innerWidth >= 1024) catalogForm.submit();
        });
    });

    // Clean URL
    catalogForm.addEventListener('submit', function() {
        this.querySelectorAll('input, select').forEach(function(input) {
            if (input.type === 'checkbox' && !input.checked) return;
            if (!input.value || input.value === '' || input.value === '0') {
                input.removeAttribute('name');
            }
        });
    });

    // ── View Toggle ──
    document.querySelectorAll('.view-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var view = this.dataset.view;
            var grid = document.getElementById('productsGrid');
            if (!grid) return;

            document.querySelectorAll('.view-btn').forEach(function(b) {
                b.classList.remove('active');
                b.classList.add('text-gray-400');
            });
            this.classList.add('active');
            this.classList.remove('text-gray-400');

            if (view === 'list') {
                grid.classList.remove('grid-cols-2', 'lg:grid-cols-3');
                grid.classList.add('grid-cols-1', 'lg:grid-cols-2');
            } else {
                grid.classList.remove('grid-cols-1', 'lg:grid-cols-2');
                grid.classList.add('grid-cols-2', 'lg:grid-cols-3');
            }
        });
    });

    // ── Add to Cart AJAX ──
    document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var button = this;
            var productId = button.dataset.productId;
            var originalHTML = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.remove('bg-gray-900', 'bg-white/95', 'text-gray-900');
                button.classList.add('bg-green-500', 'text-white');

                document.querySelectorAll('.cart-badge').forEach(function(b) {
                    b.textContent = data.cart_count;
                    b.style.display = data.cart_count > 0 ? 'flex' : 'none';
                });

                if (typeof showToast === 'function') {
                    showToast('¡Producto agregado al carrito!');
                }

                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-500');
                    button.classList.add('bg-gray-900');
                    button.disabled = false;
                }, 1500);
            })
            .catch(function() {
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        });
    });
</script>
@endsection
