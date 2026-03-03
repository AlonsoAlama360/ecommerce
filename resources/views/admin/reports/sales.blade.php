@extends('admin.layouts.app')
@section('title', 'Reporte de Ventas')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Reporte de Ventas</h1>
    <p class="text-gray-500 mt-1">Rendimiento comercial del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
</div>
@include('admin.reports._tabs')
@include('admin.reports._filters')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Revenue --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-dollar-sign text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($totalRevenue, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Ingresos totales</p>
    </div>

    {{-- Orders --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-receipt text-white text-sm"></i>
            </div>
            @if($cancelledOrders > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-red-50 text-red-500">
                {{ $cancelledOrders }} cancelados
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Pedidos totales</p>
    </div>

    {{-- Average Ticket --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-tag text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($avgTicket, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Ticket promedio</p>
    </div>

    {{-- Discounts --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-percent text-white text-sm"></i>
            </div>
            @if($cancellationRate > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-50 text-amber-600">
                {{ $cancellationRate }}% cancelaci&oacute;n
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($totalDiscount, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Descuentos aplicados</p>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Revenue Chart --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Ingresos por d&iacute;a</h3>
        <div style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Payment Method Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">M&eacute;todos de pago</h3>
        <div style="height: 260px;" class="flex items-center justify-center">
            <canvas id="paymentChart"></canvas>
        </div>
        <div class="mt-4 space-y-2">
            @php
                $paymentLabels = ['efectivo' => 'Efectivo', 'transferencia' => 'Transferencia', 'yape_plin' => 'Yape / Plin', 'tarjeta' => 'Tarjeta'];
                $paymentColors = ['efectivo' => '#10b981', 'transferencia' => '#6366f1', 'yape_plin' => '#8b5cf6', 'tarjeta' => '#f59e0b'];
            @endphp
            @foreach($byPaymentMethod as $pm)
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $paymentColors[$pm->payment_method] ?? '#94a3b8' }}"></div>
                    <span class="text-gray-600">{{ $paymentLabels[$pm->payment_method] ?? $pm->payment_method }}</span>
                </div>
                <span class="font-semibold text-gray-800">{{ $pm->count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Orders by status --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    {{-- By Status --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Pedidos por estado</h3>
        <div class="space-y-3">
            @php
                $statusLabels = ['pendiente' => 'Pendiente', 'confirmado' => 'Confirmado', 'en_preparacion' => 'En preparaci&oacute;n', 'enviado' => 'Enviado', 'entregado' => 'Entregado', 'cancelado' => 'Cancelado'];
                $statusColors = ['pendiente' => 'bg-yellow-100 text-yellow-700', 'confirmado' => 'bg-blue-100 text-blue-700', 'en_preparacion' => 'bg-indigo-100 text-indigo-700', 'enviado' => 'bg-purple-100 text-purple-700', 'entregado' => 'bg-emerald-100 text-emerald-700', 'cancelado' => 'bg-red-100 text-red-700'];
            @endphp
            @forelse($byStatus as $status)
            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $statusColors[$status->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {!! $statusLabels[$status->status] ?? $status->status !!}
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-gray-800">{{ $status->count }} pedidos</span>
                    <span class="text-xs text-gray-400 ml-2">S/ {{ number_format($status->total, 2) }}</span>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No hay pedidos en este per&iacute;odo</p>
            @endforelse
        </div>
    </div>

    {{-- By Source --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Pedidos por canal</h3>
        <div class="space-y-3">
            @php
                $sourceLabels = ['web' => 'Tienda Web', 'admin' => 'Admin / Manual'];
                $sourceIcons = ['web' => 'fa-globe', 'admin' => 'fa-user-shield'];
                $sourceGradients = ['web' => 'from-blue-500 to-cyan-500', 'admin' => 'from-violet-500 to-purple-500'];
            @endphp
            @forelse($bySource as $source)
            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50/50">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $sourceGradients[$source->source] ?? 'from-gray-400 to-gray-500' }} flex items-center justify-center shadow-sm">
                    <i class="fas {{ $sourceIcons[$source->source] ?? 'fa-circle' }} text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">{{ $sourceLabels[$source->source] ?? $source->source }}</p>
                    <p class="text-xs text-gray-400">{{ $source->count }} pedidos</p>
                </div>
                <p class="text-sm font-bold text-gray-800">S/ {{ number_format($source->total, 2) }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No hay datos</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Top Products --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h3 class="text-sm font-semibold text-gray-800 mb-4">Top 10 productos m&aacute;s vendidos</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="pb-3 pl-3">#</th>
                    <th class="pb-3">Producto</th>
                    <th class="pb-3">SKU</th>
                    <th class="pb-3 text-right">Cantidad</th>
                    <th class="pb-3 text-right pr-3">Ingresos</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($topProducts as $i => $product)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-3 pl-3">
                        <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-50 text-gray-400' }} inline-flex items-center justify-center text-xs font-bold">{{ $i + 1 }}</span>
                    </td>
                    <td class="py-3">
                        <span class="text-sm font-medium text-gray-800">{{ $product->product_name }}</span>
                    </td>
                    <td class="py-3">
                        <span class="text-xs text-gray-400 font-mono">{{ $product->product_sku ?? '-' }}</span>
                    </td>
                    <td class="py-3 text-right">
                        <span class="text-sm font-semibold text-gray-700">{{ number_format($product->total_quantity) }}</span>
                    </td>
                    <td class="py-3 text-right pr-3">
                        <span class="text-sm font-bold text-emerald-600">S/ {{ number_format($product->total_revenue, 2) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-sm text-gray-400">No hay ventas en este per&iacute;odo</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    // Revenue by day chart
    const revenueData = @json($revenueByDay);
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(d => {
                const date = new Date(d.date + 'T00:00:00');
                return date.toLocaleDateString('es-PE', { day: '2-digit', month: 'short' });
            }),
            datasets: [{
                label: 'Ingresos (S/)',
                data: revenueData.map(d => parseFloat(d.revenue)),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 12 },
                    bodyFont: { size: 13, weight: 'bold' },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => `S/ ${ctx.parsed.y.toLocaleString('es-PE', { minimumFractionDigits: 2 })}`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#94a3b8', maxRotation: 45 }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { size: 11 },
                        color: '#94a3b8',
                        callback: v => 'S/ ' + v.toLocaleString()
                    }
                }
            }
        }
    });

    // Payment method chart
    const paymentData = @json($byPaymentMethod);
    const paymentLabels = {
        'efectivo': 'Efectivo',
        'transferencia': 'Transferencia',
        'yape_plin': 'Yape / Plin',
        'tarjeta': 'Tarjeta'
    };
    const paymentColors = {
        'efectivo': '#10b981',
        'transferencia': '#6366f1',
        'yape_plin': '#8b5cf6',
        'tarjeta': '#f59e0b'
    };

    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: paymentData.map(d => paymentLabels[d.payment_method] || d.payment_method),
            datasets: [{
                data: paymentData.map(d => d.count),
                backgroundColor: paymentData.map(d => paymentColors[d.payment_method] || '#94a3b8'),
                borderWidth: 0,
                spacing: 3,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 10,
                }
            }
        }
    });
</script>
@endsection
