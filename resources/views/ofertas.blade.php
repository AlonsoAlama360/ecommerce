@extends('layouts.app')

@section('title', 'Ofertas - Romantic Gifts')

@section('styles')
    .filter-chip.active {
        background-color: #2C2C2C;
        color: white;
        border-color: #2C2C2C;
    }

    .price-range-slider {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 6px;
        border-radius: 5px;
        background: #e5e7eb;
        outline: none;
    }

    .price-range-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #2C2C2C;
        cursor: pointer;
    }

    .price-range-slider::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #2C2C2C;
        cursor: pointer;
        border: none;
    }

    .ofertas-filters-mobile {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .ofertas-filters-mobile.active {
        transform: translateX(0);
    }

    .offers-hero {
        background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 50%, #2C2C2C 100%);
        position: relative;
        overflow: hidden;
    }

    .offers-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(212,165,116,0.1) 0%, transparent 50%);
        animation: pulse-glow 4s ease-in-out infinite;
    }

    @keyframes pulse-glow {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 1; }
    }

    .discount-tier-chip {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .discount-tier-chip:hover,
    .discount-tier-chip.active {
        background-color: #D4A574;
        color: white;
        border-color: #D4A574;
    }

    .savings-badge {
        background: linear-gradient(135deg, #E8B4B8 0%, #D4A574 100%);
    }
@endsection

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition">Inicio</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">Ofertas</span>
            </div>
        </div>
    </div>

    <!-- Hero Banner -->
    <section class="offers-hero py-12 md:py-16 text-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-4 text-center md:text-left">
                    <div class="flex items-center gap-3 justify-center md:justify-start">
                        <span class="bg-[#E8B4B8] text-white px-4 py-1.5 rounded-full text-sm font-bold tracking-wide uppercase animate-pulse">
                            <i class="fas fa-fire mr-1"></i> Ofertas Activas
                        </span>
                    </div>
                    <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                        Hasta <span class="text-[#D4A574]">{{ $stats->max_discount ?? 0 }}%</span> de Descuento
                    </h1>
                    <p class="text-gray-300 text-lg max-w-lg">
                        Aprovecha nuestras ofertas exclusivas en {{ $stats->total_offers ?? 0 }} productos seleccionados.
                    </p>
                </div>
                <div class="grid grid-cols-3 gap-4 md:gap-6 text-center">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 md:p-6">
                        <p class="text-2xl md:text-3xl font-bold text-[#D4A574]">{{ $stats->total_offers ?? 0 }}</p>
                        <p class="text-xs md:text-sm text-gray-300 mt-1">Productos</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 md:p-6">
                        <p class="text-2xl md:text-3xl font-bold text-[#E8B4B8]">{{ $stats->max_discount ?? 0 }}%</p>
                        <p class="text-xs md:text-sm text-gray-300 mt-1">Descuento Max</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 md:p-6">
                        <p class="text-2xl md:text-3xl font-bold text-green-400">S/ {{ number_format($stats->min_price ?? 0, 0) }}</p>
                        <p class="text-xs md:text-sm text-gray-300 mt-1">Desde</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Discount Tier Chips -->
    <section class="bg-white border-b border-gray-200 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 overflow-x-auto pb-1">
                <span class="text-sm text-gray-500 whitespace-nowrap font-medium">Filtrar por descuento:</span>
                <a href="{{ route('ofertas', request()->except(['discount_min', 'discount_max', 'page'])) }}"
                   class="discount-tier-chip px-4 py-2 border-2 border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ !request()->filled('discount_min') ? 'active' : '' }}">
                    Todos ({{ $stats->total_offers ?? 0 }})
                </a>
                @if(($discountTiers['50+'] ?? 0) > 0)
                    <a href="{{ route('ofertas', array_merge(request()->except(['discount_min', 'discount_max', 'page']), ['discount_min' => 50])) }}"
                       class="discount-tier-chip px-4 py-2 border-2 border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ request('discount_min') == 50 && !request()->filled('discount_max') ? 'active' : '' }}">
                        <i class="fas fa-fire text-rose-500 mr-1"></i> 50%+ ({{ $discountTiers['50+'] }})
                    </a>
                @endif
                @if(($discountTiers['30-49'] ?? 0) > 0)
                    <a href="{{ route('ofertas', array_merge(request()->except(['discount_min', 'discount_max', 'page']), ['discount_min' => 30, 'discount_max' => 49])) }}"
                       class="discount-tier-chip px-4 py-2 border-2 border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ request('discount_min') == 30 ? 'active' : '' }}">
                        30-49% ({{ $discountTiers['30-49'] }})
                    </a>
                @endif
                @if(($discountTiers['15-29'] ?? 0) > 0)
                    <a href="{{ route('ofertas', array_merge(request()->except(['discount_min', 'discount_max', 'page']), ['discount_min' => 15, 'discount_max' => 29])) }}"
                       class="discount-tier-chip px-4 py-2 border-2 border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ request('discount_min') == 15 ? 'active' : '' }}">
                        15-29% ({{ $discountTiers['15-29'] }})
                    </a>
                @endif
                @if(($discountTiers['1-14'] ?? 0) > 0)
                    <a href="{{ route('ofertas', array_merge(request()->except(['discount_min', 'discount_max', 'page']), ['discount_min' => 1, 'discount_max' => 14])) }}"
                       class="discount-tier-chip px-4 py-2 border-2 border-gray-200 rounded-full text-sm font-medium whitespace-nowrap {{ request('discount_min') == 1 && request('discount_max') == 14 ? 'active' : '' }}">
                        Hasta 14% ({{ $discountTiers['1-14'] }})
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section class="py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form id="ofertasForm" method="GET" action="{{ route('ofertas') }}">
                {{-- Preservar filtros de descuento del chip activo --}}
                @if(request()->filled('discount_min'))
                    <input type="hidden" name="discount_min" value="{{ request('discount_min') }}">
                @endif
                @if(request()->filled('discount_max'))
                    <input type="hidden" name="discount_max" value="{{ request('discount_max') }}">
                @endif

                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Filters Sidebar -->
                    <aside class="lg:w-80 flex-shrink-0">
                        <!-- Mobile Filter Toggle -->
                        <button type="button" class="lg:hidden w-full bg-gray-900 text-white py-3 rounded-full mb-4 flex items-center justify-center gap-2" id="ofertasMobileFilterBtn">
                            <i class="fas fa-filter"></i>
                            Filtros
                            @if(request()->hasAny(['categories', 'price_min', 'price_max', 'in_stock']))
                                <span class="bg-white text-gray-900 text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">!</span>
                            @endif
                        </button>

                        <!-- Filters Container -->
                        <div class="ofertas-filters-mobile lg:transform-none fixed lg:static inset-y-0 left-0 w-80 bg-white lg:bg-transparent z-40 overflow-y-auto lg:overflow-visible p-6 lg:p-0 shadow-2xl lg:shadow-none" id="ofertasFiltersContainer">
                            <!-- Close button for mobile -->
                            <button type="button" class="lg:hidden absolute top-4 right-4 text-gray-400 hover:text-gray-600" id="ofertasCloseFiltersBtn">
                                <i class="fas fa-times text-xl"></i>
                            </button>

                            <div class="space-y-6">
                                <!-- Filter Header -->
                                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                                    <h2 class="font-serif text-2xl font-bold text-gray-900">Filtros</h2>
                                    <a href="{{ route('ofertas') }}" class="text-sm text-gray-600 hover:text-gray-900 transition">Limpiar todo</a>
                                </div>

                                <!-- Category Filter -->
                                <div class="pb-6 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-900 mb-4">
                                        <span>Categoría</span>
                                    </h3>
                                    <div class="space-y-3">
                                        @foreach($categories as $category)
                                            @php
                                                $selectedCats = request()->get('categories', []);
                                                if (is_string($selectedCats)) $selectedCats = explode(',', $selectedCats);
                                                $isChecked = in_array($category->slug, $selectedCats);
                                            @endphp
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="checkbox"
                                                       name="categories[]"
                                                       value="{{ $category->slug }}"
                                                       class="category-filter hidden"
                                                       {{ $isChecked ? 'checked' : '' }}>
                                                <span class="filter-chip flex-1 py-2 px-4 border-2 border-gray-200 rounded-lg transition group-hover:border-gray-400 cursor-pointer flex items-center justify-between {{ $isChecked ? 'active' : '' }}">
                                                    <span><i class="{{ $category->icon }} mr-2 {{ $isChecked ? '' : 'accent-color' }}"></i>{{ $category->name }}</span>
                                                    <span class="text-xs {{ $isChecked ? 'text-gray-300' : 'text-gray-400' }}">({{ $category->on_sale_count }})</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Price Range Filter -->
                                <div class="pb-6 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-900 mb-4">
                                        <span>Rango de Precio</span>
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-1">
                                                <label class="text-xs text-gray-600 mb-1 block">Mínimo</label>
                                                <input type="number"
                                                       name="price_min"
                                                       value="{{ request('price_min', '') }}"
                                                       min="0"
                                                       step="0.01"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-900 text-sm"
                                                       placeholder="S/ {{ floor($priceRange->min_price ?? 0) }}"
                                                       id="priceMin">
                                            </div>
                                            <div class="flex-1">
                                                <label class="text-xs text-gray-600 mb-1 block">Máximo</label>
                                                <input type="number"
                                                       name="price_max"
                                                       value="{{ request('price_max', '') }}"
                                                       min="0"
                                                       step="0.01"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-900 text-sm"
                                                       placeholder="S/ {{ ceil($priceRange->max_price ?? 500) }}"
                                                       id="priceMax">
                                            </div>
                                        </div>
                                        <input type="range"
                                               min="{{ floor($priceRange->min_price ?? 0) }}"
                                               max="{{ ceil($priceRange->max_price ?? 500) }}"
                                               value="{{ request('price_max', ceil($priceRange->max_price ?? 500)) }}"
                                               class="price-range-slider w-full"
                                               id="priceSlider">
                                        <div class="flex justify-between text-sm text-gray-600">
                                            <span>S/ {{ floor($priceRange->min_price ?? 0) }}</span>
                                            <span>S/ {{ ceil($priceRange->max_price ?? 500) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Availability Filter -->
                                <div class="pb-6">
                                    <h3 class="font-semibold text-gray-900 mb-4">
                                        <span>Disponibilidad</span>
                                    </h3>
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox"
                                                   name="in_stock"
                                                   value="1"
                                                   class="availability-filter w-5 h-5 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                                   {{ request()->boolean('in_stock') ? 'checked' : '' }}>
                                            <span class="text-gray-700">En Stock</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Apply button for mobile -->
                                <button type="submit" class="lg:hidden w-full bg-gray-900 text-white py-3 rounded-full font-medium hover:bg-gray-800 transition">
                                    Aplicar Filtros
                                </button>
                            </div>
                        </div>
                    </aside>

                    <!-- Products Grid -->
                    <div class="flex-1">
                        <!-- Toolbar -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                            <div>
                                <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-2">Ofertas Especiales</h2>
                                <p class="text-gray-600">
                                    Mostrando {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} de {{ $products->total() }} ofertas
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-900 bg-white text-sm" id="sortSelect">
                                    <option value="discount_desc" {{ request('sort', 'discount_desc') === 'discount_desc' ? 'selected' : '' }}>Mayor Descuento</option>
                                    <option value="discount_asc" {{ request('sort') === 'discount_asc' ? 'selected' : '' }}>Menor Descuento</option>
                                    <option value="savings_desc" {{ request('sort') === 'savings_desc' ? 'selected' : '' }}>Mayor Ahorro (S/)</option>
                                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Más Nuevos</option>
                                </select>
                            </div>
                        </div>

                        <!-- Active Filters Tags -->
                        @if(request()->hasAny(['categories', 'price_min', 'price_max', 'in_stock']))
                            <div class="flex flex-wrap items-center gap-2 mb-6">
                                <span class="text-sm text-gray-500">Filtros activos:</span>
                                @if(request()->filled('categories'))
                                    @php
                                        $selectedCats = request()->get('categories', []);
                                        if (is_string($selectedCats)) $selectedCats = explode(',', $selectedCats);
                                    @endphp
                                    @foreach($categories->whereIn('slug', $selectedCats) as $cat)
                                        <span class="inline-flex items-center gap-1 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-full">
                                            {{ $cat->name }}
                                            <a href="{{ request()->fullUrlWithoutQuery('categories') }}" class="hover:text-gray-300">
                                                <i class="fas fa-times text-[10px]"></i>
                                            </a>
                                        </span>
                                    @endforeach
                                @endif
                                @if(request()->filled('price_min') || request()->filled('price_max'))
                                    <span class="inline-flex items-center gap-1 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-full">
                                        Precio: S/ {{ request('price_min', floor($priceRange->min_price ?? 0)) }} - S/ {{ request('price_max', ceil($priceRange->max_price ?? 500)) }}
                                    </span>
                                @endif
                                @if(request()->boolean('in_stock'))
                                    <span class="inline-flex items-center gap-1 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-full">
                                        En Stock
                                    </span>
                                @endif
                                <a href="{{ route('ofertas') }}" class="text-sm text-gray-500 hover:text-gray-900 underline ml-2">Limpiar</a>
                            </div>
                        @endif

                        @if($products->isEmpty())
                            <!-- Empty State -->
                            <div class="text-center py-20">
                                <i class="fas fa-tags text-6xl text-gray-300 mb-6"></i>
                                <h3 class="font-serif text-2xl font-bold text-gray-900 mb-3">No hay ofertas disponibles</h3>
                                <p class="text-gray-600 mb-6">No encontramos ofertas con los filtros seleccionados. Intenta con otros filtros.</p>
                                <a href="{{ route('ofertas') }}" class="inline-block bg-gray-900 text-white px-8 py-3 rounded-full hover:bg-gray-800 transition font-medium">
                                    Ver todas las ofertas
                                </a>
                            </div>
                        @else
                            <!-- Products Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-12">
                                @foreach($products as $product)
                                    <div class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-gray-100">
                                        <a href="{{ route('product.show', $product->slug) }}" class="block relative overflow-hidden">
                                            <img src="{{ $product->primaryImage?->image_url ?? 'https://via.placeholder.com/400x300?text=Sin+Imagen' }}"
                                                 alt="{{ $product->primaryImage?->alt_text ?? $product->name }}"
                                                 class="w-full h-52 sm:h-72 lg:h-80 object-cover group-hover:scale-105 transition-transform duration-500"
                                                 loading="lazy">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                            {{-- Badge de descuento prominente --}}
                                            <div class="absolute top-3 left-3 sm:top-4 sm:left-4">
                                                <div class="bg-rose-500 text-white px-3 py-2 rounded-xl text-sm sm:text-base font-bold shadow-lg">
                                                    -{{ $product->discount_percentage }}%
                                                </div>
                                            </div>

                                            {{-- Badge de ahorro --}}
                                            <div class="absolute top-3 right-3 sm:top-4 sm:right-4 savings-badge text-white px-2.5 py-1.5 rounded-full text-xs font-semibold shadow-md">
                                                Ahorras S/ {{ number_format($product->price - $product->sale_price, 2) }}
                                            </div>

                                            {{-- Wishlist --}}
                                            <button type="button" class="wishlist-btn absolute top-12 right-3 sm:top-14 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-md" data-product-id="{{ $product->id }}">
                                                <i class="far fa-heart text-sm sm:text-base"></i>
                                            </button>

                                            @if($product->stock <= 5 && $product->stock > 0)
                                                <div class="absolute bottom-3 left-3 sm:bottom-4 sm:left-4 bg-amber-500 text-white px-2.5 py-1 rounded-full text-xs font-semibold shadow-md">
                                                    <i class="fas fa-bolt mr-1"></i>Ultimas {{ $product->stock }} uds!
                                                </div>
                                            @endif
                                        </a>
                                        <div class="p-4 sm:p-5">
                                            <p class="text-xs text-gray-400 mb-1">{{ $product->category?->name }}</p>
                                            <a href="{{ route('product.show', $product->slug) }}" class="block">
                                                <h3 class="font-semibold text-sm sm:text-base mb-2 line-clamp-2 group-hover:text-[#D4A574] transition-colors duration-200">{{ $product->name }}</h3>
                                            </a>
                                            <div class="flex items-end gap-2 mb-1">
                                                <span class="text-lg sm:text-2xl font-bold text-gray-900 leading-none">S/ {{ number_format($product->sale_price, 2) }}</span>
                                                <span class="text-xs sm:text-sm text-gray-400 line-through leading-none pb-0.5">S/ {{ number_format($product->price, 2) }}</span>
                                            </div>
                                            <p class="text-xs text-green-600 font-medium mb-3 sm:mb-4">
                                                <i class="fas fa-tag mr-1"></i>Ahorras S/ {{ number_format($product->price - $product->sale_price, 2) }}
                                            </p>
                                            <button type="button"
                                                    class="add-to-cart-btn w-full bg-gray-900 text-white py-2.5 sm:py-3 rounded-full hover:bg-gray-800 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 text-sm sm:text-base font-medium"
                                                    data-product-id="{{ $product->id }}">
                                                <i class="fas fa-shopping-bag"></i>
                                                <span class="hidden sm:inline">Agregar al Carrito</span>
                                                <span class="sm:hidden">Agregar</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($products->hasPages())
                                <div class="flex items-center justify-center gap-1 sm:gap-2">
                                    {{-- Previous --}}
                                    @if($products->onFirstPage())
                                        <span class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-300 cursor-not-allowed">
                                            <i class="fas fa-chevron-left text-sm"></i>
                                        </span>
                                    @else
                                        <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition">
                                            <i class="fas fa-chevron-left text-sm"></i>
                                        </a>
                                    @endif

                                    {{-- Pages --}}
                                    @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                                        @if($page == $products->currentPage())
                                            <span class="w-10 h-10 bg-gray-900 text-white rounded-lg flex items-center justify-center font-medium text-sm">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition text-sm">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    @if($products->currentPage() + 2 < $products->lastPage())
                                        <span class="px-1 text-gray-400">...</span>
                                        <a href="{{ $products->url($products->lastPage()) }}" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition text-sm">{{ $products->lastPage() }}</a>
                                    @endif

                                    {{-- Next --}}
                                    @if($products->hasMorePages())
                                        <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition">
                                            <i class="fas fa-chevron-right text-sm"></i>
                                        </a>
                                    @else
                                        <span class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-300 cursor-not-allowed">
                                            <i class="fas fa-chevron-right text-sm"></i>
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

    <!-- Mobile Filter Overlay -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 hidden" id="ofertasFilterOverlay"></div>
@endsection

@section('scripts')
<script>
    // Mobile Filters
    const ofertasMobileFilterBtn = document.getElementById('ofertasMobileFilterBtn');
    const ofertasFiltersContainer = document.getElementById('ofertasFiltersContainer');
    const ofertasCloseFiltersBtn = document.getElementById('ofertasCloseFiltersBtn');
    const ofertasFilterOverlay = document.getElementById('ofertasFilterOverlay');

    ofertasMobileFilterBtn.addEventListener('click', () => {
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

    // Auto-submit on filter change (desktop only)
    const ofertasForm = document.getElementById('ofertasForm');
    const sortSelect = document.getElementById('sortSelect');
    const priceSlider = document.getElementById('priceSlider');
    const priceMax = document.getElementById('priceMax');
    const priceMin = document.getElementById('priceMin');

    // Category checkboxes - auto submit on desktop
    document.querySelectorAll('.category-filter').forEach(cb => {
        cb.addEventListener('change', function() {
            const chip = this.nextElementSibling;
            chip.classList.toggle('active', this.checked);
            if (window.innerWidth >= 1024) ofertasForm.submit();
        });
    });

    // Availability checkboxes
    document.querySelectorAll('.availability-filter').forEach(cb => {
        cb.addEventListener('change', function() {
            if (window.innerWidth >= 1024) ofertasForm.submit();
        });
    });

    // Sort select
    sortSelect.addEventListener('change', () => ofertasForm.submit());

    // Price slider syncs with max input
    priceSlider.addEventListener('input', function() {
        priceMax.value = this.value;
    });

    priceSlider.addEventListener('change', function() {
        priceMax.value = this.value;
        if (window.innerWidth >= 1024) ofertasForm.submit();
    });

    // Price inputs - submit on Enter or blur
    [priceMin, priceMax].forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (window.innerWidth >= 1024) ofertasForm.submit();
            }
        });
        input.addEventListener('change', function() {
            if (this === priceMax) priceSlider.value = this.value;
            if (window.innerWidth >= 1024) ofertasForm.submit();
        });
    });

    // Remove empty fields before submit to keep URL clean
    ofertasForm.addEventListener('submit', function() {
        this.querySelectorAll('input, select').forEach(input => {
            if (input.type === 'checkbox' && !input.checked) return;
            if (!input.value || input.value === '' || input.value === '0') {
                input.removeAttribute('name');
            }
        });
    });

    // Add to Cart AJAX
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const originalHTML = this.innerHTML;

            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(r => r.json())
            .then(data => {
                this.innerHTML = '<i class="fas fa-check"></i> <span>Agregado!</span>';
                this.classList.remove('bg-gray-900');
                this.classList.add('bg-green-600');

                document.querySelectorAll('.cart-badge').forEach(b => {
                    b.textContent = data.cart_count;
                    b.style.display = data.cart_count > 0 ? 'flex' : 'none';
                });

                if (typeof showToast === 'function') {
                    showToast('Producto agregado al carrito!');
                }

                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.classList.remove('bg-green-600');
                    this.classList.add('bg-gray-900');
                    this.disabled = false;
                }, 1500);
            })
            .catch(() => {
                this.innerHTML = originalHTML;
                this.disabled = false;
            });
        });
    });
</script>
@endsection
