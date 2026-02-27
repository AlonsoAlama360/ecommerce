@extends('layouts.app')

@section('title', 'Ofertas - Arixna')

@section('styles')
    /* ── Animations ── */
    @keyframes fadeInUp {
        from { opacity:0; transform:translateY(30px); }
        to   { opacity:1; transform:translateY(0); }
    }
    @keyframes pulseGlow {
        0%,100% { opacity:.4; transform:scale(1); }
        50% { opacity:.8; transform:scale(1.15); }
    }
    @keyframes countPulse {
        0%,100% { transform:scale(1); }
        50% { transform:scale(1.08); }
    }
    @keyframes shimmer {
        0%   { background-position:-200% 0; }
        100% { background-position:200% 0; }
    }
    .anim-fade-up   { animation: fadeInUp .6s ease both; }
    .anim-fade-up-2 { animation: fadeInUp .6s ease .1s both; }
    .anim-fade-up-3 { animation: fadeInUp .6s ease .2s both; }

    .reveal-offer {
        opacity:0; transform:translateY(20px);
        transition: opacity .5s ease, transform .5s ease;
    }
    .reveal-offer.visible { opacity:1; transform:translateY(0); }

    /* ── Hero ── */
    .offers-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 40%, #0f3460 100%);
        position: relative;
        overflow: hidden;
    }
    .offers-hero::before {
        content:'';
        position:absolute; inset:0;
        background: radial-gradient(ellipse at 70% 50%, rgba(212,165,116,.15) 0%, transparent 60%);
        animation: pulseGlow 5s ease-in-out infinite;
    }
    .offers-hero::after {
        content:'';
        position:absolute; inset:0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23D4A574' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hero-stat {
        background: rgba(255,255,255,.08);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,.1);
        transition: all .3s ease;
    }
    .hero-stat:hover {
        background: rgba(255,255,255,.12);
        transform: translateY(-3px);
    }

    /* ── Discount Tier Chips ── */
    .tier-chip {
        transition: all .3s cubic-bezier(.25,.8,.25,1);
        position: relative;
        overflow: hidden;
    }
    .tier-chip::before {
        content:'';
        position:absolute; inset:0;
        background: linear-gradient(135deg, #D4A574, #C39563);
        opacity:0;
        transition: opacity .3s ease;
        border-radius: inherit;
    }
    .tier-chip:hover::before,
    .tier-chip.active::before { opacity:1; }
    .tier-chip:hover,
    .tier-chip.active {
        color:white;
        border-color:transparent;
        box-shadow: 0 4px 15px rgba(212,165,116,.3);
        transform: translateY(-2px);
    }
    .tier-chip span, .tier-chip i { position:relative; z-index:1; }

    /* ── Filter Sidebar ── */
    .filter-sidebar { scrollbar-width:thin; scrollbar-color:#D4A574 transparent; }
    .filter-sidebar::-webkit-scrollbar { width:4px; }
    .filter-sidebar::-webkit-scrollbar-thumb { background:#D4A574; border-radius:4px; }

    .filter-chip { transition: all .25s ease; }
    .filter-chip:hover { border-color:#D4A574; background:#FAF8F5; }
    .filter-chip.active {
        background: linear-gradient(135deg, #D4A574, #C39563);
        color:white; border-color:transparent;
        box-shadow: 0 4px 15px rgba(212,165,116,.3);
    }
    .filter-chip.active i { color:white !important; }

    .price-range-slider {
        -webkit-appearance:none; appearance:none;
        width:100%; height:6px; border-radius:5px;
        background: linear-gradient(90deg, #D4A574, #e5e7eb);
        outline:none;
    }
    .price-range-slider::-webkit-slider-thumb {
        -webkit-appearance:none; appearance:none;
        width:22px; height:22px; border-radius:50%;
        background: linear-gradient(135deg, #D4A574, #C39563);
        cursor:pointer; box-shadow:0 2px 8px rgba(212,165,116,.4);
        border:3px solid white; transition:transform .2s;
    }
    .price-range-slider::-webkit-slider-thumb:hover { transform:scale(1.15); }
    .price-range-slider::-moz-range-thumb {
        width:18px; height:18px; border-radius:50%;
        background: linear-gradient(135deg, #D4A574, #C39563);
        cursor:pointer; border:3px solid white;
        box-shadow:0 2px 8px rgba(212,165,116,.4);
    }

    .ofertas-filters-mobile {
        transform:translateX(-100%);
        transition: transform .35s cubic-bezier(.4,0,.2,1);
    }
    .ofertas-filters-mobile.active { transform:translateX(0); }

    /* ── Product Cards ── */
    .product-card-offer {
        transition: all .4s cubic-bezier(.25,.8,.25,1);
    }
    .product-card-offer:hover {
        transform:translateY(-6px);
        box-shadow:0 20px 50px rgba(0,0,0,.1);
    }
    .product-card-offer .product-img {
        transition: transform .7s cubic-bezier(.25,.8,.25,1);
    }
    .product-card-offer:hover .product-img {
        transform:scale(1.08);
    }
    .product-card-offer .quick-actions {
        opacity:0; transform:translateY(10px);
        transition: all .3s ease;
    }
    .product-card-offer:hover .quick-actions {
        opacity:1; transform:translateY(0);
    }

    .discount-badge {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    .savings-badge {
        background: linear-gradient(135deg, #D4A574, #C39563);
    }

    /* ── Sort ── */
    .sort-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23D4A574' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center;
        -webkit-appearance:none; appearance:none; padding-right:36px;
    }

    /* ── Filter Tags ── */
    .filter-tag {
        background: linear-gradient(135deg, #D4A574, #C39563);
        transition: all .2s ease;
    }
    .filter-tag:hover { transform:scale(1.05); box-shadow:0 4px 12px rgba(212,165,116,.3); }

    /* ── Pagination ── */
    .pagination-btn { transition: all .25s ease; }
    .pagination-btn:hover:not(.active):not(.disabled) {
        background:#D4A574; color:white; border-color:#D4A574; transform:translateY(-1px);
    }
    .pagination-btn.active {
        background: linear-gradient(135deg, #D4A574, #C39563);
        color:white; border-color:transparent;
        box-shadow:0 4px 12px rgba(212,165,116,.35);
    }
@endsection

@section('content')

    {{-- ═══════════════════ HERO BANNER ═══════════════════ --}}
    <section class="offers-hero py-14 md:py-20 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-8 anim-fade-up">
                <a href="{{ route('home') }}" class="hover:text-[#D4A574] transition"><i class="fas fa-home text-xs"></i> Inicio</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-white font-medium">Ofertas</span>
            </div>

            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-10">
                <div class="space-y-5 max-w-xl anim-fade-up-2">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-white/10">
                        <span class="w-2 h-2 bg-rose-400 rounded-full animate-pulse"></span>
                        <span class="text-sm font-semibold text-rose-300">Ofertas Activas</span>
                    </div>
                    <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                        Hasta <span class="bg-gradient-to-r from-[#D4A574] to-[#e0b88a] bg-clip-text text-transparent">{{ $stats->max_discount ?? 0 }}%</span> OFF
                    </h1>
                    <p class="text-gray-300 text-lg leading-relaxed">
                        Aprovecha nuestras ofertas exclusivas en <strong class="text-white">{{ $stats->total_offers ?? 0 }} productos</strong> seleccionados. Precios especiales por tiempo limitado.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 md:gap-4 w-full lg:w-auto anim-fade-up-3">
                    <div class="hero-stat rounded-2xl p-5 md:p-6 text-center">
                        <p class="text-2xl md:text-3xl font-bold text-[#D4A574] mb-1">{{ $stats->total_offers ?? 0 }}</p>
                        <p class="text-xs text-gray-400 font-medium">Productos</p>
                    </div>
                    <div class="hero-stat rounded-2xl p-5 md:p-6 text-center">
                        <p class="text-2xl md:text-3xl font-bold text-rose-400 mb-1">{{ $stats->max_discount ?? 0 }}%</p>
                        <p class="text-xs text-gray-400 font-medium">Descuento Max</p>
                    </div>
                    <div class="hero-stat rounded-2xl p-5 md:p-6 text-center">
                        <p class="text-2xl md:text-3xl font-bold text-emerald-400 mb-1">S/ {{ number_format($stats->min_price ?? 0, 0) }}</p>
                        <p class="text-xs text-gray-400 font-medium">Desde</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ DISCOUNT TIERS ═══════════════════ --}}
    <section class="bg-white border-b border-gray-100 py-4 sticky top-20 z-20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 overflow-x-auto pb-1 scrollbar-hide">
                <span class="text-xs text-gray-400 font-bold uppercase tracking-widest whitespace-nowrap">Descuento:</span>
                <a href="{{ route('ofertas', request()->except(['discount_min', 'discount_max', 'page'])) }}"
                   class="tier-chip px-4 py-2 border border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ !request()->filled('discount_min') ? 'active' : '' }}">
                    <span>Todos ({{ $stats->total_offers ?? 0 }})</span>
                </a>
                @if(($discountTiers['50+'] ?? 0) > 0)
                    <a href="{{ route('ofertas', array_merge(request()->except(['discount_min', 'discount_max', 'page']), ['discount_min' => 50])) }}"
                       class="tier-chip px-4 py-2 border border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ request('discount_min') == 50 && !request()->filled('discount_max') ? 'active' : '' }}">
                        <i class="fas fa-fire text-rose-500 mr-1"></i><span>50%+ ({{ $discountTiers['50+'] }})</span>
                    </a>
                @endif
                @if(($discountTiers['30-49'] ?? 0) > 0)
                    <a href="{{ route('ofertas', array_merge(request()->except(['discount_min', 'discount_max', 'page']), ['discount_min' => 30, 'discount_max' => 49])) }}"
                       class="tier-chip px-4 py-2 border border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ request('discount_min') == 30 ? 'active' : '' }}">
                        <span>30-49% ({{ $discountTiers['30-49'] }})</span>
                    </a>
                @endif
                @if(($discountTiers['15-29'] ?? 0) > 0)
                    <a href="{{ route('ofertas', array_merge(request()->except(['discount_min', 'discount_max', 'page']), ['discount_min' => 15, 'discount_max' => 29])) }}"
                       class="tier-chip px-4 py-2 border border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ request('discount_min') == 15 ? 'active' : '' }}">
                        <span>15-29% ({{ $discountTiers['15-29'] }})</span>
                    </a>
                @endif
                @if(($discountTiers['1-14'] ?? 0) > 0)
                    <a href="{{ route('ofertas', array_merge(request()->except(['discount_min', 'discount_max', 'page']), ['discount_min' => 1, 'discount_max' => 14])) }}"
                       class="tier-chip px-4 py-2 border border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ request('discount_min') == 1 && request('discount_max') == 14 ? 'active' : '' }}">
                        <span>Hasta 14% ({{ $discountTiers['1-14'] }})</span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- ═══════════════════ MAIN CONTENT ═══════════════════ --}}
    <section class="py-8 lg:py-10 bg-[#FAF8F5] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form id="ofertasForm" method="GET" action="{{ route('ofertas') }}">
                @if(request()->filled('discount_min'))
                    <input type="hidden" name="discount_min" value="{{ request('discount_min') }}">
                @endif
                @if(request()->filled('discount_max'))
                    <input type="hidden" name="discount_max" value="{{ request('discount_max') }}">
                @endif

                <div class="flex flex-col lg:flex-row gap-8">

                    {{-- ═══ SIDEBAR FILTERS ═══ --}}
                    <aside class="lg:w-[280px] flex-shrink-0">
                        {{-- Mobile Filter Toggle --}}
                        <button type="button" class="lg:hidden w-full bg-white text-gray-900 py-3.5 rounded-2xl mb-5 flex items-center justify-center gap-3 border border-gray-200 hover:border-[#D4A574] hover:shadow-md transition-all font-medium" id="ofertasMobileFilterBtn">
                            <i class="fas fa-sliders-h text-[#D4A574]"></i>
                            Filtros y Ordenar
                            @if(request()->hasAny(['categories', 'price_min', 'price_max', 'in_stock']))
                                <span class="bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">!</span>
                            @endif
                        </button>

                        {{-- Filters Container --}}
                        <div class="ofertas-filters-mobile filter-sidebar lg:transform-none fixed lg:static inset-y-0 left-0 w-[320px] lg:w-auto bg-white lg:bg-transparent z-40 overflow-y-auto lg:overflow-visible shadow-2xl lg:shadow-none" id="ofertasFiltersContainer">
                            {{-- Mobile Header --}}
                            <div class="lg:hidden flex items-center justify-between px-6 py-5 border-b border-gray-100">
                                <h2 class="font-serif text-xl font-bold text-gray-900">Filtros</h2>
                                <button type="button" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition" id="ofertasCloseFiltersBtn">
                                    <i class="fas fa-times text-gray-600"></i>
                                </button>
                            </div>

                            <div class="p-6 lg:p-0 space-y-0">
                                <div class="bg-white lg:rounded-2xl lg:shadow-sm lg:border lg:border-gray-100 lg:overflow-hidden">

                                    {{-- Header --}}
                                    <div class="hidden lg:flex items-center justify-between p-5 border-b border-gray-100 bg-gradient-to-r from-[#FAF8F5] to-white">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-gradient-to-br from-[#D4A574] to-[#C39563] rounded-lg flex items-center justify-center">
                                                <i class="fas fa-sliders-h text-white text-xs"></i>
                                            </div>
                                            <h2 class="font-bold text-gray-900">Filtros</h2>
                                        </div>
                                        <a href="{{ route('ofertas') }}" class="text-xs text-[#D4A574] hover:text-[#C39563] font-semibold transition">Limpiar</a>
                                    </div>

                                    {{-- Sort --}}
                                    <div class="p-5 border-b border-gray-100">
                                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Ordenar por</h3>
                                        <select name="sort" class="sort-select w-full px-4 py-3 bg-[#FAF8F5] border border-gray-200 rounded-xl focus:outline-none focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 text-sm font-medium text-gray-700" id="sortSelect">
                                            <option value="discount_desc" {{ request('sort', 'discount_desc') === 'discount_desc' ? 'selected' : '' }}>Mayor Descuento</option>
                                            <option value="discount_asc" {{ request('sort') === 'discount_asc' ? 'selected' : '' }}>Menor Descuento</option>
                                            <option value="savings_desc" {{ request('sort') === 'savings_desc' ? 'selected' : '' }}>Mayor Ahorro (S/)</option>
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
                                                    <input type="checkbox" name="categories[]" value="{{ $category->slug }}" class="category-filter hidden" {{ $isChecked ? 'checked' : '' }}>
                                                    <span class="filter-chip flex-1 py-2.5 px-4 border border-gray-200 rounded-xl flex items-center justify-between text-sm {{ $isChecked ? 'active' : '' }}">
                                                        <span class="flex items-center gap-2">
                                                            <i class="{{ $category->icon }} text-sm {{ $isChecked ? '' : 'text-[#D4A574]' }}"></i>
                                                            <span class="font-medium">{{ $category->name }}</span>
                                                        </span>
                                                        <span class="text-xs {{ $isChecked ? 'text-white/70' : 'text-gray-400' }} font-medium">{{ $category->on_sale_count }}</span>
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
                                                    <input type="number" name="price_min" value="{{ request('price_min', '') }}" min="0" step="0.01"
                                                        class="w-full pl-9 pr-3 py-2.5 bg-[#FAF8F5] border border-gray-200 rounded-xl focus:outline-none focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 text-sm"
                                                        placeholder="{{ floor($priceRange->min_price ?? 0) }}" id="priceMin">
                                                </div>
                                                <span class="text-gray-300 text-sm">—</span>
                                                <div class="flex-1 relative">
                                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">S/</span>
                                                    <input type="number" name="price_max" value="{{ request('price_max', '') }}" min="0" step="0.01"
                                                        class="w-full pl-9 pr-3 py-2.5 bg-[#FAF8F5] border border-gray-200 rounded-xl focus:outline-none focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 text-sm"
                                                        placeholder="{{ ceil($priceRange->max_price ?? 500) }}" id="priceMax">
                                                </div>
                                            </div>
                                            <input type="range"
                                                min="{{ floor($priceRange->min_price ?? 0) }}"
                                                max="{{ ceil($priceRange->max_price ?? 500) }}"
                                                value="{{ request('price_max', ceil($priceRange->max_price ?? 500)) }}"
                                                class="price-range-slider w-full" id="priceSlider">
                                            <div class="flex justify-between text-xs text-gray-400 font-medium">
                                                <span>S/ {{ floor($priceRange->min_price ?? 0) }}</span>
                                                <span>S/ {{ ceil($priceRange->max_price ?? 500) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Availability --}}
                                    <div class="p-5">
                                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Disponibilidad</h3>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <div class="relative">
                                                <input type="checkbox" name="in_stock" value="1" class="availability-filter peer sr-only" {{ request()->boolean('in_stock') ? 'checked' : '' }}>
                                                <div class="w-10 h-6 bg-gray-200 rounded-full peer-checked:bg-gradient-to-r peer-checked:from-[#D4A574] peer-checked:to-[#C39563] transition-all"></div>
                                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm peer-checked:translate-x-4 transition-transform"></div>
                                            </div>
                                            <span class="text-sm text-gray-700 font-medium group-hover:text-gray-900">En Stock</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Mobile Apply --}}
                                <button type="submit" class="lg:hidden w-full mt-5 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white py-3.5 rounded-2xl font-semibold hover:shadow-lg transition-all">
                                    Aplicar Filtros
                                </button>
                                <a href="{{ route('ofertas') }}" class="lg:hidden block text-center mt-3 text-sm text-gray-500 hover:text-gray-900 font-medium">
                                    Limpiar todos los filtros
                                </a>
                            </div>
                        </div>
                    </aside>

                    {{-- ═══ PRODUCTS AREA ═══ --}}
                    <div class="flex-1 min-w-0">

                        {{-- Active Filters --}}
                        @if(request()->hasAny(['categories', 'price_min', 'price_max', 'in_stock']))
                            <div class="flex flex-wrap items-center gap-2 mb-6 reveal-offer">
                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider mr-1">Filtros:</span>
                                @if(request()->filled('categories'))
                                    @php
                                        $selectedCats = request()->get('categories', []);
                                        if (is_string($selectedCats)) $selectedCats = explode(',', $selectedCats);
                                    @endphp
                                    @foreach($categories->whereIn('slug', $selectedCats) as $cat)
                                        <span class="filter-tag inline-flex items-center gap-1.5 text-white text-xs px-3 py-1.5 rounded-full font-medium cursor-default">
                                            <i class="{{ $cat->icon }} text-[10px]"></i> {{ $cat->name }}
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
                                <a href="{{ route('ofertas') }}" class="text-xs text-gray-400 hover:text-[#D4A574] font-medium transition ml-1 flex items-center gap-1">
                                    <i class="fas fa-times text-[9px]"></i> Limpiar
                                </a>
                            </div>
                        @endif

                        {{-- Results Info --}}
                        <div class="flex items-center justify-between mb-6 reveal-offer">
                            <p class="text-sm text-gray-500">
                                Mostrando <span class="font-semibold text-gray-900">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</span> de <span class="font-semibold text-gray-900">{{ $products->total() }}</span> ofertas
                            </p>
                        </div>

                        @if($products->isEmpty())
                            {{-- Empty State --}}
                            <div class="text-center py-24 reveal-offer">
                                <div class="w-24 h-24 bg-gradient-to-br from-rose-100 to-[#D4A574]/10 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-tags text-4xl text-[#D4A574]/40"></i>
                                </div>
                                <h3 class="font-serif text-2xl font-bold text-gray-900 mb-3">No hay ofertas disponibles</h3>
                                <p class="text-gray-500 mb-8 max-w-sm mx-auto">No encontramos ofertas con los filtros seleccionados. Intenta con otros filtros.</p>
                                <a href="{{ route('ofertas') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-8 py-3.5 rounded-full hover:shadow-lg hover:scale-105 transition-all font-semibold">
                                    <i class="fas fa-redo text-sm"></i> Ver todas las ofertas
                                </a>
                            </div>
                        @else
                            {{-- Products Grid --}}
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-12">
                                @foreach($products as $index => $product)
                                    <div class="product-card-offer bg-white rounded-2xl overflow-hidden border border-gray-100/80 group reveal-offer" style="transition-delay: {{ min($index * 50, 300) }}ms">
                                        <a href="{{ route('product.show', $product->slug) }}" class="block relative overflow-hidden">
                                            <img src="{{ $product->primaryImage?->image_url ?? 'https://via.placeholder.com/400x300?text=Sin+Imagen' }}"
                                                 alt="{{ $product->primaryImage?->alt_text ?? $product->name }}"
                                                 class="product-img w-full h-52 sm:h-72 lg:h-80 object-cover"
                                                 loading="lazy">

                                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                                            {{-- Discount Badge --}}
                                            <div class="absolute top-3 left-3">
                                                <div class="discount-badge text-white px-3 py-1.5 rounded-xl text-sm font-bold shadow-lg flex items-center gap-1">
                                                    <i class="fas fa-arrow-down text-[10px]"></i>
                                                    -{{ $product->discount_percentage }}%
                                                </div>
                                            </div>

                                            {{-- Savings Badge --}}
                                            <div class="absolute top-3 right-3 savings-badge text-white px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-semibold shadow-md">
                                                Ahorras S/ {{ number_format($product->price - $product->sale_price, 2) }}
                                            </div>

                                            {{-- Wishlist --}}
                                            <button type="button" class="wishlist-btn absolute top-12 right-3 sm:top-14 sm:right-3 w-9 h-9 sm:w-10 sm:h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-md" data-product-id="{{ $product->id }}">
                                                <i class="far fa-heart text-xs sm:text-sm"></i>
                                            </button>

                                            {{-- Low Stock --}}
                                            @if($product->stock <= 5 && $product->stock > 0)
                                                <div class="absolute bottom-3 left-3 bg-amber-500/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold flex items-center gap-1">
                                                    <i class="fas fa-bolt text-[9px]"></i> ¡Últimas {{ $product->stock }} uds!
                                                </div>
                                            @endif

                                            {{-- Quick Add --}}
                                            <div class="quick-actions absolute bottom-3 right-3 hidden sm:block">
                                                <button type="button"
                                                        class="add-to-cart-btn bg-white/95 backdrop-blur-sm text-gray-900 hover:bg-[#D4A574] hover:text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-all duration-300"
                                                        data-product-id="{{ $product->id }}" title="Agregar al carrito">
                                                    <i class="fas fa-shopping-bag text-sm"></i>
                                                </button>
                                            </div>
                                        </a>

                                        <div class="p-4 sm:p-5">
                                            <p class="text-[10px] sm:text-xs text-[#D4A574] font-semibold uppercase tracking-wider mb-1">{{ $product->category?->name }}</p>
                                            <a href="{{ route('product.show', $product->slug) }}" class="block">
                                                <h3 class="font-semibold text-sm sm:text-base mb-2 line-clamp-2 group-hover:text-[#D4A574] transition-colors duration-200 leading-snug">{{ $product->name }}</h3>
                                            </a>
                                            <div class="flex items-end gap-2 mb-1">
                                                <span class="text-lg sm:text-xl font-bold text-gray-900 leading-none">S/ {{ number_format($product->sale_price, 2) }}</span>
                                                <span class="text-xs text-gray-400 line-through leading-none pb-0.5">S/ {{ number_format($product->price, 2) }}</span>
                                            </div>
                                            <p class="text-xs text-emerald-600 font-semibold mb-3 sm:mb-4 flex items-center gap-1">
                                                <i class="fas fa-piggy-bank text-[10px]"></i>
                                                Ahorras S/ {{ number_format($product->price - $product->sale_price, 2) }}
                                            </p>

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
                                <div class="flex items-center justify-center gap-1.5 sm:gap-2 reveal-offer">
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
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden transition-opacity" id="ofertasFilterOverlay"></div>
@endsection

@section('scripts')
<script>
    // ── Scroll Reveal ──
    (function() {
        var els = document.querySelectorAll('.reveal-offer');
        function check() {
            els.forEach(function(el) {
                if (el.getBoundingClientRect().top < window.innerHeight - 60) el.classList.add('visible');
            });
        }
        window.addEventListener('scroll', check);
        check();
    })();

    // ── Mobile Filters ──
    var ofertasMobileFilterBtn = document.getElementById('ofertasMobileFilterBtn');
    var ofertasFiltersContainer = document.getElementById('ofertasFiltersContainer');
    var ofertasCloseFiltersBtn = document.getElementById('ofertasCloseFiltersBtn');
    var ofertasFilterOverlay = document.getElementById('ofertasFilterOverlay');

    ofertasMobileFilterBtn.addEventListener('click', function() {
        ofertasFiltersContainer.classList.add('active');
        ofertasFilterOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });
    ofertasCloseFiltersBtn.addEventListener('click', closeOfertasFilters);
    ofertasFilterOverlay.addEventListener('click', closeOfertasFilters);

    function closeOfertasFilters() {
        ofertasFiltersContainer.classList.remove('active');
        ofertasFilterOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ── Form Logic ──
    var ofertasForm = document.getElementById('ofertasForm');
    var sortSelect = document.getElementById('sortSelect');
    var priceSlider = document.getElementById('priceSlider');
    var priceMax = document.getElementById('priceMax');
    var priceMin = document.getElementById('priceMin');

    document.querySelectorAll('.category-filter').forEach(function(cb) {
        cb.addEventListener('change', function() {
            this.nextElementSibling.classList.toggle('active', this.checked);
            if (window.innerWidth >= 1024) ofertasForm.submit();
        });
    });

    document.querySelectorAll('.availability-filter').forEach(function(cb) {
        cb.addEventListener('change', function() {
            if (window.innerWidth >= 1024) ofertasForm.submit();
        });
    });

    sortSelect.addEventListener('change', function() { ofertasForm.submit(); });

    priceSlider.addEventListener('input', function() { priceMax.value = this.value; });
    priceSlider.addEventListener('change', function() {
        priceMax.value = this.value;
        if (window.innerWidth >= 1024) ofertasForm.submit();
    });

    [priceMin, priceMax].forEach(function(input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); if (window.innerWidth >= 1024) ofertasForm.submit(); }
        });
        input.addEventListener('change', function() {
            if (this === priceMax) priceSlider.value = this.value;
            if (window.innerWidth >= 1024) ofertasForm.submit();
        });
    });

    ofertasForm.addEventListener('submit', function() {
        this.querySelectorAll('input, select').forEach(function(input) {
            if (input.type === 'checkbox' && !input.checked) return;
            if (!input.value || input.value === '' || input.value === '0') input.removeAttribute('name');
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

                if (typeof showToast === 'function') showToast('¡Producto agregado al carrito!');

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
