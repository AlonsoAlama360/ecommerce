@extends('admin.layouts.app')
@section('title', 'Reporte de Tendencias')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Reporte de Tendencias</h1>
    <p class="text-gray-500 mt-1">Patrones de compra del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
</div>
@include('admin.reports._tabs')
@include('admin.reports._filters')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-chart-line text-white text-sm"></i>
            </div>
            @if($revenueGrowth > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-600">
                <i class="fas fa-arrow-trend-up text-[9px]"></i> +{{ $revenueGrowth }}%
            </span>
            @elseif($revenueGrowth < 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-red-50 text-red-500">
                <i class="fas fa-arrow-trend-down text-[9px]"></i> {{ $revenueGrowth }}%
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}%</h3>
        <p class="text-sm text-gray-400 mt-0.5">Crecimiento ingresos MoM</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-receipt text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $ordersGrowth >= 0 ? '+' : '' }}{{ $ordersGrowth }}%</h3>
        <p class="text-sm text-gray-400 mt-0.5">Crecimiento pedidos MoM</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-clock text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $peakHour ? sprintf('%02d:00', $peakHour->hour) : 'N/A' }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Hora pico</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-calendar-day text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $peakDay->day_name ?? 'N/A' }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">D&iacute;a pico</p>
    </div>
</div>

{{-- Hourly & Daily Charts --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Ventas por hora del d&iacute;a</h3>
        <div style="height: 300px;">
            <canvas id="hourlyChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Ventas por d&iacute;a de la semana</h3>
        <div style="height: 300px;">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>
</div>

{{-- Month Comparison --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <h3 class="text-sm font-semibold text-gray-800 mb-4">Comparativa mensual: {{ $comparison['currentLabel'] }} vs {{ $comparison['previousLabel'] }}</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @php
            $metrics = [
                ['label' => 'Ingresos', 'current' => 'S/ ' . number_format($comparison['current']['revenue'], 2), 'previous' => 'S/ ' . number_format($comparison['previous']['revenue'], 2), 'growth' => $revenueGrowth],
                ['label' => 'Pedidos', 'current' => number_format($comparison['current']['orders']), 'previous' => number_format($comparison['previous']['orders']), 'growth' => $ordersGrowth],
                ['label' => 'Ticket Prom.', 'current' => 'S/ ' . number_format($comparison['current']['avgTicket'], 2), 'previous' => 'S/ ' . number_format($comparison['previous']['avgTicket'], 2), 'growth' => $comparison['previous']['avgTicket'] > 0 ? round((($comparison['current']['avgTicket'] - $comparison['previous']['avgTicket']) / $comparison['previous']['avgTicket']) * 100, 1) : 0],
                ['label' => 'Nuevos Clientes', 'current' => number_format($comparison['current']['newCustomers']), 'previous' => number_format($comparison['previous']['newCustomers']), 'growth' => $comparison['previous']['newCustomers'] > 0 ? round((($comparison['current']['newCustomers'] - $comparison['previous']['newCustomers']) / $comparison['previous']['newCustomers']) * 100, 1) : 0],
            ];
        @endphp

        @foreach($metrics as $metric)
        <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ $metric['label'] }}</p>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-lg font-bold text-gray-900">{{ $metric['current'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Anterior: {{ $metric['previous'] }}</p>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] font-semibold {{ $metric['growth'] >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-500' }}">
                    <i class="fas {{ $metric['growth'] >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-[9px]"></i>
                    {{ $metric['growth'] >= 0 ? '+' : '' }}{{ $metric['growth'] }}%
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Detail Tables --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Detalle por hora</h3>
        <div class="space-y-2 max-h-[400px] overflow-y-auto">
            @foreach($hourlyPattern as $hour)
            <div class="flex items-center justify-between p-2.5 rounded-lg {{ $peakHour && $hour->hour == $peakHour->hour ? 'bg-indigo-50/80' : 'bg-gray-50/50' }}">
                <span class="text-sm font-medium text-gray-700 w-16">{{ sprintf('%02d:00', $hour->hour) }}</span>
                <div class="flex-1 mx-3">
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        @php $maxOrders = $hourlyPattern->max('orders_count'); @endphp
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full" style="width: {{ $maxOrders > 0 ? ($hour->orders_count / $maxOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-gray-800">{{ $hour->orders_count }}</span>
                    <span class="text-xs text-gray-400 ml-1">S/ {{ number_format($hour->revenue, 0) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Detalle por d&iacute;a</h3>
        <div class="space-y-3">
            @foreach($dailyPattern as $day)
            <div class="flex items-center justify-between p-3 rounded-xl {{ $peakDay && $day->day_number == $peakDay->day_number ? 'bg-indigo-50/80' : 'bg-gray-50/50' }}">
                <span class="text-sm font-medium text-gray-700 w-24">{{ $day->day_name }}</span>
                <div class="flex-1 mx-3">
                    <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                        @php $maxDayOrders = $dailyPattern->max('orders_count'); @endphp
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full" style="width: {{ $maxDayOrders > 0 ? ($day->orders_count / $maxDayOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-gray-800">{{ $day->orders_count }} pedidos</span>
                    <span class="text-xs text-gray-400 block">S/ {{ number_format($day->revenue, 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const hourlyData = @json($hourlyPattern);
    const dailyData = @json($dailyPattern);

    // Fill missing hours (0-23)
    const allHours = Array.from({length: 24}, (_, i) => {
        const found = hourlyData.find(d => d.hour === i);
        return { hour: i, orders_count: found ? found.orders_count : 0, revenue: found ? parseFloat(found.revenue) : 0 };
    });

    new Chart(document.getElementById('hourlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: allHours.map(d => `${String(d.hour).padStart(2, '0')}:00`),
            datasets: [{
                label: 'Pedidos',
                data: allHours.map(d => d.orders_count),
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: '#6366f1',
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 10 }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#94a3b8', maxRotation: 45 } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 1 } }
            }
        }
    });

    new Chart(document.getElementById('dailyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: dailyData.map(d => d.day_name),
            datasets: [{
                label: 'Pedidos',
                data: dailyData.map(d => d.orders_count),
                backgroundColor: 'rgba(139, 92, 246, 0.7)',
                borderColor: '#8b5cf6',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 50,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 10 }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 12 }, color: '#475569' } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 1 } }
            }
        }
    });
</script>
@endsection
