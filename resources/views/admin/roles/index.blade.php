@extends('admin.layouts.app')
@section('title', 'Roles y Permisos')

@section('styles')
<style>
    .role-tab { position: relative; transition: all 0.2s ease; }
    .role-tab::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: transparent;
        border-radius: 2px 2px 0 0;
        transition: all 0.2s ease;
    }
    .role-tab.active { color: #4f46e5; }
    .role-tab.active::after { background: #4f46e5; }
    .role-tab:not(.active):hover { color: #374151; }

    .perm-section { border-radius: 12px; overflow: hidden; transition: all 0.2s ease; }
    .perm-section:hover { box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
    .perm-section-header { cursor: pointer; user-select: none; }
    .perm-section-body { max-height: 500px; transition: max-height 0.3s ease; }
    .perm-section.collapsed .perm-section-body { max-height: 0; overflow: hidden; }
    .perm-section .chevron-icon { transition: transform 0.25s ease; }
    .perm-section.collapsed .chevron-icon { transform: rotate(-90deg); }

    .perm-check { transition: all 0.15s ease; }
    .perm-check:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .perm-check.checked { background: linear-gradient(135deg, #eef2ff 0%, #e8e0ff 100%); border-color: #a5b4fc; }

    .role-panel { display: none; animation: fadeSlide 0.25s ease; }
    .role-panel.active { display: block; }
    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-ring {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        position: relative;
    }
    .stat-ring svg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; transform: rotate(-90deg); }
    .stat-ring circle { fill: none; stroke-width: 3; stroke-linecap: round; }

    .module-progress { height: 3px; border-radius: 2px; background: #f1f5f9; overflow: hidden; }
    .module-progress-bar { height: 100%; border-radius: 2px; transition: width 0.4s ease; }

    .toggle-switch { position: relative; width: 44px; height: 24px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-track {
        position: absolute; inset: 0;
        background: #d1d5db; border-radius: 12px;
        transition: background 0.2s ease;
        cursor: pointer;
    }
    .toggle-track::after {
        content: '';
        position: absolute;
        top: 2px; left: 2px;
        width: 20px; height: 20px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        transition: transform 0.2s ease;
    }
    .toggle-switch input:checked + .toggle-track { background: #6366f1; }
    .toggle-switch input:checked + .toggle-track::after { transform: translateX(20px); }
</style>
@endsection

@php
    $totalPermissions = $permissions->flatten()->count();
    $moduleIcons = [
        'Dashboard' => 'fa-chart-pie',
        'Usuarios' => 'fa-users',
        'Categorías' => 'fa-tags',
        'Productos' => 'fa-box',
        'Proveedores' => 'fa-truck-field',
        'Ventas' => 'fa-receipt',
        'Compras' => 'fa-cart-shopping',
        'Kardex' => 'fa-arrow-right-arrow-left',
        'Lista de Deseos' => 'fa-heart',
        'Reseñas' => 'fa-star',
        'Suscriptores' => 'fa-bell',
        'Reclamaciones' => 'fa-book',
        'Mensajes de Contacto' => 'fa-envelope',
        'Reportes' => 'fa-chart-bar',
        'Configuración' => 'fa-gear',
        'Roles y Permisos' => 'fa-user-shield',
    ];
    $moduleColors = [
        'Dashboard' => 'indigo',
        'Usuarios' => 'blue',
        'Categorías' => 'violet',
        'Productos' => 'amber',
        'Proveedores' => 'teal',
        'Ventas' => 'emerald',
        'Compras' => 'orange',
        'Kardex' => 'cyan',
        'Lista de Deseos' => 'pink',
        'Reseñas' => 'yellow',
        'Suscriptores' => 'sky',
        'Reclamaciones' => 'rose',
        'Mensajes de Contacto' => 'slate',
        'Reportes' => 'purple',
        'Configuración' => 'gray',
        'Roles y Permisos' => 'indigo',
    ];
@endphp

@section('content')
{{-- ==================== HEADER ==================== --}}
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
    <div>
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-200/50">
                <i class="fas fa-user-shield text-white text-sm"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Roles y Permisos</h1>
                <p class="text-sm text-gray-400 mt-0.5">Control de acceso y privilegios del sistema</p>
            </div>
        </div>
    </div>
    @if(Auth::user()->hasPermission('roles.edit'))
    <button onclick="openCreateRoleDrawer()"
        class="group inline-flex items-center gap-2.5 px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all text-sm font-semibold shadow-lg shadow-indigo-200/50 hover:shadow-indigo-300/50 hover:-translate-y-0.5">
        <i class="fas fa-plus text-xs bg-white/20 w-5 h-5 rounded-md flex items-center justify-center group-hover:bg-white/30 transition"></i>
        Nuevo Rol
    </button>
    @endif
</div>

{{-- ==================== ROLE TABS + CARDS ==================== --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- Tabs Navigation --}}
    <div class="border-b border-gray-100 px-1 flex items-center overflow-x-auto">
        <div class="flex items-center gap-0.5 px-4 py-0.5">
            @foreach($roles as $i => $role)
            @php
                $permCount = count($rolePermissions[$role->name] ?? []);
                $pct = $totalPermissions > 0 ? round(($permCount / $totalPermissions) * 100) : 0;
            @endphp
            <button onclick="switchRole('{{ $role->name }}')" id="tab-{{ $role->name }}"
                class="role-tab flex items-center gap-2.5 px-4 py-3.5 text-sm font-medium text-gray-400 whitespace-nowrap {{ $i === 0 ? 'active' : '' }}">
                <div class="w-7 h-7 rounded-lg {{ $i === 0 ? 'bg-indigo-100' : 'bg-gray-100' }} flex items-center justify-center transition" id="tab-icon-{{ $role->name }}">
                    @if($role->name === 'admin')
                        <i class="fas fa-shield-halved text-[11px] {{ $i === 0 ? 'text-indigo-500' : 'text-gray-400' }}" id="tab-i-{{ $role->name }}"></i>
                    @elseif($role->name === 'vendedor')
                        <i class="fas fa-store text-[11px] {{ $i === 0 ? 'text-indigo-500' : 'text-gray-400' }}" id="tab-i-{{ $role->name }}"></i>
                    @else
                        <i class="fas fa-user-gear text-[11px] {{ $i === 0 ? 'text-indigo-500' : 'text-gray-400' }}" id="tab-i-{{ $role->name }}"></i>
                    @endif
                </div>
                <span>{{ $role->display_name }}</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md {{ $i === 0 ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-400' }} transition" id="tab-badge-{{ $role->name }}">{{ $permCount }}</span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- Role Panels --}}
    @foreach($roles as $i => $role)
    @php
        $isAdmin = $role->name === 'admin';
        $permCount = count($rolePermissions[$role->name] ?? []);
        $pct = $totalPermissions > 0 ? round(($permCount / $totalPermissions) * 100) : 0;
    @endphp
    <div id="panel-{{ $role->name }}" class="role-panel {{ $i === 0 ? 'active' : '' }}">

        {{-- Role Info Bar --}}
        <div class="px-6 py-5 bg-gradient-to-r from-gray-50/80 to-white border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                {{-- Role details --}}
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="stat-ring flex-shrink-0">
                        <svg viewBox="0 0 44 44">
                            <circle cx="22" cy="22" r="20" stroke="#e2e8f0" />
                            <circle cx="22" cy="22" r="20" stroke="{{ $isAdmin ? '#6366f1' : '#8b5cf6' }}"
                                stroke-dasharray="{{ 2 * 3.14159 * 20 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 20 * (1 - $pct / 100) }}" />
                        </svg>
                        <span class="text-xs font-bold {{ $isAdmin ? 'text-indigo-600' : 'text-violet-600' }}">{{ $pct }}%</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-gray-900">{{ $role->display_name }}</h2>
                            @if($role->is_system)
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Sistema</span>
                            @endif
                            @if(!$role->is_system && Auth::user()->hasPermission('roles.edit'))
                            <button onclick="openEditRoleDrawer({{ json_encode($role) }})"
                                class="w-6 h-6 flex items-center justify-center text-gray-300 hover:text-indigo-500 hover:bg-indigo-50 rounded-md transition" aria-label="Editar rol">
                                <i class="fas fa-pen text-[10px]"></i>
                            </button>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $role->description ?: 'Sin descripción' }}</p>
                    </div>
                </div>

                {{-- Stats chips --}}
                <div class="flex items-center gap-2.5 flex-wrap">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-100 shadow-sm">
                        <i class="fas fa-key text-[10px] text-indigo-400"></i>
                        <span class="text-xs font-semibold text-gray-700" id="stat-count-{{ $role->name }}">{{ $permCount }}</span>
                        <span class="text-[10px] text-gray-400">/ {{ $totalPermissions }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-100 shadow-sm">
                        <i class="fas fa-users text-[10px] text-violet-400"></i>
                        <span class="text-xs font-semibold text-gray-700">{{ $role->usersCount() }}</span>
                        <span class="text-[10px] text-gray-400">usuarios</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $role->is_admin ? 'bg-emerald-50 border border-emerald-100' : 'bg-gray-50 border border-gray-100' }} shadow-sm">
                        <i class="fas {{ $role->is_admin ? 'fa-lock-open text-emerald-400' : 'fa-lock text-gray-400' }} text-[10px]"></i>
                        <span class="text-xs font-semibold {{ $role->is_admin ? 'text-emerald-600' : 'text-gray-500' }}">
                            {{ $role->is_admin ? 'Panel Admin' : 'Sin acceso' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action buttons --}}
            @if(!$isAdmin && Auth::user()->hasPermission('roles.edit'))
            <div class="flex items-center gap-2 mt-4">
                <button type="button" onclick="toggleAll('{{ $role->name }}', true)"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition border border-indigo-100">
                    <i class="fas fa-check-double text-[10px]"></i> Seleccionar todo
                </button>
                <button type="button" onclick="toggleAll('{{ $role->name }}', false)"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-500 bg-white rounded-lg hover:bg-gray-50 transition border border-gray-200">
                    <i class="fas fa-xmark text-[10px]"></i> Deseleccionar todo
                </button>
                <button type="button" onclick="expandAll('{{ $role->name }}')"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-500 bg-white rounded-lg hover:bg-gray-50 transition border border-gray-200">
                    <i class="fas fa-arrows-up-down text-[10px]"></i> Expandir/Colapsar
                </button>

                @if(!$role->is_system)
                <div class="flex-1"></div>
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $role->display_name }}')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-500 bg-white rounded-lg hover:bg-red-50 transition border border-red-200">
                        <i class="fas fa-trash-can text-[10px]"></i> Eliminar rol
                    </button>
                </form>
                @endif
            </div>
            @elseif($isAdmin)
            <div class="mt-4 px-3 py-2 rounded-lg bg-indigo-50/80 border border-indigo-100 flex items-center gap-2">
                <i class="fas fa-infinity text-indigo-400 text-xs"></i>
                <p class="text-xs text-indigo-600 font-medium">El administrador tiene acceso total. Todos los permisos siempre activos.</p>
            </div>
            @endif
        </div>

        {{-- Permissions Form --}}
        <form method="POST" action="{{ route('admin.roles.update') }}" id="form-{{ $role->name }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="role" value="{{ $role->name }}">

            <div class="p-6 space-y-3">
                @foreach($permissions as $module => $modulePermissions)
                @php
                    $mIcon = $moduleIcons[$module] ?? 'fa-puzzle-piece';
                    $mColor = $moduleColors[$module] ?? 'gray';
                    $moduleChecked = collect($modulePermissions)->filter(fn($p) => in_array($p->id, $rolePermissions[$role->name] ?? []))->count();
                    $moduleTotal = $modulePermissions->count();
                    $modulePct = $moduleTotal > 0 ? round(($moduleChecked / $moduleTotal) * 100) : 0;
                @endphp
                <div class="perm-section border border-gray-100 bg-white" data-role="{{ $role->name }}">
                    {{-- Section Header --}}
                    <div class="perm-section-header flex items-center gap-3 px-4 py-3 hover:bg-gray-50/50 transition"
                        onclick="this.closest('.perm-section').classList.toggle('collapsed')">
                        <div class="w-8 h-8 rounded-lg bg-{{ $mColor }}-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $mIcon }} text-{{ $mColor }}-500 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-semibold text-gray-800">{{ $module }}</h3>
                                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $moduleChecked === $moduleTotal ? 'bg-emerald-50 text-emerald-600' : ($moduleChecked > 0 ? 'bg-amber-50 text-amber-600' : 'bg-gray-100 text-gray-400') }}">
                                    {{ $moduleChecked }}/{{ $moduleTotal }}
                                </span>
                            </div>
                            <div class="module-progress mt-1.5 w-32">
                                <div class="module-progress-bar {{ $moduleChecked === $moduleTotal ? 'bg-emerald-400' : ($moduleChecked > 0 ? 'bg-amber-400' : 'bg-gray-200') }}"
                                    style="width: {{ $modulePct }}%"></div>
                            </div>
                        </div>
                        @if(!$isAdmin)
                        <button type="button" onclick="event.stopPropagation(); toggleModule('{{ $role->name }}', '{{ Str::slug($module) }}')"
                            class="px-2.5 py-1 text-[10px] font-semibold text-gray-400 hover:text-indigo-500 bg-gray-50 hover:bg-indigo-50 rounded-md transition uppercase tracking-wider">
                            Alternar
                        </button>
                        @endif
                        <i class="fas fa-chevron-down chevron-icon text-gray-300 text-[10px] mr-1"></i>
                    </div>

                    {{-- Section Body --}}
                    <div class="perm-section-body">
                        <div class="px-4 pb-4 pt-1">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                                @foreach($modulePermissions as $permission)
                                @php
                                    $checked = in_array($permission->id, $rolePermissions[$role->name] ?? []);
                                    $permParts = explode('.', $permission->name);
                                    $action = $permParts[1] ?? $permParts[0];
                                    $actionIcons = [
                                        'view' => 'fa-eye',
                                        'create' => 'fa-plus',
                                        'edit' => 'fa-pen',
                                        'delete' => 'fa-trash-can',
                                        'export' => 'fa-file-export',
                                        'adjust' => 'fa-sliders',
                                        'moderate' => 'fa-gavel',
                                        'respond' => 'fa-reply',
                                    ];
                                    $actionIcon = $actionIcons[$action] ?? 'fa-check';
                                @endphp
                                <label class="perm-check flex items-center gap-2.5 px-3 py-2.5 rounded-xl border cursor-pointer
                                    {{ $checked ? 'checked' : 'border-gray-100 bg-gray-50/30 hover:border-gray-200' }}
                                    {{ $isAdmin ? 'opacity-60 cursor-not-allowed' : '' }}">
                                    <div class="relative flex-shrink-0">
                                        <input type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->id }}"
                                            {{ $checked ? 'checked' : '' }}
                                            {{ $isAdmin ? 'disabled' : '' }}
                                            data-role="{{ $role->name }}"
                                            data-module="{{ Str::slug($module) }}"
                                            class="w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500
                                            {{ $isAdmin ? 'cursor-not-allowed' : 'cursor-pointer' }}"
                                            onchange="updatePerm(this)"
                                        >
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas {{ $actionIcon }} text-[9px] {{ $checked ? 'text-indigo-400' : 'text-gray-300' }}"></i>
                                            <span class="text-[13px] font-medium {{ $checked ? 'text-gray-800' : 'text-gray-500' }} leading-tight">{{ $permission->display_name }}</span>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Save Footer --}}
            @if(!$isAdmin && Auth::user()->hasPermission('roles.edit'))
            <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/80 to-white flex items-center justify-between sticky bottom-0">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5 text-sm">
                        <span class="font-bold text-indigo-600" id="count-{{ $role->name }}">{{ $permCount }}</span>
                        <span class="text-gray-400">de {{ $totalPermissions }} permisos</span>
                    </div>
                    <div class="w-24 h-1.5 rounded-full bg-gray-200 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-indigo-400 to-violet-500 transition-all duration-300"
                            style="width: {{ $pct }}%" id="bar-{{ $role->name }}"></div>
                    </div>
                </div>
                <button type="submit" onclick="lockBtn(this, 'Guardando...')"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all text-sm font-semibold shadow-lg shadow-indigo-200/50 hover:shadow-indigo-300/50 hover:-translate-y-0.5">
                    <i class="fas fa-check text-xs"></i> Guardar Permisos
                </button>
            </div>
            @endif
        </form>
    </div>
    @endforeach
</div>

{{-- ==================== SHARED OVERLAY ==================== --}}
<div id="roleDrawerOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] opacity-0 invisible transition-all duration-300" onclick="closeRoleDrawer()"></div>

{{-- ==================== CREATE ROLE DRAWER ==================== --}}
<div id="createRoleDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[440px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-200/50">
                    <i class="fas fa-plus text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Nuevo Rol</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Define un nuevo rol para el sistema</p>
                </div>
            </div>
            <button onclick="closeRoleDrawer()" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600" aria-label="Cerrar">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="{{ route('admin.roles.store') }}" id="createRoleForm" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nombre del rol</label>
                <input type="text" name="display_name" required placeholder="Ej: Supervisor, Almacenero, Soporte..."
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 focus:bg-white outline-none text-sm transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Descripción <span class="text-gray-300 normal-case font-normal">(opcional)</span></label>
                <textarea name="description" rows="3" placeholder="Describe las responsabilidades de este rol..."
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 focus:bg-white outline-none text-sm transition resize-none"></textarea>
            </div>

            <div class="p-4 rounded-xl bg-gradient-to-r from-gray-50 to-indigo-50/30 border border-gray-100">
                <label class="flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-shield-halved text-indigo-500 text-sm"></i>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-700">Acceso al panel admin</span>
                            <p class="text-[11px] text-gray-400 mt-0.5">Puede ingresar al panel de administración</p>
                        </div>
                    </div>
                    <input type="hidden" name="is_admin" value="0">
                    <div class="toggle-switch">
                        <input type="checkbox" name="is_admin" value="1" checked>
                        <div class="toggle-track"></div>
                    </div>
                </label>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/80 to-white flex gap-3">
        <button onclick="closeRoleDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="submitCreate(this, 'createRoleForm', 'Creando...')" type="button"
            class="flex-1 px-4 py-2.5 text-sm bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all font-semibold shadow-sm shadow-indigo-200">
            <i class="fas fa-plus mr-1.5 text-xs"></i> Crear Rol
        </button>
    </div>
</div>

{{-- ==================== EDIT ROLE DRAWER ==================== --}}
<div id="editRoleDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[440px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-200/50">
                    <i class="fas fa-pen text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Editar Rol</h2>
                    <p class="text-xs text-gray-400 mt-0.5" id="editRoleSubtitle">Modifica los datos del rol</p>
                </div>
            </div>
            <button onclick="closeRoleDrawer()" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600" aria-label="Cerrar">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="" id="editRoleForm" class="p-6 space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nombre del rol</label>
                <input type="text" name="display_name" id="editRoleDisplayName" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 focus:bg-white outline-none text-sm transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Descripción <span class="text-gray-300 normal-case font-normal">(opcional)</span></label>
                <textarea name="description" id="editRoleDescription" rows="3"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 focus:bg-white outline-none text-sm transition resize-none"></textarea>
            </div>

            <div class="p-4 rounded-xl bg-gradient-to-r from-gray-50 to-indigo-50/30 border border-gray-100">
                <label class="flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-shield-halved text-indigo-500 text-sm"></i>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-700">Acceso al panel admin</span>
                            <p class="text-[11px] text-gray-400 mt-0.5">Puede ingresar al panel de administración</p>
                        </div>
                    </div>
                    <input type="hidden" name="is_admin" value="0">
                    <div class="toggle-switch">
                        <input type="checkbox" name="is_admin" value="1" id="editRoleIsAdmin">
                        <div class="toggle-track"></div>
                    </div>
                </label>
            </div>

            <div class="flex items-center gap-2 px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-100">
                <i class="fas fa-fingerprint text-[10px] text-gray-400"></i>
                <span class="text-xs text-gray-400">Identificador:</span>
                <code id="editRoleSlug" class="text-xs bg-white px-2 py-0.5 rounded-md text-gray-600 font-mono border border-gray-100"></code>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/80 to-white flex gap-3">
        <button onclick="closeRoleDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="document.getElementById('editRoleForm').submit()" type="button"
            class="flex-1 px-4 py-2.5 text-sm bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all font-semibold shadow-sm shadow-indigo-200">
            <i class="fas fa-check mr-1.5 text-xs"></i> Guardar Cambios
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const TOTAL_PERMS = {{ $totalPermissions }};

    // ==================== TAB SWITCHING ====================
    function switchRole(name) {
        document.querySelectorAll('.role-tab').forEach(t => {
            t.classList.remove('active');
            const badge = document.getElementById('tab-badge-' + t.id.replace('tab-', ''));
            const icon = document.getElementById('tab-icon-' + t.id.replace('tab-', ''));
            const i = document.getElementById('tab-i-' + t.id.replace('tab-', ''));
            if (badge) { badge.classList.remove('bg-indigo-100', 'text-indigo-600'); badge.classList.add('bg-gray-100', 'text-gray-400'); }
            if (icon) { icon.classList.remove('bg-indigo-100'); icon.classList.add('bg-gray-100'); }
            if (i) { i.classList.remove('text-indigo-500'); i.classList.add('text-gray-400'); }
        });
        document.querySelectorAll('.role-panel').forEach(p => p.classList.remove('active'));

        const tab = document.getElementById('tab-' + name);
        const panel = document.getElementById('panel-' + name);
        const badge = document.getElementById('tab-badge-' + name);
        const icon = document.getElementById('tab-icon-' + name);
        const i = document.getElementById('tab-i-' + name);

        if (tab) tab.classList.add('active');
        if (panel) panel.classList.add('active');
        if (badge) { badge.classList.add('bg-indigo-100', 'text-indigo-600'); badge.classList.remove('bg-gray-100', 'text-gray-400'); }
        if (icon) { icon.classList.add('bg-indigo-100'); icon.classList.remove('bg-gray-100'); }
        if (i) { i.classList.add('text-indigo-500'); i.classList.remove('text-gray-400'); }
    }

    // ==================== PERMISSION TOGGLES ====================
    function toggleAll(role, state) {
        document.querySelectorAll(`input[data-role="${role}"]`).forEach(cb => {
            cb.checked = state;
            updatePerm(cb, true);
        });
        updateCount(role);
        updateModuleBadges(role);
    }

    function toggleModule(role, module) {
        const cbs = document.querySelectorAll(`input[data-role="${role}"][data-module="${module}"]`);
        const allChecked = Array.from(cbs).every(cb => cb.checked);
        cbs.forEach(cb => {
            cb.checked = !allChecked;
            updatePerm(cb, true);
        });
        updateCount(role);
        updateModuleBadges(role);
    }

    function expandAll(role) {
        const sections = document.querySelectorAll(`#panel-${role} .perm-section`);
        const allExpanded = Array.from(sections).every(s => !s.classList.contains('collapsed'));
        sections.forEach(s => {
            if (allExpanded) s.classList.add('collapsed');
            else s.classList.remove('collapsed');
        });
    }

    function updatePerm(checkbox, batch) {
        const label = checkbox.closest('.perm-check');
        const actionIcon = label.querySelector('.fa-eye, .fa-plus, .fa-pen, .fa-trash-can, .fa-file-export, .fa-sliders, .fa-gavel, .fa-reply, .fa-check');
        const textEl = label.querySelector('span.text-\\[13px\\]');

        if (checkbox.checked) {
            label.classList.add('checked');
            label.classList.remove('border-gray-100', 'bg-gray-50/30');
            if (actionIcon) { actionIcon.classList.remove('text-gray-300'); actionIcon.classList.add('text-indigo-400'); }
            if (textEl) { textEl.classList.remove('text-gray-500'); textEl.classList.add('text-gray-800'); }
        } else {
            label.classList.remove('checked');
            label.classList.add('border-gray-100', 'bg-gray-50/30');
            if (actionIcon) { actionIcon.classList.add('text-gray-300'); actionIcon.classList.remove('text-indigo-400'); }
            if (textEl) { textEl.classList.add('text-gray-500'); textEl.classList.remove('text-gray-800'); }
        }

        if (!batch) {
            const role = checkbox.dataset.role;
            updateCount(role);
            updateModuleBadges(role);
        }
    }

    function updateCount(role) {
        const checked = document.querySelectorAll(`input[data-role="${role}"]:checked`).length;
        const counter = document.getElementById('count-' + role);
        const statCounter = document.getElementById('stat-count-' + role);
        const bar = document.getElementById('bar-' + role);
        const badge = document.getElementById('tab-badge-' + role);
        const pct = TOTAL_PERMS > 0 ? Math.round((checked / TOTAL_PERMS) * 100) : 0;

        if (counter) counter.textContent = checked;
        if (statCounter) statCounter.textContent = checked;
        if (bar) bar.style.width = pct + '%';
        if (badge) badge.textContent = checked;
    }

    function updateModuleBadges(role) {
        document.querySelectorAll(`#panel-${role} .perm-section`).forEach(section => {
            const cbs = section.querySelectorAll(`input[data-role="${role}"]`);
            const total = cbs.length;
            const checked = Array.from(cbs).filter(cb => cb.checked).length;
            const badge = section.querySelector('.perm-section-header span[class*="rounded"]');
            const progressBar = section.querySelector('.module-progress-bar');

            if (badge) {
                badge.textContent = checked + '/' + total;
                badge.className = 'text-[10px] font-semibold px-1.5 py-0.5 rounded ' +
                    (checked === total ? 'bg-emerald-50 text-emerald-600' :
                    (checked > 0 ? 'bg-amber-50 text-amber-600' : 'bg-gray-100 text-gray-400'));
            }
            if (progressBar) {
                const pct = total > 0 ? Math.round((checked / total) * 100) : 0;
                progressBar.style.width = pct + '%';
                progressBar.className = 'module-progress-bar ' +
                    (checked === total ? 'bg-emerald-400' : (checked > 0 ? 'bg-amber-400' : 'bg-gray-200'));
            }
        });
    }

    // ==================== DRAWERS ====================
    const roleOverlay = document.getElementById('roleDrawerOverlay');
    const createRoleDrawer = document.getElementById('createRoleDrawer');
    const editRoleDrawer = document.getElementById('editRoleDrawer');
    let activeRoleDrawer = null;

    function showRoleDrawer(drawer) {
        activeRoleDrawer = drawer;
        roleOverlay.classList.remove('opacity-0', 'invisible');
        roleOverlay.classList.add('opacity-100', 'visible');
        drawer.classList.remove('translate-x-full');
        drawer.classList.add('translate-x-0');
        document.body.style.overflow = 'hidden';
    }

    function closeRoleDrawer() {
        roleOverlay.classList.add('opacity-0', 'invisible');
        roleOverlay.classList.remove('opacity-100', 'visible');
        if (activeRoleDrawer) {
            activeRoleDrawer.classList.add('translate-x-full');
            activeRoleDrawer.classList.remove('translate-x-0');
        }
        document.body.style.overflow = '';
        activeRoleDrawer = null;
    }

    function openCreateRoleDrawer() {
        document.getElementById('createRoleForm').reset();
        showRoleDrawer(createRoleDrawer);
    }

    function openEditRoleDrawer(role) {
        document.getElementById('editRoleDisplayName').value = role.display_name;
        document.getElementById('editRoleDescription').value = role.description || '';
        document.getElementById('editRoleIsAdmin').checked = role.is_admin;
        document.getElementById('editRoleSlug').textContent = role.name;
        document.getElementById('editRoleSubtitle').textContent = role.display_name;
        document.getElementById('editRoleForm').action = '/admin/roles/' + role.id;
        showRoleDrawer(editRoleDrawer);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeRoleDrawer();
    });
</script>
@endsection
