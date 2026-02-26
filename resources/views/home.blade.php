@extends('layouts.app')

@section('title', 'Romantic Gifts - Detalles Románticos')

@section('styles')
    /* ── Hero ── */
    .hero-section {
        background: linear-gradient(135deg, #F5E6D3 0%, #FAF8F5 40%, #F0D5C0 70%, #E8C8B0 100%);
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -20%;
        width: 70%;
        height: 140%;
        background: radial-gradient(ellipse, rgba(212,165,116,0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-float { animation: heroFloat 6s ease-in-out infinite; }
    .hero-float-delay { animation: heroFloat 6s ease-in-out 1.5s infinite; }
    .hero-float-slow  { animation: heroFloat 8s ease-in-out 3s infinite; }
    @keyframes heroFloat {
        0%,100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-18px) rotate(3deg); }
    }
    @keyframes fadeInUp {
        from { opacity:0; transform:translateY(30px); }
        to   { opacity:1; transform:translateY(0); }
    }
    @keyframes fadeInRight {
        from { opacity:0; transform:translateX(40px); }
        to   { opacity:1; transform:translateX(0); }
    }
    @keyframes scaleIn {
        from { opacity:0; transform:scale(0.85); }
        to   { opacity:1; transform:scale(1); }
    }
    @keyframes shimmer {
        0%   { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    @keyframes marquee {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .anim-fade-up   { animation: fadeInUp .8s ease both; }
    .anim-fade-up-2 { animation: fadeInUp .8s ease .15s both; }
    .anim-fade-up-3 { animation: fadeInUp .8s ease .3s both; }
    .anim-fade-up-4 { animation: fadeInUp .8s ease .45s both; }
    .anim-fade-right { animation: fadeInRight .9s ease .2s both; }
    .anim-scale-in   { animation: scaleIn .7s ease both; }
    .shimmer-btn {
        background-size: 200% 100%;
        background-image: linear-gradient(110deg, #D4A574 0%, #C39563 25%, #e0b88a 50%, #C39563 75%, #D4A574 100%);
        animation: shimmer 3s linear infinite;
    }

    /* ── Marquee ── */
    .marquee-track { animation: marquee 30s linear infinite; }
    .marquee-track:hover { animation-play-state: paused; }

    /* ── Scroll-reveal ── */
    .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity .7s ease, transform .7s ease;
    }
    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Category cards ── */
    .category-card {
        transition: all .4s cubic-bezier(.25,.8,.25,1);
    }
    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(212,165,116,0.25);
    }
    .category-card:hover .cat-icon {
        transform: scale(1.15) rotate(-5deg);
    }
    .cat-icon { transition: transform .4s ease; }

    /* ── Product cards ── */
    .product-card-home {
        transition: all .4s cubic-bezier(.25,.8,.25,1);
    }
    .product-card-home:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 48px rgba(0,0,0,.1);
    }

    /* ── Stat counter ── */
    .stat-card {
        background: rgba(255,255,255,.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,.5);
    }

    /* ── Benefits ── */
    .benefit-card {
        transition: all .35s ease;
    }
    .benefit-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0,0,0,.08);
    }
    .benefit-card:hover .benefit-icon {
        background: linear-gradient(135deg, #D4A574, #C39563);
        color: #fff;
    }
    .benefit-icon {
        transition: all .35s ease;
    }

    /* ── Testimonial ── */
    .testimonial-card {
        transition: all .35s ease;
    }
    .testimonial-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(212,165,116,0.15);
    }

    /* ── Instagram grid ── */
    .insta-item {
        transition: all .4s ease;
    }
    .insta-item:hover {
        transform: scale(1.05);
        z-index: 10;
    }
    .insta-item:hover .insta-overlay {
        opacity: 1;
    }
    .insta-overlay {
        opacity: 0;
        transition: opacity .3s ease;
    }

    /* ── Newsletter ── */
    .newsletter-section {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        position: relative;
        overflow: hidden;
    }
    .newsletter-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23D4A574' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
@endsection

@section('content')

    {{-- ═══════════════════ HERO ═══════════════════ --}}
    <section class="hero-section py-16 md:py-24 lg:py-22">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Text --}}
                <div class="space-y-8">
                    <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-sm px-4 py-2 rounded-full border border-[#D4A574]/20 anim-fade-up">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-sm font-medium text-gray-700">Envío gratis en pedidos +S/50</span>
                    </div>

                    <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl font-bold text-gray-900 leading-[1.1] anim-fade-up-2">
                        Regalos que crean
                        <span class="relative inline-block">
                            <span class="relative z-10 bg-gradient-to-r from-[#D4A574] to-[#C39563] bg-clip-text text-transparent">momentos</span>
                            <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12" fill="none"><path d="M2 8 C50 2, 150 2, 198 8" stroke="#D4A574" stroke-width="3" stroke-linecap="round" opacity=".4"/></svg>
                        </span>
                        mágicos
                    </h1>

                    <p class="text-lg sm:text-xl text-gray-600 leading-relaxed max-w-lg anim-fade-up-3">
                        Descubre nuestra colección exclusiva de detalles románticos, diseñados para expresar tus sentimientos más profundos.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-2 anim-fade-up-4">
                        <a href="{{ route('catalog') }}" class="shimmer-btn text-white px-8 py-4 rounded-full font-semibold text-base shadow-lg shadow-[#D4A574]/30 hover:shadow-xl hover:shadow-[#D4A574]/40 hover:scale-105 transition-all duration-300">
                            Explorar Colección
                        </a>
                        <a href="{{ route('ofertas') }}" class="group flex items-center gap-2 px-8 py-4 rounded-full font-semibold text-gray-900 bg-white/80 backdrop-blur-sm border-2 border-gray-200 hover:border-[#D4A574] hover:bg-white transition-all duration-300">
                            Ver Ofertas
                            <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center gap-6 pt-4 anim-fade-up-4">
                        <div class="stat-card rounded-2xl px-5 py-3 text-center">
                            <p class="text-2xl font-bold text-[#D4A574]">10K+</p>
                            <p class="text-xs text-gray-500 font-medium">Clientes felices</p>
                        </div>
                        <div class="stat-card rounded-2xl px-5 py-3 text-center">
                            <p class="text-2xl font-bold text-[#D4A574]">500+</p>
                            <p class="text-xs text-gray-500 font-medium">Productos</p>
                        </div>
                        <div class="stat-card rounded-2xl px-5 py-3 text-center">
                            <p class="text-2xl font-bold text-[#D4A574]">4.9<span class="text-sm">★</span></p>
                            <p class="text-xs text-gray-500 font-medium">Valoración</p>
                        </div>
                    </div>
                </div>

                {{-- Image composition --}}
                <div class="relative anim-fade-right">
                    <div class="relative z-10">
                        <img src="https://i.pinimg.com/1200x/e0/4d/5e/e04d5ea05adc67cae665c023676b5ee6.jpg" alt="Romantic Gifts"
                            class="rounded-3xl shadow-2xl w-full object-cover aspect-[4/5] lg:aspect-[3/4]">
                        <div class="absolute inset-0 rounded-3xl bg-gradient-to-t from-black/10 via-transparent to-transparent"></div>
                    </div>

                    {{-- Floating cards --}}
                    <div class="absolute -bottom-6 -left-4 sm:-left-8 bg-white p-4 sm:p-5 rounded-2xl shadow-xl z-20 hero-float">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#D4A574] to-[#C39563] rounded-xl flex items-center justify-center">
                                <i class="fas fa-truck text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Envío gratis</p>
                                <p class="font-bold text-gray-900 text-sm">En compras +S/50</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -top-4 -right-2 sm:-right-6 bg-white p-4 rounded-2xl shadow-xl z-20 hero-float-delay">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-heart text-rose-500"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-sm">+2,500</p>
                                <p class="text-xs text-gray-400">Pedidos este mes</p>
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:flex absolute top-1/2 -right-10 bg-white/90 backdrop-blur-md px-4 py-3 rounded-xl shadow-lg z-20 hero-float-slow items-center gap-2">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-400 to-pink-300 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-300 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-300 border-2 border-white"></div>
                        </div>
                        <p class="text-xs text-gray-600 font-medium">+5K reviews</p>
                    </div>

                    {{-- Decorative blobs --}}
                    <div class="absolute -z-10 -top-12 -right-12 w-48 h-48 bg-[#D4A574]/10 rounded-full blur-3xl"></div>
                    <div class="absolute -z-10 -bottom-12 -left-12 w-36 h-36 bg-[#E8B4A8]/15 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ MARQUEE BENEFITS BAR ═══════════════════ --}}
    <section class="bg-gradient-to-r from-[#D4A574] to-[#C39563] py-3.5 overflow-hidden">
        <div class="flex whitespace-nowrap">
            <div class="marquee-track flex items-center gap-12 text-white/90 text-sm font-medium">
                @for($i = 0; $i < 2; $i++)
                <span class="flex items-center gap-2"><i class="fas fa-truck"></i> Envío gratis +S/50</span>
                <span class="text-white/30">•</span>
                <span class="flex items-center gap-2"><i class="fas fa-shield-alt"></i> Pago 100% seguro</span>
                <span class="text-white/30">•</span>
                <span class="flex items-center gap-2"><i class="fas fa-gift"></i> Empaque de regalo gratis</span>
                <span class="text-white/30">•</span>
                <span class="flex items-center gap-2"><i class="fas fa-undo"></i> Devolución fácil 30 días</span>
                <span class="text-white/30">•</span>
                <span class="flex items-center gap-2"><i class="fas fa-heart"></i> +10,000 clientes felices</span>
                <span class="text-white/30">•</span>
                <span class="flex items-center gap-2"><i class="fas fa-star"></i> Calidad garantizada</span>
                <span class="text-white/30 mr-12">•</span>
                @endfor
            </div>
        </div>
    </section>

    {{-- ═══════════════════ CATEGORÍAS ═══════════════════ --}}
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <span class="inline-block text-[#D4A574] font-semibold text-sm tracking-widest uppercase mb-3">Colecciones</span>
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">Explora por Categoría</h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto">Encuentra el regalo perfecto para cada momento especial</p>
            </div>

            @php
                $catGradients = [
                    'from-rose-50 to-pink-100',
                    'from-purple-50 to-fuchsia-100',
                    'from-amber-50 to-orange-100',
                    'from-red-50 to-rose-100',
                    'from-teal-50 to-emerald-100',
                ];
                $catColors = [
                    'from-rose-400 to-pink-500',
                    'from-purple-400 to-fuchsia-500',
                    'from-amber-400 to-orange-500',
                    'from-red-400 to-rose-500',
                    'from-teal-400 to-emerald-500',
                ];
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5 reveal">
                @foreach($categories as $index => $cat)
                    <a href="{{ route('catalog', ['categories' => [$cat->slug]]) }}" class="category-card bg-gradient-to-br {{ $catGradients[$index % count($catGradients)] }} rounded-3xl p-7 text-center group relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-white/20 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="cat-icon w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                            <i class="{{ $cat->icon }} text-2xl bg-gradient-to-r {{ $catColors[$index % count($catColors)] }} bg-clip-text text-transparent"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $cat->name }}</h3>
                        <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-xs font-medium text-[#D4A574]">Ver productos →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════ BANNER DOBLE ═══════════════════ --}}
    <section class="py-16 bg-[#FAF8F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-6 reveal">
                {{-- Banner 1 --}}
                <div class="relative rounded-3xl overflow-hidden group cursor-pointer h-80 md:h-96">
                    <img src="https://i.pinimg.com/1200x/e0/4d/5e/e04d5ea05adc67cae665c023676b5ee6.jpg" alt="Colección San Valentín"
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute top-5 right-5 bg-white/90 backdrop-blur-sm text-[#D4A574] px-4 py-1.5 rounded-full text-sm font-bold">
                        -30% OFF
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <h3 class="font-serif text-3xl font-bold text-white mb-2">Colección San Valentín</h3>
                        <p class="text-white/80 mb-4">Expresa tu amor con nuestros diseños exclusivos</p>
                        <span class="inline-flex items-center gap-2 text-white font-semibold group-hover:gap-4 transition-all text-sm">
                            Comprar Ahora <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>

                {{-- Banner 2 --}}
                <div class="relative rounded-3xl overflow-hidden group cursor-pointer h-80 md:h-96">
                    <img src="https://i.pinimg.com/736x/cb/ef/6e/cbef6e099db2a2b90825505936e65985.jpg" alt="Rosas Eternas"
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    <div class="absolute top-5 right-5 bg-[#D4A574] text-white px-4 py-1.5 rounded-full text-sm font-bold">
                        Nuevo
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <h3 class="font-serif text-3xl font-bold text-white mb-2">Rosas Eternas</h3>
                        <p class="text-white/80 mb-4">Belleza que perdura para siempre</p>
                        <span class="inline-flex items-center gap-2 text-white font-semibold group-hover:gap-4 transition-all text-sm">
                            Descubrir <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ MÁS VENDIDOS (SLIDER) ═══════════════════ --}}
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12 reveal">
                <div>
                    <span class="inline-block text-[#D4A574] font-semibold text-sm tracking-widest uppercase mb-3">Top Ventas</span>
                    <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900">Más Vendidos</h2>
                </div>
                <div class="hidden sm:flex gap-3">
                    <button class="w-12 h-12 rounded-full bg-gray-100 hover:bg-[#D4A574] hover:text-white transition-all duration-300 flex items-center justify-center group" id="prevBtn">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <button class="w-12 h-12 rounded-full bg-gray-100 hover:bg-[#D4A574] hover:text-white transition-all duration-300 flex items-center justify-center group" id="nextBtn">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="slider-container reveal">
                <div class="slider-track" id="sliderTrack">
                    @foreach($featuredProducts as $product)
                        <div class="min-w-[280px] sm:min-w-[300px] px-3">
                            <div class="product-card-home bg-white rounded-2xl overflow-hidden border border-gray-100 group">
                                <a href="{{ route('product.show', $product->slug) }}" class="block relative overflow-hidden">
                                    <img src="{{ $product->primaryImage?->image_url ?? '' }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    @if($product->discount_percentage)
                                        <div class="absolute top-4 left-4 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
                                            -{{ $product->discount_percentage }}%
                                        </div>
                                    @endif
                                    <button type="button" class="wishlist-btn absolute top-4 right-4 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-md" data-product-id="{{ $product->id }}">
                                        <i class="far fa-heart text-sm"></i>
                                    </button>
                                    @if($product->stock <= 5 && $product->stock > 0)
                                        <div class="absolute bottom-4 left-4 bg-amber-500/90 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-semibold">
                                            ¡Últimas {{ $product->stock }} unidades!
                                        </div>
                                    @endif
                                </a>
                                <div class="p-5">
                                    <a href="{{ route('product.show', $product->slug) }}" class="block">
                                        <h3 class="font-semibold text-base mb-1 line-clamp-1 group-hover:text-[#D4A574] transition-colors">{{ $product->name }}</h3>
                                    </a>
                                    @if($product->short_description)
                                        <p class="text-xs text-gray-400 mb-3 line-clamp-1">{{ $product->short_description }}</p>
                                    @else
                                        <p class="text-xs text-gray-400 mb-3">{{ $product->category?->name }}</p>
                                    @endif
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-end gap-2">
                                            <span class="text-xl font-bold text-gray-900">S/ {{ number_format($product->current_price, 2) }}</span>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <span class="text-sm text-gray-400 line-through pb-0.5">S/ {{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button"
                                            class="add-to-cart-btn mt-4 w-full bg-gray-900 text-white py-3 rounded-full hover:bg-[#D4A574] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 font-medium text-sm"
                                            data-product-id="{{ $product->id }}">
                                        <i class="fas fa-shopping-bag"></i> Agregar al Carrito
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ ¿POR QUÉ ELEGIRNOS? ═══════════════════ --}}
    <section class="py-20 lg:py-28 bg-gradient-to-br from-[#FAF8F5] via-white to-[#F5E6D3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <span class="inline-block text-[#D4A574] font-semibold text-sm tracking-widest uppercase mb-3">Nuestra promesa</span>
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">¿Por qué elegirnos?</h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto">Nos dedicamos a hacer que cada regalo sea una experiencia inolvidable</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">
                <div class="benefit-card bg-white rounded-2xl p-7 text-center">
                    <div class="benefit-icon w-16 h-16 bg-[#D4A574]/10 text-[#D4A574] rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-gift text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Empaque Premium</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Cada producto se envía con empaque de regalo elegante sin costo adicional.</p>
                </div>
                <div class="benefit-card bg-white rounded-2xl p-7 text-center">
                    <div class="benefit-icon w-16 h-16 bg-[#D4A574]/10 text-[#D4A574] rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-shipping-fast text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Envío Rápido</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Entregamos en 24-48 horas para que tu regalo llegue a tiempo.</p>
                </div>
                <div class="benefit-card bg-white rounded-2xl p-7 text-center">
                    <div class="benefit-icon w-16 h-16 bg-[#D4A574]/10 text-[#D4A574] rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Compra Segura</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Pago protegido con encriptación SSL y múltiples métodos de pago.</p>
                </div>
                <div class="benefit-card bg-white rounded-2xl p-7 text-center">
                    <div class="benefit-icon w-16 h-16 bg-[#D4A574]/10 text-[#D4A574] rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-headset text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Soporte 24/7</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Nuestro equipo está siempre disponible para ayudarte por WhatsApp.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ NUEVOS PRODUCTOS ═══════════════════ --}}
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12 reveal">
                <div>
                    <span class="inline-block text-[#D4A574] font-semibold text-sm tracking-widest uppercase mb-3">Lo más reciente</span>
                    <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900">Recién Llegados</h2>
                </div>
                <a href="{{ route('catalog') }}" class="hidden sm:inline-flex items-center gap-2 text-[#D4A574] font-semibold hover:gap-3 transition-all">
                    Ver todo <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 reveal">
                @foreach($newArrivals->take(4) as $product)
                    <div class="product-card-home bg-white rounded-2xl overflow-hidden border border-gray-100 group">
                        <a href="{{ route('product.show', $product->slug) }}" class="block relative overflow-hidden">
                            <img src="{{ $product->primaryImage?->image_url ?? '' }}"
                                alt="{{ $product->name }}"
                                class="w-full h-56 sm:h-72 object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            @if($product->discount_percentage)
                                <div class="absolute top-3 left-3 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-2.5 py-1 rounded-full text-xs font-bold">
                                    -{{ $product->discount_percentage }}%
                                </div>
                            @endif
                            <button type="button" class="wishlist-btn absolute top-3 right-3 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-md" data-product-id="{{ $product->id }}">
                                <i class="far fa-heart text-xs"></i>
                            </button>
                        </a>
                        <div class="p-4">
                            <p class="text-[10px] text-[#D4A574] font-semibold uppercase tracking-wider mb-1">{{ $product->category?->name }}</p>
                            <a href="{{ route('product.show', $product->slug) }}">
                                <h3 class="font-semibold text-sm mb-2 line-clamp-1 group-hover:text-[#D4A574] transition-colors">{{ $product->name }}</h3>
                            </a>
                            <div class="flex items-end gap-2 mb-3">
                                <span class="text-lg font-bold text-gray-900">S/ {{ number_format($product->current_price, 2) }}</span>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="text-xs text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <button type="button"
                                    class="add-to-cart-btn w-full bg-gray-900 text-white py-2.5 rounded-full hover:bg-[#D4A574] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 font-medium text-xs"
                                    data-product-id="{{ $product->id }}">
                                <i class="fas fa-shopping-bag text-xs"></i> Agregar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="sm:hidden text-center mt-8">
                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-[#D4A574] font-semibold">
                    Ver todos los productos <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ BANNER CTA FULLWIDTH ═══════════════════ --}}
    <section class="relative overflow-hidden reveal">
        <div class="absolute inset-0">
            <img src="https://i.pinimg.com/736x/cb/ef/6e/cbef6e099db2a2b90825505936e65985.jpg" alt="Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="max-w-xl">
                <span class="inline-block text-[#D4A574] font-semibold text-sm tracking-widest uppercase mb-4">Oferta Especial</span>
                <h2 class="font-serif text-4xl md:text-6xl font-bold text-white mb-4 leading-tight">Hasta 30% de descuento</h2>
                <p class="text-white/80 text-lg mb-8">En toda nuestra colección de San Valentín. Ofertas por tiempo limitado.</p>
                <a href="{{ route('ofertas') }}" class="inline-flex items-center gap-3 shimmer-btn text-white px-8 py-4 rounded-full font-semibold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                    Comprar Ahora <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ TESTIMONIOS ═══════════════════ --}}
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <span class="inline-block text-[#D4A574] font-semibold text-sm tracking-widest uppercase mb-3">Testimonios</span>
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">Lo que dicen nuestros clientes</h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto">Miles de personas han expresado su amor con nuestros detalles</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 reveal">
                @php
                    $testimonials = [
                        ['name' => 'María González', 'role' => 'Cliente Verificada', 'text' => 'El collar que compré superó todas mis expectativas. La calidad es excepcional y mi pareja quedó encantada. ¡Definitivamente volveré a comprar!', 'gradient' => 'from-rose-400 to-pink-500', 'initials' => 'MG'],
                        ['name' => 'Carlos Ramírez', 'role' => 'Cliente Verificado', 'text' => 'La rosa eterna es simplemente hermosa. Llegó perfectamente empaquetada y el detalle es impresionante. Un regalo perfecto para aniversarios.', 'gradient' => 'from-purple-400 to-indigo-500', 'initials' => 'CR'],
                        ['name' => 'Ana Martínez', 'role' => 'Cliente Verificada', 'text' => 'Excelente servicio al cliente y envío rápido. Los productos son de alta calidad y los precios muy competitivos. ¡Totalmente recomendado!', 'gradient' => 'from-amber-400 to-orange-500', 'initials' => 'AM'],
                    ];
                @endphp

                @foreach($testimonials as $t)
                    <div class="testimonial-card bg-gradient-to-br from-white to-[#FAF8F5] p-8 rounded-3xl border border-gray-100">
                        <div class="flex gap-1 mb-5">
                            @for($s = 0; $s < 5; $s++)
                                <i class="fas fa-star text-amber-400 text-sm"></i>
                            @endfor
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">"{{ $t['text'] }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-gradient-to-br {{ $t['gradient'] }} rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ $t['initials'] }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $t['name'] }}</p>
                                <p class="text-xs text-gray-400">{{ $t['role'] }}</p>
                            </div>
                            <div class="ml-auto">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════ INSTAGRAM FEED ═══════════════════ --}}
    <section class="py-20 lg:py-28 bg-[#FAF8F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <span class="inline-block text-[#D4A574] font-semibold text-sm tracking-widest uppercase mb-3">@romanticgifts</span>
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">Síguenos en Instagram</h2>
                <p class="text-lg text-gray-500">Inspírate con nuestras últimas publicaciones</p>
            </div>

            @php
                $instaImages = [
                    'https://i.pinimg.com/736x/cb/ef/6e/cbef6e099db2a2b90825505936e65985.jpg',
                    'https://i.pinimg.com/1200x/e0/4d/5e/e04d5ea05adc67cae665c023676b5ee6.jpg',
                    'https://i.pinimg.com/736x/cb/ef/6e/cbef6e099db2a2b90825505936e65985.jpg',
                    'https://i.pinimg.com/1200x/e0/4d/5e/e04d5ea05adc67cae665c023676b5ee6.jpg',
                    'https://i.pinimg.com/736x/cb/ef/6e/cbef6e099db2a2b90825505936e65985.jpg',
                    'https://i.pinimg.com/1200x/e0/4d/5e/e04d5ea05adc67cae665c023676b5ee6.jpg',
                ];
            @endphp
            <div class="grid grid-cols-3 md:grid-cols-6 gap-3 reveal">
                @foreach($instaImages as $img)
                    <div class="insta-item relative rounded-2xl overflow-hidden aspect-square cursor-pointer">
                        <img src="{{ $img }}" alt="Instagram" class="w-full h-full object-cover" loading="lazy">
                        <div class="insta-overlay absolute inset-0 bg-black/40 flex items-center justify-center">
                            <i class="fab fa-instagram text-white text-3xl"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════ NEWSLETTER ═══════════════════ --}}
    <section class="newsletter-section py-20 lg:py-28 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="mb-10 reveal">
                <div class="w-16 h-16 bg-gradient-to-br from-[#D4A574] to-[#C39563] rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <i class="fas fa-envelope text-2xl text-white"></i>
                </div>
                <h2 class="font-serif text-4xl md:text-5xl font-bold mb-4">No te pierdas nada</h2>
                <p class="text-xl text-gray-300 max-w-xl mx-auto">Suscríbete y recibe ofertas exclusivas, novedades y un 10% de descuento en tu primera compra</p>
            </div>
            <form class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto reveal">
                <input type="email" placeholder="Tu correo electrónico"
                    class="flex-1 px-6 py-4 rounded-full text-gray-900 focus:outline-none focus:ring-4 focus:ring-[#D4A574]/30 bg-white/95 placeholder:text-gray-400 font-medium">
                <button type="submit" class="shimmer-btn px-8 py-4 rounded-full font-semibold text-white shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 whitespace-nowrap">
                    Suscribirme
                </button>
            </form>
            <p class="text-gray-400 text-xs mt-4">Sin spam, cancela cuando quieras.</p>
        </div>
    </section>

@endsection

@section('scripts')
<script>
    // ── Scroll Reveal ──
    (function() {
        var reveals = document.querySelectorAll('.reveal');
        function checkReveal() {
            for (var i = 0; i < reveals.length; i++) {
                var el = reveals[i];
                var top = el.getBoundingClientRect().top;
                if (top < window.innerHeight - 80) {
                    el.classList.add('visible');
                }
            }
        }
        window.addEventListener('scroll', checkReveal);
        checkReveal();
    })();

    // ── Infinite Slider ──
    var sliderTrack = document.getElementById('sliderTrack');
    var prevBtn = document.getElementById('prevBtn');
    var nextBtn = document.getElementById('nextBtn');
    var isTransitioning = false;
    var autoplayTimer;

    // Clone all slides and append them for infinite loop
    (function setupInfiniteSlider() {
        var slides = Array.from(sliderTrack.children);
        // Clone all slides to the end
        slides.forEach(function(slide) {
            var clone = slide.cloneNode(true);
            clone.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
                btn.addEventListener('click', handleCartClick);
            });
            sliderTrack.appendChild(clone);
        });
    })();

    var originalCount = {{ count($featuredProducts) }};
    var currentIndex = 0;

    function getSlideWidth() {
        var firstSlide = sliderTrack.children[0];
        return firstSlide ? firstSlide.offsetWidth : 310;
    }

    function moveToIndex(index, animate) {
        if (animate === undefined) animate = true;
        sliderTrack.style.transition = animate ? 'transform 0.5s ease' : 'none';
        sliderTrack.style.transform = 'translateX(-' + (index * getSlideWidth()) + 'px)';
        currentIndex = index;
    }

    function slideNext() {
        if (isTransitioning) return;
        isTransitioning = true;
        currentIndex++;
        moveToIndex(currentIndex, true);
    }

    function slidePrev() {
        if (isTransitioning) return;
        if (currentIndex <= 0) {
            // Jump to the cloned set (end of original) without animation, then slide back
            moveToIndex(originalCount, false);
            // Force reflow
            sliderTrack.offsetHeight;
            isTransitioning = true;
            currentIndex = originalCount - 1;
            moveToIndex(currentIndex, true);
        } else {
            isTransitioning = true;
            currentIndex--;
            moveToIndex(currentIndex, true);
        }
    }

    sliderTrack.addEventListener('transitionend', function() {
        isTransitioning = false;
        // If we've scrolled past the original slides, jump back seamlessly
        if (currentIndex >= originalCount) {
            moveToIndex(currentIndex - originalCount, false);
        }
    });

    if (nextBtn && prevBtn) {
        nextBtn.addEventListener('click', function() {
            slideNext();
            resetAutoplay();
        });
        prevBtn.addEventListener('click', function() {
            slidePrev();
            resetAutoplay();
        });
    }

    function startAutoplay() {
        autoplayTimer = setInterval(slideNext, 5000);
    }

    function resetAutoplay() {
        clearInterval(autoplayTimer);
        startAutoplay();
    }

    startAutoplay();

    // Pause autoplay on hover
    sliderTrack.parentElement.addEventListener('mouseenter', function() {
        clearInterval(autoplayTimer);
    });
    sliderTrack.parentElement.addEventListener('mouseleave', function() {
        startAutoplay();
    });

    // Handle cart click for cloned slides
    function handleCartClick(e) {
        e.preventDefault();
        var button = this;
        var productId = button.dataset.productId;
        var originalHTML = button.innerHTML;

        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;

        fetch('/carrito/agregar', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            button.innerHTML = '<i class="fas fa-check"></i> <span>¡Agregado!</span>';
            button.classList.remove('bg-gray-900');
            button.classList.add('bg-green-600');

            document.querySelectorAll('.cart-badge').forEach(function(b) {
                b.textContent = data.cart_count;
                b.style.display = data.cart_count > 0 ? 'flex' : 'none';
            });

            if (typeof showToast === 'function') {
                showToast('¡Producto agregado al carrito!');
            }

            setTimeout(function() {
                button.innerHTML = originalHTML;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-gray-900');
                button.disabled = false;
            }, 1500);
        })
        .catch(function() {
            button.innerHTML = originalHTML;
            button.disabled = false;
        });
    }

    // ── Add to Cart AJAX (bind all buttons using shared handler) ──
    document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        if (!btn.dataset.cartBound) {
            btn.dataset.cartBound = '1';
            btn.addEventListener('click', handleCartClick);
        }
    });
</script>
@endsection
