@extends('layouts.app')

@section('title', $product->name . ' - Comprar en Arixna')
@section('meta_description', Str::limit(strip_tags($product->description), 155, '...'))
@section('meta_keywords', $product->name . ', ' . ($product->category->name ?? '') . ', comprar online, Arixna, Perú')
@section('canonical', route('product.show', $product->slug))
@section('og_type', 'product')
@section('og_title', $product->name . ' - Comprar en Arixna')
@section('og_description', Str::limit(strip_tags($product->description), 200, '...'))
@section('og_image', $product->primaryImage ? $product->primaryImage->image_url : asset('images/logo_arixna.png'))
@section('og_url', route('product.show', $product->slug))

@section('seo')
    {{-- Product Price Meta --}}
    <meta property="product:price:amount" content="{{ $product->sale_price ?? $product->price }}">
    <meta property="product:price:currency" content="PEN">
    <meta property="og:price:amount" content="{{ $product->sale_price ?? $product->price }}">
    <meta property="og:price:currency" content="PEN">
    <meta property="og:availability" content="{{ $product->stock > 0 ? 'instock' : 'oos' }}">

    {{-- JSON-LD Product Schema --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Product",
        "name": "{{ $product->name }}",
        "description": "{{ Str::limit(strip_tags($product->description), 300) }}",
        "sku": "{{ $product->sku ?? '' }}",
        "url": "{{ route('product.show', $product->slug) }}",
        @if($product->primaryImage)
        "image": "{{ $product->primaryImage->image_url }}",
        @endif
        "brand": {
            "@@type": "Brand",
            "name": "{{ $product->brand ?? 'Arixna' }}"
        },
        "category": "{{ $product->category->name ?? '' }}",
        "offers": {
            "@@type": "Offer",
            "url": "{{ route('product.show', $product->slug) }}",
            "priceCurrency": "PEN",
            "price": "{{ $product->sale_price ?? $product->price }}",
            @if($product->sale_price)
            "priceValidUntil": "{{ now()->addMonths(3)->format('Y-m-d') }}",
            @endif
            "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
            "seller": {
                "@@type": "Organization",
                "name": "Arixna"
            }
        }
        @if($reviewStats['total'] > 0)
        ,"aggregateRating": {
            "@@type": "AggregateRating",
            "ratingValue": "{{ $reviewStats['average'] }}",
            "reviewCount": "{{ $reviewStats['total'] }}",
            "bestRating": "5",
            "worstRating": "1"
        }
        @endif
        @if($reviews->count() > 0)
        ,"review": [
            @foreach($reviews->take(5) as $review)
            {
                "@@type": "Review",
                "author": {
                    "@@type": "Person",
                    "name": "{{ $review->user->first_name }} {{ substr($review->user->last_name, 0, 1) }}."
                },
                "datePublished": "{{ $review->created_at->format('Y-m-d') }}",
                "reviewRating": {
                    "@@type": "Rating",
                    "ratingValue": "{{ $review->rating }}",
                    "bestRating": "5"
                }
                @if($review->comment)
                ,"reviewBody": "{{ Str::limit($review->comment, 200) }}"
                @endif
            }@if(!$loop->last),@endif
            @endforeach
        ]
        @endif
    }
    </script>

    {{-- BreadcrumbList Schema --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@@type": "ListItem",
                "position": 1,
                "name": "Inicio",
                "item": "{{ url('/') }}"
            },
            {
                "@@type": "ListItem",
                "position": 2,
                "name": "{{ $product->category->name ?? 'Catálogo' }}",
                "item": "{{ route('catalog', ['categories' => [$product->category->slug ?? '']]) }}"
            },
            {
                "@@type": "ListItem",
                "position": 3,
                "name": "{{ $product->name }}",
                "item": "{{ route('product.show', $product->slug) }}"
            }
        ]
    }
    </script>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-[#D4A574] transition"><i class="fas fa-home text-[10px]"></i></a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <a href="{{ route('catalog') }}" class="hover:text-[#D4A574] transition">Catálogo</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <a href="{{ route('catalog', ['categories' => [$product->category->slug]]) }}" class="hover:text-[#D4A574] transition">{{ $product->category->name }}</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-gray-700 font-medium truncate max-w-[180px]">{{ $product->name }}</span>
            </div>
        </div>
    </div>

    <!-- Product Detail -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-14">

            <!-- Product Images -->
            <div class="space-y-3">
                <!-- Main Image -->
                <div class="aspect-square bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 relative group">
                    <img id="mainImage"
                         src="{{ $product->images->first()?->image_url ?? 'https://via.placeholder.com/600x600?text=Sin+Imagen' }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-zoom-in">
                    @if($product->discount_percentage)
                        <div class="absolute top-4 left-4 bg-[#D4A574] text-white px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                            -{{ $product->discount_percentage }}%
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Images -->
                @if($product->images->count() > 1)
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        @foreach($product->images as $index => $image)
                            <button type="button"
                                    class="thumb-btn flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-xl overflow-hidden border-2 transition-all duration-200 {{ $index === 0 ? 'border-[#D4A574] shadow-sm' : 'border-gray-100 hover:border-[#D4A574]/50' }}"
                                    data-image="{{ $image->image_url }}"
                                    aria-label="Ver imagen {{ $index + 1 }}">
                                <img src="{{ $image->image_url }}" alt="{{ $image->alt_text ?? $product->name }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-5">
                <!-- Category + Wishlist -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('catalog', ['categories' => [$product->category->slug]]) }}" class="text-xs text-[#D4A574] font-semibold uppercase tracking-wider hover:underline">
                        {{ $product->category->name }}
                    </a>
                    <button type="button" class="wishlist-btn w-9 h-9 border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:bg-rose-50 hover:text-rose-500 hover:border-rose-200 transition-all duration-200" data-product-id="{{ $product->id }}" aria-label="Agregar a lista de deseos">
                        <i class="far fa-heart text-sm"></i>
                    </button>
                </div>

                <!-- Title -->
                <h1 class="text-2xl sm:text-3xl lg:text-[2rem] font-serif font-semibold text-gray-900 leading-tight">{{ $product->name }}</h1>

                <!-- Rating & SKU -->
                <div class="flex items-center gap-3 flex-wrap">
                    <button type="button" onclick="document.querySelector('[data-tab=reviews]').click(); document.getElementById('tab-content-reviews').scrollIntoView({behavior:'smooth'})"
                        class="flex items-center gap-1.5 hover:opacity-80 transition-opacity">
                        <div class="flex gap-0.5">
                            @for($s = 1; $s <= 5; $s++)
                                <i class="fas fa-star {{ $s <= round($reviewStats['average']) ? 'text-amber-400' : 'text-gray-200' }} text-xs"></i>
                            @endfor
                        </div>
                        <span class="text-xs font-medium text-gray-500">{{ $reviewStats['average'] }} ({{ $reviewStats['total'] }})</span>
                    </button>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <p class="text-xs text-gray-400">SKU: {{ $product->sku }}</p>
                </div>

                <!-- Price -->
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-bold text-gray-900">S/ {{ number_format($product->current_price, 2) }}</span>
                    @if($product->discount_percentage)
                        <span class="text-base text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</span>
                        <span class="bg-[#D4A574]/10 text-[#D4A574] px-2 py-0.5 rounded-md text-xs font-bold">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-5">{{ $product->short_description ?? Str::limit(strip_tags($product->description), 200) }}</p>

                <!-- Material -->
                @if($product->material)
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Material:</span>
                        <span class="inline-block px-3 py-1.5 bg-[#D4A574]/10 text-[#D4A574] rounded-lg text-xs font-semibold">
                            {{ $product->material }}
                        </span>
                    </div>
                @endif

                <!-- Quantity + Stock -->
                <div class="flex items-center gap-4 pt-1">
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button type="button" id="decreaseQty" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-[#D4A574] transition" aria-label="Disminuir cantidad">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <input id="quantity" type="text" value="1" readonly class="w-12 h-10 text-center font-semibold text-sm border-x border-gray-200 bg-white" aria-label="Cantidad">
                        <button type="button" id="increaseQty" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-[#D4A574] transition" aria-label="Aumentar cantidad">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>
                    @if($product->stock > 0)
                        <span class="text-xs text-emerald-600 font-medium flex items-center gap-1"><i class="fas fa-circle text-[6px]"></i> En stock ({{ $product->stock }})</span>
                    @else
                        <span class="text-xs text-red-500 font-medium flex items-center gap-1"><i class="fas fa-circle text-[6px]"></i> Agotado</span>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-2">
                    <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1" id="cartQtyInput">
                        <button type="submit" class="w-full bg-gray-900 text-white h-11 rounded-xl font-semibold text-sm hover:bg-gray-800 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-bag text-xs"></i>
                            Agregar al Carrito
                        </button>
                    </form>
                    <button type="button" id="whatsappBtn"
                        class="group h-11 flex items-center gap-3 px-4 rounded-xl bg-white border border-gray-100 hover:border-[#25D366]/30 hover:shadow-md hover:shadow-[#25D366]/10 transition-all duration-300 flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-[#25D366] flex items-center justify-center flex-shrink-0 shadow-sm shadow-[#25D366]/25">
                            <i class="fab fa-whatsapp text-white text-sm"></i>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-semibold text-gray-700 leading-tight">WhatsApp</p>
                            <p class="text-[9px] text-gray-400 leading-tight">Consultar</p>
                        </div>
                        <i class="hidden sm:inline fas fa-chevron-right text-[8px] text-gray-300 group-hover:text-[#25D366] transition-colors"></i>
                    </button>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-xl">
                        <div class="w-8 h-8 bg-[#D4A574]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-truck text-[#D4A574] text-xs"></i>
                        </div>
                        <span class="text-xs text-gray-700 font-medium leading-tight">Envío a todo el Perú</span>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-xl">
                        <div class="w-8 h-8 bg-[#D4A574]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-[#D4A574] text-xs"></i>
                        </div>
                        <span class="text-xs text-gray-700 font-medium leading-tight">Garantía 1 Año</span>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-xl">
                        <div class="w-8 h-8 bg-[#D4A574]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-[#D4A574] text-xs"></i>
                        </div>
                        <span class="text-xs text-gray-700 font-medium leading-tight">Entrega 2-3 días</span>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 bg-gray-50 rounded-xl">
                        <div class="w-8 h-8 bg-[#D4A574]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-lock text-[#D4A574] text-xs"></i>
                        </div>
                        <span class="text-xs text-gray-700 font-medium leading-tight">Pago Seguro</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="mt-14">
            <div class="border-b border-gray-200">
                <div class="flex gap-6 sm:gap-8 overflow-x-auto">
                    <button type="button" data-tab="description" class="tab-btn pb-3 border-b-2 border-[#D4A574] text-[#D4A574] font-semibold text-sm whitespace-nowrap transition-all">
                        Descripción
                    </button>
                    @if($product->specifications)
                        <button type="button" data-tab="specifications" class="tab-btn pb-3 border-b-2 border-transparent text-gray-500 hover:text-[#D4A574] font-semibold text-sm whitespace-nowrap transition-all">
                            Especificaciones
                        </button>
                    @endif
                    <button type="button" data-tab="reviews" class="tab-btn pb-3 border-b-2 border-transparent text-gray-500 hover:text-[#D4A574] font-semibold text-sm whitespace-nowrap transition-all">
                        Reseñas ({{ $reviewStats['total'] }})
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="py-8">
                <!-- Description Tab -->
                <div id="tab-content-description" class="tab-content space-y-4">
                    <h2 class="text-xl font-serif font-semibold text-gray-900">Detalles del Producto</h2>
                    <div class="text-gray-600 leading-relaxed prose prose-sm max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Specifications Tab -->
                @if($product->specifications)
                    <div id="tab-content-specifications" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                            @foreach($product->specifications as $label => $value)
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="font-semibold text-gray-900 text-sm">{{ $label }}:</span>
                                    <span class="text-gray-600 text-sm">{{ $value }}</span>
                                </div>
                            @endforeach
                            @if($product->material)
                                <div class="flex justify-between py-3 border-b border-gray-100">
                                    <span class="font-semibold text-gray-900 text-sm">Material:</span>
                                    <span class="text-gray-600 text-sm">{{ $product->material }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="font-semibold text-gray-900 text-sm">SKU:</span>
                                <span class="text-gray-600 text-sm">{{ $product->sku }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Reviews Tab -->
                <div id="tab-content-reviews" class="tab-content hidden">
                    <div class="grid lg:grid-cols-3 gap-8">
                        <!-- Rating Summary -->
                        <div class="lg:col-span-1">
                            <div class="bg-gradient-to-br from-[#FAF8F5] to-white rounded-2xl p-6 border border-gray-100 sticky top-24">
                                <div class="text-center mb-6">
                                    <div class="text-5xl font-bold text-gray-900 mb-1">{{ $reviewStats['average'] ?: '0.0' }}</div>
                                    <div class="flex justify-center gap-1 mb-2">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="fas fa-star {{ $s <= round($reviewStats['average']) ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $reviewStats['total'] }} {{ $reviewStats['total'] == 1 ? 'reseña' : 'reseñas' }}</p>
                                </div>

                                <!-- Rating Distribution -->
                                <div class="space-y-2">
                                    @for($i = 5; $i >= 1; $i--)
                                        @php
                                            $count = $reviewStats['distribution'][$i] ?? 0;
                                            $percentage = $reviewStats['total'] > 0 ? ($count / $reviewStats['total']) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-600 w-4">{{ $i }}</span>
                                            <i class="fas fa-star text-amber-400 text-xs"></i>
                                            <div class="flex-1 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-[#D4A574] to-[#C39563] rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-400 w-8 text-right">{{ $count }}</span>
                                        </div>
                                    @endfor
                                </div>

                                <!-- Write Review Button -->
                                <div class="mt-6">
                                    @auth
                                        @if($userReview)
                                            <div class="text-center p-3 bg-green-50 border border-green-200 rounded-xl">
                                                <i class="fas fa-check-circle text-green-500 mb-1"></i>
                                                <p class="text-sm text-green-700 font-medium">Ya dejaste tu reseña</p>
                                            </div>
                                        @else
                                            <button type="button" onclick="document.getElementById('reviewForm').scrollIntoView({behavior:'smooth'})"
                                                class="w-full bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white py-3 rounded-xl font-semibold text-sm hover:shadow-lg transition-all duration-300">
                                                <i class="fas fa-pen mr-2"></i>Escribir Reseña
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="block text-center bg-gray-900 text-white py-3 rounded-xl font-semibold text-sm hover:bg-gray-800 transition-all">
                                            <i class="fas fa-sign-in-alt mr-2"></i>Inicia sesión para opinar
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- Reviews List + Form -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Messages -->
                            @if(session('review_success'))
                                <div class="p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
                                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                    <p class="text-green-700 font-medium">{{ session('review_success') }}</p>
                                </div>
                            @endif
                            @if(session('review_error'))
                                <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3">
                                    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                                    <p class="text-red-700 font-medium">{{ session('review_error') }}</p>
                                </div>
                            @endif

                            <!-- Review Form -->
                            @auth
                                @if(!$userReview)
                                    <div id="reviewForm" class="bg-white rounded-2xl border border-gray-200 p-6">
                                        <h3 class="font-semibold text-lg text-gray-900 mb-4">Escribe tu reseña</h3>
                                        <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
                                            @csrf
                                            <!-- Star Rating -->
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tu calificación</label>
                                                <div class="flex gap-1" id="starRating">
                                                    @for($s = 1; $s <= 5; $s++)
                                                        <button type="button" data-rating="{{ $s }}"
                                                            class="star-select w-10 h-10 flex items-center justify-center rounded-lg hover:bg-amber-50 transition-colors"
                                                            aria-label="{{ $s }} {{ $s === 1 ? 'estrella' : 'estrellas' }}">
                                                            <i class="far fa-star text-xl text-gray-300 hover:text-amber-400 transition-colors"></i>
                                                        </button>
                                                    @endfor
                                                </div>
                                                <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}">
                                                @error('rating')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Title -->
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Título (opcional)</label>
                                                <input type="text" name="title" value="{{ old('title') }}" placeholder="Resumen de tu experiencia"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 focus:outline-none transition-all text-sm">
                                            </div>

                                            <!-- Comment -->
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tu comentario</label>
                                                <textarea name="comment" rows="4" placeholder="Cuéntanos tu experiencia con este producto..."
                                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 focus:outline-none transition-all text-sm resize-none">{{ old('comment') }}</textarea>
                                                @error('comment')
                                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <button type="submit"
                                                class="bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:shadow-lg transition-all duration-300">
                                                Publicar Reseña
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth

                            <!-- Reviews List -->
                            @if($reviews->count() > 0)
                                @php
                                    $reviewGradients = [
                                        'from-rose-400 to-pink-500',
                                        'from-purple-400 to-indigo-500',
                                        'from-amber-400 to-orange-500',
                                        'from-teal-400 to-emerald-500',
                                        'from-blue-400 to-cyan-500',
                                    ];
                                @endphp
                                <div class="space-y-4">
                                    @foreach($reviews as $index => $review)
                                        <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition-shadow">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br {{ $reviewGradients[$index % count($reviewGradients)] }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                        {{ strtoupper(substr($review->user->first_name, 0, 1) . substr($review->user->last_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900 text-sm">{{ $review->user->full_name }}</p>
                                                        <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex gap-0.5">
                                                    @for($s = 1; $s <= 5; $s++)
                                                        <i class="fas fa-star {{ $s <= $review->rating ? 'text-amber-400' : 'text-gray-200' }} text-xs"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            @if($review->title)
                                                <h3 class="font-semibold text-gray-900 mb-1">{{ $review->title }}</h3>
                                            @endif
                                            <p class="text-gray-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                @if($reviews->hasPages())
                                    <div class="mt-6">
                                        {{ $reviews->fragment('tab-content-reviews')->links() }}
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-12 bg-gray-50 rounded-2xl">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-comment-dots text-2xl text-gray-400"></i>
                                    </div>
                                    <h3 class="font-semibold text-gray-700 mb-1">Sin reseñas aún</h3>
                                    <p class="text-gray-500 text-sm">Sé el primero en dejar tu opinión sobre este producto.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Slider -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-14 pt-10 border-t border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <p class="text-xs font-semibold text-[#D4A574] uppercase tracking-widest mb-1">Descubre más</p>
                        <h2 class="text-xl sm:text-2xl font-serif font-bold text-gray-900">Productos Relacionados</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="relatedPrev" class="group/arrow w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:border-[#D4A574] hover:text-[#D4A574] hover:shadow-md hover:shadow-[#D4A574]/10 active:scale-95 transition-all duration-300 disabled:opacity-30 disabled:pointer-events-none">
                            <i class="fas fa-chevron-left text-[11px] group-hover/arrow:-translate-x-0.5 transition-transform duration-300"></i>
                        </button>
                        <button type="button" id="relatedNext" class="group/arrow w-10 h-10 rounded-full bg-[#D4A574] text-white flex items-center justify-center hover:bg-[#c99660] hover:shadow-md hover:shadow-[#D4A574]/25 active:scale-95 transition-all duration-300 disabled:opacity-30 disabled:pointer-events-none">
                            <i class="fas fa-chevron-right text-[11px] group-hover/arrow:translate-x-0.5 transition-transform duration-300"></i>
                        </button>
                    </div>
                </div>
                <div class="relative overflow-hidden -mx-2">
                    <div id="relatedSlider" class="flex transition-transform duration-500 ease-[cubic-bezier(0.25,0.1,0.25,1)]">
                        @foreach($relatedProducts as $related)
                            <div class="related-slide flex-shrink-0 w-1/2 sm:w-1/3 lg:w-1/4 px-2">
                                <div class="bg-white rounded-2xl overflow-hidden border border-gray-100/80 group hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] transition-all duration-500">
                                    <a href="{{ route('product.show', $related->slug) }}" class="block relative overflow-hidden aspect-square">
                                        <img src="{{ $related->primaryImage?->thumbnail() ?? asset('images/placeholder.png') }}"
                                             alt="{{ $related->name }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
                                             loading="lazy">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        @if($related->discount_percentage)
                                            <div class="absolute top-3 left-3 bg-red-500 text-white px-2 py-0.5 rounded-md text-[10px] font-bold">
                                                -{{ $related->discount_percentage }}%
                                            </div>
                                        @endif
                                        <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                            <button type="button"
                                                    class="add-to-cart-btn w-full bg-white text-gray-900 py-2.5 rounded-xl hover:bg-[#D4A574] hover:text-white active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 font-semibold text-xs shadow-lg backdrop-blur-sm"
                                                    data-product-id="{{ $related->id }}">
                                                <i class="fas fa-shopping-bag text-[10px]"></i> Agregar al carrito
                                            </button>
                                        </div>
                                        <button type="button" class="wishlist-btn absolute top-3 right-3 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-sm opacity-0 group-hover:opacity-100" data-product-id="{{ $related->id }}" aria-label="Agregar a lista de deseos">
                                            <i class="far fa-heart text-xs"></i>
                                        </button>
                                    </a>
                                    <div class="p-4 flex flex-col">
                                        <p class="text-[10px] text-[#D4A574] font-semibold uppercase tracking-wider mb-1.5">{{ $related->category?->name }}</p>
                                        <a href="{{ route('product.show', $related->slug) }}">
                                            <h3 class="font-medium text-sm mb-2.5 line-clamp-2 group-hover:text-[#D4A574] transition-colors leading-snug min-h-[2.5rem]">{{ $related->name }}</h3>
                                        </a>
                                        <div class="mt-auto">
                                            <div class="flex items-baseline gap-2">
                                                <span class="text-lg font-bold text-gray-900">S/ {{ number_format($related->current_price, 2) }}</span>
                                                @if($related->sale_price && $related->sale_price < $related->price)
                                                    <span class="text-[11px] text-gray-400 line-through">S/ {{ number_format($related->price, 2) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Slider dots -->
                <div id="relatedDots" class="flex items-center justify-center gap-1.5 mt-6"></div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
    // Image Gallery
    const mainImage = document.getElementById('mainImage');
    const thumbBtns = document.querySelectorAll('.thumb-btn');

    thumbBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            mainImage.src = this.dataset.image;
            thumbBtns.forEach(b => {
                b.classList.remove('border-[#D4A574]', 'shadow-sm');
                b.classList.add('border-gray-100');
            });
            this.classList.remove('border-gray-100');
            this.classList.add('border-[#D4A574]', 'shadow-sm');
        });
    });

    // Quantity Controls
    const qtyInput = document.getElementById('quantity');
    const cartQtyInput = document.getElementById('cartQtyInput');
    const maxStock = {{ $product->stock }};

    document.getElementById('decreaseQty').addEventListener('click', () => {
        const val = parseInt(qtyInput.value);
        if (val > 1) {
            qtyInput.value = val - 1;
            cartQtyInput.value = val - 1;
        }
    });

    document.getElementById('increaseQty').addEventListener('click', () => {
        const val = parseInt(qtyInput.value);
        if (val < maxStock) {
            qtyInput.value = val + 1;
            cartQtyInput.value = val + 1;
        }
    });

    // Add to Cart via AJAX
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = form.querySelector('button[type="submit"]');
        const originalHTML = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Agregando...';
        btn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: {{ $product->id }},
                quantity: parseInt(qtyInput.value),
            })
        })
        .then(r => r.json())
        .then(data => {
            btn.innerHTML = '<i class="fas fa-check text-xs"></i> ¡Agregado!';
            btn.classList.remove('bg-gray-900');
            btn.classList.add('bg-emerald-600');

            // Update cart badge
            document.querySelectorAll('.cart-badge').forEach(b => {
                b.textContent = data.cart_count;
                b.style.display = data.cart_count > 0 ? 'flex' : 'none';
            });

            if (typeof openCartSidebar === 'function') openCartSidebar();

            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('bg-emerald-600');
                btn.classList.add('bg-gray-900');
                btn.disabled = false;
            }, 2000);
        })
        .catch(() => {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        });
    });

    // WhatsApp button
    document.getElementById('whatsappBtn').addEventListener('click', function() {
        const phone = '{{ $settings["whatsapp_number"] ?? "" }}';
        const qty = parseInt(qtyInput.value);
        const productUrl = window.location.href;

        var e = { gift: '\uD83C\uDF81', pkg: '\uD83D\uDCE6', spark: '\u2728', money: '\uD83D\uDCB0', cash: '\uD83D\uDCB5', down: '\uD83D\uDC47', pray: '\uD83D\uDE4F' };
        let message = 'Hola, estoy interesado/a en este producto de *Arixna* ' + e.pray + '\n\n';
        message += e.pkg + ' *{{ $product->name }}*\n';
        @if($product->material)
        message += e.spark + ' {{ $product->material }}\n';
        @endif
        message += e.money + ' S/ {{ number_format($product->current_price, 2) }}';
        @if($product->discount_percentage)
        message += ' _(antes S/ {{ number_format($product->price, 2) }})_';
        @endif
        message += '\n';
        message += e.pkg + ' Cantidad: ' + qty + '\n';
        message += e.cash + ' Total: *S/ ' + ({{ $product->current_price }} * qty).toFixed(2) + '*\n\n';
        message += e.down + ' *Ver producto:*\n' + productUrl;

        window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(message), '_blank');
    });

    // Tabs
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.dataset.tab;

            tabContents.forEach(c => c.classList.add('hidden'));
            tabBtns.forEach(b => {
                b.classList.remove('border-[#D4A574]', 'text-[#D4A574]');
                b.classList.add('border-transparent', 'text-gray-500');
            });

            document.getElementById('tab-content-' + target).classList.remove('hidden');
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-[#D4A574]', 'text-[#D4A574]');
        });
    });

    // Star Rating Selector
    const starBtns = document.querySelectorAll('.star-select');
    const ratingInput = document.getElementById('ratingInput');

    if (starBtns.length > 0) {
        starBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                ratingInput.value = rating;

                starBtns.forEach((b, index) => {
                    const icon = b.querySelector('i');
                    if (index < rating) {
                        icon.classList.remove('far', 'text-gray-300');
                        icon.classList.add('fas', 'text-amber-400');
                    } else {
                        icon.classList.remove('fas', 'text-amber-400');
                        icon.classList.add('far', 'text-gray-300');
                    }
                });
            });

            btn.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                starBtns.forEach((b, index) => {
                    const icon = b.querySelector('i');
                    if (index < rating) {
                        icon.classList.add('text-amber-400');
                        icon.classList.remove('text-gray-300');
                    }
                });
            });

            btn.addEventListener('mouseleave', function() {
                const currentRating = parseInt(ratingInput.value) || 0;
                starBtns.forEach((b, index) => {
                    const icon = b.querySelector('i');
                    if (index < currentRating) {
                        icon.classList.remove('far', 'text-gray-300');
                        icon.classList.add('fas', 'text-amber-400');
                    } else {
                        icon.classList.remove('fas', 'text-amber-400');
                        icon.classList.add('far', 'text-gray-300');
                    }
                });
            });
        });

        // Set initial rating if old value exists
        const initialRating = parseInt(ratingInput.value) || 0;
        if (initialRating > 0) {
            starBtns.forEach((b, index) => {
                const icon = b.querySelector('i');
                if (index < initialRating) {
                    icon.classList.remove('far', 'text-gray-300');
                    icon.classList.add('fas', 'text-amber-400');
                }
            });
        }
    }

    // If redirected with review errors/success, auto-switch to reviews tab
    @if(session('review_success') || session('review_error') || $errors->has('rating') || $errors->has('comment'))
        document.querySelector('[data-tab="reviews"]')?.click();
    @endif

    // ── Related Products Slider ──
    (function() {
        const slider = document.getElementById('relatedSlider');
        const prevBtn = document.getElementById('relatedPrev');
        const nextBtn = document.getElementById('relatedNext');
        const dotsContainer = document.getElementById('relatedDots');
        if (!slider || !prevBtn || !nextBtn) return;

        let currentIndex = 0;

        function getVisibleCount() {
            if (window.innerWidth >= 1024) return 4;
            if (window.innerWidth >= 640) return 3;
            return 2;
        }

        function getTotalSlides() {
            return slider.querySelectorAll('.related-slide').length;
        }

        function getMaxIndex() {
            return Math.max(0, getTotalSlides() - getVisibleCount());
        }

        function buildDots() {
            if (!dotsContainer) return;
            const maxIdx = getMaxIndex();
            dotsContainer.innerHTML = '';
            if (maxIdx <= 0) return;
            for (let i = 0; i <= maxIdx; i++) {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'w-2 h-2 rounded-full transition-all duration-300 ' +
                    (i === currentIndex ? 'bg-[#D4A574] w-6' : 'bg-gray-300 hover:bg-gray-400');
                dot.addEventListener('click', () => { currentIndex = i; update(); });
                dotsContainer.appendChild(dot);
            }
        }

        function update() {
            const slide = slider.querySelector('.related-slide');
            if (!slide) return;
            const slideWidth = slide.offsetWidth;
            slider.style.transform = 'translateX(-' + (currentIndex * slideWidth) + 'px)';

            prevBtn.disabled = currentIndex <= 0;
            nextBtn.disabled = currentIndex >= getMaxIndex();
            buildDots();
        }

        prevBtn.addEventListener('click', () => { if (currentIndex > 0) { currentIndex--; update(); } });
        nextBtn.addEventListener('click', () => { if (currentIndex < getMaxIndex()) { currentIndex++; update(); } });

        // Touch/swipe
        let touchX = 0;
        slider.addEventListener('touchstart', (e) => { touchX = e.changedTouches[0].screenX; }, { passive: true });
        slider.addEventListener('touchend', (e) => {
            const diff = touchX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) {
                if (diff > 0 && currentIndex < getMaxIndex()) currentIndex++;
                else if (diff < 0 && currentIndex > 0) currentIndex--;
                update();
            }
        }, { passive: true });

        window.addEventListener('resize', () => { currentIndex = Math.min(currentIndex, getMaxIndex()); update(); });
        update();
    })();

    // ── Add to Cart for Related Products ──
    document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var button = this;
            var productId = button.dataset.productId;
            var originalHTML = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
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
                button.innerHTML = '<i class="fas fa-check text-xs"></i> ¡Agregado!';
                button.classList.add('!bg-emerald-500', '!text-white');

                document.querySelectorAll('.cart-badge').forEach(function(b) {
                    b.textContent = data.cart_count;
                    b.style.display = data.cart_count > 0 ? 'flex' : 'none';
                });

                if (typeof openCartSidebar === 'function') openCartSidebar();

                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('!bg-emerald-500', '!text-white');
                    button.disabled = false;
                }, 1800);
            })
            .catch(function() {
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        });
    });
</script>
@endsection
