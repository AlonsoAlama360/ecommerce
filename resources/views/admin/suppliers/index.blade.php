@extends('admin.layouts.app')
@section('title', 'Proveedores')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Proveedores</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalSuppliers) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Registrados en la plataforma</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-truck-field text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Proveedores Activos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($activeSuppliers) }}</h3>
            <p class="text-xs mt-1">
                @if($totalSuppliers > 0)
                <span class="text-emerald-500 font-semibold">{{ round(($activeSuppliers / $totalSuppliers) * 100) }}%</span>
                <span class="text-gray-400">del total</span>
                @else
                <span class="text-gray-400">Sin datos</span>
                @endif
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check-circle text-emerald-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Proveedores Inactivos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($inactiveSuppliers) }}</h3>
            <p class="text-xs mt-1">
                @if($totalSuppliers > 0)
                <span class="text-red-500 font-semibold">{{ round(($inactiveSuppliers / $totalSuppliers) * 100) }}%</span>
                <span class="text-gray-400">del total</span>
                @else
                <span class="text-gray-400">Sin datos</span>
                @endif
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-ban text-red-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Nuevos esta semana</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($newSuppliersWeek) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Últimos 7 días</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-truck-ramp-box text-amber-500"></i>
        </div>
    </div>
</div>

{{-- ==================== SEARCH FILTERS CARD ==================== --}}
@php
    $selectStyle = "background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E\"); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;";
    $selectClass = "w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer transition appearance-none";
    $hasFilters = request('search') || (request('status') !== null && request('status') !== '') || request('city');
@endphp
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="px-5 pt-5 pb-2">
        <h3 class="text-base font-semibold text-gray-800">Filtros de búsqueda</h3>
    </div>
    <div class="px-5 pb-5 mt-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Estado</label>
                <select id="filter_status" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Seleccionar estado</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Ciudad</label>
                <select id="filter_city" onchange="applyFilters()" class="{{ $selectClass }}" style="{{ $selectStyle }}">
                    <option value="">Todas las ciudades</option>
                    @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                @if($hasFilters)
                <a href="{{ route('admin.suppliers.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-rotate-left text-xs"></i> Limpiar filtros
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ==================== TABLE CARD ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- Table Toolbar --}}
    <div class="px-5 py-4 border-b border-gray-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <select id="filter_per_page" onchange="applyFilters()"
                    class="pl-3 pr-8 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 cursor-pointer bg-white appearance-none"
                    style="{{ $selectStyle }}">
                    @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-3 flex-1 sm:flex-initial">
                <div class="relative flex-1 sm:flex-initial">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="filter_search" value="{{ request('search') }}"
                        placeholder="Buscar proveedor"
                        onkeydown="if(event.key==='Enter'){event.preventDefault();applyFilters()}"
                        class="w-full sm:w-56 pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
                <button type="button" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-up-from-bracket text-xs"></i> Exportar
                </button>
                <button onclick="openCreateDrawer()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm shadow-indigo-200 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-plus text-xs"></i>
                    <span class="hidden sm:inline">Nuevo Proveedor</span>
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
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Proveedor</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">RUC</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacto</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ciudad</th>
                    <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                    <td class="px-5 py-3">
                        <input type="checkbox" class="row-check w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                {{ strtoupper(substr($supplier->business_name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-800 text-sm truncate">{{ $supplier->business_name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $supplier->contact_name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600 font-mono">{{ $supplier->ruc ?: '—' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            @if($supplier->email)
                            <p class="text-sm text-gray-600 truncate">{{ $supplier->email }}</p>
                            @endif
                            @if($supplier->phone)
                            <p class="text-xs text-gray-400">{{ $supplier->phone }}</p>
                            @endif
                            @if(!$supplier->email && !$supplier->phone)
                            <span class="text-sm text-gray-400">—</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        @if($supplier->city)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-600">
                            <i class="fas fa-location-dot mr-1.5 text-[9px] text-gray-400"></i>{{ $supplier->city }}
                        </span>
                        @else
                        <span class="text-sm text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($supplier->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600">Activo</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-500">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <button data-supplier="{{ json_encode($supplier->only('id','business_name','contact_name','email','phone','ruc','address','city','notes','is_active','created_at','updated_at')) }}"
                                onclick="openEditDrawer(JSON.parse(this.dataset.supplier))"
                                class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition" title="Editar">
                                <i class="fas fa-pen-to-square text-sm"></i>
                            </button>
                            <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $supplier->business_name }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition" title="Eliminar">
                                    <i class="fas fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                                <i class="fas fa-truck-field text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-600 font-semibold mb-1">Sin resultados</h3>
                            <p class="text-sm text-gray-400 max-w-xs">No se encontraron proveedores que coincidan con los filtros</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile List --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($suppliers as $supplier)
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($supplier->business_name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate">{{ $supplier->business_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $supplier->contact_name }}</p>
                </div>
                <div class="flex items-center gap-0.5">
                    <button data-supplier="{{ json_encode($supplier->only('id','business_name','contact_name','email','phone','ruc','address','city','notes','is_active','created_at','updated_at')) }}"
                        onclick="openEditDrawer(JSON.parse(this.dataset.supplier))"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 rounded-md transition">
                        <i class="fas fa-pen-to-square text-sm"></i>
                    </button>
                    <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $supplier->business_name }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-md transition">
                            <i class="fas fa-trash-can text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="mt-2 ml-[52px] flex items-center gap-2 flex-wrap">
                @if($supplier->ruc)
                <span class="text-xs text-gray-500 font-mono">{{ $supplier->ruc }}</span>
                <span class="text-gray-300">|</span>
                @endif
                @if($supplier->is_active)
                <span class="text-xs font-semibold text-emerald-600">Activo</span>
                @else
                <span class="text-xs font-semibold text-red-500">Inactivo</span>
                @endif
                @if($supplier->city)
                <span class="text-gray-300">|</span>
                <span class="text-xs text-gray-400"><i class="fas fa-location-dot mr-0.5"></i>{{ $supplier->city }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-truck-field text-xl text-gray-300"></i>
            </div>
            <p class="text-sm text-gray-400">No se encontraron proveedores</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Footer --}}
    @if($suppliers->total() > 0)
    <div class="px-5 py-3.5 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-gray-500">
                Mostrando {{ $suppliers->firstItem() }} a {{ $suppliers->lastItem() }} de {{ number_format($suppliers->total()) }} registros
            </p>
            @if($suppliers->hasPages())
            <nav class="flex items-center gap-1">
                @if($suppliers->currentPage() > 2)
                <a href="{{ $suppliers->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Primera">
                    <i class="fas fa-angles-left"></i>
                </a>
                @endif
                @if($suppliers->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-left"></i></span>
                @else
                <a href="{{ $suppliers->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-left"></i></a>
                @endif
                @php
                    $current = $suppliers->currentPage();
                    $last = $suppliers->lastPage();
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
                    <a href="{{ $suppliers->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 transition text-xs">{{ $page }}</a>
                    @endif
                @endforeach
                @if($suppliers->hasMorePages())
                <a href="{{ $suppliers->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs"><i class="fas fa-chevron-right"></i></a>
                @else
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-300 cursor-not-allowed text-xs"><i class="fas fa-chevron-right"></i></span>
                @endif
                @if($suppliers->currentPage() < $last - 1)
                <a href="{{ $suppliers->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 transition text-xs" title="Última">
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
<div id="createDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[500px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Nuevo Proveedor</h2>
            <p class="text-sm text-gray-400 mt-0.5">Completa los datos del nuevo proveedor</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="{{ route('admin.suppliers.store') }}" id="createSupplierForm" class="p-6">
            @csrf
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i class="fas fa-truck-field text-2xl text-white"></i>
                </div>
            </div>

            <div class="space-y-4">
                {{-- Business Name --}}
                <div>
                    <label for="create_business_name" class="block text-xs font-medium text-gray-500 mb-1.5">Razón social <span class="text-red-400">*</span></label>
                    <input type="text" name="business_name" id="create_business_name" value="{{ old('business_name') }}" required
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('business_name') border-red-400 @enderror">
                    @error('business_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Contact Name --}}
                <div>
                    <label for="create_contact_name" class="block text-xs font-medium text-gray-500 mb-1.5">Persona de contacto <span class="text-red-400">*</span></label>
                    <input type="text" name="contact_name" id="create_contact_name" value="{{ old('contact_name') }}" required
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('contact_name') border-red-400 @enderror">
                    @error('contact_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- RUC --}}
                    <div>
                        <label for="create_ruc" class="block text-xs font-medium text-gray-500 mb-1.5">RUC</label>
                        <input type="text" name="ruc" id="create_ruc" value="{{ old('ruc') }}" placeholder="20XXXXXXXXX" maxlength="20"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition font-mono @error('ruc') border-red-400 @enderror">
                        @error('ruc')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    {{-- Phone --}}
                    <div>
                        <label for="create_phone" class="block text-xs font-medium text-gray-500 mb-1.5">Teléfono</label>
                        <input type="text" name="phone" id="create_phone" value="{{ old('phone') }}" placeholder="+51 999 999 999"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('phone') border-red-400 @enderror">
                        @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="create_email" class="block text-xs font-medium text-gray-500 mb-1.5">Correo electrónico</label>
                    <input type="email" name="email" id="create_email" value="{{ old('email') }}" placeholder="contacto@empresa.com"
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('email') border-red-400 @enderror">
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Address --}}
                <div>
                    <label for="create_address" class="block text-xs font-medium text-gray-500 mb-1.5">Dirección</label>
                    <input type="text" name="address" id="create_address" value="{{ old('address') }}" placeholder="Av. Principal 123"
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('address') border-red-400 @enderror">
                    @error('address')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- City --}}
                <div>
                    <label for="create_city" class="block text-xs font-medium text-gray-500 mb-1.5">Ciudad</label>
                    <input type="text" name="city" id="create_city" value="{{ old('city') }}" placeholder="Lima"
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('city') border-red-400 @enderror">
                    @error('city')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label for="create_notes" class="block text-xs font-medium text-gray-500 mb-1.5">Notas internas</label>
                    <textarea name="notes" id="create_notes" rows="3" placeholder="Notas sobre este proveedor..."
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none @error('notes') border-red-400 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Toggle --}}
                <div class="pt-3 border-t border-gray-100">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Proveedor activo</span>
                            <p class="text-xs text-gray-400">Disponible para operaciones</p>
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
        <button onclick="document.getElementById('createSupplierForm').submit()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-plus mr-1.5 text-xs"></i> Crear Proveedor
        </button>
    </div>
</div>

{{-- ==================== EDIT DRAWER ==================== --}}
<div id="editDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[500px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Editar Proveedor</h2>
            <p class="text-sm text-gray-400 mt-0.5" id="editDrawerSubtitle">Modifica los datos del proveedor</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="" id="editSupplierForm" class="p-6">
            @csrf
            @method('PUT')
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <span id="editAvatarPreview" class="text-xl font-bold text-white">??</span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="edit_business_name" class="block text-xs font-medium text-gray-500 mb-1.5">Razón social <span class="text-red-400">*</span></label>
                    <input type="text" name="business_name" id="edit_business_name" required
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
                <div>
                    <label for="edit_contact_name" class="block text-xs font-medium text-gray-500 mb-1.5">Persona de contacto <span class="text-red-400">*</span></label>
                    <input type="text" name="contact_name" id="edit_contact_name" required
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_ruc" class="block text-xs font-medium text-gray-500 mb-1.5">RUC</label>
                        <input type="text" name="ruc" id="edit_ruc" placeholder="20XXXXXXXXX" maxlength="20"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition font-mono">
                    </div>
                    <div>
                        <label for="edit_phone" class="block text-xs font-medium text-gray-500 mb-1.5">Teléfono</label>
                        <input type="text" name="phone" id="edit_phone" placeholder="+51 999 999 999"
                            class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                </div>
                <div>
                    <label for="edit_email" class="block text-xs font-medium text-gray-500 mb-1.5">Correo electrónico</label>
                    <input type="email" name="email" id="edit_email" placeholder="contacto@empresa.com"
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
                <div>
                    <label for="edit_address" class="block text-xs font-medium text-gray-500 mb-1.5">Dirección</label>
                    <input type="text" name="address" id="edit_address" placeholder="Av. Principal 123"
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
                <div>
                    <label for="edit_city" class="block text-xs font-medium text-gray-500 mb-1.5">Ciudad</label>
                    <input type="text" name="city" id="edit_city" placeholder="Lima"
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>
                <div>
                    <label for="edit_notes" class="block text-xs font-medium text-gray-500 mb-1.5">Notas internas</label>
                    <textarea name="notes" id="edit_notes" rows="3" placeholder="Notas sobre este proveedor..."
                        class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none"></textarea>
                </div>
                <div class="pt-3 border-t border-gray-100">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Proveedor activo</span>
                            <p class="text-xs text-gray-400">Disponible para operaciones</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" id="edit_is_active">
                            <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:bg-indigo-500 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-gray-100">
                <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                    <span><i class="fas fa-calendar-alt mr-1"></i> Creado: <span id="editCreatedAt"></span></span>
                    <span><i class="fas fa-clock mr-1"></i> Actualizado: <span id="editUpdatedAt"></span></span>
                </div>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="document.getElementById('editSupplierForm').submit()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
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

    // ==================== UNIFIED FILTERS ====================
    function applyFilters() {
        const params = new URLSearchParams();
        const fields = {
            search: document.getElementById('filter_search')?.value?.trim(),
            status: document.getElementById('filter_status')?.value,
            city: document.getElementById('filter_city')?.value,
            per_page: document.getElementById('filter_per_page')?.value,
        };

        for (const [key, val] of Object.entries(fields)) {
            if (val !== '' && val !== null && val !== undefined) {
                params.set(key, val);
            }
        }

        window.location.href = '{{ route("admin.suppliers.index") }}' + (params.toString() ? '?' + params.toString() : '');
    }

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
        setTimeout(() => document.getElementById('create_business_name').focus(), 300);
    }

    function openEditDrawer(supplier) {
        document.getElementById('edit_business_name').value = supplier.business_name || '';
        document.getElementById('edit_contact_name').value = supplier.contact_name || '';
        document.getElementById('edit_email').value = supplier.email || '';
        document.getElementById('edit_phone').value = supplier.phone || '';
        document.getElementById('edit_ruc').value = supplier.ruc || '';
        document.getElementById('edit_address').value = supplier.address || '';
        document.getElementById('edit_city').value = supplier.city || '';
        document.getElementById('edit_notes').value = supplier.notes || '';

        document.getElementById('editSupplierForm').action = '/admin/suppliers/' + supplier.id;
        document.getElementById('editDrawerSubtitle').textContent = supplier.business_name;

        document.getElementById('edit_is_active').checked = supplier.is_active;

        // Avatar
        const initials = (supplier.business_name || '??').substring(0, 2).toUpperCase();
        document.getElementById('editAvatarPreview').textContent = initials;

        // Dates
        const fmtDate = (d) => {
            if (!d) return '—';
            const dt = new Date(d);
            return dt.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        };
        document.getElementById('editCreatedAt').textContent = fmtDate(supplier.created_at);
        document.getElementById('editUpdatedAt').textContent = fmtDate(supplier.updated_at);

        showDrawer(editDrawer);
        setTimeout(() => document.getElementById('edit_business_name').focus(), 300);
    }

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDrawer();
    });

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
        id: '{{ old("_supplier_id") }}',
        business_name: '{{ old("business_name") }}',
        contact_name: '{{ old("contact_name") }}',
        email: '{{ old("email") }}',
        phone: '{{ old("phone") }}',
        ruc: '{{ old("ruc") }}',
        address: '{{ old("address") }}',
        city: '{{ old("city") }}',
        notes: '{{ old("notes") }}',
        is_active: {{ old('is_active', 1) }},
        created_at: null,
        updated_at: null
    });
    @elseif($errors->any())
    openCreateDrawer();
    @endif
</script>
@endsection
