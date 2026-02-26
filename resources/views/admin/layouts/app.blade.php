<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Panel de Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }

        .sidebar-link {
            position: relative;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #e2e8f0;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2) 0%, rgba(139, 92, 246, 0.15) 100%);
            color: #fff;
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: linear-gradient(180deg, #818cf8, #a78bfa);
            border-radius: 0 4px 4px 0;
        }

        .sidebar-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 1023px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }
        }

        .nav-badge {
            font-size: 10px;
            min-width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            padding: 0 5px;
            font-weight: 600;
        }

        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
    @yield('styles')
</head>

<body class="bg-gray-50/80 min-h-screen antialiased">

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay fixed inset-0 z-40 lg:hidden" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar sidebar fixed top-0 left-0 z-50 w-[270px] h-full flex flex-col" id="sidebar">

        <!-- Logo -->
        <div class="px-6 py-6 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:shadow-indigo-500/40 transition-shadow">
                    <i class="fas fa-gem text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="font-bold text-[15px] text-white leading-tight tracking-tight">ShopAdmin</h1>
                    <p class="text-[10px] text-slate-500 font-medium tracking-widest uppercase">E-Commerce</p>
                </div>
            </a>
            <button class="lg:hidden w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center text-slate-400 transition" onclick="closeSidebar()">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 pb-4 overflow-y-auto sidebar-scroll">

            {{-- Menu --}}
            <p class="px-3 pt-2 pb-2 text-[10px] font-semibold text-slate-500/80 uppercase tracking-[0.15em]">Menú</p>

            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-chart-pie text-xs {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Dashboard</span>
            </a>

            {{-- Gestión --}}
            <p class="px-3 pt-5 pb-2 text-[10px] font-semibold text-slate-500/80 uppercase tracking-[0.15em]">Gestión</p>

            <a href="{{ route('admin.users.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.users.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-users text-xs {{ request()->routeIs('admin.users.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Usuarios</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-tags text-xs {{ request()->routeIs('admin.categories.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Categorías</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.products.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-box text-xs {{ request()->routeIs('admin.products.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Productos</span>
            </a>

            <a href="{{ route('admin.suppliers.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.suppliers.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.suppliers.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-truck-field text-xs {{ request()->routeIs('admin.suppliers.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Proveedores</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.orders.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-receipt text-xs {{ request()->routeIs('admin.orders.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Ventas</span>
            </a>

            <a href="{{ route('admin.purchases.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.purchases.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.purchases.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-cart-shopping text-xs {{ request()->routeIs('admin.purchases.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Compras</span>
            </a>

            <a href="{{ route('admin.kardex.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.kardex.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.kardex.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-arrow-right-arrow-left text-xs {{ request()->routeIs('admin.kardex.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Kardex</span>
            </a>

            <a href="{{ route('admin.wishlists.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.wishlists.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.wishlists.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-heart text-xs {{ request()->routeIs('admin.wishlists.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Lista de Deseos</span>
            </a>

            {{-- Tienda --}}
            <p class="px-3 pt-5 pb-2 text-[10px] font-semibold text-slate-500/80 uppercase tracking-[0.15em]">Tienda</p>

            <a href="{{ route('home') }}" target="_blank"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 text-slate-400">
                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                    <i class="fas fa-store text-xs text-slate-500"></i>
                </div>
                <span>Ver tienda</span>
                <i class="fas fa-arrow-up-right-from-square text-[9px] text-slate-600 ml-auto"></i>
            </a>

        </nav>

        <!-- User Card -->
        <div class="px-4 pb-4">
            <div class="rounded-xl bg-white/[0.04] border border-white/[0.06] p-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-indigo-500/20 flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->full_name }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center text-slate-500 hover:text-red-400 transition" title="Cerrar sesión">
                            <i class="fas fa-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-[270px] min-h-screen flex flex-col">
        <!-- Topbar -->
        <header class="bg-white/80 backdrop-blur-xl sticky top-0 z-30 border-b border-gray-100">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3">
                <!-- Left: Mobile toggle + breadcrumb -->
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle" class="lg:hidden w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition">
                        <i class="fas fa-bars text-gray-500 text-sm"></i>
                    </button>
                    <div class="hidden sm:flex items-center gap-2 text-sm">
                        <span class="text-gray-400">Admin</span>
                        <i class="fas fa-chevron-right text-[8px] text-gray-300"></i>
                        <span class="text-gray-700 font-medium">@yield('title', 'Dashboard')</span>
                    </div>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-2">
                    {{-- Search --}}
                    <div class="hidden md:block relative">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" placeholder="Buscar..."
                            class="w-52 pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-300 focus:w-72 transition-all">
                    </div>

                    {{-- Notifications placeholder --}}
                    <button class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition relative">
                        <i class="fas fa-bell text-gray-500 text-sm"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    {{-- Divider --}}
                    <div class="w-px h-7 bg-gray-200 mx-1 hidden sm:block"></div>

                    {{-- User --}}
                    <div class="flex items-center gap-2.5 pl-1">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-[11px] font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-sm font-semibold text-gray-800 leading-tight">{{ Auth::user()->full_name }}</p>
                            <p class="text-[11px] text-gray-400">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <!-- Toast Notifications -->
    <div id="toastContainer" class="fixed top-4 right-4 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none"></div>

    @if(session('success'))
    <template id="flashSuccessData" data-message="{{ session('success') }}"></template>
    @endif
    @if(session('error'))
    <template id="flashErrorData" data-message="{{ session('error') }}"></template>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-[90] flex items-center justify-center opacity-0 invisible transition-all duration-300">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div id="deleteModalContent" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 scale-95 transition-transform duration-300">
            <div class="flex justify-center mb-4">
                <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center">
                    <i class="fas fa-trash-can text-red-500 text-xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center" id="deleteModalTitle">¿Eliminar registro?</h3>
            <p class="text-sm text-gray-500 text-center mt-2" id="deleteModalMessage">Este registro será eliminado permanentemente.</p>
            <div class="flex gap-3 mt-6">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition font-medium">
                    Cancelar
                </button>
                <button onclick="confirmDelete()" class="flex-1 px-4 py-2.5 text-sm bg-red-500 text-white rounded-xl hover:bg-red-600 transition font-medium shadow-sm shadow-red-200">
                    <i class="fas fa-trash-can mr-1.5 text-xs"></i> Eliminar
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        toggle.addEventListener('click', openSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Toast system
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const colors = {
                success: {
                    bg: 'bg-emerald-500',
                    icon: 'fa-check-circle'
                },
                error: {
                    bg: 'bg-red-500',
                    icon: 'fa-exclamation-circle'
                },
                warning: {
                    bg: 'bg-amber-500',
                    icon: 'fa-exclamation-triangle'
                }
            };
            const c = colors[type] || colors.success;

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto ${c.bg} text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 transform translate-x-full transition-transform duration-300`;
            toast.innerHTML = `
                <i class="fas ${c.icon} text-lg"></i>
                <p class="text-sm font-medium flex-1">${message}</p>
                <button onclick="dismissToast(this.parentElement)" class="text-white/70 hover:text-white transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            });

            setTimeout(() => dismissToast(toast), 4000);
        }

        function dismissToast(toast) {
            if (!toast || !toast.parentElement) return;
            toast.classList.add('translate-x-full');
            toast.classList.remove('translate-x-0');
            setTimeout(() => toast.remove(), 300);
        }

        // Show flash toasts
        const successTpl = document.getElementById('flashSuccessData');
        if (successTpl) showToast(successTpl.dataset.message, 'success');

        const errorTpl = document.getElementById('flashErrorData');
        if (errorTpl) showToast(errorTpl.dataset.message, 'error');

        // Delete modal
        let deleteForm = null;

        function openDeleteModal(form, itemName, customMessage) {
            deleteForm = form;
            document.getElementById('deleteModalMessage').textContent = customMessage || `Se eliminará "${itemName}". Esta acción no se puede deshacer.`;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');
            document.getElementById('deleteModalContent').classList.remove('scale-95');
            document.getElementById('deleteModalContent').classList.add('scale-100');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('opacity-0', 'invisible');
            modal.classList.remove('opacity-100', 'visible');
            document.getElementById('deleteModalContent').classList.add('scale-95');
            document.getElementById('deleteModalContent').classList.remove('scale-100');
            deleteForm = null;
        }

        function confirmDelete() {
            if (deleteForm) deleteForm.submit();
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>

    @yield('scripts')
</body>

</html>