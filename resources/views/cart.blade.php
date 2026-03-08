@extends('layouts.app')

@section('title', 'Carrito de Compras - Arixna')

@section('styles')
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    .animate-fade-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease forwards;
    }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }

    .cart-card {
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        border: 1px solid rgba(0,0,0,0.04);
    }
    .cart-card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
        border-color: rgba(212,165,116,0.15);
    }

    .cart-item-row {
        transition: all 0.3s ease;
    }
    .cart-item-row:hover {
        background: linear-gradient(135deg, rgba(212,165,116,0.03), rgba(232,180,184,0.03));
    }

    .checkout-btn {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .checkout-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    .checkout-btn:active {
        transform: translateY(0);
    }
    .checkout-btn::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
        background-size: 200% 100%;
        animation: shimmer 3s ease infinite;
    }

    .trust-badge {
        transition: all 0.3s ease;
    }
    .trust-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .step-line-active {
        background: linear-gradient(90deg, #D4A574, #E8B4B8);
    }

    .qty-control {
        transition: all 0.2s ease;
    }
    .qty-control:hover {
        border-color: #D4A574;
        box-shadow: 0 0 0 3px rgba(212,165,116,0.08);
    }
@endsection

@section('content')
    {{-- ═══════════ HEADER CON BREADCRUMB Y STEPS ═══════════ --}}
    <section class="relative bg-gradient-to-br from-[#F5E6D3] via-[#FAF8F5] to-[#E8D4C0] overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-8 right-16 w-48 h-48 bg-[#D4A574]/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-4 left-8 w-32 h-32 bg-[#E8B4B8]/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10 relative z-10">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-fade-up">
                <a href="{{ route('home') }}" class="hover:text-[#D4A574] transition">
                    <i class="fas fa-home text-xs"></i> Inicio
                </a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-gray-900 font-medium">Carrito</span>
            </div>

            {{-- Title --}}
            <div class="flex items-center justify-between mb-8 animate-fade-up delay-1">
                <div>
                    <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900">Carrito de Compras</h1>
                    @if(!empty($cartItems))
                        <p class="text-sm text-gray-500 mt-1">{{ $totalItems }} {{ $totalItems === 1 ? 'artículo' : 'artículos' }} en tu carrito</p>
                    @endif
                </div>
                @if(!empty($cartItems))
                    <a href="{{ route('catalog') }}" class="hidden sm:inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#D4A574] transition font-medium bg-white/60 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/80">
                        <i class="fas fa-arrow-left text-xs"></i>
                        Seguir comprando
                    </a>
                @endif
            </div>

            {{-- Steps indicator --}}
            <div class="flex items-center justify-center gap-0 mb-2 animate-fade-up delay-2">
                {{-- Step 1: Carrito (activo) --}}
                <div class="flex items-center gap-2.5 relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D4A574] to-[#C39563] flex items-center justify-center shadow-lg shadow-[#D4A574]/30" style="box-shadow: 0 0 0 4px rgba(212,165,116,0.15), 0 10px 15px -3px rgba(212,165,116,0.3)">
                        <i class="fas fa-shopping-bag text-white text-xs"></i>
                    </div>
                    <span class="text-sm font-bold text-gray-900 hidden sm:inline">Carrito</span>
                </div>

                {{-- Línea 1 --}}
                <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-3 rounded-full bg-gray-200"></div>

                {{-- Step 2: Pago --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                        <i class="fas fa-credit-card text-gray-400 text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-400 hidden sm:inline">Pago</span>
                </div>

                {{-- Línea 2 --}}
                <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-3 rounded-full bg-gray-200"></div>

                {{-- Step 3: Confirmación --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                        <i class="fas fa-box-open text-gray-400 text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-400 hidden sm:inline">Confirmación</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ CONTENIDO PRINCIPAL ═══════════ --}}
    <div class="bg-[#FAF8F5] min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl px-5 py-4 flex items-center gap-3 animate-fade-up">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-sm"></i>
                    </div>
                    <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600 transition">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            @endif

            @if(empty($cartItems))
                {{-- Empty Cart --}}
                <div class="text-center py-20 animate-fade-up">
                    <div class="w-28 h-28 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-gray-100">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h2 class="font-serif text-3xl font-bold text-gray-900 mb-3">Tu carrito está vacío</h2>
                    <p class="text-gray-500 mb-8 max-w-sm mx-auto">Descubre nuestros productos y encuentra algo especial para ti</p>
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-8 py-3.5 rounded-xl hover:shadow-lg hover:shadow-[#D4A574]/25 transition-all font-semibold">
                        <i class="fas fa-store text-sm"></i>
                        Explorar Catálogo
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    {{-- ═══════════ COLUMNA IZQUIERDA: PRODUCTOS ═══════════ --}}
                    <div class="lg:col-span-7 space-y-4">
                        <div class="cart-card bg-white rounded-2xl overflow-hidden animate-fade-up delay-1">
                            {{-- Card header --}}
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#D4A574]/15 to-[#E8B4B8]/10 flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-[#D4A574] text-sm"></i>
                                    </div>
                                    <h2 class="text-base font-bold text-gray-900">Productos</h2>
                                </div>
                                <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-3 py-1.5 rounded-full">{{ $totalItems }} {{ $totalItems === 1 ? 'artículo' : 'artículos' }}</span>
                            </div>

                            {{-- Items --}}
                            <div class="divide-y divide-gray-50">
                                @foreach($cartItems as $item)
                                    @php $product = $item['product']; @endphp
                                    <div class="cart-item-row px-5 sm:px-6 py-5 transition-all duration-300" id="cart-item-{{ $product->id }}">
                                        <div class="flex gap-4 sm:gap-5">
                                            {{-- Image --}}
                                            <a href="{{ route('product.show', $product->slug) }}" class="flex-shrink-0">
                                                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 shadow-sm border border-gray-100">
                                                    <img src="{{ $product->primaryImage?->thumbnail() ?? asset('images/placeholder.png') }}"
                                                         alt="{{ $product->name }}"
                                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                                </div>
                                            </a>

                                            {{-- Info --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="min-w-0">
                                                        <a href="{{ route('product.show', $product->slug) }}" class="hover:text-[#D4A574] transition">
                                                            <h3 class="text-sm sm:text-base font-semibold text-gray-900 line-clamp-2 leading-snug">{{ $product->name }}</h3>
                                                        </a>
                                                        @if($product->category)
                                                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                                                <i class="fas fa-tag text-[9px]"></i>
                                                                {{ $product->category->name }}
                                                            </p>
                                                        @endif
                                                        @if($product->material)
                                                            <p class="text-xs text-gray-400 mt-0.5">{{ $product->material }}</p>
                                                        @endif
                                                    </div>
                                                    {{-- Remove --}}
                                                    <button type="button"
                                                            class="remove-btn w-8 h-8 rounded-lg bg-gray-50 hover:bg-red-50 flex items-center justify-center text-gray-300 hover:text-red-500 transition flex-shrink-0"
                                                            data-product-id="{{ $product->id }}"
                                                            aria-label="Eliminar {{ $product->name }}">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                </div>

                                                {{-- Price + Quantity --}}
                                                <div class="flex items-end justify-between mt-3 sm:mt-4">
                                                    <div class="qty-control flex items-center bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
                                                        <button type="button"
                                                                class="qty-btn w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-[#D4A574]/10 hover:text-[#D4A574] transition"
                                                                data-product-id="{{ $product->id }}"
                                                                data-action="decrease"
                                                                aria-label="Disminuir cantidad">
                                                            <i class="fas fa-minus text-[10px]"></i>
                                                        </button>
                                                        <input type="text"
                                                               value="{{ $item['quantity'] }}"
                                                               readonly
                                                               class="qty-input w-9 h-9 text-center bg-transparent text-sm font-bold text-gray-900"
                                                               id="qty-{{ $product->id }}"
                                                               aria-label="Cantidad">
                                                        <button type="button"
                                                                class="qty-btn w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-[#D4A574]/10 hover:text-[#D4A574] transition"
                                                                data-product-id="{{ $product->id }}"
                                                                data-action="increase"
                                                                data-max="{{ $product->stock }}"
                                                                aria-label="Aumentar cantidad">
                                                            <i class="fas fa-plus text-[10px]"></i>
                                                        </button>
                                                    </div>
                                                    <div class="text-right">
                                                        <span class="text-lg font-bold text-gray-900">S/ {{ number_format($product->current_price * $item['quantity'], 2) }}</span>
                                                        @if($product->discount_percentage)
                                                            <p class="text-xs text-gray-400 line-through">S/ {{ number_format($product->price * $item['quantity'], 2) }}</p>
                                                        @elseif($item['quantity'] > 1)
                                                            <p class="text-xs text-gray-400">S/ {{ number_format($product->current_price, 2) }} c/u</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Mobile continue shopping --}}
                        <div class="sm:hidden animate-fade-up delay-2">
                            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#D4A574] font-medium transition">
                                <i class="fas fa-arrow-left text-xs"></i>
                                Seguir comprando
                            </a>
                        </div>

                        {{-- Trust Badges --}}
                        <div class="grid grid-cols-3 gap-3 animate-fade-up delay-2">
                            <div class="trust-badge bg-white rounded-xl p-4 text-center border border-gray-50">
                                <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-shield-alt text-emerald-500 text-sm"></i>
                                </div>
                                <p class="text-[11px] font-semibold text-gray-700">Compra segura</p>
                                <p class="text-[10px] text-gray-400">SSL encriptado</p>
                            </div>
                            <div class="trust-badge bg-white rounded-xl p-4 text-center border border-gray-50">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-truck text-blue-500 text-sm"></i>
                                </div>
                                <p class="text-[11px] font-semibold text-gray-700">Envío gratis</p>
                                <p class="text-[10px] text-gray-400">A todo el Perú</p>
                            </div>
                            <div class="trust-badge bg-white rounded-xl p-4 text-center border border-gray-50">
                                <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-undo text-amber-500 text-sm"></i>
                                </div>
                                <p class="text-[11px] font-semibold text-gray-700">Devoluciones</p>
                                <p class="text-[10px] text-gray-400">Hasta 30 días</p>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════ COLUMNA DERECHA: RESUMEN ═══════════ --}}
                    <div class="lg:col-span-5">
                        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden sticky top-24 animate-fade-up delay-2">

                            {{-- Header del resumen --}}
                            <div class="bg-gradient-to-r from-[#1a1a1a] to-[#2d2d2d] px-6 py-5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                            <i class="fas fa-receipt text-white text-xs"></i>
                                        </div>
                                        <h2 class="text-base font-bold text-white">Resumen del pedido</h2>
                                    </div>
                                    <span class="text-xs text-white/50">{{ $totalItems }} {{ $totalItems === 1 ? 'artículo' : 'artículos' }}</span>
                                </div>
                            </div>

                            <div class="p-6">
                                {{-- Totales --}}
                                <div class="space-y-3 text-sm" id="cartSummary">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Subtotal (<span id="summaryCount">{{ $totalItems }}</span> artículos)</span>
                                        <span class="text-gray-900 font-medium" id="summarySubtotal">S/ {{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    @if($totalDiscount > 0)
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Descuento</span>
                                            <span class="font-semibold text-emerald-600" id="summaryDiscount">-S/ {{ number_format($totalDiscount, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Envío</span>
                                        <span class="font-semibold text-emerald-600 flex items-center gap-1">
                                            <i class="fas fa-gift text-[10px]"></i> Gratis
                                        </span>
                                    </div>
                                </div>

                                {{-- Total --}}
                                <div class="mt-4 bg-gradient-to-r from-[#FAF8F5] to-[#F5E6D3] rounded-xl p-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700 font-semibold">Total</span>
                                        <span class="text-2xl font-bold text-gray-900 font-serif" id="summaryTotal">S/ {{ number_format($total, 2) }}</span>
                                    </div>
                                </div>

                                {{-- Checkout Buttons --}}
                                <div class="mt-5 space-y-3">
                                    @auth
                                        <a href="{{ route('checkout') }}" class="checkout-btn w-full text-white py-4 rounded-xl font-semibold flex items-center justify-center gap-2.5 text-[15px]">
                                            <i class="fas fa-lock text-xs relative z-10"></i>
                                            <span class="relative z-10">Proceder al Pago</span>
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout')) }}" class="checkout-btn w-full text-white py-4 rounded-xl font-semibold flex items-center justify-center gap-2.5 text-[15px]">
                                            <i class="fas fa-lock text-xs relative z-10"></i>
                                            <span class="relative z-10">Iniciar sesión para pagar</span>
                                        </a>
                                    @endauth

                                    <button type="button"
                                            id="whatsappCartBtn"
                                            class="w-full bg-[#25D366] text-white py-3.5 rounded-xl font-semibold hover:bg-[#20bd5a] transition-all hover:shadow-lg hover:shadow-[#25D366]/20 flex items-center justify-center gap-2">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                        Pedir por WhatsApp
                                    </button>
                                </div>

                                {{-- Payment Methods --}}
                                <div class="mt-5 pt-4 border-t border-gray-100">
                                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider text-center mb-3">Métodos de pago aceptados</p>
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-12 h-8 bg-gray-50 rounded-md flex items-center justify-center border border-gray-100">
                                            <i class="fab fa-cc-visa text-xl text-gray-400"></i>
                                        </div>
                                        <div class="w-12 h-8 bg-gray-50 rounded-md flex items-center justify-center border border-gray-100">
                                            <i class="fab fa-cc-mastercard text-xl text-gray-400"></i>
                                        </div>
                                        <div class="w-12 h-8 bg-gray-50 rounded-md flex items-center justify-center border border-gray-100">
                                            <i class="fab fa-cc-amex text-xl text-gray-400"></i>
                                        </div>
                                        <div class="w-12 h-8 bg-gray-50 rounded-md flex items-center justify-center border border-gray-100">
                                            <i class="fab fa-cc-diners-club text-xl text-gray-400"></i>
                                        </div>
                                        <div class="h-8 px-2.5 bg-purple-50 rounded-md flex items-center justify-center border border-purple-100 gap-1">
                                            <i class="fas fa-mobile-alt text-purple-500 text-[10px]"></i>
                                            <span class="text-[10px] font-bold text-purple-600">Yape</span>
                                        </div>
                                        <div class="h-8 px-2.5 bg-green-50 rounded-md flex items-center justify-center border border-green-100 gap-1">
                                            <i class="fas fa-mobile-alt text-green-500 text-[10px]"></i>
                                            <span class="text-[10px] font-bold text-green-600">Plin</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-center text-[11px] text-gray-400 mt-4 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-shield-alt text-[9px]"></i>
                                    Compra 100% segura y protegida
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Suggested Products --}}
            @if($suggestedProducts->isNotEmpty())
                <div class="mt-16 pt-10 border-t border-gray-200/50 animate-fade-up delay-3">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#D4A574]/15 to-[#E8B4B8]/10 flex items-center justify-center">
                            <i class="fas fa-star text-[#D4A574] text-sm"></i>
                        </div>
                        <h2 class="text-2xl font-serif font-bold text-gray-900">También te puede gustar</h2>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                        @foreach($suggestedProducts as $suggested)
                            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg hover:border-[#D4A574]/20 transition-all duration-300 group">
                                <a href="{{ route('product.show', $suggested->slug) }}" class="block relative overflow-hidden aspect-[4/5]">
                                    <img src="{{ $suggested->primaryImage?->thumbnail() ?? asset('images/placeholder.png') }}"
                                         alt="{{ $suggested->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                         loading="lazy">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    @if($suggested->discount_percentage)
                                        <div class="absolute top-3 left-3 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                            -{{ $suggested->discount_percentage }}%
                                        </div>
                                    @endif
                                </a>
                                <div class="p-4 sm:p-5">
                                    <p class="text-[10px] text-[#D4A574] font-semibold uppercase tracking-wider mb-1">{{ $suggested->category?->name }}</p>
                                    <a href="{{ route('product.show', $suggested->slug) }}">
                                        <h3 class="font-semibold text-sm sm:text-base mb-2 line-clamp-2 group-hover:text-[#D4A574] transition-colors leading-snug min-h-[2.5rem]">{{ $suggested->name }}</h3>
                                    </a>
                                    <div class="flex items-end gap-2 mb-3">
                                        <span class="text-lg sm:text-xl font-bold text-gray-900 leading-none">S/ {{ number_format($suggested->current_price, 2) }}</span>
                                        @if($suggested->sale_price && $suggested->sale_price < $suggested->price)
                                            <span class="text-xs text-gray-400 line-through leading-none pb-0.5">S/ {{ number_format($suggested->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <button type="button"
                                            class="add-to-cart-btn w-full bg-gray-900 text-white py-2.5 sm:py-3 rounded-full hover:bg-[#D4A574] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 font-medium text-xs sm:text-sm"
                                            data-product-id="{{ $suggested->id }}">
                                        <i class="fas fa-shopping-bag text-xs"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const CART_UPDATE_URL = '{{ route("cart.update") }}';
    const CART_REMOVE_URL = '{{ route("cart.remove") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Quantity buttons
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const action = this.dataset.action;
            const input = document.getElementById('qty-' + productId);
            let qty = parseInt(input.value);

            if (action === 'increase') {
                const max = parseInt(this.dataset.max || 99);
                if (qty < max) qty++;
            } else {
                if (qty > 1) qty--;
            }

            input.value = qty;

            fetch(CART_UPDATE_URL, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: qty })
            }).then(r => r.json()).then(data => {
                updateCartBadge(data.cart_count);
                location.reload();
            });
        });
    });

    // Remove buttons
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const item = document.getElementById('cart-item-' + productId);

            item.style.opacity = '0';
            item.style.transform = 'translateX(100px)';

            fetch(CART_REMOVE_URL, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId })
            }).then(r => r.json()).then(data => {
                updateCartBadge(data.cart_count);
                setTimeout(() => location.reload(), 300);
            });
        });
    });

    // WhatsApp cart button
    const whatsappCartBtn = document.getElementById('whatsappCartBtn');
    if (whatsappCartBtn) {
        whatsappCartBtn.addEventListener('click', function() {
            const phone = '{{ $settings["whatsapp_number"] ?? "" }}';
            var e = { cart: '\uD83D\uDED2', dot: '\u25B8', money: '\uD83D\uDCB0', line: '\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500' };
            let message = e.cart + ' *Mi Pedido - Arixna*\n\n';

            @foreach($cartItems as $item)
                message += e.dot + ' *{{ $item["product"]->name }}*\n';
                message += '  Cantidad: {{ $item["quantity"] }}\n';
                message += '  Precio: S/ {{ number_format($item["product"]->current_price * $item["quantity"], 2) }}\n\n';
            @endforeach

            message += e.line + '\n';
            message += e.money + ' *Total: S/ {{ number_format($total, 2) }}*\n\n';
            message += 'Hola, me gustar\u00EDa realizar este pedido. \u00BFPodr\u00EDan confirmar disponibilidad? \uD83D\uDE4F';

            const url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message);
            window.open(url, '_blank');
        });
    }

    function updateCartBadge(count) {
        document.querySelectorAll('.cart-badge').forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        });
    }
</script>
@endsection
