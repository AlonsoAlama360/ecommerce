@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Buenos {{ now()->hour < 12 ? 'días' : (now()->hour < 19 ? 'tardes' : 'noches') }}, {{ Auth::user()->first_name }}</h1>
        <p class="text-gray-500 mt-1">Resumen de tu tienda — {{ now()->translatedFormat('l j \\d\\e F, Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
        @if($stats['pending_complaints'] > 0)
        <a href="{{ route('admin.complaints.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition">
            <i class="fas fa-triangle-exclamation text-[10px]"></i> {{ $stats['pending_complaints'] }} quejas pendientes
        </a>
        @endif
        @if($stats['unread_messages'] > 0)
        <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition">
            <i class="fas fa-envelope text-[10px]"></i> {{ $stats['unread_messages'] }} mensajes
        </a>
        @endif
    </div>
</div>

{{-- Main Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Monthly Revenue --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-dollar-sign text-white text-sm"></i>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $stats['revenue_growth'] >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                <i class="fas {{ $stats['revenue_growth'] >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-[9px]"></i>
                {{ $stats['revenue_growth'] >= 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}%
            </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($stats['monthly_revenue'], 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Ingresos del mes</p>
    </div>

    {{-- Monthly Orders --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-receipt text-white text-sm"></i>
            </div>
            @if($stats['pending_orders'] > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-50 text-amber-600">
                <i class="fas fa-clock text-[9px]"></i> {{ $stats['pending_orders'] }} pendientes
            </span>
            @else
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $stats['orders_growth'] >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                <i class="fas {{ $stats['orders_growth'] >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-[9px]"></i>
                {{ $stats['orders_growth'] >= 0 ? '+' : '' }}{{ $stats['orders_growth'] }}%
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['monthly_orders']) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Pedidos del mes <span class="text-gray-300">&middot;</span> {{ $stats['orders_today'] }} hoy</p>
    </div>

    {{-- Avg Ticket --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-tag text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($stats['avg_ticket'], 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Ticket promedio</p>
    </div>

    {{-- Rating & Reviews --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-star text-white text-sm"></i>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-50 text-amber-600">
                {{ $stats['total_reviews'] }} rese&ntilde;as
            </span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_rating'], 1) }} <span class="text-sm text-gray-400 font-normal">/ 5</span></h3>
        <p class="text-sm text-gray-400 mt-0.5">Rating promedio</p>
    </div>
</div>

{{-- Secondary Stats Row --}}
<div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-6 gap-3 mb-8">
    <div class="bg-white rounded-xl p-3.5 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Usuarios</p>
        <p class="text-lg font-bold text-gray-900 mt-0.5">{{ number_format($stats['total_users']) }}</p>
        @if($stats['new_users_week'] > 0)
        <p class="text-[10px] text-emerald-500 font-semibold">+{{ $stats['new_users_week'] }} esta semana</p>
        @endif
    </div>
    <div class="bg-white rounded-xl p-3.5 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Productos</p>
        <p class="text-lg font-bold text-gray-900 mt-0.5">{{ number_format($stats['active_products']) }}</p>
        <p class="text-[10px] text-gray-400">de {{ $stats['total_products'] }} totales</p>
    </div>
    <div class="bg-white rounded-xl p-3.5 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Sin stock</p>
        <p class="text-lg font-bold {{ $stats['out_of_stock'] > 0 ? 'text-red-500' : 'text-gray-900' }} mt-0.5">{{ $stats['out_of_stock'] }}</p>
        @if($stats['low_stock'] > 0)
        <p class="text-[10px] text-amber-500 font-semibold">{{ $stats['low_stock'] }} stock bajo</p>
        @endif
    </div>
    <div class="bg-white rounded-xl p-3.5 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Categor&iacute;as</p>
        <p class="text-lg font-bold text-gray-900 mt-0.5">{{ number_format($stats['active_categories']) }}</p>
        <p class="text-[10px] text-gray-400">activas</p>
    </div>
    <div class="bg-white rounded-xl p-3.5 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Gasto del mes</p>
        <p class="text-lg font-bold text-gray-900 mt-0.5">S/ {{ number_format($stats['monthly_spending'], 0) }}</p>
        <p class="text-[10px] text-gray-400">en compras</p>
    </div>
    <div class="bg-white rounded-xl p-3.5 border border-gray-100 shadow-sm">
        <p class="text-xs text-gray-400 font-medium">Deseos</p>
        <p class="text-lg font-bold text-gray-900 mt-0.5">{{ number_format($stats['total_wishlists']) }}</p>
        <p class="text-[10px] text-gray-400">en listas</p>
    </div>
</div>

{{-- Chart + Top Selling --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Revenue Chart --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">&Uacute;ltimos 7 d&iacute;as</h3>
                <p class="text-xs text-gray-400 mt-0.5">Ingresos y pedidos diarios</p>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Ingresos</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span> Pedidos</span>
            </div>
        </div>
        <div style="height: 260px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Top Selling Products --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">M&aacute;s vendidos del mes</h3>
            <p class="text-xs text-gray-400 mt-0.5">Por ingresos generados</p>
        </div>
        <div class="p-4 space-y-2">
            @forelse($topSellingProducts as $i => $product)
            <div class="flex items-center gap-3 p-2.5 rounded-xl {{ $i < 3 ? 'bg-indigo-50/50' : 'bg-gray-50/50' }}">
                <span class="w-6 h-6 rounded-lg {{ $i < 3 ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-[10px] font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-800 truncate">{{ $product->product_name }}</p>
                    <p class="text-[10px] text-gray-400">{{ $product->total_qty }} uds</p>
                </div>
                <span class="text-xs font-bold text-emerald-600 flex-shrink-0">S/ {{ number_format($product->total_revenue, 0) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">Sin ventas este mes</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Orders + Low Stock + Pending Purchases --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Recent Orders --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Pedidos recientes</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    Hoy: <span class="text-emerald-600 font-semibold">S/ {{ number_format($stats['revenue_today'], 2) }}</span>
                    <span class="text-gray-300 mx-1">&middot;</span>
                    {{ $stats['orders_today'] }} pedidos
                </p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                Ver todos <i class="fas fa-arrow-right text-[9px]"></i>
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentOrders as $order)
            <div class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50/50 transition">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $order->source === 'web' ? 'bg-gradient-to-br from-cyan-50 to-blue-50' : 'bg-gradient-to-br from-violet-50 to-purple-50' }}">
                    <i class="fas {{ $order->source === 'web' ? 'fa-globe text-cyan-500' : 'fa-user-shield text-violet-500' }} text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $order->customer_name }}</p>
                        <span class="text-[10px] font-mono text-gray-400">{{ $order->order_number }}</span>
                    </div>
                    <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-gray-800">S/ {{ number_format($order->total, 2) }}</p>
                    @php $sc = $order->status_color; @endphp
                    <span class="text-[10px] font-semibold {{ $sc['text'] }}">{{ $order->status_label }}</span>
                </div>
            </div>
            @empty
            <div class="py-10 text-center">
                <i class="fas fa-receipt text-gray-200 text-2xl mb-2"></i>
                <p class="text-sm text-gray-400">No hay pedidos a&uacute;n</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Right Column --}}
    <div class="space-y-6">
        {{-- Low Stock Alert --}}
        @if($lowStockProducts->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-semibold text-gray-800">Alertas de stock</h3>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-red-50 text-red-500">{{ $lowStockProducts->count() }}</span>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-indigo-600 hover:underline">Ver</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($lowStockProducts as $product)
                <div class="flex items-center gap-3 px-6 py-2.5">
                    @if($product->primaryImage)
                    <img src="{{ $product->primaryImage->thumbnail() }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0">
                    @else
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-300 text-[10px]"></i></div>
                    @endif
                    <p class="text-xs font-medium text-gray-700 truncate flex-1">{{ $product->name }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $product->stock == 0 ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }}">
                        {{ $product->stock }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Pending Purchases --}}
        @if($pendingPurchases->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-semibold text-gray-800">Compras pendientes</h3>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600">{{ $pendingPurchases->count() }}</span>
                </div>
                <a href="{{ route('admin.purchases.index') }}" class="text-xs text-indigo-600 hover:underline">Ver</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($pendingPurchases as $purchase)
                <div class="flex items-center gap-3 px-6 py-2.5">
                    <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-truck text-violet-500 text-[10px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-700 truncate">{{ $purchase->supplier->business_name ?? 'Sin proveedor' }}</p>
                        <p class="text-[10px] text-gray-400">{{ $purchase->purchase_number }}</p>
                    </div>
                    <span class="text-xs font-bold text-gray-700">S/ {{ number_format($purchase->total, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Top Categories --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Top categor&iacute;as</h3>
                <a href="{{ route('admin.categories.index') }}" class="text-xs text-indigo-600 hover:underline">Ver</a>
            </div>
            <div class="p-5 space-y-3.5">
                @forelse($topCategories as $cat)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                        <i class="{{ $cat->icon ?? 'fas fa-tag' }} text-indigo-500 text-[10px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-700 truncate">{{ $cat->name }}</p>
                        <div class="mt-1 w-full bg-gray-100 rounded-full h-1">
                            @php $pct = ($topCategories->max('products_count') ?: 1); @endphp
                            <div class="h-1 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: {{ ($cat->products_count / $pct) * 100 }}%"></div>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-500">{{ $cat->products_count }}</span>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">Sin categor&iacute;as</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Recent Reviews --}}
@if($recentReviews->count() > 0)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <div>
            <h3 class="text-sm font-semibold text-gray-800">&Uacute;ltimas rese&ntilde;as</h3>
            <p class="text-xs text-gray-400 mt-0.5">Rating promedio: <span class="text-amber-500 font-semibold">{{ number_format($stats['avg_rating'], 1) }}</span> de {{ $stats['total_reviews'] }} rese&ntilde;as</p>
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
            Ver todas <i class="fas fa-arrow-right text-[9px]"></i>
        </a>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach($recentReviews as $review)
        <div class="flex items-start gap-4 px-6 py-3.5 hover:bg-gray-50/50 transition">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-amber-600">{{ $review->rating }}<i class="fas fa-star text-[8px] ml-0.5 text-amber-400"></i></span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $review->user->full_name ?? 'An&oacute;nimo' }}</p>
                    <span class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-xs text-gray-500 truncate mt-0.5">{{ $review->product->name ?? '' }}</p>
                @if($review->comment)
                <p class="text-xs text-gray-400 mt-1 line-clamp-1">{{ $review->comment }}</p>
                @endif
            </div>
            <div class="flex items-center gap-0.5 flex-shrink-0">
                @for($s = 1; $s <= 5; $s++)
                <i class="fas fa-star text-[9px] {{ $s <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                @endfor
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Quick Actions --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <a href="{{ route('admin.products.index') }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-indigo-200 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center transition">
                <i class="fas fa-plus text-indigo-500 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Nuevo producto</p>
                <p class="text-xs text-gray-400">Agregar al cat&aacute;logo</p>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.orders.index') }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-emerald-200 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center transition">
                <i class="fas fa-cart-plus text-emerald-500 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Nueva venta</p>
                <p class="text-xs text-gray-400">Registrar pedido</p>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.reports.sales') }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-violet-200 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-violet-50 group-hover:bg-violet-100 flex items-center justify-center transition">
                <i class="fas fa-chart-bar text-violet-500 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Ver reportes</p>
                <p class="text-xs text-gray-400">An&aacute;lisis completo</p>
            </div>
        </div>
    </a>
    <a href="{{ route('admin.users.index') }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-amber-200 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center transition">
                <i class="fas fa-user-gear text-amber-500 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Usuarios</p>
                <p class="text-xs text-gray-400">Gestionar cuentas</p>
            </div>
        </div>
    </a>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const data = @json($chartData);

    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: data.map(d => {
                const date = new Date(d.date + 'T00:00:00');
                return date.toLocaleDateString('es-PE', { weekday: 'short', day: '2-digit' });
            }),
            datasets: [{
                label: 'Ingresos',
                data: data.map(d => d.revenue),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 6,
                yAxisID: 'y',
            }, {
                label: 'Pedidos',
                data: data.map(d => d.orders),
                borderColor: '#34d399',
                backgroundColor: 'rgba(52, 211, 153, 0.08)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#34d399',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                yAxisID: 'y1',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => ctx.datasetIndex === 0
                            ? `Ingresos: S/ ${ctx.parsed.y.toLocaleString('es-PE', { minimumFractionDigits: 2 })}`
                            : `Pedidos: ${ctx.parsed.y}`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#94a3b8' } },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11 }, color: '#94a3b8', callback: v => 'S/ ' + v.toLocaleString() }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { font: { size: 11 }, color: '#6ee7b7', stepSize: 1 }
                }
            }
        }
    });
</script>
@endsection
