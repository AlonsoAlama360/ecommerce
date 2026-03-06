@extends('admin.layouts.app')
@section('title', 'Reporte de Productos')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Reporte de Productos</h1>
    <p class="text-gray-500 mt-1">Inventario y rendimiento del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
</div>
@include('admin.reports._tabs')
@include('admin.reports._filters')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-box text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalProducts) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Total productos</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-check-circle text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($activeProducts) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Productos activos</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                <i class="fas fa-box-open text-white text-sm"></i>
            </div>
            @if($outOfStock > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-red-50 text-red-500">
                <i class="fas fa-exclamation text-[9px]"></i> Alerta
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($outOfStock) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Sin stock</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-triangle-exclamation text-white text-sm"></i>
            </div>
            @if($lowStock > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-50 text-amber-600">
                <i class="fas fa-arrow-down text-[9px]"></i> Bajo
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($lowStock) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Stock bajo (1-5)</p>
    </div>
</div>

{{-- Low Stock Alert --}}
@if($lowStockProducts->count() > 0)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
            <i class="fas fa-triangle-exclamation text-amber-500 text-sm"></i>
        </div>
        <h3 class="text-sm font-semibold text-gray-800">Alertas de stock</h3>
        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-600">{{ $lowStockProducts->count() }} productos</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="pb-3 pl-3">Producto</th>
                    <th class="pb-3">SKU</th>
                    <th class="pb-3 text-center">Stock</th>
                    <th class="pb-3 text-right pr-3">Precio</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($lowStockProducts as $product)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-3 pl-3">
                        <div class="flex items-center gap-3">
                            @if($product->primaryImage)
                            <img src="{{ asset(ltrim($product->primaryImage->image_url, '/')) }}" alt="" class="w-9 h-9 rounded-lg object-cover">
                            @else
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-image text-gray-300 text-xs"></i>
                            </div>
                            @endif
                            <span class="text-sm font-medium text-gray-800">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="py-3"><span class="text-xs text-gray-400 font-mono">{{ $product->sku ?? '-' }}</span></td>
                    <td class="py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $product->stock == 0 ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="py-3 text-right pr-3">
                        <span class="text-sm font-semibold text-gray-700">S/ {{ number_format($product->current_price, 2) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Best Sellers & Most Wished --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    {{-- Best Sellers --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">M&aacute;s vendidos en el per&iacute;odo</h3>
        <div class="space-y-3">
            @forelse($bestSellers as $i => $product)
            <div class="flex items-center gap-3 p-3 rounded-xl {{ $i < 3 ? 'bg-indigo-50/50' : 'bg-gray-50/50' }}">
                <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $product->product_name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->product_sku ?? '-' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-gray-800">{{ number_format($product->total_quantity) }} uds</p>
                    <p class="text-xs text-emerald-500 font-semibold">S/ {{ number_format($product->total_revenue, 2) }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No hay ventas en este per&iacute;odo</p>
            @endforelse
        </div>
    </div>

    {{-- Most Wished --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">M&aacute;s deseados</h3>
        <div class="space-y-3">
            @forelse($mostWished as $i => $product)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50/50">
                <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-pink-100 text-pink-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">S/ {{ number_format($product->current_price, 2) }}</p>
                </div>
                <div class="flex items-center gap-1.5 text-pink-500">
                    <i class="fas fa-heart text-xs"></i>
                    <span class="text-sm font-bold">{{ $product->wishlists_count }}</span>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No hay productos en listas de deseos</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Best Rated & No Sales --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    {{-- Best Rated --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Mejor calificados</h3>
        <div class="space-y-3">
            @forelse($bestRated as $i => $product)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50/50">
                <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->reviews_count }} rese&ntilde;as</p>
                </div>
                <div class="flex items-center gap-1">
                    <i class="fas fa-star text-amber-400 text-xs"></i>
                    <span class="text-sm font-bold text-gray-800">{{ number_format($product->reviews_avg_rating, 1) }}</span>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No hay productos con suficientes rese&ntilde;as</p>
            @endforelse
        </div>
    </div>

    {{-- No Sales --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Sin ventas en el per&iacute;odo</h3>
        <div class="space-y-3">
            @forelse($noSalesProducts as $product)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50/50">
                @if($product->primaryImage)
                <img src="{{ asset(ltrim($product->primaryImage->image_url, '/')) }}" alt="" class="w-9 h-9 rounded-lg object-cover flex-shrink-0">
                @else
                <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-image text-gray-300 text-xs"></i>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">Stock: {{ $product->stock }}</p>
                </div>
                <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-lg">Sin movimiento</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">Todos los productos tuvieron ventas</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
