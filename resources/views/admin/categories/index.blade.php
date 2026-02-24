@extends('admin.layouts.app')
@section('title', 'Categorías')

@php
    $colors = [
        ['bg' => 'bg-blue-100', 'icon' => 'text-blue-500', 'bar' => 'bg-blue-500'],
        ['bg' => 'bg-emerald-100', 'icon' => 'text-emerald-500', 'bar' => 'bg-emerald-500'],
        ['bg' => 'bg-amber-100', 'icon' => 'text-amber-500', 'bar' => 'bg-amber-500'],
        ['bg' => 'bg-rose-100', 'icon' => 'text-rose-500', 'bar' => 'bg-rose-400'],
        ['bg' => 'bg-violet-100', 'icon' => 'text-violet-500', 'bar' => 'bg-violet-500'],
        ['bg' => 'bg-purple-100', 'icon' => 'text-purple-500', 'bar' => 'bg-purple-500'],
        ['bg' => 'bg-teal-100', 'icon' => 'text-teal-500', 'bar' => 'bg-teal-500'],
        ['bg' => 'bg-orange-100', 'icon' => 'text-orange-500', 'bar' => 'bg-orange-500'],
    ];
    $maxProducts = $categories->max('products_count') ?: 1;
@endphp

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Categorías</h1>
        <p class="text-gray-500 mt-1">{{ $categories->count() }} categorías disponibles</p>
    </div>
    <a href="{{ route('admin.categories.create') }}"
       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium shadow-sm">
        <i class="fas fa-plus text-xs"></i>
        Nueva Categoría
    </a>
</div>

<!-- Categories Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
    @forelse($categories as $index => $category)
        @php $color = $colors[$index % count($colors)]; @endphp
        <div class="category-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 relative group hover:shadow-md transition-shadow">
            <!-- Actions (visible on hover) -->
            <div class="card-actions absolute top-4 right-4 flex items-center gap-1 opacity-0 transition-opacity">
                <a href="{{ route('admin.categories.edit', $category) }}"
                   class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                   title="Editar">
                    <i class="fas fa-pen text-xs"></i>
                </a>
                @if($category->products_count === 0)
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar la categoría {{ $category->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                            title="Eliminar">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </form>
                @endif
            </div>

            <!-- Icon -->
            <div class="w-12 h-12 rounded-xl {{ $color['bg'] }} flex items-center justify-center mb-4">
                @if($category->icon)
                    <i class="{{ $category->icon }} {{ $color['icon'] }} text-lg"></i>
                @else
                    <i class="fas fa-folder {{ $color['icon'] }} text-lg"></i>
                @endif
            </div>

            <!-- Info -->
            <h3 class="font-semibold text-gray-900 text-base">{{ $category->name }}</h3>
            <p class="text-sm text-gray-400 mt-0.5">{{ $category->products_count }} productos</p>

            <!-- Status badge -->
            @if(!$category->is_active)
                <span class="absolute top-4 left-4 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-500">Inactiva</span>
            @endif

            <!-- Progress bar -->
            <div class="mt-4 h-1 bg-gray-100 rounded-full overflow-hidden">
                <div class="{{ $color['bar'] }} h-full rounded-full transition-all duration-500"
                     style="width: {{ ($category->products_count / $maxProducts) * 100 }}%"></div>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-tags text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-gray-600 font-medium mb-1">Sin categorías</h3>
            <p class="text-sm text-gray-400 mb-4">Comienza creando tu primera categoría</p>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm">
                <i class="fas fa-plus text-xs"></i> Crear categoría
            </a>
        </div>
    @endforelse
</div>

@endsection

@section('styles')
<style>
    .category-card:hover .card-actions {
        opacity: 1;
    }
    @media (max-width: 767px) {
        .card-actions {
            opacity: 1 !important;
        }
    }
</style>
@endsection
