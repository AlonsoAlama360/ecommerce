@extends('admin.layouts.app')
@section('title', 'Agencias de Envío')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Agencias</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $totalAgencies }}</h3>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-shipping-fast text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Activas</p>
            <h3 class="text-2xl font-bold text-emerald-600">{{ $activeAgencies }}</h3>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check-circle text-emerald-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Inactivas</p>
            <h3 class="text-2xl font-bold text-gray-400">{{ $inactiveAgencies }}</h3>
        </div>
        <div class="w-11 h-11 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-pause-circle text-gray-400"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Direcciones</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $totalAddresses }}</h3>
        </div>
        <div class="w-11 h-11 rounded-lg bg-violet-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-map-marker-alt text-violet-500"></i>
        </div>
    </div>
</div>

{{-- ==================== FILTERS ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="px-5 pt-5 pb-2">
        <h3 class="text-base font-semibold text-gray-800">Filtros de búsqueda</h3>
    </div>
    <div class="px-5 pb-5 mt-3">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Buscar</label>
                <input type="text" id="filter_search" value="{{ request('search') }}" placeholder="Nombre de agencia..."
                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition"
                    onkeydown="if(event.key==='Enter') applyFilters()">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado</label>
                <select id="filter_status" onchange="applyFilters()"
                    class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition bg-white">
                    <option value="">Todos</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activas</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivas</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button onclick="applyFilters()" class="flex-1 px-4 py-2.5 bg-indigo-500 text-white text-sm rounded-lg hover:bg-indigo-600 transition font-medium">
                    <i class="fas fa-search mr-1.5 text-xs"></i> Buscar
                </button>
                <a href="{{ route('admin.shipping-agencies.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-500 text-sm rounded-lg hover:bg-gray-50 transition font-medium">
                    <i class="fas fa-times text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ==================== TABLE ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    {{-- Toolbar --}}
    <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100">
        <div>
            <h3 class="text-base font-semibold text-gray-800">Agencias de Envío</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $agencies->total() }} registro{{ $agencies->total() !== 1 ? 's' : '' }}</p>
        </div>
        <button onclick="openCreateDrawer()" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-500 text-white text-sm rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-plus mr-2 text-xs"></i> Nueva Agencia
        </button>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Agencia</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Direcciones</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Creado</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($agencies as $agency)
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                {{ strtoupper(substr($agency->name, 0, 2)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-800">{{ $agency->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @if($agency->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activa
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactiva
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-violet-50 text-violet-600 text-xs font-semibold">{{ $agency->addresses_count }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-500">{{ $agency->created_at->format('d/m/Y') }}</span>
                        <p class="text-[10px] text-gray-400">{{ $agency->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <button onclick="openEditDrawer({{ $agency->toJson() }})"
                                class="w-8 h-8 rounded-lg hover:bg-indigo-50 flex items-center justify-center transition text-gray-400 hover:text-indigo-600" title="Editar">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                            <form action="{{ route('admin.shipping-agencies.destroy', $agency) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $agency->name }}')"
                                    class="w-8 h-8 rounded-lg hover:bg-red-50 flex items-center justify-center transition text-gray-400 hover:text-red-600" title="Eliminar">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-shipping-fast text-2xl text-gray-300"></i>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-600 mb-1">No hay agencias</h4>
                            <p class="text-xs text-gray-400 mb-4">Agrega tu primera agencia de envío</p>
                            <button onclick="openCreateDrawer()" class="px-4 py-2 bg-indigo-500 text-white text-xs rounded-lg hover:bg-indigo-600 transition font-medium">
                                <i class="fas fa-plus mr-1.5"></i> Nueva Agencia
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($agencies as $agency)
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($agency->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $agency->name }}</p>
                        <p class="text-[11px] text-gray-400">{{ $agency->addresses_count }} direcciones</p>
                    </div>
                </div>
                @if($agency->is_active)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-600">Activa</span>
                @else
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-500">Inactiva</span>
                @endif
            </div>
            <div class="flex items-center justify-between mt-3">
                <span class="text-xs text-gray-400">{{ $agency->created_at->format('d/m/Y H:i') }}</span>
                <div class="flex items-center gap-1">
                    <button onclick="openEditDrawer({{ $agency->toJson() }})"
                        class="w-8 h-8 rounded-lg hover:bg-indigo-50 flex items-center justify-center text-gray-400 hover:text-indigo-600">
                        <i class="fas fa-pen text-xs"></i>
                    </button>
                    <form action="{{ route('admin.shipping-agencies.destroy', $agency) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $agency->name }}')"
                            class="w-8 h-8 rounded-lg hover:bg-red-50 flex items-center justify-center text-gray-400 hover:text-red-600">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <i class="fas fa-shipping-fast text-3xl text-gray-300 mb-3"></i>
            <p class="text-sm text-gray-500">No hay agencias registradas</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($agencies->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-xs text-gray-500">
            Mostrando {{ $agencies->firstItem() }}–{{ $agencies->lastItem() }} de {{ $agencies->total() }}
        </p>
        <div class="flex items-center gap-1">
            @if($agencies->onFirstPage())
                <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-300 text-xs"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $agencies->previousPageUrl() }}" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-500 text-xs transition"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($agencies->getUrlRange(max(1, $agencies->currentPage()-2), min($agencies->lastPage(), $agencies->currentPage()+2)) as $page => $url)
                @if($page == $agencies->currentPage())
                    <span class="w-8 h-8 rounded-lg bg-indigo-500 text-white flex items-center justify-center text-xs font-medium">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-500 text-xs transition">{{ $page }}</a>
                @endif
            @endforeach
            @if($agencies->hasMorePages())
                <a href="{{ $agencies->nextPageUrl() }}" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-500 text-xs transition"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-300 text-xs"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ==================== DRAWER OVERLAY ==================== --}}
<div id="drawerOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] opacity-0 invisible transition-all duration-300" onclick="closeDrawer()"></div>

{{-- ==================== CREATE DRAWER ==================== --}}
<div id="createDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[500px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Nueva Agencia</h2>
            <p class="text-sm text-gray-400 mt-0.5">Registra una nueva agencia de envío</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="{{ route('admin.shipping-agencies.store') }}" id="createAgencyForm" class="p-6">
            @csrf
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i class="fas fa-shipping-fast text-2xl text-white"></i>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="create_name" class="block text-xs font-medium text-gray-500 mb-1.5">Nombre de la agencia <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="create_name" value="{{ old('name') }}" required placeholder="Ej: Shalom, Olva Courier..."
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('name') border-red-400 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Agencia activa</span>
                            <p class="text-xs text-gray-400">Disponible en checkout y ventas</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:bg-indigo-500 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </div>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="submitCreate(this, 'createAgencyForm', 'Creando...')" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-plus mr-1.5 text-xs"></i> Crear Agencia
        </button>
    </div>
</div>

{{-- ==================== EDIT DRAWER ==================== --}}
<div id="editDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[500px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Editar Agencia</h2>
            <p class="text-sm text-gray-400 mt-0.5" id="editDrawerSubtitle"></p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" id="editAgencyForm" class="p-6">
            @csrf @method('PUT')

            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <span class="text-xl font-bold text-white" id="editAvatarPreview"></span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="edit_name" class="block text-xs font-medium text-gray-500 mb-1.5">Nombre de la agencia <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Agencia activa</span>
                            <p class="text-xs text-gray-400">Disponible en checkout y ventas</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:bg-indigo-500 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </div>
            </div>
        </form>

        {{-- ---- Addresses Section ---- --}}
        <div class="px-6 pb-6">
            <div class="border-t border-gray-100 pt-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">
                        <i class="fas fa-map-marker-alt text-violet-400 mr-1.5 text-xs"></i>
                        Direcciones
                        <span class="text-gray-400 font-normal ml-1" id="addressCount"></span>
                    </h3>
                </div>

                {{-- Add address --}}
                <div class="flex gap-2 mb-3">
                    <input type="text" id="newAddressInput" placeholder="Agregar nueva dirección..."
                        class="flex-1 px-3.5 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition"
                        onkeydown="if(event.key==='Enter'){event.preventDefault();addAddress();}">
                    <button type="button" onclick="addAddress()"
                        class="px-3 py-2 bg-violet-500 text-white rounded-lg hover:bg-violet-600 transition text-sm font-medium">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>

                {{-- Address list --}}
                <div id="addressList" class="space-y-2 max-h-60 overflow-y-auto">
                    <div class="text-center py-4 text-xs text-gray-400" id="addressLoading">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Cargando direcciones...
                    </div>
                </div>
            </div>
        </div>

        {{-- Timestamps --}}
        <div class="px-6 pb-6">
            <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-400 space-y-1">
                <p>Creado: <span id="editCreatedAt">—</span></p>
                <p>Actualizado: <span id="editUpdatedAt">—</span></p>
            </div>
        </div>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="submitCreate(this, 'editAgencyForm', 'Guardando...')" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let activeDrawer = null;
    let currentAgencyId = null;

    // ==================== FILTERS ====================
    function applyFilters() {
        const params = new URLSearchParams();
        const search = document.getElementById('filter_search')?.value?.trim();
        const status = document.getElementById('filter_status')?.value;

        if (search) params.set('search', search);
        if (status !== '') params.set('status', status);

        window.location.href = '{{ route("admin.shipping-agencies.index") }}' + (params.toString() ? '?' + params.toString() : '');
    }

    // ==================== DRAWERS ====================
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
        currentAgencyId = null;
    }

    function openCreateDrawer() {
        showDrawer(createDrawer);
        setTimeout(() => document.getElementById('create_name').focus(), 300);
    }

    function openEditDrawer(agency) {
        currentAgencyId = agency.id;
        document.getElementById('edit_name').value = agency.name || '';
        document.getElementById('editAgencyForm').action = '/admin/shipping-agencies/' + agency.id;
        document.getElementById('editDrawerSubtitle').textContent = agency.name;
        document.getElementById('edit_is_active').checked = agency.is_active;
        document.getElementById('editAvatarPreview').textContent = (agency.name || '??').substring(0, 2).toUpperCase();

        const fmtDate = (d) => {
            if (!d) return '—';
            return new Date(d).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        };
        document.getElementById('editCreatedAt').textContent = fmtDate(agency.created_at);
        document.getElementById('editUpdatedAt').textContent = fmtDate(agency.updated_at);

        loadAddresses(agency.id);
        showDrawer(editDrawer);
        setTimeout(() => document.getElementById('edit_name').focus(), 300);
    }

    // ==================== ADDRESSES (AJAX) ====================
    function loadAddresses(agencyId) {
        const list = document.getElementById('addressList');
        const loading = document.getElementById('addressLoading');
        list.innerHTML = '';
        list.appendChild(loading);
        loading.classList.remove('hidden');

        fetch('/admin/shipping-agencies/' + agencyId + '/addresses', {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(addresses => {
            list.innerHTML = '';
            document.getElementById('addressCount').textContent = '(' + addresses.length + ')';
            if (!addresses.length) {
                list.innerHTML = '<p class="text-center py-3 text-xs text-gray-400">Sin direcciones registradas</p>';
                return;
            }
            addresses.forEach(addr => list.appendChild(createAddressRow(addr)));
        })
        .catch(() => {
            list.innerHTML = '<p class="text-center py-3 text-xs text-red-400">Error al cargar direcciones</p>';
        });
    }

    function createAddressRow(addr) {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg group';
        div.id = 'addr-' + addr.id;
        div.innerHTML = `
            <i class="fas fa-map-pin text-[10px] ${addr.is_active ? 'text-violet-400' : 'text-gray-300'} flex-shrink-0"></i>
            <span class="flex-1 text-sm ${addr.is_active ? 'text-gray-700' : 'text-gray-400 line-through'} truncate">${escapeHtml(addr.address)}</span>
            <button type="button" onclick="toggleAddr(${addr.id}, this)" title="${addr.is_active ? 'Desactivar' : 'Activar'}"
                class="w-7 h-7 rounded-md ${addr.is_active ? 'hover:bg-amber-50 text-emerald-500' : 'hover:bg-emerald-50 text-gray-400'} flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                <i class="fas ${addr.is_active ? 'fa-toggle-on' : 'fa-toggle-off'} text-xs"></i>
            </button>
            <button type="button" onclick="deleteAddr(${addr.id})" title="Eliminar"
                class="w-7 h-7 rounded-md hover:bg-red-50 text-gray-400 hover:text-red-500 flex items-center justify-center transition opacity-0 group-hover:opacity-100">
                <i class="fas fa-trash-alt text-[10px]"></i>
            </button>
        `;
        return div;
    }

    function escapeHtml(t) {
        const d = document.createElement('div');
        d.textContent = t;
        return d.innerHTML;
    }

    function addAddress() {
        const input = document.getElementById('newAddressInput');
        const address = input.value.trim();
        if (!address || !currentAgencyId) return;

        input.disabled = true;
        fetch('/admin/shipping-agencies/' + currentAgencyId + '/addresses', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ address: address })
        })
        .then(r => {
            if (!r.ok) return r.json().then(d => { throw new Error(d.message || 'Error'); });
            return r.json();
        })
        .then(addr => {
            const list = document.getElementById('addressList');
            const empty = list.querySelector('p');
            if (empty) empty.remove();
            list.appendChild(createAddressRow(addr));
            input.value = '';
            // Update count
            const count = list.querySelectorAll('[id^="addr-"]').length;
            document.getElementById('addressCount').textContent = '(' + count + ')';
            showToast('Dirección agregada', 'success');
        })
        .catch(e => showToast(e.message || 'Error al agregar', 'error'))
        .finally(() => { input.disabled = false; input.focus(); });
    }

    function toggleAddr(addrId, btn) {
        fetch('/admin/shipping-agency-addresses/' + addrId + '/toggle', {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            loadAddresses(currentAgencyId);
        })
        .catch(() => showToast('Error al cambiar estado', 'error'));
    }

    function deleteAddr(addrId) {
        if (!confirm('¿Eliminar esta dirección?')) return;

        fetch('/admin/shipping-agency-addresses/' + addrId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(() => {
            const row = document.getElementById('addr-' + addrId);
            if (row) row.remove();
            const list = document.getElementById('addressList');
            const count = list.querySelectorAll('[id^="addr-"]').length;
            document.getElementById('addressCount').textContent = '(' + count + ')';
            if (!count) list.innerHTML = '<p class="text-center py-3 text-xs text-gray-400">Sin direcciones registradas</p>';
            showToast('Dirección eliminada', 'success');
        })
        .catch(() => showToast('Error al eliminar', 'error'));
    }

    // ==================== KEYBOARD ====================
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDrawer();
    });

    // Auto-open if validation errors
    @if($errors->any())
    openCreateDrawer();
    @endif
</script>
@endsection
