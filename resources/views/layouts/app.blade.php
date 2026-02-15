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
            transition: all 0.3s ease;
        }

        .cart-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
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
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .search-modal.active {
            display: flex;
            opacity: 1;
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
                    <a href="#" class="block py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
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
                    <a href="#" class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-user text-lg"></i>
                        <span>Mi Perfil</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 py-3 px-4 text-gray-700 hover:bg-gray-50 rounded-lg transition">
                        <i class="fas fa-shopping-bag text-lg"></i>
                        <span>Mis Pedidos</span>
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
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition">Ofertas</a>
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

                        <div class="cart-dropdown absolute top-full right-0 mt-2 w-80 bg-white shadow-2xl rounded-lg p-6" id="cartDropdown">
                            <div id="cartDropdownContent">
                                <div class="text-center py-8">
                                    <i class="fas fa-shopping-bag text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Tu carrito está vacío</p>
                                    <a href="{{ route('catalog') }}" class="inline-block mt-4 bg-gray-900 text-white px-6 py-2 rounded-full hover:bg-gray-800 transition text-sm font-medium">Explorar Tienda</a>
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
                                    <a href="#" class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-gray-50 transition">
                                        <i class="fas fa-user w-5 text-center text-[#D4A574]"></i>
                                        <span>Mi Perfil</span>
                                    </a>
                                    <a href="#" class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-gray-50 transition">
                                        <i class="fas fa-shopping-bag w-5 text-center text-[#D4A574]"></i>
                                        <span>Mis Pedidos</span>
                                    </a>
                                    <a href="#" class="flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-gray-50 transition">
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
        <div class="bg-white rounded-2xl p-8 max-w-3xl w-full mx-4 relative">
            <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl" id="closeSearchBtn">
                <i class="fas fa-times"></i>
            </button>
            <div class="mb-6">
                <input type="text" placeholder="Busca tu detalle perfecto..."
                    class="w-full px-6 py-4 text-lg border-2 border-gray-200 rounded-full focus:outline-none focus:border-gray-400 transition"
                    id="searchInput">
            </div>
            <div class="space-y-3" id="searchResults">
                <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                    <img src="https://i.pinimg.com/736x/05/f1/89/05f189b8862acc9f463bfa53af869d85.jpg" alt="Producto" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium">Collar Corazón Infinito</h4>
                        <p class="text-sm text-gray-500">Oro Rosa 18k</p>
                    </div>
                    <span class="font-semibold text-lg">$89.99</span>
                </div>
                <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                    <img src="https://i.pinimg.com/1200x/09/26/49/092649e7126954d695440adcfd78db80.jpg" alt="Producto" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium">Pulsera Amor Eterno</h4>
                        <p class="text-sm text-gray-500">Plata 925</p>
                    </div>
                    <span class="font-semibold text-lg">$64.99</span>
                </div>
                <div class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                    <img src="https://i.pinimg.com/736x/cb/ef/6e/cbef6e099db2a2b90825505936e65985.jpg" alt="Producto" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium">Rosa Eterna en Cúpula</h4>
                        <p class="text-sm text-gray-500">Roja Premium</p>
                    </div>
                    <span class="font-semibold text-lg">$129.99</span>
                </div>
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
            fetch('{{ route("cart.items") }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                cartDropdownLoaded = true;
                if (data.items.length === 0) {
                    cartDropdownContent.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-bag text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Tu carrito está vacío</p>
                            <a href="{{ route('catalog') }}" class="inline-block mt-4 bg-gray-900 text-white px-6 py-2 rounded-full hover:bg-gray-800 transition text-sm font-medium">Explorar Tienda</a>
                        </div>`;
                    return;
                }

                let itemsHtml = '';
                data.items.forEach(item => {
                    const imgSrc = item.image || 'https://via.placeholder.com/60';
                    itemsHtml += `
                        <div class="flex items-center gap-3 py-2">
                            <img src="${imgSrc}" alt="${item.name}" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${item.name}</p>
                                <p class="text-xs text-gray-500">${item.quantity} × S/ ${item.price.toFixed(2)}</p>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 flex-shrink-0">S/ ${item.line_total.toFixed(2)}</span>
                        </div>`;
                });

                cartDropdownContent.innerHTML = `
                    <h3 class="font-semibold text-gray-900 mb-3">Mi Carrito (${data.count})</h3>
                    <div class="cart-dropdown-items divide-y divide-gray-100">${itemsHtml}</div>
                    <div class="border-t border-gray-200 mt-3 pt-3 flex items-center justify-between">
                        <span class="font-semibold text-gray-900">Total:</span>
                        <span class="font-bold text-lg text-gray-900">S/ ${data.total.toFixed(2)}</span>
                    </div>
                    <a href="{{ route('cart') }}" class="block mt-3 bg-gray-900 text-white text-center px-6 py-2.5 rounded-full hover:bg-gray-800 transition text-sm font-medium">Ver Carrito</a>`;
            })
            .catch(() => {});
        }

        if (cartBtn && cartDropdown) {
            cartBtn.addEventListener('mouseenter', () => {
                clearTimeout(cartTimeout);
                cartDropdownLoaded = false;
                loadCartDropdown();
                cartDropdown.classList.add('active');
            });
            cartBtn.parentElement.addEventListener('mouseleave', () => { cartTimeout = setTimeout(() => cartDropdown.classList.remove('active'), 200); });
            cartDropdown.addEventListener('mouseenter', () => clearTimeout(cartTimeout));
            cartDropdown.addEventListener('mouseleave', () => { cartTimeout = setTimeout(() => cartDropdown.classList.remove('active'), 200); });
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
        const searchBtn = document.getElementById('searchBtn');
        const searchModal = document.getElementById('searchModal');
        const closeSearchBtn = document.getElementById('closeSearchBtn');
        const searchInput = document.getElementById('searchInput');

        searchBtn.addEventListener('click', () => { searchModal.classList.add('active'); setTimeout(() => searchInput.focus(), 100); });
        closeSearchBtn.addEventListener('click', () => searchModal.classList.remove('active'));
        searchModal.addEventListener('click', (e) => { if (e.target === searchModal) searchModal.classList.remove('active'); });
    </script>

    @yield('scripts')
</body>
</html>
