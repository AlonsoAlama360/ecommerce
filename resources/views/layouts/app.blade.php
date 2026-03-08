<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($settings['business_name'] ?? 'Arixna') . ' - Tu Tienda Online')</title>

    {{-- Favicon --}}
    @php $faviconUrl = !empty($settings['site_favicon']) ? asset('storage/' . $settings['site_favicon']) : asset('images/logo_arixna1024512_min.webp'); @endphp
    <link rel="icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    {{-- SEO Meta Tags --}}
    <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', 'tienda online, perfumes, electrodomésticos, joyería, anillos, zapatillas, Perú, envíos')">
    <meta name="author" content="{{ $settings['business_name'] ?? 'Arixna' }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph (Facebook, WhatsApp, Messenger) --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $settings['business_name'] ?? 'Arixna' }}">
    <meta property="og:title" content="@yield('og_title', ($settings['business_name'] ?? 'Arixna') . ' - Tu Tienda Online')">
    <meta property="og:description" content="@yield('og_description', $settings['meta_description'] ?? '')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo_arixna.png'))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:locale" content="es_PE">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('og_title', 'Arixna - Tu Tienda Online')">
    <meta name="twitter:description" content="@yield('og_description', 'Descubre perfumes, electrodomésticos, joyería y zapatillas en Arixna. Envíos a todo el Perú.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/logo_arixna.png'))">

    {{-- Global Structured Data: SiteNavigationElement como ItemList --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "ItemList",
        "name": "Navegación principal de Arixna",
        "itemListElement": [
            {
                "@@type": "SiteNavigationElement",
                "position": 1,
                "name": "Catálogo",
                "description": "Explora todos nuestros productos: perfumes, electrodomésticos, joyería y zapatillas",
                "url": "{{ route('catalog') }}"
            },
            {
                "@@type": "SiteNavigationElement",
                "position": 2,
                "name": "Ofertas",
                "description": "Descubre las mejores ofertas y descuentos en productos seleccionados",
                "url": "{{ route('ofertas') }}"
            },
            {
                "@@type": "SiteNavigationElement",
                "position": 3,
                "name": "Contacto",
                "description": "Contáctanos para consultas, pedidos especiales o soporte al cliente",
                "url": "{{ route('contact.show') }}"
            },
            {
                "@@type": "SiteNavigationElement",
                "position": 4,
                "name": "Preguntas Frecuentes",
                "description": "Resuelve tus dudas sobre envíos, pagos, devoluciones y más",
                "url": "{{ route('legal.faq') }}"
            },
            {
                "@@type": "SiteNavigationElement",
                "position": 5,
                "name": "Términos y Condiciones",
                "description": "Conoce nuestros términos y condiciones de servicio",
                "url": "{{ route('legal.terms') }}"
            }
        ]
    }
    </script>

    @yield('seo')

    {{-- Google Fonts: carga asíncrona para no bloquear el renderizado --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"></noscript>

    {{-- Vite: Tailwind CSS + Font Awesome + custom styles + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @hasSection('styles')
    <style>@yield('styles')</style>
    @endif
</head>

<body
    data-authenticated="{{ Auth::check() ? '1' : '0' }}"
    data-login-url="{{ route('login') }}"
    data-wishlist-toggle-url="{{ route('wishlist.toggle') }}"
    data-wishlist-count-url="{{ route('wishlist.count') }}"
>
    <!-- Toast container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Overlay para el menú móvil -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

    <!-- Menú móvil lateral -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <img src="{{ !empty($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('images/logo_arixna.png') }}"
                    alt="Logo" class="h-10">
            </div>
            <button id="closeMobileMenu" class="text-gray-600 hover:text-gray-900" aria-label="Cerrar menú">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <nav class="p-6">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('home') }}"
                        class="block py-3 px-4 text-gray-900 font-medium hover:bg-gray-50 rounded-lg transition">
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="{{ route('catalog') }}" class="block py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        Catálogo
                    </a>
                </li>
                <li>
                    <div>
                        <button
                            class="w-full flex items-center justify-between py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition"
                            id="mobileCategoriesBtn">
                            <span>Categorías</span>
                            <i class="fas fa-chevron-down text-sm transition-transform" id="mobileCategoriesIcon"></i>
                        </button>
                        <div class="mobile-category-submenu pl-4" id="mobileCategorySubmenu">
                            <ul class="space-y-1 mt-2">
                                @foreach($navCategories as $cat)
                                    <li><a href="{{ route('catalog', ['categories' => [$cat->slug]]) }}" class="block py-2.5 px-4 text-gray-600 hover:bg-gray-50 rounded-lg transition"><i class="{{ $cat->icon }} mr-2 accent-color"></i>{{ $cat->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="{{ route('ofertas') }}" class="block py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        Ofertas
                    </a>
                </li>
            </ul>

            <div class="mt-8 pt-6 border-t border-gray-200">
                @auth
                    <div class="px-4 py-3">
                        <p class="font-semibold text-gray-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-user text-lg"></i>
                        <span>Mi Perfil</span>
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-shopping-bag text-lg"></i>
                        <span>Mis Pedidos</span>
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-heart text-lg text-[#D4A574]"></i>
                        <span>Lista de Deseos</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 py-3 px-4 text-red-600 hover:bg-red-50 rounded-lg transition w-full">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-user text-lg"></i>
                        <span>Iniciar sesión</span>
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-user-plus text-lg"></i>
                        <span>Crear cuenta</span>
                    </a>
                @endauth
            </div>
        </nav>
    </div>

    <!-- Header -->
    <header class="bg-white/95 backdrop-blur-sm sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <img src="{{ !empty($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('images/logo_arixna.png') }}"
                            alt="Logo" class="h-14">
                    </a>
                </div>

                <!-- Navigation - Solo visible en desktop -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-900 hover:text-gray-600 font-medium transition">Inicio</a>

                    <!-- Categorías con Mega Menu -->
                    <div class="relative group">
                        <button class="text-gray-700 hover:text-gray-900 transition flex items-center space-x-1"
                            id="categoriesBtn">
                            <span>Categorías</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <div class="mega-menu absolute top-full left-0 mt-2 bg-white shadow-2xl rounded-lg p-6"
                            id="megaMenu">
                            <div class="mega-menu-content">
                                <div class="category-list space-y-2">
                                    <h2 class="font-serif text-lg font-semibold mb-3 px-3">Categorías</h2>
                                    @foreach($navCategories as $cat)
                                        <a href="{{ route('catalog', ['categories' => [$cat->slug]]) }}" class="block text-gray-700 hover:text-gray-900 hover:bg-gray-50 py-2.5 px-3 rounded-lg transition category-item" data-category="{{ $cat->slug }}"><i class="{{ $cat->icon }} mr-2 accent-color"></i>{{ $cat->name }}</a>
                                    @endforeach
                                </div>
                                <div class="product-hover-panel" id="productPanel">
                                    <h3 class="font-serif text-base font-semibold mb-3">Productos Destacados</h3>
                                    <div class="grid grid-cols-2 gap-3" id="productGrid"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('catalog') }}" class="text-gray-700 hover:text-gray-900 transition">Catálogo</a>
                    <a href="{{ route('ofertas') }}" class="text-gray-700 hover:text-gray-900 transition">Ofertas</a>
                </nav>

                <!-- Icons -->
                <div class="flex items-center space-x-4 sm:space-x-6">
                    <button class="text-gray-700 hover:text-gray-900 transition" id="searchBtn" aria-label="Buscar productos">
                        <i class="fas fa-search text-xl"></i>
                    </button>

                    <!-- Carrito -->
                    <div class="relative">
                        <button class="text-gray-700 hover:text-gray-900 transition relative" id="cartBtn" aria-label="Abrir carrito">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
                            <span class="cart-badge absolute -top-2 -right-2 bg-[#E8B4B8] text-white text-xs rounded-full w-5 h-5 items-center justify-center {{ $cartCount > 0 ? 'flex' : 'hidden' }}">{{ $cartCount }}</span>
                        </button>
                    </div>

                    <!-- Icono de usuario - Desktop -->
                    <div class="hidden lg:block relative">
                        @auth
                            {{-- Usuario autenticado: avatar con dropdown --}}
                            <button class="flex items-center gap-2 text-gray-700 hover:text-gray-900 transition" id="userBtn" aria-label="Menú de usuario">
                                <div class="w-9 h-9 bg-gradient-to-br from-[#D4A574] to-[#C39563] rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                                </div>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div class="user-dropdown absolute top-full right-0 mt-2 w-64 bg-white shadow-2xl rounded-xl overflow-hidden" id="userDropdown">
                                <div class="px-5 py-4 bg-gradient-to-r from-[#FAF8F5] to-[#F5E6D3] border-b border-gray-100">
                                    <p class="font-semibold text-gray-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-gray-50 transition">
                                        <i class="fas fa-user w-5 text-center text-[#D4A574]"></i>
                                        <span>Mi Perfil</span>
                                    </a>
                                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-gray-50 transition">
                                        <i class="fas fa-shopping-bag w-5 text-center text-[#D4A574]"></i>
                                        <span>Mis Pedidos</span>
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-gray-50 transition">
                                        <i class="fas fa-heart w-5 text-center text-[#D4A574]"></i>
                                        <span>Lista de Deseos</span>
                                    </a>
                                </div>
                                <div class="border-t border-gray-100 py-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 px-5 py-3 text-red-600 hover:bg-red-50 transition w-full">
                                            <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                            <span>Cerrar Sesión</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            {{-- Usuario no autenticado: icono simple --}}
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 transition" aria-label="Iniciar sesión">
                                <i class="fas fa-user text-xl"></i>
                            </a>
                        @endauth
                    </div>

                    <!-- Botón hamburguesa solo en móvil -->
                    <button class="lg:hidden text-gray-700 hover:text-gray-900 transition" id="hamburgerBtn" aria-label="Abrir menú">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Modal de Búsqueda -->
    <div class="search-modal fixed inset-0 bg-black/40 backdrop-blur-md z-50 items-center justify-center sm:justify-start sm:pt-[15vh]" id="searchModal">
        <div class="search-modal-card bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-7 max-w-2xl w-full mx-3 sm:mx-auto relative shadow-2xl" style="max-height: 75vh; display: flex; flex-direction: column;">

            {{-- Header con título y cerrar --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#E8B4B8]/20 to-[#D4A574]/10 flex items-center justify-center">
                        <i class="fas fa-search text-[#D4A574] text-xs"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 tracking-wide">Buscar</h3>
                </div>
                <button class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all" id="closeSearchBtn" aria-label="Cerrar búsqueda">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            {{-- Input de búsqueda --}}
            <div class="search-input-wrap mb-4">
                <div class="flex items-center px-4 sm:px-5">
                    <i class="fas fa-search text-gray-300 text-sm mr-3"></i>
                    <input type="text" placeholder="¿Qué estás buscando hoy?"
                        class="w-full py-3.5 sm:py-4 text-[15px] sm:text-base text-gray-800 placeholder-gray-400 focus:outline-none"
                        id="searchInput" autocomplete="off">
                    <div class="hidden" id="searchSpinner">
                        <i class="fas fa-spinner fa-spin text-[#D4A574]"></i>
                    </div>
                    <span class="search-kbd ml-2">ESC</span>
                </div>
            </div>

            {{-- Contenido --}}
            <div class="overflow-y-auto overflow-x-hidden flex-1" id="searchResults">
                <!-- Estado inicial: búsquedas populares con slider -->
                <div id="searchDefault">
                    <div class="flex items-center gap-2 mb-3 px-1">
                        <i class="fas fa-fire text-[10px] text-[#D4A574]"></i>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Búsquedas populares</p>
                    </div>

                    <div class="popular-slider-wrapper">
                        <button type="button" class="popular-slider-arrow left" id="popularArrowLeft" aria-label="Anterior">
                            <i class="fas fa-chevron-left text-[9px]"></i>
                        </button>
                        <button type="button" class="popular-slider-arrow right" id="popularArrowRight" aria-label="Siguiente">
                            <i class="fas fa-chevron-right text-[9px]"></i>
                        </button>

                        <div class="popular-slider-track" id="popularSliderTrack">
                            <button class="search-suggestion popular-chip"><i class="fas fa-seedling text-rose-400"></i> Rosas</button>
                            <button class="search-suggestion popular-chip"><i class="fas fa-gem text-purple-400"></i> Collar</button>
                            <button class="search-suggestion popular-chip"><i class="fas fa-heart text-pink-400"></i> Peluche</button>
                            <button class="search-suggestion popular-chip"><i class="fas fa-glass-cheers text-amber-400"></i> Aniversario</button>
                            <button class="search-suggestion popular-chip"><i class="fas fa-cookie-bite text-yellow-700"></i> Chocolate</button>
                            <button class="search-suggestion popular-chip"><i class="fas fa-ring text-indigo-400"></i> Pulsera</button>
                            <button class="search-suggestion popular-chip"><i class="fas fa-gift text-red-400"></i> Regalo</button>
                            <button class="search-suggestion popular-chip"><i class="fas fa-spa text-emerald-400"></i> Arreglo floral</button>
                            <button class="search-suggestion popular-chip"><i class="fas fa-crown text-amber-500"></i> Premium</button>
                            <button class="search-suggestion popular-chip"><i class="fas fa-star text-yellow-400"></i> Más vendidos</button>
                        </div>
                    </div>

                    {{-- Accesos rápidos --}}
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-1">Acceso rápido</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="/catalogo" class="flex items-center gap-3 px-3.5 py-3 rounded-xl bg-gray-50 hover:bg-[#E8B4B8]/10 transition-all group">
                                <div class="w-9 h-9 rounded-lg bg-white border border-gray-100 flex items-center justify-center group-hover:border-[#E8B4B8]/30 transition">
                                    <i class="fas fa-th-large text-[11px] text-gray-400 group-hover:text-[#D4A574] transition"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-600 group-hover:text-gray-800 transition">Catálogo</span>
                            </a>
                            <a href="/catalogo?sort=newest" class="flex items-center gap-3 px-3.5 py-3 rounded-xl bg-gray-50 hover:bg-[#E8B4B8]/10 transition-all group">
                                <div class="w-9 h-9 rounded-lg bg-white border border-gray-100 flex items-center justify-center group-hover:border-[#E8B4B8]/30 transition">
                                    <i class="fas fa-bolt text-[11px] text-gray-400 group-hover:text-[#D4A574] transition"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-600 group-hover:text-gray-800 transition">Novedades</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Resultados dinámicos -->
                <div id="searchDynamic" class="hidden"></div>
            </div>
        </div>
    </div>

    {{-- Mensajes Flash --}}
    @if(session('success'))
        <div class="bg-green-50 border-b border-green-200 px-4 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <p class="text-green-800 font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800" aria-label="Cerrar notificación"><i class="fas fa-times"></i></button>
            </div>
        </div>
    @endif

    <!-- Contenido Principal -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-16 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="{{ !empty($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('images/logo_arixna.png') }}" alt="Arixna" class="h-14">
                    </div>
                    <p class="text-gray-600 leading-relaxed">{{ $settings['tagline'] ?? '' }}</p>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900 mb-4">Comprar</h2>
                    <ul class="space-y-2 text-gray-600">
                        @foreach($navCategories as $cat)
                            <li><a href="{{ route('catalog', ['categories' => [$cat->slug]]) }}" class="hover:text-gray-900 transition">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900 mb-4">Ayuda</h2>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="{{ route('legal.terms') }}" class="hover:text-gray-900 transition">Términos y Condiciones</a></li>
                        <li><a href="{{ route('legal.returns') }}" class="hover:text-gray-900 transition">Cambios y Devoluciones</a></li>
                        <li><a href="{{ route('legal.faq') }}" class="hover:text-gray-900 transition">Preguntas Frecuentes</a></li>
                        <li><a href="{{ route('contact.show') }}" class="hover:text-gray-900 transition">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900 mb-4">Síguenos</h2>
                    <div class="flex gap-3">
                        @if(!empty($settings['facebook_url']))
                            <a href="{{ $settings['facebook_url'] }}" target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-900 hover:text-white transition" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($settings['instagram_url']))
                            <a href="{{ $settings['instagram_url'] }}" target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-900 hover:text-white transition" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(!empty($settings['pinterest_url']))
                            <a href="{{ $settings['pinterest_url'] }}" target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-900 hover:text-white transition" aria-label="Pinterest"><i class="fab fa-pinterest"></i></a>
                        @endif
                        @if(!empty($settings['tiktok_url']))
                            <a href="{{ $settings['tiktok_url'] }}" target="_blank" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-900 hover:text-white transition" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Libro de Reclamaciones + Copyright -->
            <div class="border-t border-gray-200 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-gray-600">&copy; {{ date('Y') }} {{ $settings['business_name'] ?? 'Arixna' }}. Todos los derechos reservados.</p>
                <a href="{{ route('complaint.create') }}" class="group relative flex items-center gap-3 border border-gray-200 bg-gray-50/50 rounded-xl px-5 py-3 hover:bg-gray-100/80 hover:border-gray-300 hover:shadow-sm transition-all duration-300">
                    <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-gray-900 group-hover:scale-105 transition-all duration-300 shadow-sm">
                        <i class="fas fa-book-open text-white text-sm"></i>
                    </div>
                    <div class="text-left">
                        <span class="block text-[11px] font-semibold text-gray-800 uppercase tracking-wider leading-none mb-0.5">Libro de</span>
                        <span class="block text-sm font-bold text-gray-800 leading-tight">Reclamaciones</span>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 text-xs ml-1 group-hover:translate-x-1 group-hover:text-gray-500 transition-all duration-300"></i>
                </a>
            </div>
        </div>
    </footer>

    @yield('scripts')

    <!-- Cart Sidebar -->
    <div class="cart-sidebar-overlay" id="cartOverlay"></div>
    <aside class="cart-sidebar" id="cartSidebar" aria-label="Carrito de compras">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900 text-lg">Mi Carrito</h2>
                    <p class="text-xs text-gray-400" id="cartSidebarCount">0 productos</p>
                </div>
            </div>
            <button onclick="closeCartSidebar()" class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition" aria-label="Cerrar carrito">
                <i class="fas fa-times text-gray-500"></i>
            </button>
        </div>
        <!-- Items -->
        <div class="flex-1 overflow-y-auto cart-sidebar-items" id="cartSidebarItems">
            <div class="p-6">
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-bag text-3xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Tu carrito está vacío</p>
                    <p class="text-sm text-gray-400 mt-1">Agrega productos para comenzar</p>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="border-t border-gray-100 px-6 py-5 bg-white" id="cartSidebarFooter" style="display:none">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-500">Subtotal</span>
                <span class="text-xl font-bold text-gray-900" id="cartSidebarTotal">S/ 0.00</span>
            </div>
            <a href="{{ route('cart') }}" class="block w-full bg-gray-900 text-white text-center py-3.5 rounded-xl font-semibold hover:bg-gray-800 transition shadow-lg shadow-gray-900/10">
                Ver Carrito Completo
            </a>
            <a href="{{ route('catalog') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700 mt-3 transition">
                Continuar comprando
            </a>
        </div>
    </aside>

</body>
</html>
