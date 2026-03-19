@extends('layouts.app')

@section('title', 'Arixna - Tu Tienda Online | Perfumes, Electrodomésticos, Joyería y Zapatillas')
@section('meta_description', 'Arixna es tu tienda online en Perú. Encuentra perfumes, electrodomésticos, joyería y zapatillas de las mejores marcas con envío a todo el país.')
@section('meta_keywords', 'tienda online Perú, perfumes originales, electrodomésticos, anillos, joyería, zapatillas, compras online, envíos Perú')
@section('og_title', 'Arixna - Tu Tienda Online | Perfumes, Electrodomésticos, Joyería y Zapatillas')
@section('og_description', 'Encuentra perfumes, electrodomésticos, joyería y zapatillas de las mejores marcas. Envíos a todo el Perú.')

@section('seo')
    {{-- WebSite schema con SearchAction y secciones principales --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "Arixna",
        "alternateName": "Arixna Tienda Online",
        "url": "{{ url('/') }}",
        "description": "Tu tienda online de perfumes, electrodomésticos, joyería y zapatillas en Perú.",
        "inLanguage": "es",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": {
                "@@type": "EntryPoint",
                "urlTemplate": "{{ url('/buscar') }}?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        },
        "hasPart": [
            {
                "@@type": "WebPage",
                "name": "Catálogo",
                "description": "Explora todos nuestros productos: perfumes, electrodomésticos, joyería y zapatillas",
                "url": "{{ route('catalog') }}"
            },
            {
                "@@type": "WebPage",
                "name": "Ofertas",
                "description": "Las mejores ofertas y descuentos en productos seleccionados",
                "url": "{{ route('ofertas') }}"
            },
            {
                "@@type": "WebPage",
                "name": "Contacto",
                "description": "Contáctanos para consultas o soporte al cliente",
                "url": "{{ route('contact.show') }}"
            },
            {
                "@@type": "WebPage",
                "name": "Preguntas Frecuentes",
                "description": "Resuelve tus dudas sobre envíos, pagos y devoluciones",
                "url": "{{ route('legal.faq') }}"
            },
            {
                "@@type": "WebPage",
                "name": "Términos y Condiciones",
                "description": "Nuestros términos y condiciones de servicio",
                "url": "{{ route('legal.terms') }}"
            }
        ]
    }
    </script>

    {{-- Organization schema enriquecido --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "Arixna",
        "alternateName": "Arixna Tienda Online",
        "url": "{{ url('/') }}",
        "logo": {
            "@@type": "ImageObject",
            "url": "{{ asset('images/logo_arixna.png') }}",
            "width": 512,
            "height": 512
        },
        "description": "Tu tienda online de perfumes, electrodomésticos, joyería y zapatillas en Perú.",
        "contactPoint": [
            {
                "@@type": "ContactPoint",
                "contactType": "customer service",
                "email": "{{ $settings['contact_email'] ?? 'contacto@arixna.com' }}",
                @if(!empty($settings['contact_phone']))
                "telephone": "{{ $settings['contact_phone'] }}",
                @endif
                "availableLanguage": "Spanish",
                "areaServed": "PE"
            }
        ],
        @if(!empty($settings['contact_address']))
        "address": {
            "@@type": "PostalAddress",
            "addressCountry": "PE",
            "addressLocality": "{{ $settings['contact_address'] }}"
        },
        @endif
        "sameAs": [
            @if(!empty($settings['social_facebook']))"{{ $settings['social_facebook'] }}"@endif
            @if(!empty($settings['social_instagram'])),
            "{{ $settings['social_instagram'] }}"@endif
            @if(!empty($settings['social_tiktok'])),
            "{{ $settings['social_tiktok'] }}"@endif
        ]
    }
    </script>
@endsection

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

    /* ── Category marquee pills ── */
    .cat-marquee {
        overflow: hidden;
        margin: 0 -1rem;
        padding: .5rem 0;
    }
    @media(min-width:1024px) { .cat-marquee { margin: 0 -2rem; } }

    .cat-marquee-track {
        display: flex;
        gap: .75rem;
        width: max-content;
        animation: catScroll 35s linear infinite;
    }
    .cat-marquee:hover .cat-marquee-track { animation-play-state: paused; }

    @keyframes catScroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .cat-pill {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .7rem 1.25rem .7rem .7rem;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 100px;
        text-decoration: none;
        white-space: nowrap;
        transition: all .25s;
        flex-shrink: 0;
    }
    .cat-pill:hover {
        border-color: #d1d5db;
        background: #fafafa;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        transform: translateY(-2px);
    }

    .cat-pill-icon {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #6b7280;
        flex-shrink: 0;
        transition: all .25s;
    }
    .cat-pill:hover .cat-pill-icon {
        background: #111;
        color: #fff;
    }

    .cat-pill-name {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

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
    .benefits-section {
        background: linear-gradient(160deg, #141414 0%, #1a1a1a 50%, #111 100%);
        position: relative;
        overflow: hidden;
    }
    .benefits-section::before {
        content: '';
        position: absolute;
        width: 500px; height: 500px;
        border-radius: 50%;
        background: rgba(212,165,116,0.06);
        filter: blur(100px);
        top: -200px; right: -100px;
        pointer-events: none;
    }
    .benefit-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
    }
    .benefit-card:hover {
        background: rgba(255,255,255,0.06);
        border-color: rgba(212,165,116,0.2);
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.2);
    }
    .benefit-icon {
        transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
    }
    .benefit-card:hover .benefit-icon {
        background: linear-gradient(135deg, #D4A574, #C39563);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 8px 20px rgba(212,165,116,0.25);
    }

    /* ── Testimonial ── */
    /* ── Testimonials ── */
    .tm-card {
        position: relative;
        transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
    }
    .tm-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
    }
    .tm-card::before {
        content: '';
        position: absolute;
        top: 0; left: 24px; right: 24px; height: 3px;
        background: linear-gradient(90deg, #D4A574, #e8c9a0, #D4A574);
        border-radius: 0 0 4px 4px;
        opacity: 0;
        transform: scaleX(0);
        transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
    }
    .tm-card:hover::before {
        opacity: 1;
        transform: scaleX(1);
    }
    .tm-quote {
        position: absolute;
        font-family: Georgia, serif;
        font-size: 5rem;
        line-height: 1;
        color: rgba(212,165,116,0.08);
        top: 16px; left: 24px;
        pointer-events: none;
    }
    .tm-stars { --star-filled: #D4A574; --star-empty: #e5e7eb; }
    .tm-avatar {
        transition: transform 0.3s ease;
    }
    .tm-card:hover .tm-avatar {
        transform: scale(1.08);
    }

    /* ── Instagram grid ── */
    .insta-section {
        background: #111;
        position: relative;
        overflow: hidden;
    }
    .insta-section::before {
        content: '';
        position: absolute;
        width: 400px; height: 400px;
        border-radius: 50%;
        background: rgba(212,165,116,0.05);
        filter: blur(100px);
        bottom: -150px; left: 50%;
        transform: translateX(-50%);
        pointer-events: none;
    }
    .insta-item {
        transition: all .4s cubic-bezier(0.16,1,0.3,1);
    }
    .insta-item:hover {
        transform: scale(1.08);
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
    .nl-section {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(135deg, #faf7f4 0%, #f3ece3 35%, #ede4d8 65%, #f7f1ea 100%);
    }
    /* Animated blobs */
    .nl-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(90px);
        pointer-events: none;
    }
    .nl-blob-1 {
        width: 350px; height: 350px;
        background: rgba(212,165,116,0.15);
        top: -100px; right: -60px;
        animation: nlMove1 8s ease-in-out infinite alternate;
    }
    .nl-blob-2 {
        width: 280px; height: 280px;
        background: rgba(196,139,82,0.1);
        bottom: -80px; left: -40px;
        animation: nlMove2 10s ease-in-out infinite alternate;
    }
    .nl-blob-3 {
        width: 180px; height: 180px;
        background: rgba(212,165,116,0.08);
        top: 40%; left: 30%;
        animation: nlMove3 6s ease-in-out infinite alternate;
    }
    @keyframes nlMove1 { to { transform: translate(-50px, 40px) scale(1.15); } }
    @keyframes nlMove2 { to { transform: translate(40px, -30px) scale(1.1); } }
    @keyframes nlMove3 { to { transform: translate(30px, -25px) scale(0.85); } }
    /* Reveal */
    .nl-up {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.7s cubic-bezier(0.16,1,0.3,1), transform 0.7s cubic-bezier(0.16,1,0.3,1);
    }
    .nl-up.visible { opacity: 1; transform: translateY(0); }
    .nl-up-d1 { transition-delay: 0.12s; }
    .nl-up-d2 { transition-delay: 0.24s; }
    .nl-up-d3 { transition-delay: 0.36s; }
    .nl-up-d4 { transition-delay: 0.48s; }
    .nl-up-d5 { transition-delay: 0.6s; }
    /* Marquee */
    .nl-marquee {
        display: flex;
        width: max-content;
        animation: nlScroll 25s linear infinite;
    }
    @keyframes nlScroll { to { transform: translateX(-50%); } }
    /* Glass card */
    .nl-glass {
        background: rgba(255,255,255,0.55);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(212,165,116,0.12);
        box-shadow:
            0 4px 24px rgba(212,165,116,0.06),
            0 1px 3px rgba(0,0,0,0.04),
            inset 0 1px 0 rgba(255,255,255,0.7);
    }
    /* Form wrapper animated border */
    .nl-form-wrap {
        position: relative;
        background: #fff;
        border-radius: 9999px;
        padding: 5px;
        box-shadow: 0 8px 32px rgba(212,165,116,0.1), 0 2px 8px rgba(0,0,0,0.04);
    }
    .nl-form-wrap::before {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: inherit;
        background: conic-gradient(from var(--nl-a, 0deg), #D4A574, #e8c9a0, #D4A574, transparent, transparent, #D4A574);
        opacity: 0.35;
        z-index: -1;
        animation: nlBorder 4s linear infinite;
    }
    @property --nl-a {
        syntax: '<angle>';
        initial-value: 0deg;
        inherits: false;
    }
    @keyframes nlBorder { to { --nl-a: 360deg; } }
    @media (max-width: 639px) {
        .nl-form-wrap { border-radius: 1rem; padding: 10px; }
    }
    /* Input */
    .nl-input:focus {
        border-color: #D4A574;
        box-shadow: 0 0 0 4px rgba(212,165,116,0.1);
    }
    /* Button shine */
    .nl-btn { position: relative; overflow: hidden; }
    .nl-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.35) 50%, transparent 60%);
        transform: translateX(-100%);
    }
    .nl-btn:hover::after { animation: nlSweep 0.7s ease forwards; }
    @keyframes nlSweep { to { transform: translateX(100%); } }
    /* Particles */
    .nl-particle {
        position: absolute;
        width: 4px; height: 4px;
        border-radius: 50%;
        background: #D4A574;
        opacity: 0;
        pointer-events: none;
        animation: nlParticle 4s ease-in-out infinite;
    }
    @keyframes nlParticle {
        0% { opacity: 0; transform: translateY(0) scale(0.5); }
        30% { opacity: 0.3; }
        70% { opacity: 0.15; }
        100% { opacity: 0; transform: translateY(-60px) scale(0); }
    }
    /* Counter animation */
    .nl-counter {
        display: inline-block;
        font-variant-numeric: tabular-nums;
    }
    /* Pill hover lift */
    .nl-pill {
        transition: all 0.35s cubic-bezier(0.16,1,0.3,1);
    }
    .nl-pill:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(212,165,116,0.1);
        border-color: rgba(212,165,116,0.25);
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
                        <img src="https://i.pinimg.com/1200x/e0/4d/5e/e04d5ea05adc67cae665c023676b5ee6.jpg" alt="Arixna"
                            class="rounded-3xl shadow-2xl w-full object-cover aspect-[4/5] lg:aspect-[4/5]">
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
    <section class="py-20 lg:py-28 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10 lg:mb-14 reveal">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-8 h-[2px] bg-[#D4A574] rounded-full"></span>
                        <span class="text-[#D4A574] font-semibold text-xs tracking-[0.2em] uppercase">Colecciones</span>
                    </div>
                    <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 leading-tight">Explora por Categoría</h2>
                </div>
                <a href="{{ route('catalog') }}" class="text-sm font-medium text-gray-400 hover:text-gray-900 transition flex items-center gap-1.5 group">
                    Ver todas <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="cat-marquee reveal">
                <div class="cat-marquee-track">
                    @for($r = 0; $r < 2; $r++)
                    @foreach($categories as $index => $cat)
                        <a href="{{ route('catalog', ['categories' => [$cat->slug]]) }}" class="cat-pill group">
                            <span class="cat-pill-icon"><i class="{{ $cat->icon }}"></i></span>
                            <span class="cat-pill-name">{{ $cat->name }}</span>
                            <i class="fas fa-arrow-up-right text-[10px] text-gray-300 group-hover:text-gray-500 transition-all group-hover:-translate-y-0.5 group-hover:translate-x-0.5"></i>
                        </a>
                    @endforeach
                    @endfor
                </div>
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
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        loading="lazy" decoding="async">
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
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        loading="lazy" decoding="async">
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
            <div class="flex items-end justify-between mb-10 reveal">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-8 h-[2px] bg-[#D4A574] rounded-full"></span>
                        <span class="text-[#D4A574] font-semibold text-xs tracking-[0.2em] uppercase">Top Ventas</span>
                    </div>
                    <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900">Más Vendidos</h2>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <button class="group/btn w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white border border-gray-200 hover:border-[#D4A574] hover:shadow-md hover:shadow-[#D4A574]/10 transition-all duration-300 flex items-center justify-center" id="prevBtn" aria-label="Producto anterior">
                        <i class="fas fa-chevron-left text-[10px] sm:text-xs text-gray-400 group-hover/btn:text-[#D4A574] group-hover/btn:-translate-x-0.5 transition-all duration-300"></i>
                    </button>
                    <button class="group/btn w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-[#D4A574] text-white hover:bg-[#c99660] hover:shadow-md hover:shadow-[#D4A574]/25 transition-all duration-300 flex items-center justify-center" id="nextBtn" aria-label="Producto siguiente">
                        <i class="fas fa-chevron-right text-[10px] sm:text-xs group-hover/btn:translate-x-0.5 transition-all duration-300"></i>
                    </button>
                </div>
            </div>

            <div class="slider-container reveal">
                <div class="slider-track" id="sliderTrack">
                    @foreach($featuredProducts as $product)
                        <div class="min-w-[160px] sm:min-w-[280px] px-1.5 sm:px-2.5">
                            <div class="product-card-home bg-white rounded-2xl overflow-hidden border border-gray-100/80 group hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] hover:border-[#D4A574]/15 transition-all duration-500">
                                <a href="{{ route('product.show', $product->slug) }}" class="block relative overflow-hidden aspect-square">
                                    <img src="{{ $product->primaryImage?->thumbnail() ?? '' }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" loading="lazy">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    @if($product->discount_percentage)
                                        <div class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-red-500 text-white px-2 py-0.5 rounded-md text-[9px] sm:text-[10px] font-bold">
                                            -{{ $product->discount_percentage }}%
                                        </div>
                                    @endif
                                    <button type="button" class="wishlist-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-8 sm:h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-sm opacity-0 group-hover:opacity-100" data-product-id="{{ $product->id }}" aria-label="Agregar a lista de deseos">
                                        <i class="far fa-heart text-[10px] sm:text-xs"></i>
                                    </button>
                                    @if($product->stock <= 5 && $product->stock > 0)
                                        <div class="absolute bottom-2 left-2 sm:bottom-3 sm:left-3 bg-amber-500/90 backdrop-blur-sm text-white px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg text-[9px] sm:text-[10px] font-semibold flex items-center gap-1">
                                            <i class="fas fa-fire text-[8px] sm:text-[9px]"></i> ¡Últimas {{ $product->stock }}!
                                        </div>
                                    @endif
                                    {{-- Quick add overlay --}}
                                    <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 hidden sm:block">
                                        <button type="button"
                                                class="add-to-cart-btn w-full bg-white text-gray-900 py-2.5 rounded-xl hover:bg-[#D4A574] hover:text-white active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 font-semibold text-xs shadow-lg"
                                                data-product-id="{{ $product->id }}">
                                            <i class="fas fa-shopping-bag text-[10px]"></i> Agregar al carrito
                                        </button>
                                    </div>
                                </a>
                                <div class="p-3 sm:p-4 flex flex-col">
                                    <p class="text-[9px] sm:text-[10px] text-[#D4A574] font-semibold uppercase tracking-wider mb-1">{{ $product->category?->name }}</p>
                                    <a href="{{ route('product.show', $product->slug) }}" class="block">
                                        <h3 class="font-medium text-xs sm:text-sm mb-2 line-clamp-2 group-hover:text-[#D4A574] transition-colors leading-snug min-h-[2rem] sm:min-h-[2.5rem]">{{ $product->name }}</h3>
                                    </a>
                                    <div class="mt-auto">
                                        <div class="flex items-baseline gap-1.5 sm:gap-2">
                                            <span class="text-sm sm:text-lg font-bold text-gray-900">S/ {{ number_format($product->current_price, 2) }}</span>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <span class="text-[10px] sm:text-[11px] text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ ¿POR QUÉ ELEGIRNOS? ═══════════════════ --}}
    <section class="benefits-section py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12 reveal">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-8 h-[2px] bg-[#D4A574] rounded-full"></span>
                        <span class="text-[#D4A574] font-semibold text-xs tracking-[0.2em] uppercase">Nuestra promesa</span>
                    </div>
                    <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-tight">¿Por qué elegirnos?</h2>
                </div>
                <p class="text-gray-400 text-sm sm:text-base max-w-md leading-relaxed">Nos dedicamos a que cada regalo sea una experiencia inolvidable.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5 reveal">
                <div class="benefit-card rounded-2xl p-6 sm:p-7 group">
                    <div class="flex items-start gap-4">
                        <div class="benefit-icon w-12 h-12 rounded-xl border border-[#D4A574]/25 bg-[#D4A574]/10 text-[#D4A574] flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-gift text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm mb-1.5">Empaque Premium</h3>
                            <p class="text-gray-400 text-xs leading-relaxed">Empaque de regalo elegante sin costo adicional.</p>
                        </div>
                    </div>
                </div>
                <div class="benefit-card rounded-2xl p-6 sm:p-7 group">
                    <div class="flex items-start gap-4">
                        <div class="benefit-icon w-12 h-12 rounded-xl border border-[#D4A574]/25 bg-[#D4A574]/10 text-[#D4A574] flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shipping-fast text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm mb-1.5">Envío Rápido</h3>
                            <p class="text-gray-400 text-xs leading-relaxed">Entregamos en 24-48 horas para que llegue a tiempo.</p>
                        </div>
                    </div>
                </div>
                <div class="benefit-card rounded-2xl p-6 sm:p-7 group">
                    <div class="flex items-start gap-4">
                        <div class="benefit-icon w-12 h-12 rounded-xl border border-[#D4A574]/25 bg-[#D4A574]/10 text-[#D4A574] flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm mb-1.5">Compra Segura</h3>
                            <p class="text-gray-400 text-xs leading-relaxed">Pago protegido con encriptación y múltiples métodos.</p>
                        </div>
                    </div>
                </div>
                <div class="benefit-card rounded-2xl p-6 sm:p-7 group">
                    <div class="flex items-start gap-4">
                        <div class="benefit-icon w-12 h-12 rounded-xl border border-[#D4A574]/25 bg-[#D4A574]/10 text-[#D4A574] flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-headset text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm mb-1.5">Soporte 24/7</h3>
                            <p class="text-gray-400 text-xs leading-relaxed">Siempre disponibles para ayudarte por WhatsApp.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ NUEVOS PRODUCTOS (SLIDER) ═══════════════════ --}}
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-10 reveal">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-8 h-[2px] bg-[#D4A574] rounded-full"></span>
                        <span class="text-[#D4A574] font-semibold text-xs tracking-[0.2em] uppercase">Lo más reciente</span>
                    </div>
                    <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900">Recién Llegados</h2>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('catalog') }}" class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-gray-900 bg-gray-50 hover:bg-[#D4A574] hover:text-white px-5 py-2.5 rounded-full transition-all duration-300">
                        Ver todo <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                    <button class="group/btn w-11 h-11 rounded-full bg-white border border-gray-200 hover:border-[#D4A574] hover:shadow-md hover:shadow-[#D4A574]/10 transition-all duration-300 flex items-center justify-center" id="newPrevBtn" aria-label="Anterior">
                        <i class="fas fa-chevron-left text-xs text-gray-400 group-hover/btn:text-[#D4A574] group-hover/btn:-translate-x-0.5 transition-all duration-300"></i>
                    </button>
                    <button class="group/btn w-11 h-11 rounded-full bg-[#D4A574] text-white hover:bg-[#c99660] hover:shadow-md hover:shadow-[#D4A574]/25 transition-all duration-300 flex items-center justify-center" id="newNextBtn" aria-label="Siguiente">
                        <i class="fas fa-chevron-right text-xs group-hover/btn:translate-x-0.5 transition-all duration-300"></i>
                    </button>
                </div>
            </div>

            <div class="slider-container reveal">
                <div class="slider-track" id="newArrivalsTrack">
                    @foreach($newArrivals as $product)
                        <div class="min-w-[160px] sm:min-w-[280px] px-1.5 sm:px-2.5">
                            <div class="product-card-home bg-white rounded-2xl overflow-hidden border border-gray-100/80 group hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] hover:border-[#D4A574]/15 transition-all duration-500">
                                <a href="{{ route('product.show', $product->slug) }}" class="block relative overflow-hidden aspect-square">
                                    <img src="{{ $product->primaryImage?->thumbnail() ?? '' }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" loading="lazy">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    @if($product->discount_percentage)
                                        <div class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-red-500 text-white px-2 py-0.5 rounded-md text-[9px] sm:text-[10px] font-bold">
                                            -{{ $product->discount_percentage }}%
                                        </div>
                                    @endif
                                    <button type="button" class="wishlist-btn absolute top-2 right-2 sm:top-3 sm:right-3 w-7 h-7 sm:w-8 sm:h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-200 shadow-sm opacity-0 group-hover:opacity-100" data-product-id="{{ $product->id }}" aria-label="Agregar a lista de deseos">
                                        <i class="far fa-heart text-[10px] sm:text-xs"></i>
                                    </button>
                                    {{-- Quick add overlay --}}
                                    <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 hidden sm:block">
                                        <button type="button"
                                                class="add-to-cart-btn w-full bg-white text-gray-900 py-2.5 rounded-xl hover:bg-[#D4A574] hover:text-white active:scale-[0.97] transition-all duration-200 flex items-center justify-center gap-2 font-semibold text-xs shadow-lg"
                                                data-product-id="{{ $product->id }}">
                                            <i class="fas fa-shopping-bag text-[10px]"></i> Agregar al carrito
                                        </button>
                                    </div>
                                </a>
                                <div class="p-3 sm:p-4 flex flex-col">
                                    <p class="text-[9px] sm:text-[10px] text-[#D4A574] font-semibold uppercase tracking-wider mb-1">{{ $product->category?->name }}</p>
                                    <a href="{{ route('product.show', $product->slug) }}" class="block">
                                        <h3 class="font-medium text-xs sm:text-sm mb-2 line-clamp-2 group-hover:text-[#D4A574] transition-colors leading-snug min-h-[2rem] sm:min-h-[2.5rem]">{{ $product->name }}</h3>
                                    </a>
                                    <div class="mt-auto">
                                        <div class="flex items-baseline gap-1.5 sm:gap-2">
                                            <span class="text-sm sm:text-lg font-bold text-gray-900">S/ {{ number_format($product->current_price, 2) }}</span>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <span class="text-[10px] sm:text-[11px] text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="sm:hidden text-center mt-6">
                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-900 bg-gray-50 px-5 py-2.5 rounded-full">
                    Ver todos los productos <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════ BANNER CTA FULLWIDTH ═══════════════════ --}}
    <section class="relative overflow-hidden reveal">
        <div class="absolute inset-0">
            <img src="https://i.pinimg.com/736x/cb/ef/6e/cbef6e099db2a2b90825505936e65985.jpg" alt="Background" class="w-full h-full object-cover" loading="lazy" decoding="async">
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
    <section class="py-20 lg:py-28 bg-[#FAF8F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12 reveal">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-8 h-[2px] bg-[#D4A574] rounded-full"></span>
                        <span class="text-[#D4A574] font-semibold text-xs tracking-[0.2em] uppercase">Testimonios</span>
                    </div>
                    <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 leading-tight">Lo que dicen<br class="hidden sm:block"> nuestros clientes</h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1">
                        @for($s = 0; $s < 5; $s++)
                            <i class="fas fa-star text-[#D4A574] text-sm"></i>
                        @endfor
                    </div>
                    <div class="h-5 w-px bg-gray-200"></div>
                    <p class="text-sm text-gray-500"><span class="font-bold text-gray-900">4.9</span> promedio</p>
                </div>
            </div>

            @php
                $gradients = [
                    'from-[#D4A574] to-[#c48f55]',
                    'from-[#8B7355] to-[#6B5B45]',
                    'from-[#C19A6B] to-[#A67C52]',
                    'from-[#B8956A] to-[#9A7B5A]',
                    'from-[#D4A574] to-[#B8864E]',
                    'from-[#A68B6B] to-[#8B7355]',
                ];

                $fallbackTestimonials = [
                    ['name' => 'María González', 'text' => 'El collar que compré superó todas mis expectativas. La calidad es excepcional y mi pareja quedó encantada. ¡Definitivamente volveré a comprar!', 'rating' => 5, 'initials' => 'MG', 'gradient' => $gradients[0], 'product' => null],
                    ['name' => 'Carlos Ramírez', 'text' => 'La rosa eterna es simplemente hermosa. Llegó perfectamente empaquetada y el detalle es impresionante. Un regalo perfecto para aniversarios.', 'rating' => 5, 'initials' => 'CR', 'gradient' => $gradients[1], 'product' => null],
                    ['name' => 'Ana Martínez', 'text' => 'Excelente servicio al cliente y envío rápido. Los productos son de alta calidad y los precios muy competitivos. ¡Totalmente recomendado!', 'rating' => 5, 'initials' => 'AM', 'gradient' => $gradients[2], 'product' => null],
                ];
            @endphp

            <div class="grid md:grid-cols-3 gap-5 lg:gap-6 reveal">
                @if($reviews->count() > 0)
                    @foreach($reviews->take(3) as $index => $review)
                        <div class="tm-card bg-white rounded-2xl border border-gray-100/80 p-7 sm:p-8 relative overflow-hidden">
                            <span class="tm-quote">"</span>
                            <div class="relative z-10">
                                {{-- Stars --}}
                                <div class="tm-stars flex gap-0.5 mb-5">
                                    @for($s = 1; $s <= 5; $s++)
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="{{ $s <= $review->rating ? 'var(--star-filled)' : 'var(--star-empty)' }}">
                                            <path d="M8 0l2.47 4.94L16 5.73l-4 3.82.94 5.45L8 12.47 3.06 15l.94-5.45-4-3.82 5.53-.79z"/>
                                        </svg>
                                    @endfor
                                </div>

                                {{-- Comment --}}
                                @if($review->title)
                                    <h3 class="font-bold text-gray-900 text-sm mb-2">{{ $review->title }}</h3>
                                @endif
                                <p class="text-gray-600 text-sm leading-relaxed mb-7">{{ Str::limit($review->comment, 180) }}</p>

                                {{-- Author --}}
                                <div class="flex items-center gap-3 pt-5 border-t border-gray-100">
                                    <div class="tm-avatar w-10 h-10 bg-gradient-to-br {{ $gradients[$index % count($gradients)] }} rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                        {{ strtoupper(substr($review->user->first_name, 0, 1) . substr($review->user->last_name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">{{ $review->user->full_name }}</p>
                                        @if($review->product)
                                            <a href="{{ route('product.show', $review->product->slug) }}" class="text-[11px] text-[#D4A574] hover:underline truncate block">
                                                {{ Str::limit($review->product->name, 30) }}
                                            </a>
                                        @else
                                            <p class="text-[11px] text-gray-400">Compra verificada</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach($fallbackTestimonials as $index => $t)
                        <div class="tm-card bg-white rounded-2xl border border-gray-100/80 p-7 sm:p-8 relative overflow-hidden">
                            <span class="tm-quote">"</span>
                            <div class="relative z-10">
                                {{-- Stars --}}
                                <div class="tm-stars flex gap-0.5 mb-5">
                                    @for($s = 0; $s < $t['rating']; $s++)
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="var(--star-filled)">
                                            <path d="M8 0l2.47 4.94L16 5.73l-4 3.82.94 5.45L8 12.47 3.06 15l.94-5.45-4-3.82 5.53-.79z"/>
                                        </svg>
                                    @endfor
                                </div>

                                {{-- Comment --}}
                                <p class="text-gray-600 text-sm leading-relaxed mb-7">{{ $t['text'] }}</p>

                                {{-- Author --}}
                                <div class="flex items-center gap-3 pt-5 border-t border-gray-100">
                                    <div class="tm-avatar w-10 h-10 bg-gradient-to-br {{ $t['gradient'] }} rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                        {{ $t['initials'] }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm">{{ $t['name'] }}</p>
                                        <p class="text-[11px] text-gray-400">Compra verificada</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    {{-- ═══════════════════ INSTAGRAM FEED ═══════════════════ --}}
    <section class="insta-section py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10 reveal">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-8 h-[2px] bg-[#D4A574] rounded-full"></span>
                        @php $instaHandle = '@' . trim(parse_url($settings['instagram_url'] ?? '', PHP_URL_PATH) ?: 'instagram', '/'); @endphp
                        <span class="text-[#D4A574] font-semibold text-xs tracking-[0.2em] uppercase">{{ $instaHandle }}</span>
                    </div>
                    <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-tight">Síguenos en Instagram</h2>
                </div>
                <a href="{{ $settings['instagram_url'] ?? '#' }}" target="_blank" rel="noopener"
                    class="text-white/50 hover:text-white text-sm font-medium transition-all duration-300 flex items-center gap-2 group self-start sm:self-auto">
                    <i class="fab fa-instagram text-lg"></i>
                    <span>{{ $instaHandle }}</span>
                    <i class="fas fa-arrow-up-right text-[9px] opacity-0 group-hover:opacity-100 transition-all"></i>
                </a>
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
            <div class="grid grid-cols-3 md:grid-cols-6 gap-2 sm:gap-3 reveal">
                @foreach($instaImages as $img)
                    <div class="insta-item relative rounded-xl sm:rounded-2xl overflow-hidden aspect-square cursor-pointer">
                        <img src="{{ $img }}" alt="Instagram" class="w-full h-full object-cover" loading="lazy">
                        <div class="insta-overlay absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent flex flex-col items-center justify-center gap-1.5">
                            <i class="fab fa-instagram text-white text-2xl sm:text-3xl"></i>
                            <span class="text-white/80 text-[10px] font-semibold tracking-wide hidden sm:block">{{ $instaHandle }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════ NEWSLETTER ═══════════════════ --}}
    <section class="nl-section py-20 sm:py-24 lg:py-32" id="nlSection">
        {{-- Animated blobs --}}
        <div class="nl-blob nl-blob-1"></div>
        <div class="nl-blob nl-blob-2"></div>
        <div class="nl-blob nl-blob-3"></div>

        {{-- Floating particles --}}
        <div class="nl-particle" style="left:12%;bottom:30%;animation-delay:0s"></div>
        <div class="nl-particle" style="left:32%;bottom:18%;animation-delay:1.2s"></div>
        <div class="nl-particle" style="left:52%;bottom:25%;animation-delay:0.6s"></div>
        <div class="nl-particle" style="left:72%;bottom:35%;animation-delay:1.8s"></div>
        <div class="nl-particle" style="left:88%;bottom:22%;animation-delay:2.4s"></div>
        <div class="nl-particle" style="left:22%;bottom:40%;animation-delay:3s"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Marquee --}}
            <div class="overflow-hidden mb-10 sm:mb-14 nl-up">
                <div class="nl-marquee">
                    @for($i = 0; $i < 2; $i++)
                    <span class="flex items-center gap-8 sm:gap-12 whitespace-nowrap px-6">
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.25em] uppercase text-[#D4A574]/40">Ofertas Exclusivas</span>
                        <span class="text-[#D4A574]/25 text-lg">&#10022;</span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.25em] uppercase text-[#D4A574]/40">Novedades</span>
                        <span class="text-[#D4A574]/25 text-lg">&#10022;</span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.25em] uppercase text-[#D4A574]/40">Acceso VIP</span>
                        <span class="text-[#D4A574]/25 text-lg">&#10022;</span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.25em] uppercase text-[#D4A574]/40">Descuentos</span>
                        <span class="text-[#D4A574]/25 text-lg">&#10022;</span>
                        <span class="text-[11px] sm:text-xs font-bold tracking-[0.25em] uppercase text-[#D4A574]/40">Lanzamientos</span>
                        <span class="text-[#D4A574]/25 text-lg">&#10022;</span>
                    </span>
                    @endfor
                </div>
            </div>

            {{-- Glass card --}}
            <div class="nl-glass rounded-2xl sm:rounded-3xl lg:rounded-[2rem] p-5 sm:p-10 lg:p-14 nl-up nl-up-d1">
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-14 items-center">
                    {{-- Left: Content + Form --}}
                    <div>
                        {{-- Badge --}}
                        <div class="nl-up nl-up-d2 mb-4 sm:mb-5">
                            <span class="inline-flex items-center gap-2 bg-gradient-to-r from-[#D4A574]/15 to-[#D4A574]/5 border border-[#D4A574]/15 rounded-full px-3 sm:px-4 py-1 sm:py-1.5">
                                <span class="relative flex h-1.5 w-1.5 sm:h-2 sm:w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#D4A574] opacity-50"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 sm:h-2 sm:w-2 bg-[#D4A574]"></span>
                                </span>
                                <span class="text-[10px] sm:text-[11px] font-bold tracking-wide text-[#b8956a]">Nuevas colecciones cada semana</span>
                            </span>
                        </div>

                        {{-- Headline --}}
                        <h2 class="nl-up nl-up-d2 font-serif text-2xl sm:text-4xl lg:text-5xl font-bold text-gray-900 leading-[1.08] mb-3 sm:mb-4">
                            No te pierdas<br>
                            <span class="relative inline-block text-[#D4A574]">
                                nada
                                <svg class="absolute -bottom-1 sm:-bottom-1.5 left-0 w-full" height="8" viewBox="0 0 120 10" fill="none" preserveAspectRatio="none">
                                    <path d="M2 7 C30 2, 90 2, 118 7" stroke="#D4A574" stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.3">
                                        <animate attributeName="d" values="M2 7 C30 2, 90 2, 118 7;M2 5 C30 9, 90 9, 118 5;M2 7 C30 2, 90 2, 118 7" dur="4s" repeatCount="indefinite"/>
                                    </path>
                                </svg>
                            </span>
                        </h2>

                        <p class="nl-up nl-up-d3 text-gray-500 text-xs sm:text-base leading-relaxed mb-6 sm:mb-8">
                            Únete a nuestra comunidad y recibe ofertas exclusivas, acceso anticipado y contenido curado solo para ti.
                        </p>

                        {{-- Form --}}
                        <form id="newsletterForm" class="nl-up nl-up-d3">
                            {{-- Mobile: stacked with visible input --}}
                            <div class="sm:hidden space-y-3">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="far fa-envelope text-[#D4A574]/50 text-sm"></i>
                                    </div>
                                    <input type="email" id="newsletterEmailMobile" placeholder="Tu correo electrónico"
                                        class="nl-input w-full pl-10 pr-4 py-3.5 bg-white border border-gray-200 text-gray-900 text-sm placeholder:text-gray-400 focus:outline-none rounded-xl transition-all duration-300">
                                </div>
                                <button type="submit"
                                    class="nl-btn w-full py-3.5 rounded-xl font-semibold text-sm bg-gray-900 text-white hover:bg-[#D4A574] active:scale-[0.97] transition-all duration-300 flex items-center justify-center gap-2">
                                    Suscribirme
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </button>
                            </div>
                            {{-- Desktop: pill form with animated border --}}
                            <div class="hidden sm:block">
                                <div class="nl-form-wrap">
                                    <div class="flex gap-2">
                                        <input type="email" id="newsletterEmail" placeholder="Tu correo electrónico"
                                            class="nl-input flex-1 px-6 py-3.5 bg-transparent text-gray-900 text-sm placeholder:text-gray-400 focus:outline-none rounded-full transition-all duration-300">
                                        <button type="submit" id="newsletterBtn"
                                            class="nl-btn px-7 py-3.5 rounded-full font-semibold text-sm bg-gray-900 text-white hover:bg-[#D4A574] active:scale-[0.97] transition-all duration-300 whitespace-nowrap flex items-center justify-center gap-2">
                                            Suscribirme
                                            <i class="fas fa-arrow-right text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-400 text-[10px] sm:text-[11px] mt-2.5 sm:mt-3 flex items-center gap-1.5" id="newsletterHint">
                                <i class="fas fa-lock text-[#D4A574]/40 text-[8px] sm:text-[9px]"></i>
                                Sin spam, cancela cuando quieras.
                            </p>
                        </form>
                    </div>

                    {{-- Right: Stats + Pills --}}
                    <div class="nl-up nl-up-d4">
                        {{-- Counter stats --}}
                        <div class="grid grid-cols-3 gap-3 sm:gap-4 mb-5 sm:mb-6">
                            <div class="text-center">
                                <p class="text-xl sm:text-3xl lg:text-4xl font-bold text-gray-900 nl-counter" data-target="2000" data-suffix="+">0</p>
                                <p class="text-gray-400 text-[9px] sm:text-xs mt-0.5 sm:mt-1 font-medium">Suscriptoras</p>
                            </div>
                            <div class="text-center border-x border-gray-200/60">
                                <p class="text-xl sm:text-3xl lg:text-4xl font-bold text-[#D4A574] nl-counter" data-target="50" data-suffix="+">0</p>
                                <p class="text-gray-400 text-[9px] sm:text-xs mt-0.5 sm:mt-1 font-medium">Marcas</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xl sm:text-3xl lg:text-4xl font-bold text-gray-900">1<span class="text-sm sm:text-lg text-gray-400 font-normal">x</span></p>
                                <p class="text-gray-400 text-[9px] sm:text-xs mt-0.5 sm:mt-1 font-medium">Por semana</p>
                            </div>
                        </div>

                        {{-- Feature pills --}}
                        <div class="nl-up nl-up-d5 flex flex-wrap gap-2">
                            <div class="nl-pill flex items-center gap-2 bg-white/80 border border-gray-100 rounded-full px-3 sm:px-4 py-2 cursor-default">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-[#D4A574]/20 to-[#D4A574]/5 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-gift text-[#D4A574] text-[9px] sm:text-xs"></i>
                                </div>
                                <span class="text-gray-700 text-[10px] sm:text-xs font-semibold">Ofertas exclusivas</span>
                            </div>
                            <div class="nl-pill flex items-center gap-2 bg-white/80 border border-gray-100 rounded-full px-3 sm:px-4 py-2 cursor-default">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-[#D4A574]/20 to-[#D4A574]/5 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-bolt text-[#D4A574] text-[9px] sm:text-xs"></i>
                                </div>
                                <span class="text-gray-700 text-[10px] sm:text-xs font-semibold">Acceso anticipado</span>
                            </div>
                            <div class="nl-pill flex items-center gap-2 bg-white/80 border border-gray-100 rounded-full px-3 sm:px-4 py-2 cursor-default">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-[#D4A574]/20 to-[#D4A574]/5 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-crown text-[#D4A574] text-[9px] sm:text-xs"></i>
                                </div>
                                <span class="text-gray-700 text-[10px] sm:text-xs font-semibold">Contenido VIP</span>
                            </div>
                            <div class="nl-pill flex items-center gap-2 bg-white/80 border border-gray-100 rounded-full px-3 sm:px-4 py-2 cursor-default">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-[#D4A574]/20 to-[#D4A574]/5 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-heart text-[#D4A574] text-[9px] sm:text-xs"></i>
                                </div>
                                <span class="text-gray-700 text-[10px] sm:text-xs font-semibold">Curado para ti</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    // ── Newsletter Scroll Animations ──
    (function() {
        var nlSection = document.getElementById('nlSection');
        if (!nlSection) return;
        var upEls = nlSection.querySelectorAll('.nl-up');
        var counters = nlSection.querySelectorAll('.nl-counter');
        var triggered = false;

        function animateCounter(el) {
            var target = parseInt(el.dataset.target) || 0;
            var suffix = el.dataset.suffix || '';
            var duration = 1800;
            var start = performance.now();

            function step(now) {
                var progress = Math.min((now - start) / duration, 1);
                var ease = 1 - Math.pow(1 - progress, 3);
                var current = Math.floor(ease * target);
                el.textContent = (target >= 1000 ? (current / 1000).toFixed(current >= target ? 0 : 1).replace('.0', '') + 'K' : current) + suffix;
                if (progress < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting && !triggered) {
                    triggered = true;
                    upEls.forEach(function(el) { el.classList.add('visible'); });
                    setTimeout(function() {
                        counters.forEach(function(c) { animateCounter(c); });
                    }, 500);
                }
            });
        }, { threshold: 0.1 });

        observer.observe(nlSection);
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

    // ── Touch/Swipe Support ──
    var touchStartX = 0;
    var touchStartY = 0;
    var touchSwiping = false;

    sliderTrack.parentElement.addEventListener('touchstart', function(e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
        touchSwiping = true;
        clearInterval(autoplayTimer);
    }, { passive: true });

    sliderTrack.parentElement.addEventListener('touchmove', function(e) {
        if (!touchSwiping) return;
        var diffX = Math.abs(e.touches[0].clientX - touchStartX);
        var diffY = Math.abs(e.touches[0].clientY - touchStartY);
        if (diffX > diffY && diffX > 10) {
            e.preventDefault();
        }
    }, { passive: false });

    sliderTrack.parentElement.addEventListener('touchend', function(e) {
        if (!touchSwiping) return;
        touchSwiping = false;
        var touchEndX = e.changedTouches[0].clientX;
        var diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 40) {
            isTransitioning = false;
            if (diff > 0) {
                slideNext();
            } else {
                slidePrev();
            }
        }
        startAutoplay();
    }, { passive: true });

    // ── Recién Llegados Slider ──
    (function() {
        var track = document.getElementById('newArrivalsTrack');
        var prevBtn = document.getElementById('newPrevBtn');
        var nextBtn = document.getElementById('newNextBtn');
        if (!track || !track.children.length) return;

        var transitioning = false;
        var autoTimer;
        var count = {{ count($newArrivals) }};
        var idx = 0;

        // Clone slides for infinite loop
        Array.from(track.children).forEach(function(slide) {
            var clone = slide.cloneNode(true);
            clone.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
                btn.addEventListener('click', handleCartClick);
            });
            track.appendChild(clone);
        });

        function slideWidth() {
            return track.children[0] ? track.children[0].offsetWidth : 280;
        }

        function moveTo(i, animate) {
            track.style.transition = animate !== false ? 'transform 0.5s ease' : 'none';
            track.style.transform = 'translateX(-' + (i * slideWidth()) + 'px)';
            idx = i;
        }

        function next() {
            if (transitioning) return;
            transitioning = true;
            idx++;
            moveTo(idx);
        }

        function prev() {
            if (transitioning) return;
            if (idx <= 0) {
                moveTo(count, false);
                track.offsetHeight;
                transitioning = true;
                idx = count - 1;
                moveTo(idx);
            } else {
                transitioning = true;
                idx--;
                moveTo(idx);
            }
        }

        track.addEventListener('transitionend', function() {
            transitioning = false;
            if (idx >= count) moveTo(idx - count, false);
        });

        if (nextBtn) nextBtn.addEventListener('click', function() { next(); resetAuto(); });
        if (prevBtn) prevBtn.addEventListener('click', function() { prev(); resetAuto(); });

        function startAuto() { autoTimer = setInterval(next, 5500); }
        function resetAuto() { clearInterval(autoTimer); startAuto(); }
        startAuto();

        track.parentElement.addEventListener('mouseenter', function() { clearInterval(autoTimer); });
        track.parentElement.addEventListener('mouseleave', function() { startAuto(); });

        // Touch/Swipe
        var sx = 0, sy = 0, swiping = false;
        track.parentElement.addEventListener('touchstart', function(e) {
            sx = e.touches[0].clientX; sy = e.touches[0].clientY;
            swiping = true; clearInterval(autoTimer);
        }, { passive: true });
        track.parentElement.addEventListener('touchmove', function(e) {
            if (!swiping) return;
            if (Math.abs(e.touches[0].clientX - sx) > Math.abs(e.touches[0].clientY - sy) && Math.abs(e.touches[0].clientX - sx) > 10) {
                e.preventDefault();
            }
        }, { passive: false });
        track.parentElement.addEventListener('touchend', function(e) {
            if (!swiping) return;
            swiping = false;
            var diff = sx - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 40) {
                transitioning = false;
                diff > 0 ? next() : prev();
            }
            startAuto();
        }, { passive: true });
    })();

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

            if (typeof openCartSidebar === 'function') openCartSidebar();

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

    // ── Newsletter AJAX ──
    document.getElementById('newsletterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var mobileInput = document.getElementById('newsletterEmailMobile');
        var desktopInput = document.getElementById('newsletterEmail');
        var isMobile = mobileInput && mobileInput.offsetParent !== null;
        var emailInput = isMobile ? mobileInput : desktopInput;
        var btn = e.target.querySelector('button[type="submit"]');
        var email = emailInput.value.trim();

        if (!email) return;

        var originalHTML = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';

        fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
        .then(function(result) {
            if (result.ok) {
                showToast(result.data.message);
                emailInput.value = '';
            } else {
                var errors = result.data.errors;
                var msg = errors && errors.email ? errors.email[0] : 'Ocurrió un error, intenta de nuevo.';
                showToast(msg);
            }
        })
        .catch(function() {
            showToast('Error de conexión, intenta de nuevo.');
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        });
    });
</script>
@endsection
