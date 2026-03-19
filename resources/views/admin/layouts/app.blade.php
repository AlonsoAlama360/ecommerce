<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Panel de Administración</title>
    <link rel="icon" href="{{ !empty($settings['site_favicon']) ? asset('storage/' . $settings['site_favicon']) : asset('images/logo_arixna1024512_min.webp') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Arixna Admin">
    <link rel="apple-touch-icon" href="/images/logo_arixna.png">
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

        /* Sidebar dropdown */
        .sidebar-dropdown-items {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease;
        }
        .sidebar-dropdown.open .sidebar-dropdown-items {
            max-height: 500px;
        }
        .sidebar-dropdown .dropdown-arrow {
            transition: transform 0.25s ease;
        }
        .sidebar-dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Collapsible Filters */
        .filter-collapsible .filter-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .filter-collapsible.open .filter-panel {
            max-height: 500px;
        }
        .filter-collapsible .filter-chevron {
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .filter-collapsible.open .filter-chevron {
            transform: rotate(180deg);
        }

        /* Filter field styling */
        .filter-field select,
        .filter-field input[type="date"],
        .filter-field input[type="text"] {
            width: 100%;
            padding: 9px 14px;
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            color: #374151;
            outline: none;
            transition: all 0.2s ease;
        }
        .filter-field select { padding-right: 36px; }
        .filter-field select:hover,
        .filter-field input:hover {
            border-color: #c7d2fe;
            background: #fff;
        }
        .filter-field select:focus,
        .filter-field input:focus {
            border-color: #818cf8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }
        .filter-field label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        /* Notification dropdown */
        #notifDropdown.open {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
        }
        .notif-item { transition: background 0.15s; }
        .notif-item:hover { background: #f8fafc; }
        .notif-item.unread { background: #f0f4ff; }
        .notif-item.unread:hover { background: #e8edff; }

        #notifList::-webkit-scrollbar { width: 4px; }
        #notifList::-webkit-scrollbar-track { background: transparent; }
        #notifList::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        /* Search modal */
        .search-backdrop { animation: fadeIn .15s ease-out; }
        .search-panel { animation: slideDown .2s cubic-bezier(.16,1,.3,1); }
        @keyframes fadeIn { from { opacity:0 } to { opacity:1 } }
        @keyframes slideDown { from { opacity:0; transform:translateY(-8px) scale(.98) } to { opacity:1; transform:none } }
        #globalSearchList::-webkit-scrollbar { width: 4px; }
        #globalSearchList::-webkit-scrollbar-track { background: transparent; }
        #globalSearchList::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
        .search-item { transition: background .1s; }
        .search-item:hover, .search-item.active { background: #eef2ff; }
        .search-item.active { box-shadow: inset 3px 0 0 #6366f1; }
        .search-arrow { opacity: 0; transition: all .15s; }
        .search-item:hover .search-arrow, .search-item.active .search-arrow { opacity: 1; color: #6366f1; }
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
                <img src="{{ !empty($settings['site_logo']) ? asset('storage/' . $settings['site_logo']) : asset('images/logo_arixna_blanco.webp') }}" alt="Logo" class="h-10 max-w-[160px] object-contain">
            </a>
            <button class="lg:hidden w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center text-slate-400 transition" onclick="closeSidebar()" aria-label="Cerrar menú lateral">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 pb-4 overflow-y-auto sidebar-scroll">

            {{-- Menu --}}
            <p class="px-3 pt-2 pb-2 text-[10px] font-semibold text-slate-500/80 uppercase tracking-[0.15em]">Menú</p>

            @if(Auth::user()->hasPermission('dashboard.view'))
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-chart-pie text-xs {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Dashboard</span>
            </a>
            @endif

            {{-- Gestión --}}
            <p class="px-3 pt-5 pb-2 text-[10px] font-semibold text-slate-500/80 uppercase tracking-[0.15em]">Gestión</p>

            @if(Auth::user()->hasPermission('users.view'))
            <a href="{{ route('admin.users.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.users.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-users text-xs {{ request()->routeIs('admin.users.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Usuarios</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('categories.view'))
            <a href="{{ route('admin.categories.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-tags text-xs {{ request()->routeIs('admin.categories.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Categorías</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('products.view'))
            <a href="{{ route('admin.products.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.products.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-box text-xs {{ request()->routeIs('admin.products.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Productos</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('suppliers.view'))
            <a href="{{ route('admin.suppliers.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.suppliers.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.suppliers.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-truck-field text-xs {{ request()->routeIs('admin.suppliers.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Proveedores</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('shipping_agencies.view'))
            <a href="{{ route('admin.shipping-agencies.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.shipping-agencies.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.shipping-agencies.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-shipping-fast text-xs {{ request()->routeIs('admin.shipping-agencies.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Agencias de Envío</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('orders.view'))
            <a href="{{ route('admin.orders.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.orders.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-receipt text-xs {{ request()->routeIs('admin.orders.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Ventas</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('purchases.view'))
            <a href="{{ route('admin.purchases.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.purchases.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.purchases.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-cart-shopping text-xs {{ request()->routeIs('admin.purchases.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Compras</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('kardex.view'))
            <a href="{{ route('admin.kardex.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.kardex.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.kardex.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-arrow-right-arrow-left text-xs {{ request()->routeIs('admin.kardex.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Kardex</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('wishlists.view') || Auth::user()->hasPermission('reviews.view'))
            @php
                $clientesActive = request()->routeIs('admin.wishlists.*') || request()->routeIs('admin.reviews.*');
            @endphp
            <div class="sidebar-dropdown {{ $clientesActive ? 'open' : '' }}">
                <button onclick="this.closest('.sidebar-dropdown').classList.toggle('open')" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 w-full {{ $clientesActive ? 'text-white' : 'text-slate-400' }}">
                    <div class="w-8 h-8 rounded-lg {{ $clientesActive ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                        <i class="fas fa-users-gear text-xs {{ $clientesActive ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                    </div>
                    <span class="flex-1 text-left">Clientes</span>
                    <i class="fas fa-chevron-down text-[9px] text-slate-500 dropdown-arrow"></i>
                </button>
                <div class="sidebar-dropdown-items pl-[2.75rem]">
                    @if(Auth::user()->hasPermission('wishlists.view'))
                    <a href="{{ route('admin.wishlists.index') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.wishlists.*') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-heart text-[10px] {{ request()->routeIs('admin.wishlists.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Lista de Deseos</span>
                    </a>
                    @endif
                    @if(Auth::user()->hasPermission('reviews.view'))
                    <a href="{{ route('admin.reviews.index') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reviews.*') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-star text-[10px] {{ request()->routeIs('admin.reviews.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Reseñas</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermission('contact_messages.view') || Auth::user()->hasPermission('complaints.view') || Auth::user()->hasPermission('subscribers.view'))
            @php
                $atencionActive = request()->routeIs('admin.contact-messages.*') || request()->routeIs('admin.complaints.*') || request()->routeIs('admin.subscribers.*');
            @endphp
            <div class="sidebar-dropdown {{ $atencionActive ? 'open' : '' }}">
                <button onclick="this.closest('.sidebar-dropdown').classList.toggle('open')" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 w-full {{ $atencionActive ? 'text-white' : 'text-slate-400' }}">
                    <div class="w-8 h-8 rounded-lg {{ $atencionActive ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                        <i class="fas fa-headset text-xs {{ $atencionActive ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                    </div>
                    <span class="flex-1 text-left">Atención</span>
                    <i class="fas fa-chevron-down text-[9px] text-slate-500 dropdown-arrow"></i>
                </button>
                <div class="sidebar-dropdown-items pl-[2.75rem]">
                    @if(Auth::user()->hasPermission('contact_messages.view'))
                    <a href="{{ route('admin.contact-messages.index') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.contact-messages.*') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-envelope text-[10px] {{ request()->routeIs('admin.contact-messages.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Contacto</span>
                    </a>
                    @endif
                    @if(Auth::user()->hasPermission('complaints.view'))
                    <a href="{{ route('admin.complaints.index') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.complaints.*') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-book text-[10px] {{ request()->routeIs('admin.complaints.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Reclamaciones</span>
                    </a>
                    @endif
                    @if(Auth::user()->hasPermission('subscribers.view'))
                    <a href="{{ route('admin.subscribers.index') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.subscribers.*') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-bell text-[10px] {{ request()->routeIs('admin.subscribers.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Suscriptores</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermission('reports.view'))
            @php
                $reportesActive = request()->routeIs('admin.reports.*');
            @endphp
            <div class="sidebar-dropdown {{ $reportesActive ? 'open' : '' }}">
                <button onclick="this.closest('.sidebar-dropdown').classList.toggle('open')" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 w-full {{ $reportesActive ? 'text-white' : 'text-slate-400' }}">
                    <div class="w-8 h-8 rounded-lg {{ $reportesActive ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                        <i class="fas fa-chart-bar text-xs {{ $reportesActive ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                    </div>
                    <span class="flex-1 text-left">Reportes</span>
                    <i class="fas fa-chevron-down text-[9px] text-slate-500 dropdown-arrow"></i>
                </button>
                <div class="sidebar-dropdown-items pl-[2.75rem]">
                    <a href="{{ route('admin.reports.sales') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reports.sales') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-dollar-sign text-[10px] {{ request()->routeIs('admin.reports.sales') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Ventas</span>
                    </a>
                    <a href="{{ route('admin.reports.products') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reports.products') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-box text-[10px] {{ request()->routeIs('admin.reports.products') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Productos</span>
                    </a>
                    <a href="{{ route('admin.reports.customers') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reports.customers') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-user-group text-[10px] {{ request()->routeIs('admin.reports.customers') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Clientes</span>
                    </a>
                    <a href="{{ route('admin.reports.purchases') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reports.purchases') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-cart-shopping text-[10px] {{ request()->routeIs('admin.reports.purchases') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Compras</span>
                    </a>
                    <div class="border-t border-slate-700/50 my-1.5"></div>
                    <a href="{{ route('admin.reports.profitability') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reports.profitability') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-coins text-[10px] {{ request()->routeIs('admin.reports.profitability') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Rentabilidad</span>
                    </a>
                    <a href="{{ route('admin.reports.inventory') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reports.inventory') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-warehouse text-[10px] {{ request()->routeIs('admin.reports.inventory') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Inventario</span>
                    </a>
                    <a href="{{ route('admin.reports.geographic') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reports.geographic') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-map-location-dot text-[10px] {{ request()->routeIs('admin.reports.geographic') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Geográfico</span>
                    </a>
                    <a href="{{ route('admin.reports.trends') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reports.trends') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-chart-line text-[10px] {{ request()->routeIs('admin.reports.trends') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Tendencias</span>
                    </a>
                    <a href="{{ route('admin.reports.satisfaction') }}"
                        class="sidebar-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-[12.5px] font-medium mb-0.5 {{ request()->routeIs('admin.reports.satisfaction') ? 'active' : 'text-slate-400' }}">
                        <i class="fas fa-face-smile text-[10px] {{ request()->routeIs('admin.reports.satisfaction') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                        <span>Satisfacción</span>
                    </a>
                </div>
            </div>
            @endif

            {{-- Sistema --}}
            <p class="px-3 pt-5 pb-2 text-[10px] font-semibold text-slate-500/80 uppercase tracking-[0.15em]">Sistema</p>

            @if(Auth::user()->hasPermission('roles.view'))
            <a href="{{ route('admin.roles.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.roles.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.roles.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-user-shield text-xs {{ request()->routeIs('admin.roles.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Roles y Permisos</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('settings.view'))
            <a href="{{ route('admin.settings.index') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium mb-0.5 {{ request()->routeIs('admin.settings.*') ? 'active' : 'text-slate-400' }}">
                <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center transition">
                    <i class="fas fa-gear text-xs {{ request()->routeIs('admin.settings.*') ? 'text-indigo-400' : 'text-slate-500' }}"></i>
                </div>
                <span>Configuración</span>
            </a>
            @endif

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
                        <button type="submit" class="w-8 h-8 rounded-lg hover:bg-white/10 flex items-center justify-center text-slate-500 hover:text-red-400 transition" aria-label="Cerrar sesión">
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
                    <button id="sidebarToggle" class="lg:hidden w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition" aria-label="Abrir menú lateral">
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
                    {{-- Global Search: Trigger --}}
                    <button type="button" onclick="openSearchModal()" class="group flex items-center gap-2 h-9 px-2.5 bg-gray-50 hover:bg-white border border-gray-200 hover:border-gray-300 rounded-xl transition-all cursor-pointer hover:shadow-sm" aria-label="Buscar">
                        <i class="fas fa-search text-gray-400 text-xs group-hover:text-indigo-500 transition-colors"></i>
                        <span class="hidden md:inline text-sm text-gray-400 group-hover:text-gray-500">Buscar...</span>
                        <kbd class="hidden md:inline text-[10px] text-gray-400 bg-gray-100 border border-gray-200 rounded px-1.5 py-0.5 font-mono ml-3" id="globalSearchKbd">⌘K</kbd>
                    </button>

                    {{-- Notifications --}}
                    <div class="relative" id="notifWrapper">
                        <button id="notifBtn" class="w-9 h-9 rounded-xl bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition relative" aria-label="Notificaciones">
                            <i class="fas fa-bell text-gray-500 text-sm"></i>
                            <span id="notifBadge" class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white hidden"></span>
                        </button>

                        {{-- Dropdown --}}
                        <div id="notifDropdown" class="fixed sm:absolute right-0 left-0 sm:left-auto top-[56px] sm:top-full sm:mt-2 w-full sm:w-[360px] max-h-[calc(100vh-56px)] sm:max-h-[480px] bg-white sm:rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible transition-all duration-200 origin-top-right scale-95 z-50 flex flex-col">
                            {{-- Header --}}
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                <h3 class="text-sm font-bold text-gray-800">Notificaciones</h3>
                                <div class="flex items-center gap-2">
                                    <button id="notifPushToggle" class="text-[11px] text-gray-400 hover:text-indigo-500 transition" title="Activar notificaciones push">
                                        <i class="fas fa-mobile-screen text-xs"></i>
                                    </button>
                                    <button id="notifMarkAll" class="text-[11px] text-indigo-500 hover:text-indigo-700 font-medium transition">
                                        Marcar todas leídas
                                    </button>
                                </div>
                            </div>
                            {{-- List --}}
                            <div id="notifList" class="flex-1 overflow-y-auto divide-y divide-gray-50" style="max-height: 380px;">
                                <div class="flex items-center justify-center py-8 text-gray-400 text-sm">
                                    <i class="fas fa-spinner fa-spin mr-2"></i> Cargando...
                                </div>
                            </div>
                        </div>
                    </div>

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
                <button onclick="dismissToast(this.parentElement)" class="text-white/70 hover:text-white transition" aria-label="Cerrar notificación">
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

    <script>
    function submitCreate(btn, formId, loadingText) {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5 text-xs"></i> ' + loadingText;
        document.getElementById(formId).submit();
    }
    function lockBtn(btn, loadingText) {
        setTimeout(() => {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5 text-xs"></i> ' + loadingText;
        }, 0);
    }
    </script>

    {{-- Notification System --}}
    <script>
    (function() {
        const VAPID_PUBLIC = @json(config('webpush.vapid.public_key'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        const notifBadge = document.getElementById('notifBadge');
        const notifList = document.getElementById('notifList');
        const notifMarkAll = document.getElementById('notifMarkAll');
        const notifPushToggle = document.getElementById('notifPushToggle');
        let dropdownOpen = false;

        const colorMap = {
            green: 'bg-emerald-50 text-emerald-600',
            red: 'bg-red-50 text-red-600',
            blue: 'bg-blue-50 text-blue-600',
            orange: 'bg-amber-50 text-amber-600',
            yellow: 'bg-yellow-50 text-yellow-600',
            gray: 'bg-gray-50 text-gray-500',
        };

        // Toggle dropdown
        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownOpen = !dropdownOpen;
            notifDropdown.classList.toggle('open', dropdownOpen);
            if (dropdownOpen) loadNotifications();
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('notifWrapper').contains(e.target)) {
                dropdownOpen = false;
                notifDropdown.classList.remove('open');
            }
        });

        // Load notifications
        function loadNotifications() {
            fetch('{{ route("admin.notifications.index") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(items => {
                if (!items.length) {
                    notifList.innerHTML = '<div class="flex flex-col items-center justify-center py-10 text-gray-400"><i class="fas fa-bell-slash text-2xl mb-2"></i><p class="text-sm">Sin notificaciones</p></div>';
                    return;
                }
                notifList.innerHTML = items.map(n => {
                    const colors = colorMap[n.color] || colorMap.gray;
                    return `<a href="${n.url}" class="notif-item flex items-start gap-3 px-4 py-3 cursor-pointer ${n.read ? '' : 'unread'}" data-id="${n.id}">
                        <div class="w-8 h-8 rounded-lg ${colors} flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas ${n.icon} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-semibold text-gray-800 ${n.read ? '' : ''}">${n.title}</p>
                            <p class="text-[12px] text-gray-500 truncate">${n.message}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">${n.time}</p>
                        </div>
                        ${n.read ? '' : '<div class="w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0 mt-2"></div>'}
                    </a>`;
                }).join('');

                // Click on notification -> mark as read + navigate
                notifList.querySelectorAll('.notif-item').forEach(el => {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();
                        const id = this.dataset.id;
                        const url = this.getAttribute('href');
                        fetch('{{ route("admin.notifications.read") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify({ id: id })
                        }).finally(() => {
                            window.location.href = url;
                        });
                    });
                });
            })
            .catch(() => {
                notifList.innerHTML = '<div class="flex items-center justify-center py-8 text-red-400 text-sm">Error al cargar</div>';
            });
        }

        // Mark all as read
        notifMarkAll.addEventListener('click', function() {
            fetch('{{ route("admin.notifications.read") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({})
            }).then(() => {
                notifList.querySelectorAll('.unread').forEach(el => {
                    el.classList.remove('unread');
                    const dot = el.querySelector('.bg-indigo-500');
                    if (dot) dot.remove();
                });
                notifBadge.classList.add('hidden');
            });
        });

        // Poll unread count every 30s
        function pollCount() {
            fetch('{{ route("admin.notifications.count") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.count > 0) {
                    notifBadge.classList.remove('hidden');
                } else {
                    notifBadge.classList.add('hidden');
                }
            })
            .catch(() => {});
        }
        pollCount();
        setInterval(pollCount, 30000);

        // Push subscription
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        async function isPushSubscribed() {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) return false;
            const reg = await navigator.serviceWorker.getRegistration('/sw.js');
            if (!reg) return false;
            const sub = await reg.pushManager.getSubscription();
            return !!sub;
        }

        async function updatePushButton() {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                notifPushToggle.innerHTML = '<i class="fas fa-mobile-screen text-xs"></i>';
                notifPushToggle.title = 'Push no disponible en este navegador';
                notifPushToggle.classList.add('text-gray-300');
                return;
            }
            const subscribed = await isPushSubscribed();
            notifPushToggle.innerHTML = subscribed
                ? '<i class="fas fa-bell-slash text-xs"></i>'
                : '<i class="fas fa-mobile-screen text-xs"></i>';
            notifPushToggle.title = subscribed ? 'Desactivar push' : 'Activar push en este dispositivo';
            notifPushToggle.classList.toggle('text-indigo-500', subscribed);
        }

        notifPushToggle.addEventListener('click', async function() {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
                if (isIOS) {
                    showToast('En iPhone/iPad, agrega esta web a la pantalla de inicio para activar notificaciones push', 'warning');
                } else {
                    showToast('Tu navegador no soporta notificaciones push. Prueba con Chrome o Edge.', 'warning');
                }
                return;
            }

            const subscribed = await isPushSubscribed();

            if (subscribed) {
                // Unsubscribe
                const reg = await navigator.serviceWorker.getRegistration('/sw.js');
                const sub = await reg.pushManager.getSubscription();
                if (sub) {
                    await fetch('{{ route("admin.push.destroy") }}', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({ endpoint: sub.endpoint })
                    });
                    await sub.unsubscribe();
                    showToast('Push desactivado en este dispositivo', 'success');
                }
            } else {
                // Subscribe
                try {
                    const permission = await Notification.requestPermission();
                    if (permission !== 'granted') {
                        showToast('Permiso de notificaciones denegado', 'warning');
                        return;
                    }
                    const reg = await navigator.serviceWorker.register('/sw.js');
                    await navigator.serviceWorker.ready;
                    const sub = await reg.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC)
                    });
                    const subJson = sub.toJSON();
                    await fetch('{{ route("admin.push.store") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: JSON.stringify({
                            endpoint: subJson.endpoint,
                            keys: { p256dh: subJson.keys.p256dh, auth: subJson.keys.auth }
                        })
                    });
                    showToast('Push activado — recibirás alertas en este dispositivo', 'success');
                } catch (err) {
                    console.error('Push subscription error:', err);
                    if (window.location.protocol === 'http:' && window.location.hostname !== 'localhost') {
                        showToast('Push requiere HTTPS. Usa tu dominio con SSL.', 'error');
                    } else {
                        showToast('No se pudo activar push. Intenta de nuevo.', 'error');
                    }
                    return;
                }
            }
            updatePushButton();
        });

        updatePushButton();
    })();
    </script>

    {{-- Search Modal --}}
    <div id="searchModal" class="hidden fixed inset-0 z-[100]" role="dialog" aria-modal="true">
        <div class="search-backdrop absolute inset-0 bg-black/25" id="searchBackdrop"></div>
        <div class="relative w-full h-full sm:h-auto sm:max-w-lg sm:mx-auto sm:mt-[15vh] sm:px-4">
            <div class="search-panel bg-white h-full sm:h-auto sm:rounded-2xl sm:shadow-2xl sm:ring-1 sm:ring-black/5 flex flex-col sm:max-h-[70vh] overflow-hidden">
                {{-- Input --}}
                <div class="flex items-center gap-3 px-4 h-14 border-b border-gray-200/60 flex-shrink-0">
                    <i class="fas fa-search text-gray-400 text-sm" id="searchIcon"></i>
                    <i class="fas fa-circle-notch fa-spin text-indigo-500 text-sm hidden" id="searchSpinner"></i>
                    <input type="text" id="globalSearchInput"
                        placeholder="Buscar..."
                        autocomplete="off"
                        class="flex-1 text-base sm:text-[15px] bg-transparent outline-none text-gray-900 placeholder-gray-400">
                    <button type="button" onclick="closeSearchModal()" class="sm:hidden text-sm text-gray-500 font-medium px-1">Cerrar</button>
                    <kbd class="hidden sm:block text-[10px] text-gray-400 bg-gray-100 border border-gray-200 rounded px-1.5 py-0.5 font-mono cursor-pointer hover:bg-gray-200 transition" onclick="closeSearchModal()">ESC</kbd>
                </div>

                {{-- Body --}}
                <div class="flex-1 min-h-0 overflow-hidden">
                    {{-- Hint --}}
                    <div id="globalSearchHint" class="flex flex-col items-center justify-center py-12 px-6 text-center">
                        <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center mb-3">
                            <i class="fas fa-magnifying-glass text-indigo-400"></i>
                        </div>
                        <p class="text-sm text-gray-500">Busca ventas, productos, usuarios y más</p>
                        <p class="text-xs text-gray-400 mt-1 hidden sm:block">Usa <kbd class="bg-gray-100 border border-gray-200 rounded px-1 font-mono text-[10px]">↑</kbd><kbd class="bg-gray-100 border border-gray-200 rounded px-1 font-mono text-[10px]">↓</kbd> para navegar, <kbd class="bg-gray-100 border border-gray-200 rounded px-1 font-mono text-[10px]">↵</kbd> para abrir</p>
                    </div>

                    {{-- Loading --}}
                    <div id="globalSearchLoading" class="hidden flex flex-col items-center justify-center py-12">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center mb-2">
                            <i class="fas fa-circle-notch fa-spin text-indigo-400"></i>
                        </div>
                        <p class="text-sm text-gray-400">Buscando...</p>
                    </div>

                    {{-- Empty --}}
                    <div id="globalSearchEmpty" class="hidden flex flex-col items-center justify-center py-12 px-6 text-center">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                            <i class="fas fa-search text-gray-300"></i>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">Sin resultados</p>
                        <p class="text-xs text-gray-400 mt-0.5">Intenta con otro término</p>
                    </div>

                    {{-- Results --}}
                    <div id="globalSearchList" class="overflow-y-auto overscroll-contain h-full"></div>
                </div>

                {{-- Footer desktop --}}
                <div id="globalSearchFooter" class="hidden border-t border-gray-100 bg-gray-50/80 px-4 py-2 flex-shrink-0">
                    <div class="flex items-center gap-4 text-[11px] text-gray-400">
                        <span><kbd class="bg-white border border-gray-200 rounded px-1 py-0.5 font-mono text-[10px] shadow-sm">↑↓</kbd> navegar</span>
                        <span><kbd class="bg-white border border-gray-200 rounded px-1 py-0.5 font-mono text-[10px] shadow-sm">↵</kbd> abrir</span>
                        <span><kbd class="bg-white border border-gray-200 rounded px-1 py-0.5 font-mono text-[10px] shadow-sm">esc</kbd> cerrar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Global Search Script --}}
    <script>
    (function() {
        const modal    = document.getElementById('searchModal');
        const backdrop = document.getElementById('searchBackdrop');
        const input    = document.getElementById('globalSearchInput');
        const listEl   = document.getElementById('globalSearchList');
        const loadingEl = document.getElementById('globalSearchLoading');
        const emptyEl  = document.getElementById('globalSearchEmpty');
        const hintEl   = document.getElementById('globalSearchHint');
        const footerEl = document.getElementById('globalSearchFooter');
        const kbdEl    = document.getElementById('globalSearchKbd');
        const iconEl   = document.getElementById('searchIcon');
        const spinEl   = document.getElementById('searchSpinner');

        if (!modal || !input) return;

        // OS shortcut label
        const isMac = /Mac|iPhone|iPad/.test(navigator.userAgent);
        if (kbdEl && !isMac) kbdEl.textContent = 'Ctrl K';

        let timer = null, abort = null, idx = -1, items = [];

        const colors = {
            'fa-receipt':       { bg: 'bg-indigo-50',  tx: 'text-indigo-500' },
            'fa-box':           { bg: 'bg-amber-50',   tx: 'text-amber-500' },
            'fa-users':         { bg: 'bg-emerald-50', tx: 'text-emerald-500' },
            'fa-truck-field':   { bg: 'bg-sky-50',     tx: 'text-sky-500' },
            'fa-cart-shopping': { bg: 'bg-violet-50',  tx: 'text-violet-500' },
        };

        function spin(on) {
            iconEl.classList.toggle('hidden', on);
            spinEl.classList.toggle('hidden', !on);
        }

        function reset() {
            loadingEl.classList.add('hidden');
            emptyEl.classList.add('hidden');
            hintEl.classList.add('hidden');
        }

        function showFooter(show) {
            footerEl.style.display = show ? '' : 'none';
        }

        // ---- Open / Close ----
        window.openSearchModal = function() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            hintEl.classList.remove('hidden');
            showFooter(false);
            setTimeout(() => input.focus(), 60);
        };

        window.closeSearchModal = function() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            input.value = '';
            listEl.innerHTML = '';
            reset();
            hintEl.classList.remove('hidden');
            showFooter(false);
            spin(false);
            idx = -1;
            items = [];
            if (abort) { abort.abort(); abort = null; }
        };

        // Close on backdrop click
        backdrop.addEventListener('click', closeSearchModal);

        // ---- ESC global + Ctrl+K ----
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                e.preventDefault();
                e.stopPropagation();
                closeSearchModal();
                return;
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                modal.classList.contains('hidden') ? openSearchModal() : closeSearchModal();
            }
        });

        // ---- Render ----
        function esc(t) { const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }

        function render(groups) {
            listEl.innerHTML = '';
            reset();
            spin(false);
            items = [];
            idx = -1;

            if (!groups.length) { emptyEl.classList.remove('hidden'); showFooter(false); return; }

            showFooter(true);

            groups.forEach(g => {
                const c = colors[g.icon] || { bg: 'bg-gray-50', tx: 'text-gray-400' };

                const hdr = document.createElement('div');
                hdr.className = 'search-group sticky top-0 z-10 flex items-center gap-2 px-4 py-2 bg-gray-50/95 backdrop-blur-sm border-b border-gray-100';
                hdr.innerHTML = `<i class="fas ${g.icon} text-[10px] ${c.tx} w-4 text-center"></i>`
                    + `<span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">${g.group}</span>`
                    + `<span class="ml-auto text-[10px] text-gray-300">${g.items.length}</span>`;
                listEl.appendChild(hdr);

                g.items.forEach(item => {
                    const a = document.createElement('a');
                    a.href = item.url;
                    a.className = 'search-item flex items-center gap-3 px-4 py-2.5 cursor-pointer';
                    a.innerHTML = `<div class="w-9 h-9 rounded-lg ${c.bg} flex items-center justify-center flex-shrink-0">`
                        + `<i class="fas ${g.icon} text-[11px] ${c.tx}"></i></div>`
                        + `<div class="min-w-0 flex-1">`
                        + `<p class="text-sm font-medium text-gray-700 truncate">${esc(item.title)}</p>`
                        + `<p class="text-[11px] text-gray-400 truncate">${esc(item.subtitle || '')}</p>`
                        + `</div>`
                        + `<i class="fas fa-arrow-right text-[10px] text-gray-300 search-arrow flex-shrink-0"></i>`;
                    listEl.appendChild(a);
                    items.push(a);
                });
            });

            highlight(0);
        }

        function highlight(i) {
            items.forEach((el, n) => el.classList.toggle('active', n === i));
            idx = i;
            if (items[i]) items[i].scrollIntoView({ block: 'nearest' });
        }

        // ---- Search ----
        function doSearch(q) {
            if (q.length < 2) {
                listEl.innerHTML = ''; reset(); hintEl.classList.remove('hidden');
                spin(false); showFooter(false);
                return;
            }

            if (abort) abort.abort();
            abort = new AbortController();

            reset(); spin(true);
            loadingEl.classList.remove('hidden');
            listEl.innerHTML = '';

            fetch('{{ route("admin.search") }}?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json' },
                signal: abort.signal
            })
            .then(r => r.json())
            .then(data => {
                if (input.value.trim().length < 2) return;
                render(data);
            })
            .catch(e => {
                if (e.name === 'AbortError') return;
                spin(false); reset();
                emptyEl.classList.remove('hidden');
            });
        }

        input.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => doSearch(this.value.trim()), 400);
        });

        // ---- Keyboard nav ----
        input.addEventListener('keydown', function(e) {
            if (!items.length) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); highlight(idx < items.length - 1 ? idx + 1 : 0); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); highlight(idx > 0 ? idx - 1 : items.length - 1); }
            else if (e.key === 'Enter' && idx >= 0) { e.preventDefault(); items[idx].click(); }
        });
    })();
    </script>

    @yield('scripts')
</body>

</html>