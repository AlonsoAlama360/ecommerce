@extends('admin.layouts.app')
@section('title', 'Reseñas')

@section('content')
{{-- ==================== STAT CARDS ==================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Total Reseñas</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalReviews) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Todas las reseñas</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-star text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Aprobadas</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($approvedReviews) }}</h3>
            <p class="text-xs mt-1">
                <span class="text-emerald-500 font-semibold">{{ $totalReviews > 0 ? round(($approvedReviews / $totalReviews) * 100) : 0 }}%</span>
                <span class="text-gray-400">del total</span>
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check-circle text-emerald-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Pendientes</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($pendingReviews) }}</h3>
            <p class="text-xs mt-1">
                @if($pendingReviews > 0)
                    <span class="text-amber-500 font-semibold">Requieren revisión</span>
                @else
                    <span class="text-gray-400">Sin pendientes</span>
                @endif
            </p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-clock text-amber-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">En Inicio</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($featuredCount) }}</h3>
            <p class="text-xs text-gray-400 mt-1">Mostradas en la tienda</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-home text-purple-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 mb-1">Calificación Promedio</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $averageRating }} <span class="text-base text-amber-400">&#9733;</span></h3>
            <p class="text-xs text-gray-400 mt-1">De reseñas aprobadas</p>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-star-half-alt text-amber-500"></i>
        </div>
    </div>
</div>

{{-- ==================== FILTERS ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
    <div class="p-4 flex flex-col sm:flex-row gap-3">
        <form method="GET" class="flex flex-col sm:flex-row gap-3 flex-1 flex-wrap">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por usuario, producto o comentario..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
            </div>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 bg-white">
                <option value="">Todos los estados</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobadas</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
            </select>
            <select name="rating" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 bg-white">
                <option value="">Todas las estrellas</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} estrella{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>
            <label class="flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-lg text-sm cursor-pointer hover:bg-gray-50 transition {{ request('featured') ? 'bg-purple-50 border-purple-300' : '' }}">
                <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }} class="w-4 h-4 text-purple-500 border-gray-300 rounded focus:ring-purple-400">
                <span class="font-medium text-gray-700"><i class="fas fa-home text-purple-500 mr-1"></i> En Inicio</span>
            </label>
            <button type="submit" class="px-5 py-2.5 bg-indigo-500 text-white rounded-lg text-sm font-medium hover:bg-indigo-600 transition">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            @if(request()->hasAny(['search', 'status', 'rating', 'featured']))
                <a href="{{ route('admin.reviews.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>
</div>

{{-- ==================== SUCCESS MESSAGE ==================== --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
        <i class="fas fa-check-circle text-emerald-500"></i>
        <p class="text-emerald-700 text-sm font-medium">{{ session('success') }}</p>
    </div>
@endif

{{-- ==================== REVIEWS TABLE ==================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    @if($reviews->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Cliente</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Producto</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Calificación</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Comentario</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Estado</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Inicio</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Fecha</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @php
                        $avatarGradients = [
                            'from-rose-400 to-pink-500',
                            'from-purple-400 to-indigo-500',
                            'from-amber-400 to-orange-500',
                            'from-teal-400 to-emerald-500',
                            'from-blue-400 to-cyan-500',
                        ];
                    @endphp
                    @foreach($reviews as $index => $review)
                        <tr class="hover:bg-gray-50/50 transition {{ $review->is_featured ? 'bg-purple-50/30' : '' }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $avatarGradients[$index % count($avatarGradients)] }} flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($review->user->first_name, 0, 1) . substr($review->user->last_name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-900 text-sm">{{ $review->user->full_name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="text-indigo-600 hover:underline text-sm font-medium">
                                    {{ Str::limit($review->product->name, 30) }}
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex gap-0.5">
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="fas fa-star {{ $s <= $review->rating ? 'text-amber-400' : 'text-gray-200' }} text-xs"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="max-w-xs">
                                    @if($review->title)
                                        <p class="font-semibold text-gray-900 text-xs mb-0.5">{{ $review->title }}</p>
                                    @endif
                                    <p class="text-gray-600 text-xs line-clamp-2">{{ $review->comment }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($review->is_approved)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-semibold">
                                        <i class="fas fa-check text-[10px]"></i> Aprobada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-600 rounded-full text-xs font-semibold">
                                        <i class="fas fa-clock text-[10px]"></i> Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <form action="{{ route('admin.reviews.featured', $review) }}" method="POST" class="inline">
                                    @csrf @method('PUT')
                                    <button type="submit" title="{{ $review->is_featured ? 'Quitar del inicio' : 'Mostrar en inicio' }}"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center transition {{ $review->is_featured ? 'bg-purple-100 text-purple-600 hover:bg-purple-200' : 'bg-gray-100 text-gray-400 hover:bg-purple-50 hover:text-purple-500' }}">
                                        <i class="fas fa-home text-xs"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500">
                                {{ $review->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    @if(!$review->is_approved)
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <button type="submit" title="Aprobar" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center transition">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <button type="submit" title="Rechazar" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition">
                                                <i class="fas fa-ban text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta reseña?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Eliminar" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reviews->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $reviews->links() }}
            </div>
        @endif
    @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-star text-2xl text-gray-400"></i>
            </div>
            <h3 class="font-semibold text-gray-700 mb-1">Sin reseñas</h3>
            <p class="text-gray-500 text-sm">No se encontraron reseñas con los filtros aplicados.</p>
        </div>
    @endif
</div>
@endsection
