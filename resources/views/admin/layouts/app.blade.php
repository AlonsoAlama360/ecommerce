<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Panel de Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        .sidebar-link {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.08);
        }
        .sidebar-link.active {
            background: rgba(99, 132, 255, 0.15);
            border-left-color: #6366f1;
            color: #fff;
        }

        .sidebar-overlay {
            background: rgba(0, 0, 0, 0.5);
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
                transition: transform 0.3s ease;
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay fixed inset-0 z-40 lg:hidden" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar fixed top-0 left-0 z-50 w-64 h-full flex flex-col" id="sidebar"
           style="background: #1e2a3a;">
        <!-- Logo -->
        <div class="px-5 py-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-500 flex items-center justify-center">
                    <i class="fas fa-store text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="font-bold text-base text-white leading-tight">ShopAdmin</h1>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">E-Commerce Panel</p>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 py-2 px-3 overflow-y-auto">
            <!-- PRINCIPAL -->
            <p class="px-3 pt-4 pb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Principal</p>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-slate-400' }}">
                <i class="fas fa-th-large w-5 text-center text-xs"></i>
                <span>Dashboard</span>
            </a>

            <!-- GESTIÓN -->
            <p class="px-3 pt-5 pb-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Gestión</p>
            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.users.*') ? 'active' : 'text-slate-400' }}">
                <i class="fas fa-users w-5 text-center text-xs"></i>
                <span>Usuarios</span>
            </a>
            <a href="#"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-400 opacity-50 cursor-not-allowed">
                <i class="fas fa-box w-5 text-center text-xs"></i>
                <span>Productos</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-slate-400' }}">
                <i class="fas fa-tags w-5 text-center text-xs"></i>
                <span>Categorías</span>
            </a>
        </nav>

        <!-- User Info -->
        <div class="px-4 py-4 border-t border-slate-700/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-semibold text-white">
                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->full_name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 transition" title="Cerrar sesión">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
        <!-- Topbar -->
        <header class="bg-white shadow-sm sticky top-0 z-30">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3">
                <!-- Mobile toggle -->
                <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-bars text-gray-600 text-lg"></i>
                </button>

                <!-- Search -->
                <div class="hidden sm:block flex-1 max-w-md ml-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Buscar..."
                               class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-indigo-500 transition" title="Ver tienda">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-semibold text-indigo-600">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700 hidden md:inline">{{ ucfirst(Auth::user()->role) }}</span>
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
                <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-trash-can text-red-500 text-xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center" id="deleteModalTitle">¿Eliminar registro?</h3>
            <p class="text-sm text-gray-500 text-center mt-2" id="deleteModalMessage">Este registro será eliminado permanentemente.</p>
            <div class="flex gap-3 mt-6">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition font-medium">
                    Cancelar
                </button>
                <button onclick="confirmDelete()" class="flex-1 px-4 py-2.5 text-sm bg-red-500 text-white rounded-xl hover:bg-red-600 transition font-medium">
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
                success: { bg: 'bg-emerald-500', icon: 'fa-check-circle' },
                error: { bg: 'bg-red-500', icon: 'fa-exclamation-circle' },
                warning: { bg: 'bg-amber-500', icon: 'fa-exclamation-triangle' }
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
