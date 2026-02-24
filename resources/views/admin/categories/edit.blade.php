@extends('admin.layouts.app')
@section('title', 'Editar Categoría')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
        <i class="fas fa-arrow-left text-xs"></i> Volver a categorías
    </a>
    <h1 class="text-2xl font-bold text-gray-900 mt-2">Editar Categoría</h1>
    <p class="text-gray-500 mt-1">{{ $category->name }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-2xl">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="p-4 sm:p-6">
        @csrf
        @method('PUT')

        <div class="space-y-5">
            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                       placeholder="Se genera automáticamente si se deja vacío"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('slug') border-red-400 @enderror">
                @error('slug')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm resize-none @error('description') border-red-400 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                <!-- Icono -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icono (Font Awesome)</label>
                    <div class="flex gap-2">
                        <input type="text" name="icon" id="icon" value="{{ old('icon', $category->icon) }}"
                               placeholder="ej: fas fa-gem"
                               class="flex-1 px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('icon') border-red-400 @enderror">
                        @if($category->icon)
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i class="{{ $category->icon }} text-gray-600"></i>
                            </div>
                        @endif
                    </div>
                    @error('icon')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Orden -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('sort_order') border-red-400 @enderror">
                    @error('sort_order')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- URL de imagen -->
            <div>
                <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">URL de imagen</label>
                <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $category->image_url) }}"
                       placeholder="https://ejemplo.com/imagen.jpg"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('image_url') border-red-400 @enderror">
                @error('image_url')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                @if($category->image_url)
                    <div class="mt-2">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="h-20 w-20 object-cover rounded-lg border">
                    </div>
                @endif
            </div>

            <!-- Activa -->
            <div class="pt-4 border-t border-gray-100">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Categoría activa</span>
                        <p class="text-xs text-gray-400">Se mostrará en la tienda</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Info -->
        <div class="mt-5 pt-5 border-t border-gray-100">
            <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                <span><i class="fas fa-calendar-alt mr-1"></i> Creada: {{ $category->created_at->format('d/m/Y H:i') }}</span>
                <span><i class="fas fa-box mr-1"></i> Productos: {{ $category->products()->count() }}</span>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-6 pt-5 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-center">
                Cancelar
            </a>
            <button type="submit"
                    class="px-6 py-2.5 text-sm bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition font-medium">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
