@extends('admin.layouts.app')
@section('title', 'Categorías')

@php
    $colors = [
        ['bg' => 'bg-blue-50', 'icon' => 'text-blue-500', 'ring' => 'ring-blue-500/20', 'gradient' => 'from-blue-400 to-blue-600'],
        ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-500', 'ring' => 'ring-emerald-500/20', 'gradient' => 'from-emerald-400 to-emerald-600'],
        ['bg' => 'bg-amber-50', 'icon' => 'text-amber-500', 'ring' => 'ring-amber-500/20', 'gradient' => 'from-amber-400 to-amber-600'],
        ['bg' => 'bg-rose-50', 'icon' => 'text-rose-500', 'ring' => 'ring-rose-500/20', 'gradient' => 'from-rose-400 to-rose-600'],
        ['bg' => 'bg-violet-50', 'icon' => 'text-violet-500', 'ring' => 'ring-violet-500/20', 'gradient' => 'from-violet-400 to-violet-600'],
        ['bg' => 'bg-teal-50', 'icon' => 'text-teal-500', 'ring' => 'ring-teal-500/20', 'gradient' => 'from-teal-400 to-teal-600'],
        ['bg' => 'bg-orange-50', 'icon' => 'text-orange-500', 'ring' => 'ring-orange-500/20', 'gradient' => 'from-orange-400 to-orange-600'],
        ['bg' => 'bg-cyan-50', 'icon' => 'text-cyan-500', 'ring' => 'ring-cyan-500/20', 'gradient' => 'from-cyan-400 to-cyan-600'],
    ];
    $maxProducts = $categories->max('products_count') ?: 1;
@endphp

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Categorías</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalCategories) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Registradas en la plataforma</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-tags text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Categorías Activas</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($activeCategories) }}</h3>
            <p class="text-xs mt-1">
                @if($totalCategories > 0)
                    <span class="text-emerald-500 font-semibold">{{ round(($activeCategories / $totalCategories) * 100) }}%</span>
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
            <p class="text-sm text-gray-500 mb-1">Categorías Inactivas</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($inactiveCategories) }}</h3>
            <p class="text-xs mt-1">
                @if($totalCategories > 0)
                    <span class="text-red-500 font-semibold">{{ round(($inactiveCategories / $totalCategories) * 100) }}%</span>
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
            <p class="text-sm text-gray-500 mb-1">Total Productos</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalProducts) }}</h3>
            <p class="text-xs text-gray-400 mt-1">En todas las categorías</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-box text-amber-500"></i>
        </div>
    </div>
</div>

{{-- ==================== CATEGORIES CARD ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    {{-- Toolbar --}}
    <div class="px-5 py-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-gray-800">Categorías</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $categories->count() }} resultado{{ $categories->count() !== 1 ? 's' : '' }}</p>
            </div>
            <button onclick="openCreateDrawer()"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm shadow-indigo-200 cursor-pointer whitespace-nowrap">
                <i class="fas fa-plus text-xs"></i>
                <span class="hidden sm:inline">Nueva Categoría</span>
                <span class="sm:hidden">Nueva</span>
            </button>
        </div>
    </div>

    {{-- Categories Grid --}}
    <div class="p-5">
        @if($categories->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($categories as $index => $category)
                @php $color = $colors[$index % count($colors)]; @endphp
                <div class="category-card group relative bg-white rounded-xl border border-gray-100 p-5 hover:shadow-lg hover:border-gray-200 transition-all duration-300 hover:-translate-y-0.5">
                    {{-- Actions --}}
                    <div class="card-actions absolute top-3 right-3 flex items-center gap-0.5 opacity-0 transition-all duration-200">
                        <button data-category="{{ json_encode($category->only('id','name','slug','description','icon','image_url','is_active','sort_order','created_at')) }}"
                           onclick="openEditDrawer(JSON.parse(this.dataset.category))"
                           class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Editar">
                            <i class="fas fa-pen-to-square text-sm"></i>
                        </button>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $category->name }}')" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                <i class="fas fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Status badge --}}
                    @if(!$category->is_active)
                        <span class="absolute top-3 left-3 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-500">Inactiva</span>
                    @endif

                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-xl {{ $color['bg'] }} flex items-center justify-center mb-4 ring-1 {{ $color['ring'] }}">
                        @if($category->icon)
                            <i class="{{ $category->icon }} {{ $color['icon'] }} text-lg"></i>
                        @else
                            <i class="fas fa-folder {{ $color['icon'] }} text-lg"></i>
                        @endif
                    </div>

                    {{-- Info --}}
                    <h3 class="font-semibold text-gray-900 text-sm leading-tight">{{ $category->name }}</h3>
                    @if($category->description)
                        <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $category->description }}</p>
                    @endif

                    {{-- Footer --}}
                    <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <i class="fas fa-box text-gray-300 text-[10px]"></i>
                            <span class="text-xs font-medium text-gray-500">{{ $category->products_count }} producto{{ $category->products_count !== 1 ? 's' : '' }}</span>
                        </div>
                        <span class="text-[10px] text-gray-300">orden: {{ $category->sort_order }}</span>
                    </div>

                    {{-- Progress bar --}}
                    <div class="mt-2.5 h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r {{ $color['gradient'] }} h-full rounded-full transition-all duration-700 ease-out"
                             style="width: {{ ($category->products_count / $maxProducts) * 100 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <div class="py-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-tags text-2xl text-gray-300"></i>
            </div>
            <h3 class="text-gray-600 font-semibold mb-1">Sin resultados</h3>
            <p class="text-sm text-gray-400 max-w-xs mx-auto">No se encontraron categorías que coincidan con los filtros</p>
        </div>
        @endif
    </div>
</div>

{{-- ==================== SHARED OVERLAY ==================== --}}
<div id="drawerOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] opacity-0 invisible transition-all duration-300" onclick="closeDrawer()"></div>

{{-- ==================== CREATE DRAWER ==================== --}}
<div id="createDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[480px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Nueva Categoría</h2>
            <p class="text-sm text-gray-400 mt-0.5">Completa los datos de la nueva categoría</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="{{ route('admin.categories.store') }}" id="createCategoryForm" class="p-6">
            @csrf
            {{-- Icon Preview --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i id="createIconPreview" class="fas fa-folder text-2xl text-white"></i>
                </div>
            </div>

            <div class="space-y-4">
                {{-- Name --}}
                <div>
                    <label for="create_name" class="block text-xs font-medium text-gray-500 mb-1.5">Nombre <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="create_name" value="{{ old('name') }}" required
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('name') border-red-400 @enderror">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="create_slug" class="block text-xs font-medium text-gray-500 mb-1.5">Slug</label>
                    <input type="text" name="slug" id="create_slug" value="{{ old('slug') }}" placeholder="Se genera automáticamente"
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('slug') border-red-400 @enderror">
                    @error('slug')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="create_description" class="block text-xs font-medium text-gray-500 mb-1.5">Descripción</label>
                    <textarea name="description" id="create_description" rows="3" placeholder="Descripción de la categoría..."
                              class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Icon --}}
                    <div>
                        <label for="create_icon" class="block text-xs font-medium text-gray-500 mb-1.5">Icono</label>
                        <input type="text" name="icon" id="create_icon" value="{{ old('icon') }}" placeholder="fas fa-gem"
                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('icon') border-red-400 @enderror">
                        @error('icon')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    {{-- Sort Order --}}
                    <div>
                        <label for="create_sort_order" class="block text-xs font-medium text-gray-500 mb-1.5">Orden</label>
                        <input type="number" name="sort_order" id="create_sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('sort_order') border-red-400 @enderror">
                        @error('sort_order')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Image URL --}}
                <div>
                    <label for="create_image_url" class="block text-xs font-medium text-gray-500 mb-1.5">URL de imagen</label>
                    <input type="url" name="image_url" id="create_image_url" value="{{ old('image_url') }}" placeholder="https://ejemplo.com/imagen.jpg"
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition @error('image_url') border-red-400 @enderror">
                    @error('image_url')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Active toggle --}}
                <div class="pt-3 border-t border-gray-100">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Categoría activa</span>
                            <p class="text-xs text-gray-400">Se mostrará en la tienda</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="sr-only peer" id="create_is_active">
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
        <button onclick="document.getElementById('createCategoryForm').submit()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-plus mr-1.5 text-xs"></i> Crear Categoría
        </button>
    </div>
</div>

{{-- ==================== EDIT DRAWER ==================== --}}
<div id="editDrawer" class="fixed top-0 right-0 z-[70] h-full w-full sm:w-[480px] translate-x-full transition-transform duration-300 ease-out flex flex-col bg-white shadow-2xl">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Editar Categoría</h2>
            <p class="text-sm text-gray-400 mt-0.5" id="editDrawerSubtitle">Modifica los datos de la categoría</p>
        </div>
        <button onclick="closeDrawer()" class="w-9 h-9 rounded-lg hover:bg-gray-100 flex items-center justify-center transition text-gray-400 hover:text-gray-600">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto">
        <form method="POST" action="" id="editCategoryForm" class="p-6">
            @csrf
            @method('PUT')
            {{-- Icon Preview --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                    <i id="editIconPreview" class="fas fa-folder text-2xl text-white"></i>
                </div>
            </div>

            <div class="space-y-4">
                {{-- Name --}}
                <div>
                    <label for="edit_name" class="block text-xs font-medium text-gray-500 mb-1.5">Nombre <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="edit_name" required
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>

                {{-- Slug --}}
                <div>
                    <label for="edit_slug" class="block text-xs font-medium text-gray-500 mb-1.5">Slug</label>
                    <input type="text" name="slug" id="edit_slug" placeholder="Se genera automáticamente"
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                </div>

                {{-- Description --}}
                <div>
                    <label for="edit_description" class="block text-xs font-medium text-gray-500 mb-1.5">Descripción</label>
                    <textarea name="description" id="edit_description" rows="3" placeholder="Descripción de la categoría..."
                              class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Icon --}}
                    <div>
                        <label for="edit_icon" class="block text-xs font-medium text-gray-500 mb-1.5">Icono</label>
                        <input type="text" name="icon" id="edit_icon" placeholder="fas fa-gem"
                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                    {{-- Sort Order --}}
                    <div>
                        <label for="edit_sort_order" class="block text-xs font-medium text-gray-500 mb-1.5">Orden</label>
                        <input type="number" name="sort_order" id="edit_sort_order" min="0"
                               class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    </div>
                </div>

                {{-- Image URL --}}
                <div>
                    <label for="edit_image_url" class="block text-xs font-medium text-gray-500 mb-1.5">URL de imagen</label>
                    <input type="url" name="image_url" id="edit_image_url" placeholder="https://ejemplo.com/imagen.jpg"
                           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition">
                    <div id="editImagePreview" class="mt-2 hidden">
                        <img id="editImagePreviewImg" src="" alt="" class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                    </div>
                </div>

                {{-- Active toggle --}}
                <div class="pt-3 border-t border-gray-100">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Categoría activa</span>
                            <p class="text-xs text-gray-400">Se mostrará en la tienda</p>
                        </div>
                        <div class="relative">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   class="sr-only peer" id="edit_is_active">
                            <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:bg-indigo-500 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-5 pt-5 border-t border-gray-100">
                <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                    <span><i class="fas fa-calendar-alt mr-1"></i> Creada: <span id="editCreatedAt"></span></span>
                </div>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/80 flex gap-3">
        <button onclick="closeDrawer()" type="button" class="flex-1 px-4 py-2.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition font-medium">
            Cancelar
        </button>
        <button onclick="document.getElementById('editCategoryForm').submit()" type="button" class="flex-1 px-4 py-2.5 text-sm bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition font-medium shadow-sm shadow-indigo-200">
            <i class="fas fa-check mr-1.5 text-xs"></i> Guardar Cambios
        </button>
    </div>
</div>
@endsection

@section('styles')
<style>
    .category-card:hover .card-actions { opacity: 1; }
    @media (max-width: 767px) { .card-actions { opacity: 1 !important; } }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
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
        setTimeout(() => document.getElementById('create_name').focus(), 300);
    }

    function openEditDrawer(category) {
        document.getElementById('edit_name').value = category.name || '';
        document.getElementById('edit_slug').value = category.slug || '';
        document.getElementById('edit_description').value = category.description || '';
        document.getElementById('edit_icon').value = category.icon || '';
        document.getElementById('edit_sort_order').value = category.sort_order || 0;
        document.getElementById('edit_image_url').value = category.image_url || '';

        document.getElementById('editCategoryForm').action = '/admin/categories/' + category.id;
        document.getElementById('editDrawerSubtitle').textContent = category.name;

        // Icon preview
        const iconPreview = document.getElementById('editIconPreview');
        iconPreview.className = (category.icon || 'fas fa-folder') + ' text-2xl text-white';

        // Image preview
        const imgPreview = document.getElementById('editImagePreview');
        const imgTag = document.getElementById('editImagePreviewImg');
        if (category.image_url) {
            imgTag.src = category.image_url;
            imgPreview.classList.remove('hidden');
        } else {
            imgPreview.classList.add('hidden');
        }

        // Active toggle
        document.getElementById('edit_is_active').checked = category.is_active;

        // Date
        const fmtDate = (d) => {
            if (!d) return '—';
            const dt = new Date(d);
            return dt.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        };
        document.getElementById('editCreatedAt').textContent = fmtDate(category.created_at);

        showDrawer(editDrawer);
        setTimeout(() => document.getElementById('edit_name').focus(), 300);
    }

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeDrawer();
    });

    // Icon live preview - create
    const createIconInput = document.getElementById('create_icon');
    const createIconPreview = document.getElementById('createIconPreview');
    createIconInput.addEventListener('input', function () {
        createIconPreview.className = (this.value || 'fas fa-folder') + ' text-2xl text-white';
    });

    // Icon live preview - edit
    const editIconInput = document.getElementById('edit_icon');
    const editIconPreviewEl = document.getElementById('editIconPreview');
    editIconInput.addEventListener('input', function () {
        editIconPreviewEl.className = (this.value || 'fas fa-folder') + ' text-2xl text-white';
    });

    // Auto-open if validation errors
    @if($errors->any() && old('_method') === 'PUT')
        openEditDrawer({
            id: '{{ old("_category_id") }}',
            name: '{{ old("name") }}',
            slug: '{{ old("slug") }}',
            description: '{{ old("description") }}',
            icon: '{{ old("icon") }}',
            sort_order: {{ old('sort_order', 0) }},
            image_url: '{{ old("image_url") }}',
            is_active: {{ old('is_active', 1) }},
            created_at: null
        });
    @elseif($errors->any())
        openCreateDrawer();
    @endif
</script>
@endsection
