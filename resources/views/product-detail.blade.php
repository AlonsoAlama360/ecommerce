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
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition">Inicio</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('catalog') }}" class="hover:text-gray-900 transition">Catálogo</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('catalog', ['categories' => [$product->category->slug]]) }}" class="hover:text-gray-900 transition">{{ $product->category->name }}</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium truncate max-w-[200px]">{{ $product->name }}</span>
            </div>
        </div>
    </div>

    <!-- Product Detail -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="aspect-square bg-white rounded-2xl overflow-hidden shadow-lg">
                    <img id="mainImage"
                         src="{{ $product->images->first()?->image_url ?? 'https://via.placeholder.com/600x600?text=Sin+Imagen' }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                </div>

                <!-- Thumbnail Images -->
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($product->images as $index => $image)
                            <button type="button"
                                    class="thumb-btn aspect-square bg-white rounded-lg overflow-hidden border-2 shadow-sm hover:shadow-md transition-all {{ $index === 0 ? 'border-[#D4A574]' : 'border-transparent hover:border-[#D4A574]' }}"
                                    data-image="{{ $image->image_url }}"
                                    aria-label="Ver imagen {{ $index + 1 }}">
                                <img src="{{ $image->image_url }}" alt="{{ $image->alt_text ?? $product->name }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Category -->
                <a href="{{ route('catalog', ['categories' => [$product->category->slug]]) }}" class="inline-block text-sm text-[#D4A574] font-medium hover:underline">
                    {{ $product->category->name }}
                </a>

                <!-- Title -->
                <div class="flex items-start justify-between gap-3">
                    <h1 class="text-3xl lg:text-4xl font-serif font-semibold text-gray-900">{{ $product->name }}</h1>
                    <button type="button" class="wishlist-btn flex-shrink-0 w-11 h-11 border-2 border-gray-200 rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all duration-200" data-product-id="{{ $product->id }}">
                        <i class="far fa-heart text-lg"></i>
                    </button>
                </div>

                <!-- Rating & SKU -->
                <div class="flex items-center gap-4">
                    <button type="button" onclick="document.querySelector('[data-tab=reviews]').click(); document.getElementById('tab-content-reviews').scrollIntoView({behavior:'smooth'})"
                        class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                        <div class="flex gap-0.5">
                            @for($s = 1; $s <= 5; $s++)
                                <i class="fas fa-star {{ $s <= round($reviewStats['average']) ? 'text-amber-400' : 'text-gray-200' }} text-sm"></i>
                            @endfor
                        </div>
                        <span class="text-sm font-medium text-gray-600">{{ $reviewStats['average'] }} ({{ $reviewStats['total'] }})</span>
                    </button>
                    <span class="text-gray-300">|</span>
                    <p class="text-sm text-gray-400">SKU: {{ $product->sku }}</p>
                </div>

                <!-- Price -->
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl lg:text-4xl font-bold text-gray-900">S/ {{ number_format($product->current_price, 2) }}</span>
                    @if($product->discount_percentage)
                        <span class="text-xl lg:text-2xl text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</span>
                        <span class="bg-[#E8B4B8] text-white px-3 py-1 rounded-full text-sm font-semibold">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>

                <!-- Description -->
                <div class="border-t border-b border-gray-200 py-6">
                    <p class="text-gray-700 leading-relaxed">{{ $product->short_description ?? $product->description }}</p>
                </div>

                <!-- Material -->
                @if($product->material)
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Material</label>
                        <span class="inline-block px-6 py-3 border-2 border-[#D4A574] bg-[#D4A574] text-white rounded-lg font-medium">
                            {{ $product->material }}
                        </span>
                    </div>
                @endif

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Cantidad</label>
                    <div class="flex items-center gap-4">
                        <button type="button" id="decreaseQty" class="w-12 h-12 border-2 border-gray-300 rounded-lg flex items-center justify-center hover:border-[#D4A574] hover:text-[#D4A574] transition" aria-label="Disminuir cantidad">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input id="quantity" type="text" value="1" readonly class="w-20 h-12 text-center border-2 border-gray-300 rounded-lg font-semibold text-lg" aria-label="Cantidad">
                        <button type="button" id="increaseQty" class="w-12 h-12 border-2 border-gray-300 rounded-lg flex items-center justify-center hover:border-[#D4A574] hover:text-[#D4A574] transition" aria-label="Aumentar cantidad">
                            <i class="fas fa-plus"></i>
                        </button>
                        @if($product->stock > 0)
                            <span class="text-sm text-green-600 font-medium"><i class="fas fa-check-circle mr-1"></i>En stock ({{ $product->stock }})</span>
                        @else
                            <span class="text-sm text-red-500 font-medium"><i class="fas fa-times-circle mr-1"></i>Agotado</span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3 pt-4">
                    <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1" id="cartQtyInput">
                        <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold text-lg hover:bg-gray-800 transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            Agregar al Carrito
                        </button>
                    </form>
                    <button type="button" id="whatsappBtn" class="w-full bg-green-500 text-white py-4 rounded-full font-semibold text-lg hover:bg-green-600 transition flex items-center justify-center gap-2">
                        <i class="fab fa-whatsapp text-xl"></i>
                        Consultar por WhatsApp
                    </button>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shipping-fast text-[#D4A574]"></i>
                        <span class="text-sm text-gray-700">Envío Gratis</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shield-alt text-[#D4A574]"></i>
                        <span class="text-sm text-gray-700">Garantía 1 Año</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-[#D4A574]"></i>
                        <span class="text-sm text-gray-700">Entrega 2-3 días</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-credit-card text-[#D4A574]"></i>
                        <span class="text-sm text-gray-700">Pago Seguro</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="mt-16">
            <div class="border-b border-gray-200">
                <div class="flex gap-6 sm:gap-8 overflow-x-auto">
                    <button type="button" data-tab="description" class="tab-btn pb-4 border-b-2 border-[#D4A574] text-[#D4A574] font-semibold whitespace-nowrap">
                        Descripción
                    </button>
                    @if($product->specifications)
                        <button type="button" data-tab="specifications" class="tab-btn pb-4 border-b-2 border-transparent text-gray-600 hover:text-[#D4A574] font-semibold whitespace-nowrap">
                            Especificaciones
                        </button>
                    @endif
                    <button type="button" data-tab="reviews" class="tab-btn pb-4 border-b-2 border-transparent text-gray-600 hover:text-[#D4A574] font-semibold whitespace-nowrap">
                        Reseñas ({{ $reviewStats['total'] }})
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="py-8">
                <!-- Description Tab -->
                <div id="tab-content-description" class="tab-content space-y-4">
                    <h2 class="text-2xl font-serif font-semibold text-gray-900">Detalles del Producto</h2>
                    <div class="text-gray-700 leading-relaxed prose max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Specifications Tab -->
                @if($product->specifications)
                    <div id="tab-content-specifications" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                            @foreach($product->specifications as $label => $value)
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-900">{{ $label }}:</span>
                                    <span class="text-gray-700">{{ $value }}</span>
                                </div>
                            @endforeach
                            @if($product->material)
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-900">Material:</span>
                                    <span class="text-gray-700">{{ $product->material }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-900">SKU:</span>
                                <span class="text-gray-700">{{ $product->sku }}</span>
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
                                                class="w-full bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300">
                                                <i class="fas fa-pen mr-2"></i>Escribir Reseña
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="block text-center bg-gray-900 text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition-all">
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
                                                            class="star-select w-10 h-10 flex items-center justify-center rounded-lg hover:bg-amber-50 transition-colors">
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
                                                class="bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300">
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

        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-16">
                <h2 class="text-3xl font-serif font-semibold text-gray-900 mb-8">Productos Relacionados</h2>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('product.show', $related->slug) }}" class="group">
                            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow">
                                <div class="relative aspect-square overflow-hidden">
                                    <img src="{{ $related->primaryImage?->image_url ?? 'https://via.placeholder.com/300x300?text=Sin+Imagen' }}"
                                         alt="{{ $related->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                         loading="lazy">
                                    @if($related->discount_percentage)
                                        <span class="absolute top-3 right-3 bg-[#E8B4B8] text-white px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">-{{ $related->discount_percentage }}%</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 text-sm sm:text-base line-clamp-2">{{ $related->name }}</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-gray-900">S/ {{ number_format($related->current_price, 2) }}</span>
                                        @if($related->sale_price && $related->sale_price < $related->price)
                                            <span class="text-sm text-gray-400 line-through">S/ {{ number_format($related->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
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
                b.classList.remove('border-[#D4A574]');
                b.classList.add('border-transparent');
            });
            this.classList.remove('border-transparent');
            this.classList.add('border-[#D4A574]');
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

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Agregando...';
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
            btn.innerHTML = '<i class="fas fa-check"></i> ¡Agregado!';
            btn.classList.remove('bg-gray-900');
            btn.classList.add('bg-green-600');

            // Update cart badge
            document.querySelectorAll('.cart-badge').forEach(b => {
                b.textContent = data.cart_count;
                b.style.display = data.cart_count > 0 ? 'flex' : 'none';
            });

            if (typeof openCartSidebar === 'function') openCartSidebar();

            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('bg-green-600');
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
        const phone = '{{ config("app.whatsapp_phone") }}';
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
                b.classList.add('border-transparent', 'text-gray-600');
            });

            document.getElementById('tab-content-' + target).classList.remove('hidden');
            this.classList.remove('border-transparent', 'text-gray-600');
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
</script>
@endsection
