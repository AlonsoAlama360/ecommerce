<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Romantic Gifts - Detalles Románticos')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #FAF8F5;
            color: #2C2C2C;
        }

        .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }

        .mega-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            width: auto;
        }

        .mega-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .mega-menu-content {
            display: flex;
            gap: 0;
            transition: all 0.3s ease;
        }

        .category-list {
            width: 250px;
            flex-shrink: 0;
        }

        .product-hover-panel {
            width: 0;
            opacity: 0;
            visibility: hidden;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .product-hover-panel.active {
            width: 450px;
            opacity: 1;
            visibility: visible;
            padding-left: 2rem;
            border-left: 1px solid #e5e7eb;
        }

        .cart-dropdown {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
            pointer-events: none;
        }

        .cart-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .user-dropdown {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .user-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .search-modal {
            display: flex;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .search-modal.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .search-result-item {
            transition: all 0.15s ease;
        }

        .search-result-item:hover {
            background: #fdf2f4;
            transform: translateX(4px);
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 85%;
            max-width: 400px;
            height: 100vh;
            background: white;
            z-index: 60;
            transition: left 0.3s ease;
            overflow-y: auto;
        }

        .mobile-menu.active {
            left: 0;
        }

        .mobile-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 55;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-category-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .mobile-category-submenu.active {
            max-height: 500px;
        }

        .slider-container {
            overflow: hidden;
            position: relative;
        }

        .slider-track {
            display: flex;
            transition: transform 0.5s ease;
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .badge-discount {
            background: linear-gradient(135deg, #D4A574 0%, #C89B6D 100%);
        }

        .btn-primary {
            background: #2C2C2C;
            color: #FAF8F5;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1A1A1A;
            transform: translateY(-2px);
        }

        .btn-secondary {
            border: 2px solid #2C2C2C;
            color: #2C2C2C;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #2C2C2C;
            color: #FAF8F5;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #F5E6D3 0%, #FAF8F5 50%, #E8D5C4 100%);
        }

        .accent-color {
            color: #D4A574;
        }

        .bg-accent {
            background-color: #D4A574;
        }

        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            background: white;
            border-radius: 12px;
            padding: 14px 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            border-left: 4px solid #22c55e;
            max-width: 340px;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #dcfce7;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #22c55e;
        }

        .cart-dropdown-items {
            max-height: 280px;
            overflow-y: auto;
        }

        .cart-dropdown-items::-webkit-scrollbar {
            width: 4px;
        }

        .cart-dropdown-items::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        @yield('styles')
    </style>
</head>

<body>
    <!-- Toast container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Overlay para el menú móvil -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

    <!-- Menú móvil lateral -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <img src="https://aztrosperu.com/cdn/shop/files/Logo_Aztros_copia.png?v=1669076562&width=500"
                    alt="Logo" class="h-10">
            </div>
            <button id="closeMobileMenu" class="text-gray-600 hover:text-gray-900">
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
                        <img src="https://aztrosperu.com/cdn/shop/files/Logo_Aztros_copia.png?v=1669076562&width=500"
                            alt="Logo" class="h-10">
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
                                    <h3 class="font-serif text-lg font-semibold mb-3 px-3">Categorías</h3>
                                    @foreach($navCategories as $cat)
                                        <a href="{{ route('catalog', ['categories' => [$cat->slug]]) }}" class="block text-gray-700 hover:text-gray-900 hover:bg-gray-50 py-2.5 px-3 rounded-lg transition category-item" data-category="{{ $cat->slug }}"><i class="{{ $cat->icon }} mr-2 accent-color"></i>{{ $cat->name }}</a>
                                    @endforeach
                                </div>
                                <div class="product-hover-panel" id="productPanel">
                                    <h4 class="font-serif text-base font-semibold mb-3">Productos Destacados</h4>
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
                    <button class="text-gray-700 hover:text-gray-900 transition" id="searchBtn">
                        <i class="fas fa-search text-xl"></i>
                    </button>

                    <!-- Carrito -->
                    <div class="relative">
                        <a href="{{ route('cart') }}" class="text-gray-700 hover:text-gray-900 transition relative" id="cartBtn">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
                            <span class="cart-badge absolute -top-2 -right-2 bg-[#E8B4B8] text-white text-xs rounded-full w-5 h-5 items-center justify-center {{ $cartCount > 0 ? 'flex' : 'hidden' }}">{{ $cartCount }}</span>
                        </a>

                        <div class="cart-dropdown absolute top-full right-0 pt-2 w-80" id="cartDropdown">
                        <div class="bg-white shadow-2xl rounded-lg p-6">
                            <div id="cartDropdownContent">
                                <div class="text-center py-8">
                                    <i class="fas fa-shopping-bag text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Tu carrito está vacío</p>
                                    <a href="{{ route('catalog') }}" class="inline-block mt-4 bg-gray-900 text-white px-6 py-2 rounded-full hover:bg-gray-800 transition text-sm font-medium">Explorar Tienda</a>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Icono de usuario - Desktop -->
                    <div class="hidden lg:block relative">
                        @auth
                            {{-- Usuario autenticado: avatar con dropdown --}}
                            <button class="flex items-center gap-2 text-gray-700 hover:text-gray-900 transition" id="userBtn">
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
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 transition">
                                <i class="fas fa-user text-xl"></i>
                            </a>
                        @endauth
                    </div>

                    <!-- Botón hamburguesa solo en móvil -->
                    <button class="lg:hidden text-gray-700 hover:text-gray-900 transition" id="hamburgerBtn">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Modal de Búsqueda -->
    <div class="search-modal fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center" id="searchModal">
        <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-2xl w-full mx-4 relative shadow-2xl" style="max-height: 80vh; display: flex; flex-direction: column;">
            <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl" id="closeSearchBtn">
                <i class="fas fa-times"></i>
            </button>
            <div class="mb-4 relative">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Buscar productos, categorías..."
                    class="w-full pl-12 pr-6 py-4 text-lg border-2 border-gray-200 rounded-full focus:outline-none focus:border-[#E8B4B8] transition"
                    id="searchInput" autocomplete="off">
                <div class="absolute right-5 top-1/2 -translate-y-1/2 hidden" id="searchSpinner">
                    <i class="fas fa-spinner fa-spin text-gray-400"></i>
                </div>
            </div>

            <div class="overflow-y-auto overflow-x-hidden flex-1" id="searchResults">
                <!-- Estado inicial: sugerencias rápidas -->
                <div id="searchDefault">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-1">Búsquedas populares</p>
                    <div class="flex flex-wrap gap-2">
                        <button class="search-suggestion px-4 py-2 bg-gray-100 hover:bg-[#E8B4B8]/20 hover:text-[#D4A574] rounded-full text-sm text-gray-600 transition-colors duration-200">Rosas</button>
                        <button class="search-suggestion px-4 py-2 bg-gray-100 hover:bg-[#E8B4B8]/20 hover:text-[#D4A574] rounded-full text-sm text-gray-600 transition-colors duration-200">Collar</button>
                        <button class="search-suggestion px-4 py-2 bg-gray-100 hover:bg-[#E8B4B8]/20 hover:text-[#D4A574] rounded-full text-sm text-gray-600 transition-colors duration-200">Peluche</button>
                        <button class="search-suggestion px-4 py-2 bg-gray-100 hover:bg-[#E8B4B8]/20 hover:text-[#D4A574] rounded-full text-sm text-gray-600 transition-colors duration-200">Aniversario</button>
                        <button class="search-suggestion px-4 py-2 bg-gray-100 hover:bg-[#E8B4B8]/20 hover:text-[#D4A574] rounded-full text-sm text-gray-600 transition-colors duration-200">Chocolate</button>
                        <button class="search-suggestion px-4 py-2 bg-gray-100 hover:bg-[#E8B4B8]/20 hover:text-[#D4A574] rounded-full text-sm text-gray-600 transition-colors duration-200">Pulsera</button>
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
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800"><i class="fas fa-times"></i></button>
            </div>
        </div>
    @endif

    <!-- Contenido Principal -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-white py-16 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-rose-400 to-pink-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-white text-lg"></i>
                        </div>
                        <span class="font-serif text-2xl font-semibold text-gray-900">Amor Eterno</span>
                    </div>
                    <p class="text-gray-600 leading-relaxed">Creando momentos inolvidables con detalles que expresan amor verdadero.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Comprar</h4>
                    <ul class="space-y-2 text-gray-600">
                        @foreach($navCategories as $cat)
                            <li><a href="{{ route('catalog', ['categories' => [$cat->slug]]) }}" class="hover:text-gray-900 transition">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Ayuda</h4>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#" class="hover:text-gray-900 transition">Envíos</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition">Devoluciones</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition">Preguntas Frecuentes</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition">Contacto</a></li>
                        <li><a href="#" class="hover:text-gray-900 transition">Garantía</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Síguenos</h4>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-900 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-900 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-900 hover:text-white transition"><i class="fab fa-pinterest"></i></a>
                        <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-900 hover:text-white transition"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-8 text-center text-gray-600">
                <p>&copy; {{ date('Y') }} Romantic Gifts. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileCategoriesBtn = document.getElementById('mobileCategoriesBtn');
        const mobileCategorySubmenu = document.getElementById('mobileCategorySubmenu');
        const mobileCategoriesIcon = document.getElementById('mobileCategoriesIcon');

        hamburgerBtn.addEventListener('click', () => {
            mobileMenu.classList.add('active');
            mobileMenuOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        closeMobileMenu.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            mobileMenuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });

        mobileMenuOverlay.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            mobileMenuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });

        mobileCategoriesBtn.addEventListener('click', () => {
            mobileCategorySubmenu.classList.toggle('active');
            mobileCategoriesIcon.style.transform = mobileCategorySubmenu.classList.contains('active')
                ? 'rotate(180deg)' : 'rotate(0deg)';
        });

        // Desktop Mega Menu
        const categoriesBtn = document.getElementById('categoriesBtn');
        const megaMenu = document.getElementById('megaMenu');
        const categoryItems = document.querySelectorAll('.category-item');
        const productPanel = document.getElementById('productPanel');
        const productGrid = document.getElementById('productGrid');
        let megaMenuTimeout;

        categoriesBtn.addEventListener('mouseenter', () => {
            clearTimeout(megaMenuTimeout);
            megaMenu.classList.add('active');
        });

        categoriesBtn.parentElement.addEventListener('mouseleave', () => {
            megaMenuTimeout = setTimeout(() => {
                megaMenu.classList.remove('active');
                productPanel.classList.remove('active');
            }, 200);
        });

        megaMenu.addEventListener('mouseenter', () => { clearTimeout(megaMenuTimeout); });
        megaMenu.addEventListener('mouseleave', () => {
            megaMenuTimeout = setTimeout(() => {
                megaMenu.classList.remove('active');
                productPanel.classList.remove('active');
            }, 200);
        });

        // Cache de productos por categoría (evita llamadas repetidas)
        const categoryCache = {};

        categoryItems.forEach(item => {
            item.addEventListener('mouseenter', async (e) => {
                const slug = e.currentTarget.dataset.category;

                if (categoryCache[slug]) {
                    renderProducts(categoryCache[slug]);
                    return;
                }

                try {
                    const res = await fetch(`/api/categories/${slug}/products`);
                    const data = await res.json();
                    categoryCache[slug] = data;
                    renderProducts(data);
                } catch (err) {
                    productPanel.classList.remove('active');
                }
            });
        });

        function renderProducts(products) {
            let html = '';
            products.forEach(p => {
                html += `<div class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                    <img src="${p.image}" alt="${p.name}" class="w-14 h-14 object-cover rounded-lg" loading="lazy">
                    <div class="flex-1 min-w-0"><h5 class="font-medium text-xs truncate">${p.name}</h5><p class="text-gray-900 font-semibold text-sm">$${p.price}</p></div>
                </div>`;
            });
            productGrid.innerHTML = html;
            productPanel.classList.add('active');
        }

        // Toast notification
        function showToast(message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `
                <div class="toast-icon"><i class="fas fa-check text-sm"></i></div>
                <div>
                    <p class="font-medium text-gray-900 text-sm">${message}</p>
                </div>
            `;
            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.add('show'));
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }

        // Cart Dropdown
        const cartBtn = document.getElementById('cartBtn');
        const cartDropdown = document.getElementById('cartDropdown');
        const cartDropdownContent = document.getElementById('cartDropdownContent');
        let cartTimeout;
        let cartDropdownLoaded = false;

        function loadCartDropdown() {
            fetch('/carrito/items', {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(data => {
                cartDropdownLoaded = true;
                if (!data.items || data.items.length === 0) {
                    cartDropdownContent.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-bag text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Tu carrito está vacío</p>
                            <a href="/catalogo" class="inline-block mt-4 bg-gray-900 text-white px-6 py-2 rounded-full hover:bg-gray-800 transition text-sm font-medium">Explorar Tienda</a>
                        </div>`;
                    return;
                }

                let itemsHtml = '';
                data.items.forEach(function(item) {
                    const imgSrc = item.image || '/images/placeholder.png';
                    const price = Number(item.price).toFixed(2);
                    const lineTotal = Number(item.line_total).toFixed(2);
                    itemsHtml += '<div class="flex items-center gap-3 py-2">' +
                        '<img src="' + imgSrc + '" alt="' + item.name + '" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">' +
                        '<div class="flex-1 min-w-0">' +
                        '<p class="text-sm font-medium text-gray-900 truncate">' + item.name + '</p>' +
                        '<p class="text-xs text-gray-500">' + item.quantity + ' × S/ ' + price + '</p>' +
                        '</div>' +
                        '<span class="text-sm font-semibold text-gray-900 flex-shrink-0">S/ ' + lineTotal + '</span>' +
                        '</div>';
                });

                const total = Number(data.total).toFixed(2);
                cartDropdownContent.innerHTML = '<h3 class="font-semibold text-gray-900 mb-3">Mi Carrito (' + data.count + ')</h3>' +
                    '<div class="cart-dropdown-items divide-y divide-gray-100">' + itemsHtml + '</div>' +
                    '<div class="border-t border-gray-200 mt-3 pt-3 flex items-center justify-between">' +
                    '<span class="font-semibold text-gray-900">Total:</span>' +
                    '<span class="font-bold text-lg text-gray-900">S/ ' + total + '</span>' +
                    '</div>' +
                    '<a href="/carrito" class="block mt-3 bg-gray-900 text-white text-center px-6 py-2.5 rounded-full hover:bg-gray-800 transition text-sm font-medium">Ver Carrito</a>';
            })
            .catch(function(err) {
                console.error('Cart dropdown error:', err);
            });
        }

        if (cartBtn && cartDropdown) {
            var cartContainer = cartBtn.parentElement;
            cartContainer.addEventListener('mouseenter', () => {
                clearTimeout(cartTimeout);
                cartDropdownLoaded = false;
                loadCartDropdown();
                cartDropdown.classList.add('active');
            });
            cartContainer.addEventListener('mouseleave', () => {
                cartTimeout = setTimeout(() => cartDropdown.classList.remove('active'), 300);
            });
        }

        // User Dropdown (solo si existe)
        const userBtn = document.getElementById('userBtn');
        const userDropdown = document.getElementById('userDropdown');
        if (userBtn && userDropdown) {
            let userTimeout;
            userBtn.addEventListener('mouseenter', () => { clearTimeout(userTimeout); userDropdown.classList.add('active'); });
            userBtn.parentElement.addEventListener('mouseleave', () => { userTimeout = setTimeout(() => userDropdown.classList.remove('active'), 200); });
            userDropdown.addEventListener('mouseenter', () => clearTimeout(userTimeout));
            userDropdown.addEventListener('mouseleave', () => { userTimeout = setTimeout(() => userDropdown.classList.remove('active'), 200); });
        }

        // Search Modal
        var searchBtn = document.getElementById('searchBtn');
        var searchModal = document.getElementById('searchModal');
        var closeSearchBtn = document.getElementById('closeSearchBtn');
        var searchInput = document.getElementById('searchInput');
        var searchDefault = document.getElementById('searchDefault');
        var searchDynamic = document.getElementById('searchDynamic');
        var searchSpinner = document.getElementById('searchSpinner');
        var searchTimer = null;

        function openSearch() {
            searchModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(function() { searchInput.focus(); }, 100);
        }

        function closeSearch() {
            searchModal.classList.remove('active');
            document.body.style.overflow = '';
            searchInput.value = '';
            searchDefault.classList.remove('hidden');
            searchDynamic.classList.add('hidden');
        }

        searchBtn.addEventListener('click', openSearch);
        closeSearchBtn.addEventListener('click', closeSearch);

        searchModal.addEventListener('click', function(e) {
            if (e.target === searchModal) {
                closeSearch();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchModal.classList.contains('active')) {
                closeSearch();
            }
        });

        // Sugerencias rápidas
        document.querySelectorAll('.search-suggestion').forEach(function(btn) {
            btn.addEventListener('click', function() {
                searchInput.value = this.textContent;
                searchInput.dispatchEvent(new Event('input'));
            });
        });

        // Búsqueda en tiempo real con debounce
        searchInput.addEventListener('input', function() {
            var query = this.value.trim();
            clearTimeout(searchTimer);

            if (query.length < 2) {
                searchDefault.classList.remove('hidden');
                searchDynamic.classList.add('hidden');
                searchDynamic.innerHTML = '';
                searchSpinner.classList.add('hidden');
                return;
            }

            searchSpinner.classList.remove('hidden');

            searchTimer = setTimeout(function() {
                fetch('/buscar?q=' + encodeURIComponent(query), {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    searchSpinner.classList.add('hidden');
                    searchDefault.classList.add('hidden');
                    searchDynamic.classList.remove('hidden');

                    var html = '';

                    // Categorías encontradas
                    if (data.categories.length > 0) {
                        html += '<p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Categorías</p>';
                        data.categories.forEach(function(cat) {
                            html += '<a href="' + cat.url + '" class="search-result-item flex items-center gap-3 px-3 py-2.5 rounded-xl">' +
                                '<div class="w-10 h-10 bg-[#E8B4B8]/20 rounded-full flex items-center justify-center flex-shrink-0">' +
                                '<i class="' + (cat.icon || 'fas fa-tag') + ' text-[#D4A574] text-sm"></i></div>' +
                                '<span class="font-medium text-gray-700">' + cat.name + '</span>' +
                                '<i class="fas fa-chevron-right text-gray-300 text-xs ml-auto"></i></a>';
                        });
                        html += '<div class="my-3 border-t border-gray-100"></div>';
                    }

                    // Productos encontrados
                    if (data.products.length > 0) {
                        html += '<p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Productos</p>';
                        data.products.forEach(function(p) {
                            var imgSrc = p.image || '/images/placeholder.png';
                            var price = Number(p.price).toFixed(2);
                            html += '<a href="' + p.url + '" class="search-result-item flex items-center gap-3 px-3 py-2.5 rounded-xl">' +
                                '<img src="' + imgSrc + '" alt="' + p.name + '" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">' +
                                '<div class="flex-1 min-w-0">' +
                                '<h4 class="font-medium text-sm text-gray-900 truncate">' + p.name + '</h4>' +
                                '<p class="text-xs text-gray-400">' + (p.category || '') + '</p></div>' +
                                '<span class="font-bold text-gray-900 flex-shrink-0">S/ ' + price + '</span></a>';
                        });
                    }

                    // Sin resultados
                    if (data.products.length === 0 && data.categories.length === 0) {
                        html = '<div class="text-center py-10">' +
                            '<i class="fas fa-search text-4xl text-gray-200 mb-4"></i>' +
                            '<p class="text-gray-500 font-medium">No encontramos resultados para "<span class="text-gray-700">' + query + '</span>"</p>' +
                            '<p class="text-gray-400 text-sm mt-1">Intenta con otras palabras clave</p></div>';
                    }

                    // Link ver todos
                    if (data.products.length > 0) {
                        html += '<div class="mt-3 pt-3 border-t border-gray-100">' +
                            '<a href="/catalogo?q=' + encodeURIComponent(query) + '" class="flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-[#D4A574] hover:text-[#c4955e] transition">' +
                            'Ver todos los resultados <i class="fas fa-arrow-right text-xs"></i></a></div>';
                    }

                    searchDynamic.innerHTML = html;
                })
                .catch(function() {
                    searchSpinner.classList.add('hidden');
                });
            }, 300);
        });
    </script>

    {{-- Wishlist Global JS --}}
    <script>
        (function() {
            var wishlistIds = [];
            var isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};

            function markAsWishlisted(btn) {
                var icon = btn.querySelector('i');
                icon.classList.remove('far');
                icon.classList.add('fas');
                btn.classList.add('bg-rose-500', 'text-white', 'border-rose-500');
                btn.classList.remove('bg-white/90', 'border-gray-200');
            }

            function markAsNotWishlisted(btn) {
                var icon = btn.querySelector('i');
                icon.classList.remove('fas');
                icon.classList.add('far');
                btn.classList.remove('bg-rose-500', 'text-white', 'border-rose-500');
                if (btn.classList.contains('backdrop-blur-sm')) {
                    btn.classList.add('bg-white/90');
                } else {
                    btn.classList.add('border-gray-200');
                }
            }

            function handleWishlistClick(e) {
                e.preventDefault();
                e.stopPropagation();

                var btn = this;

                if (!isAuthenticated) {
                    window.location.href = '{{ route("login") }}';
                    return;
                }

                var productId = parseInt(btn.dataset.productId);
                btn.style.pointerEvents = 'none';

                fetch('{{ route("wishlist.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.status === 'added') {
                        wishlistIds.push(productId);
                        document.querySelectorAll('.wishlist-btn[data-product-id="' + productId + '"]').forEach(markAsWishlisted);
                        if (typeof showToast === 'function') {
                            showToast('Agregado a tu lista de deseos');
                        }
                    } else {
                        wishlistIds = wishlistIds.filter(function(id) { return id !== productId; });
                        document.querySelectorAll('.wishlist-btn[data-product-id="' + productId + '"]').forEach(markAsNotWishlisted);
                        if (typeof showToast === 'function') {
                            showToast('Eliminado de tu lista de deseos');
                        }
                    }
                    btn.style.pointerEvents = '';
                })
                .catch(function() {
                    btn.style.pointerEvents = '';
                });
            }

            function bindWishlistButtons() {
                document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
                    if (btn.dataset.wishlistBound) return;
                    btn.dataset.wishlistBound = '1';
                    btn.addEventListener('click', handleWishlistClick);
                    var productId = parseInt(btn.dataset.productId);
                    if (wishlistIds.indexOf(productId) !== -1) {
                        markAsWishlisted(btn);
                    }
                });
            }

            if (isAuthenticated) {
                fetch('{{ route("wishlist.count") }}', {
                    headers: { 'Accept': 'application/json' }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    wishlistIds = data.ids || [];
                    bindWishlistButtons();
                })
                .catch(function() {
                    bindWishlistButtons();
                });
            } else {
                bindWishlistButtons();
            }
        })();
    </script>

    @yield('scripts')
</body>
</html>
