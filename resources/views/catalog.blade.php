@extends('layouts.app')

@section('title', 'Catálogo - Romantic Gifts')

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

    .catalog-filters-mobile {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .catalog-filters-mobile.active {
        transform: translateX(0);
    }
@endsection

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition">Inicio</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">Catálogo</span>
            </div>
        </div>
    </div>

    <!-- Catalog Section -->
    <section class="py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form id="catalogForm" method="GET" action="{{ route('catalog') }}">
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Filters Sidebar -->
                    <aside class="lg:w-80 flex-shrink-0">
                        <!-- Mobile Filter Toggle -->
                        <button type="button" class="lg:hidden w-full bg-gray-900 text-white py-3 rounded-full mb-4 flex items-center justify-center gap-2" id="catalogMobileFilterBtn">
                            <i class="fas fa-filter"></i>
                            Filtros
                            @if(request()->hasAny(['categories', 'price_min', 'price_max', 'in_stock', 'on_sale']))
                                <span class="bg-white text-gray-900 text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">!</span>
                            @endif
                        </button>

                        <!-- Filters Container -->
                        <div class="catalog-filters-mobile lg:transform-none fixed lg:static inset-y-0 left-0 w-80 bg-white lg:bg-transparent z-40 overflow-y-auto lg:overflow-visible p-6 lg:p-0 shadow-2xl lg:shadow-none" id="catalogFiltersContainer">
                            <!-- Close button for mobile -->
                            <button type="button" class="lg:hidden absolute top-4 right-4 text-gray-400 hover:text-gray-600" id="catalogCloseFiltersBtn">
                                <i class="fas fa-times text-xl"></i>
                            </button>

                            <div class="space-y-6">
                                <!-- Filter Header -->
                                <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                                    <h2 class="font-serif text-2xl font-bold text-gray-900">Filtros</h2>
                                    <a href="{{ route('catalog') }}" class="text-sm text-gray-600 hover:text-gray-900 transition">Limpiar todo</a>
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
                                                    <span class="text-xs {{ $isChecked ? 'text-gray-300' : 'text-gray-400' }}">({{ $category->products_count }})</span>
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
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox"
                                                   name="on_sale"
                                                   value="1"
                                                   class="availability-filter w-5 h-5 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                                   {{ request()->boolean('on_sale') ? 'checked' : '' }}>
                                            <span class="text-gray-700">En Oferta</span>
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
                                <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-2">Todos los Productos</h1>
                                <p class="text-gray-600">
                                    Mostrando {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} de {{ $products->total() }} productos
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-900 bg-white text-sm" id="sortSelect">
                                    <option value="relevant" {{ request('sort', 'relevant') === 'relevant' ? 'selected' : '' }}>Más Relevantes</option>
                                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Más Nuevos</option>
                                </select>
                            </div>
                        </div>

                        <!-- Active Filters Tags -->
                        @if(request()->hasAny(['categories', 'price_min', 'price_max', 'in_stock', 'on_sale']))
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
                                @if(request()->boolean('on_sale'))
                                    <span class="inline-flex items-center gap-1 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-full">
                                        En Oferta
                                    </span>
                                @endif
                                <a href="{{ route('catalog') }}" class="text-sm text-gray-500 hover:text-gray-900 underline ml-2">Limpiar</a>
                            </div>
                        @endif

                        @if($products->isEmpty())
                            <!-- Empty State -->
                            <div class="text-center py-20">
                                <i class="fas fa-search text-6xl text-gray-300 mb-6"></i>
                                <h3 class="font-serif text-2xl font-bold text-gray-900 mb-3">No encontramos productos</h3>
                                <p class="text-gray-600 mb-6">Intenta cambiar los filtros o explorar otras categorías.</p>
                                <a href="{{ route('catalog') }}" class="inline-block bg-gray-900 text-white px-8 py-3 rounded-full hover:bg-gray-800 transition font-medium">
                                    Ver todos los productos
                                </a>
                            </div>
                        @else
                            <!-- Products Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-12">
                                @foreach($products as $product)
                                    <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg">
                                        <div class="relative">
                                            <img src="{{ $product->primaryImage?->image_url ?? 'https://via.placeholder.com/400x300?text=Sin+Imagen' }}"
                                                 alt="{{ $product->primaryImage?->alt_text ?? $product->name }}"
                                                 class="w-full h-52 sm:h-72 lg:h-80 object-cover"
                                                 loading="lazy">
                                            @if($product->discount_percentage)
                                                <div class="absolute top-3 left-3 sm:top-4 sm:left-4 badge-discount text-white px-2.5 py-1 rounded-full text-xs sm:text-sm font-semibold">
                                                    -{{ $product->discount_percentage }}%
                                                </div>
                                            @endif
                                            <button type="button" class="absolute top-3 right-3 sm:top-4 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition shadow-md">
                                                <i class="far fa-heart text-sm sm:text-base"></i>
                                            </button>
                                        </div>
                                        <div class="p-4 sm:p-6">
                                            <p class="text-xs text-gray-400 mb-1">{{ $product->category?->name }}</p>
                                            <h3 class="font-semibold text-sm sm:text-lg mb-2 line-clamp-2">{{ $product->name }}</h3>
                                            <div class="flex items-center gap-2 mb-3 sm:mb-4">
                                                <span class="text-lg sm:text-2xl font-bold text-gray-900">S/ {{ number_format($product->current_price, 2) }}</span>
                                                @if($product->sale_price && $product->sale_price < $product->price)
                                                    <span class="text-sm sm:text-lg text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</span>
                                                @endif
                                            </div>
                                            <button type="button" class="w-full bg-gray-900 text-white py-2.5 sm:py-3 rounded-full hover:bg-gray-800 transition flex items-center justify-center gap-2 text-sm sm:text-base font-medium">
                                                <i class="fas fa-shopping-cart"></i>
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
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 hidden" id="catalogFilterOverlay"></div>
@endsection

@section('scripts')
<script>
    // Mobile Filters
    const catalogMobileFilterBtn = document.getElementById('catalogMobileFilterBtn');
    const catalogFiltersContainer = document.getElementById('catalogFiltersContainer');
    const catalogCloseFiltersBtn = document.getElementById('catalogCloseFiltersBtn');
    const catalogFilterOverlay = document.getElementById('catalogFilterOverlay');

    catalogMobileFilterBtn.addEventListener('click', () => {
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

    // Auto-submit on filter change (desktop only)
    const catalogForm = document.getElementById('catalogForm');
    const sortSelect = document.getElementById('sortSelect');
    const priceSlider = document.getElementById('priceSlider');
    const priceMax = document.getElementById('priceMax');
    const priceMin = document.getElementById('priceMin');

    // Category checkboxes - auto submit on desktop
    document.querySelectorAll('.category-filter').forEach(cb => {
        cb.addEventListener('change', function() {
            const chip = this.nextElementSibling;
            chip.classList.toggle('active', this.checked);
            if (window.innerWidth >= 1024) catalogForm.submit();
        });
    });

    // Availability checkboxes
    document.querySelectorAll('.availability-filter').forEach(cb => {
        cb.addEventListener('change', function() {
            if (window.innerWidth >= 1024) catalogForm.submit();
        });
    });

    // Sort select
    sortSelect.addEventListener('change', () => catalogForm.submit());

    // Price slider syncs with max input
    priceSlider.addEventListener('input', function() {
        priceMax.value = this.value;
    });

    priceSlider.addEventListener('change', function() {
        priceMax.value = this.value;
        if (window.innerWidth >= 1024) catalogForm.submit();
    });

    // Price inputs - submit on Enter or blur
    [priceMin, priceMax].forEach(input => {
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

    // Remove empty fields before submit to keep URL clean
    catalogForm.addEventListener('submit', function() {
        this.querySelectorAll('input, select').forEach(input => {
            if (input.type === 'checkbox' && !input.checked) return;
            if (!input.value || input.value === '' || input.value === '0') {
                input.removeAttribute('name');
            }
        });
    });
</script>
@endsection
