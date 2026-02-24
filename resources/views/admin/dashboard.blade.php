@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Buenos {{ now()->hour < 12 ? 'días' : (now()->hour < 19 ? 'tardes' : 'noches') }}, {{ Auth::user()->first_name }}</h1>
    <p class="text-gray-500 mt-1">Aquí tienes el resumen de tu tienda hoy, {{ now()->translatedFormat('l j \\d\\e F') }}</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Total Users --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-users text-white text-sm"></i>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $stats['new_users_week'] > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-50 text-gray-400' }}">
                @if($stats['new_users_week'] > 0)
                <i class="fas fa-arrow-trend-up text-[9px]"></i> +{{ $stats['new_users_week'] }}
                @else
                <i class="fas fa-minus text-[9px]"></i> 0
                @endif
            </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Usuarios registrados</p>
    </div>

    {{-- Total Products --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-box text-white text-sm"></i>
            </div>
            @if($stats['total_products'] > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-violet-50 text-violet-600">
                <i class="fas fa-check text-[9px]"></i> {{ $stats['active_products'] }} activos
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_products']) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Productos totales</p>
    </div>

    {{-- Categories --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-tags text-white text-sm"></i>
            </div>
            @if($stats['total_categories'] > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-50 text-amber-600">
                {{ $stats['active_categories'] }} activas
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_categories']) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Categorías creadas</p>
    </div>

    {{-- Stock Alerts --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                <i class="fas fa-box-open text-white text-sm"></i>
            </div>
            @if($stats['low_stock'] > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-50 text-amber-600">
                <i class="fas fa-exclamation text-[9px]"></i> {{ $stats['low_stock'] }} bajo
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['out_of_stock']) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Productos sin stock</p>
    </div>
</div>

{{-- Content Grid --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Recent Products --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Productos recientes</h3>
                <p class="text-xs text-gray-400 mt-0.5">Últimos productos agregados</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                Ver todos <i class="fas fa-arrow-right text-[9px]"></i>
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentProducts as $product)
            <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/50 transition">
                @if($product->primaryImage)
                <img src="{{ $product->primaryImage->image_url }}" alt="{{ $product->name }}" class="w-11 h-11 rounded-xl object-cover border border-gray-100 flex-shrink-0">
                @else
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-image text-gray-300 text-xs"></i>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                        @if($product->is_featured)
                        <i class="fas fa-star text-amber-400 text-[9px]"></i>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400">{{ $product->category->name ?? '—' }} &middot; SKU: {{ $product->sku }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-gray-800">S/ {{ number_format($product->sale_price ?? $product->price, 2) }}</p>
                    @if($product->stock === 0)
                    <span class="text-[10px] font-semibold text-red-500">Agotado</span>
                    @elseif($product->stock <= 5)
                    <span class="text-[10px] font-semibold text-amber-500">Stock: {{ $product->stock }}</span>
                    @else
                    <span class="text-[10px] font-semibold text-emerald-500">Stock: {{ $product->stock }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="py-12 text-center">
                <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-box text-gray-300 text-lg"></i>
                </div>
                <p class="text-sm text-gray-400">No hay productos aún</p>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-indigo-500 hover:underline mt-1 inline-block">Crear primer producto</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Right Column --}}
    <div class="space-y-6">

        {{-- Top Categories --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Top categorías</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Por cantidad de productos</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                    Ver <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>
            <div class="p-5 space-y-4">
                @forelse($topCategories as $cat)
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-50 to-violet-50 flex items-center justify-center flex-shrink-0">
                        @if($cat->icon)
                        <i class="{{ $cat->icon }} text-indigo-500 text-xs"></i>
                        @else
                        <i class="fas fa-tag text-indigo-500 text-xs"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ $cat->name }}</p>
                        <div class="mt-1.5 w-full bg-gray-100 rounded-full h-1.5">
                            @php
                                $maxProducts = $topCategories->max('products_count') ?: 1;
                                $percentage = ($cat->products_count / $maxProducts) * 100;
                            @endphp
                            <div class="h-1.5 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-500 flex-shrink-0">{{ $cat->products_count }}</span>
                </div>
                @empty
                <div class="py-6 text-center">
                    <p class="text-sm text-gray-400">Sin categorías</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Usuarios recientes</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Últimos registros</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                    Ver <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentUsers as $user)
                <div class="flex items-center gap-3 px-6 py-3 hover:bg-gray-50/50 transition">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-[11px] font-bold text-white flex-shrink-0
                        {{ $user->role === 'admin' ? 'bg-gradient-to-br from-indigo-500 to-violet-600' : ($user->role === 'vendedor' ? 'bg-gradient-to-br from-amber-400 to-orange-500' : 'bg-gradient-to-br from-teal-400 to-cyan-500') }}">
                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ $user->full_name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                    </div>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md flex-shrink-0
                        {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-600' : ($user->role === 'vendedor' ? 'bg-amber-50 text-amber-600' : 'bg-gray-100 text-gray-500') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                @empty
                <div class="py-8 text-center">
                    <p class="text-sm text-gray-400">Sin usuarios</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

{{-- Recent Orders --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-6">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <div>
            <h3 class="text-sm font-semibold text-gray-800">Ventas recientes</h3>
            <p class="text-xs text-gray-400 mt-0.5">
                @if($stats['pending_orders'] > 0)
                <span class="text-amber-500 font-semibold">{{ $stats['pending_orders'] }} pendientes</span> ·
                @endif
                Ingresos del mes: <span class="text-emerald-600 font-semibold">S/ {{ number_format($stats['monthly_revenue'], 2) }}</span>
            </p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
            Ver todas <i class="fas fa-arrow-right text-[9px]"></i>
        </a>
    </div>
    <div class="divide-y divide-gray-50">
        @forelse($recentOrders as $order)
        <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/50 transition">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0
                {{ $order->source === 'web' ? 'bg-gradient-to-br from-cyan-50 to-blue-50' : 'bg-gradient-to-br from-violet-50 to-purple-50' }}">
                <i class="fas {{ $order->source === 'web' ? 'fa-globe text-cyan-500' : 'fa-user-shield text-violet-500' }} text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $order->customer_name }}</p>
                    <span class="text-[10px] font-mono text-gray-400">{{ $order->order_number }}</span>
                </div>
                <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }} · {{ $order->items_count ?? $order->items->count() }} items</p>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-bold text-gray-800">S/ {{ number_format($order->total, 2) }}</p>
                @php $sc = $order->status_color; @endphp
                <span class="text-[10px] font-semibold {{ $sc['text'] }}">{{ $order->status_label }}</span>
            </div>
        </div>
        @empty
        <div class="py-12 text-center">
            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-receipt text-gray-300 text-lg"></i>
            </div>
            <p class="text-sm text-gray-400">No hay ventas aún</p>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-indigo-500 hover:underline mt-1 inline-block">Crear primera venta</a>
        </div>
        @endforelse
    </div>
</div>

{{-- Quick Actions --}}
<div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <a href="{{ route('admin.products.index') }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-indigo-200 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center transition">
                <i class="fas fa-plus text-indigo-500 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Nuevo producto</p>
                <p class="text-xs text-gray-400">Agregar al catálogo</p>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-amber-200 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center transition">
                <i class="fas fa-folder-plus text-amber-500 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Nueva categoría</p>
                <p class="text-xs text-gray-400">Organizar productos</p>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.users.index') }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-emerald-200 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center transition">
                <i class="fas fa-user-gear text-emerald-500 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Gestionar usuarios</p>
                <p class="text-xs text-gray-400">Administrar cuentas</p>
            </div>
        </div>
    </a>
</div>
@endsection
