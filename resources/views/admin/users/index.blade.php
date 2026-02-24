@extends('admin.layouts.app')
@section('title', 'Usuarios')

@php
    $roleBadges = [
        'admin' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'label' => 'Admin', 'icon' => 'fa-shield-halved'],
        'vendedor' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'label' => 'Vendedor', 'icon' => 'fa-store'],
        'cliente' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => 'Cliente', 'icon' => 'fa-user'],
    ];
    $avatarColors = [
        'admin' => 'from-indigo-400 to-indigo-600',
        'vendedor' => 'from-orange-400 to-orange-600',
        'cliente' => 'from-slate-400 to-slate-600',
    ];
@endphp

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    {{-- Total Users --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Usuarios</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Registrados en la plataforma</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-users text-indigo-500"></i>
        </div>
    </div>
    {{-- Active Users --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Usuarios Activos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($activeUsers) }}</h3>
            <p class="text-xs mt-1">
                @if($totalUsers > 0)
                    <span class="text-emerald-500 font-semibold">{{ round(($activeUsers / $totalUsers) * 100) }}%</span>
                    <span class="text-gray-400">del total</span>
                @else
                    <span class="text-gray-400">Sin datos</span>
                @endif
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-check text-emerald-500"></i>
        </div>
    </div>
    {{-- Inactive Users --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Usuarios Inactivos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($inactiveUsers) }}</h3>
            <p class="text-xs mt-1">
                @if($totalUsers > 0)
                    <span class="text-red-500 font-semibold">{{ round(($inactiveUsers / $totalUsers) * 100) }}%</span>
                    <span class="text-gray-400">del total</span>
                @else
                    <span class="text-gray-400">Sin datos</span>
                @endif
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-xmark text-red-500"></i>
        </div>
    </div>
    {{-- New This Week --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Nuevos esta semana</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($newUsersWeek) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Últimos 7 días</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-plus text-amber-500"></i>
        </div>
    </div>
</div>

{{-- ==================== SEARCH FILTERS CARD ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="px-5 pt-5 pb-2">
        <h3 class="text-base font-semibold text-gray-800">Filtros de búsqueda</h3>
    </div>
    <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm" class="px-5 pb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Rol</label>
                <select name="role" onchange="this.form.submit()"
                        class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none"
                        style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 20 20%22 fill=%22%236b7280%22><path fill-rule=%22evenodd%22 d=%22M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z%22 clip-rule=%22evenodd%22/></svg>'); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                    <option value="">Seleccionar rol</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="vendedor" {{ request('role') === 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                    <option value="cliente" {{ request('role') === 'cliente' ? 'selected' : '' }}>Cliente</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado</label>
                <select name="status" onchange="this.form.submit()"
                        class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none"
                        style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 20 20%22 fill=%22%236b7280%22><path fill-rule=%22evenodd%22 d=%22M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z%22 clip-rule=%22evenodd%22/></svg>'); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                    <option value="">Seleccionar estado</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <div class="flex items-end">
                @if(request('role') || (request('status') !== null && request('status') !== '') || request('search'))
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-rotate-left text-xs"></i> Limpiar filtros
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- ==================== TABLE CARD ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- Table Toolbar --}}
    <div class="px-5 py-4 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                {{-- Per page --}}
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                    @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                    @if(request('role'))<input type="hidden" name="role" value="{{ request('role') }}">@endif
                    @if(request('status') !== null && request('status') !== '')<input type="hidden" name="status" value="{{ request('status') }}">@endif
                    <select name="per_page" onchange="this.form.submit()"
                            class="pl-3 pr-8 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer bg-white appearance-none"
                            style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 20 20%22 fill=%22%236b7280%22><path fill-rule=%22evenodd%22 d=%22M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z%22 clip-rule=%22evenodd%22/></svg>'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 0.875rem;">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="flex items-center gap-3 flex-1 sm:flex-initial">
                {{-- Search --}}
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 sm:flex-initial">
                    @if(request('role'))<input type="hidden" name="role" value="{{ request('role') }}">@endif
                    @if(request('status') !== null && request('status') !== '')<input type="hidden" name="status" value="{{ request('status') }}">@endif
                    @if(request('per_page'))<input type="hidden" name="per_page" value="{{ request('per_page') }}">@endif
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar usuario"
                               class="w-full sm:w-52 pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                </form>
                {{-- Export --}}
                <button type="button" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-up-from-bracket text-xs"></i> Exportar
                </button>
                {{-- Add New --}}
                <button onclick="openCreateDrawer()"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm shadow-indigo-200 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-plus text-xs"></i>
                    <span class="hidden sm:inline">Nuevo Usuario</span>
                    <span class="sm:hidden">Nuevo</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="w-12 px-5 py-3.5">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                    </th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                @php
                    $badge = $roleBadges[$user->role] ?? $roleBadges['cliente'];
                    $avatarGradient = $avatarColors[$user->role] ?? $avatarColors['cliente'];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3">
                        <input type="checkbox" class="row-check w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $avatarGradient }} flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-800 text-sm">{{ $user->full_name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-md {{ $badge['bg'] }} flex items-center justify-center">
                                <i class="fas {{ $badge['icon'] }} {{ $badge['text'] }} text-xs"></i>
                            </span>
                            <span class="text-sm text-gray-700">{{ $badge['label'] }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-500">{{ $user->phone ?: '—' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($user->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600">Active</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <button data-user="{{ json_encode($user->only('id','first_name','last_name','email','phone','role','is_active','newsletter','created_at','updated_at')) }}" onclick="openEditDrawer(JSON.parse(this.dataset.user))"
                               class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition" title="Editar">
                                <i class="fas fa-pen-to-square text-sm"></i>
                            </button>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $user->full_name }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition" title="Eliminar">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-users text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-600 font-semibold mb-1">Sin resultados</h3>
                            <p class="text-sm text-gray-400 max-w-xs">No se encontraron usuarios que coincidan con los filtros</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($users as $user)
        @php
            $badge = $roleBadges[$user->role] ?? $roleBadges['cliente'];
            $avatarGradient = $avatarColors[$user->role] ?? $avatarColors['cliente'];
        @endphp
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $avatarGradient }} flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ $user->full_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                </div>
                <div class="flex items-center gap-0.5">
                    <button data-user="{{ json_encode($user->only('id','first_name','last_name','email','phone','role','is_active','newsletter','created_at','updated_at')) }}" onclick="openEditDrawer(JSON.parse(this.dataset.user))"
                       class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 rounded-md transition">
                        <i class="fas fa-pen-to-square text-sm"></i>
                    </button>
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $user->full_name }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-md transition">
                            <i class="fas fa-trash-can text-sm"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="mt-2 ml-[52px] flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded {{ $badge['bg'] }} flex items-center justify-center">
                        <i class="fas {{ $badge['icon'] }} {{ $badge['text'] }} text-[9px]"></i>
                    </span>
                    <span class="text-xs text-gray-600">{{ $badge['label'] }}</span>
                </div>
                <span class="text-gray-300">|</span>
                @if($user->is_active)
                    <span class="text-xs font-semibold text-emerald-600">Active</span>
                @else
                    <span class="text-xs font-semibold text-red-500">Inactive</span>
                @endif
                @if($user->phone)
                    <span class="text-gray-300">|</span>
                    <span class="text-xs text-gray-400">{{ $user->phone }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-users text-xl text-gray-300"></i>
            </div>
            <p class="text-sm text-gray-400">No se encontraron usuarios</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Footer --}}
    @if($users->total() > 0)
    <div class="px-5 py-3.5 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ number_format($users->total()) }} registros
            </p>
            @if($users->hasPages())
            <nav class="flex items-center gap-1">
                {{-- First --}}
                @if($users->currentPage() > 2)
                    <a href="{{ $users->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Primera">
                        <i class="fas fa-angles-left"></i>
                    </a>
                @endif
                {{-- Prev --}}
                @if($users->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-left"></i></a>
                @endif

                @php
                    $current = $users->currentPage();
                    $last = $users->lastPage();
                    $pages = [];
                    if ($last <= 5) { $pages = range(1, $last); }
                    else {
                        $pages[] = 1;
                        if ($current > 3) $pages[] = '...';
                        for ($i = max(2, $current - 1); $i <= min($last - 1, $current + 1); $i++) { $pages[] = $i; }
                        if ($current < $last - 2) $pages[] = '...';
                        $pages[] = $last;
                    }
                @endphp
                @foreach($pages as $page)
                    @if($page === '...')
                        <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-xs">...</span>
                    @elseif($page == $current)
                        <span class="w-8 h-8 flex items-center justify-center rounded-md bg-indigo-500 text-white text-xs font-semibold shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $users->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
                @endif
                {{-- Last --}}
                @if($users->currentPage() < $last - 1)
                    <a href="{{ $users->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Última">
                        <i class="fas fa-angles-right"></i>
                    </a>
                @endif
            </nav>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ==================== SHARED OVERLAY ==================== --}}
<div id="drawerOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] opacity-0 invisible transition-all duration-300" onclick="closeDrawer()"></div>

{{-- ==================== CREATE DRAWER ==================== --}}
<div id="createDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[480px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Nuevo Usuario</h2>
            <p class="text-sm text-gray-400 mt-0.5">Completa los datos del nuevo usuario</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm" class="p-6">
            @csrf
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <span id="createAvatarPreview" class="text-2xl font-bold text-white">?</span>
                </div>
            </div>
            @include('admin.users._form-fields', ['user' => null, 'prefix' => 'create', 'passwordRequired' => true])
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="document.getElementById('createUserForm').submit()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-plus mr-1.5 text-xs"></i> Crear Usuario
        </button>
    </div>
</div>

{{-- ==================== EDIT DRAWER ==================== --}}
<div id="editDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[480px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Editar Usuario</h2>
            <p class="text-sm text-gray-400 mt-0.5" id="editDrawerSubtitle">Modifica los datos del usuario</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="" id="editUserForm" class="p-6">
            @csrf
            @method('PUT')
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <span id="editAvatarPreview" class="text-2xl font-bold text-white">?</span>
                </div>
            </div>
            @include('admin.users._form-fields', ['user' => null, 'prefix' => 'edit', 'passwordRequired' => false])

            <div class="mt-5 pt-5 border-t border-gray-100">
                <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                    <span><i class="fas fa-calendar-alt mr-1"></i> Registro: <span id="editCreatedAt"></span></span>
                    <span><i class="fas fa-clock mr-1"></i> Actualizado: <span id="editUpdatedAt"></span></span>
                </div>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="document.getElementById('editUserForm').submit()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-check mr-1.5 text-xs"></i> Guardar Cambios
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const drawerOverlay = document.getElementById('drawerOverlay');
    const createDrawer = document.getElementById('createDrawer');
    const editDrawer = document.getElementById('editDrawer');
    let activeDrawer = null;

    function showDrawer(drawer) {
        activeDrawer = drawer;
        drawerOverlay.classList.remove('opacity-0', 'invisible');
        drawerOverlay.classList.add('opacity-100', 'visible');
        drawer.classList.remove('translate-x-full');
        drawer.classList.add('translate-x-0');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        drawerOverlay.classList.add('opacity-0', 'invisible');
        drawerOverlay.classList.remove('opacity-100', 'visible');
        if (activeDrawer) {
            activeDrawer.classList.add('translate-x-full');
            activeDrawer.classList.remove('translate-x-0');
        }
        document.body.style.overflow = '';
        activeDrawer = null;
    }

    function openCreateDrawer() {
        showDrawer(createDrawer);
        setTimeout(() => document.getElementById('create_first_name').focus(), 300);
    }

    function openEditDrawer(user) {
        document.getElementById('edit_first_name').value = user.first_name;
        document.getElementById('edit_last_name').value = user.last_name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_phone').value = user.phone || '';
        document.getElementById('edit_password').value = '';

        document.getElementById('editUserForm').action = '/admin/users/' + user.id;
        document.getElementById('editDrawerSubtitle').textContent = user.first_name + ' ' + user.last_name;

        const f = (user.first_name || '?')[0].toUpperCase();
        const l = (user.last_name || '')[0]?.toUpperCase() || '';
        document.getElementById('editAvatarPreview').textContent = f + l;

        document.querySelectorAll('#editDrawer input[name="role"]').forEach(r => {
            r.checked = r.value === user.role;
        });

        const activeCheck = document.querySelector('#editDrawer input[name="is_active"][type="checkbox"]');
        if (activeCheck) activeCheck.checked = user.is_active;

        const newsletterCheck = document.querySelector('#editDrawer input[name="newsletter"][type="checkbox"]');
        if (newsletterCheck) newsletterCheck.checked = user.newsletter;

        const fmtDate = (d) => {
            if (!d) return '—';
            const dt = new Date(d);
            return dt.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        };
        document.getElementById('editCreatedAt').textContent = fmtDate(user.created_at);
        document.getElementById('editUpdatedAt').textContent = fmtDate(user.updated_at);

        showDrawer(editDrawer);
        setTimeout(() => document.getElementById('edit_first_name').focus(), 300);
    }

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDrawer();
    });

    // Avatar live preview - create
    const cf = document.getElementById('create_first_name');
    const cl = document.getElementById('create_last_name');
    const ca = document.getElementById('createAvatarPreview');
    function updateCreateAvatar() {
        ca.textContent = ((cf.value || '?')[0] + (cl.value || '')[0]?.toUpperCase() || '').toUpperCase();
    }
    cf.addEventListener('input', updateCreateAvatar);
    cl.addEventListener('input', updateCreateAvatar);

    // Avatar live preview - edit
    const ef = document.getElementById('edit_first_name');
    const el2 = document.getElementById('edit_last_name');
    const ea = document.getElementById('editAvatarPreview');
    function updateEditAvatar() {
        ea.textContent = ((ef.value || '?')[0] + (el2.value || '')[0]?.toUpperCase() || '').toUpperCase();
    }
    ef.addEventListener('input', updateEditAvatar);
    el2.addEventListener('input', updateEditAvatar);

    // Toggle password visibility
    function togglePass(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Select all checkboxes
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
        });
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.addEventListener('change', () => {
                const all = document.querySelectorAll('.row-check');
                const checked = document.querySelectorAll('.row-check:checked');
                selectAll.checked = all.length === checked.length;
                selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
            });
        });
    }

    // Auto-open if validation errors
    @if($errors->any() && old('_method') === 'PUT')
        openEditDrawer({
            id: '{{ old("_user_id") }}',
            first_name: '{{ old("first_name") }}',
            last_name: '{{ old("last_name") }}',
            email: '{{ old("email") }}',
            phone: '{{ old("phone") }}',
            role: '{{ old("role", "cliente") }}',
            is_active: {{ old('is_active', 1) }},
            newsletter: {{ old('newsletter', 0) }},
            created_at: null,
            updated_at: null
        });
    @elseif($errors->any())
        openCreateDrawer();
    @endif
</script>
@endsection
