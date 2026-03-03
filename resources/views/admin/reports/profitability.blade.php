@extends('admin.layouts.app')
@section('title', 'Reporte de Rentabilidad')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Reporte de Rentabilidad</h1>
    <p class="text-gray-500 mt-1">An&aacute;lisis de m&aacute;rgenes del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
</div>
@include('admin.reports._tabs')
@include('admin.reports._filters')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-dollar-sign text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($totalRevenue, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Ingresos totales</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center shadow-lg shadow-rose-500/20">
                <i class="fas fa-file-invoice-dollar text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($totalCost, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Costos totales</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-coins text-white text-sm"></i>
            </div>
            @if($grossProfit > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-600">
                <i class="fas fa-arrow-trend-up text-[9px]"></i> Ganancia
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($grossProfit, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Ganancia bruta</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-percent text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $profitMargin }}%</h3>
        <p class="text-sm text-gray-400 mt-0.5">Margen de ganancia</p>
    </div>
</div>

{{-- Revenue vs Cost Chart + Category Profitability --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Ingresos vs Costos por mes</h3>
        <div style="height: 300px;">
            <canvas id="revenueVsCostChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Ingresos por categor&iacute;a</h3>
        <div class="space-y-3">
            @php $maxRevenue = $categoryProfitability->max('revenue'); @endphp
            @forelse($categoryProfitability as $cat)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm text-gray-600">{{ $cat->category }}</span>
                    <span class="text-sm font-bold text-gray-800">S/ {{ number_format($cat->revenue, 2) }}</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full transition-all" style="width: {{ $maxRevenue > 0 ? ($cat->revenue / $maxRevenue) * 100 : 0 }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No hay datos de categor&iacute;as</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Top Profitable Products --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h3 class="text-sm font-semibold text-gray-800 mb-4">Top 10 productos m&aacute;s rentables</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="pb-3 pl-3">#</th>
                    <th class="pb-3">Producto</th>
                    <th class="pb-3 text-right">Uds vendidas</th>
                    <th class="pb-3 text-right">Ingreso</th>
                    <th class="pb-3 text-right">Costo</th>
                    <th class="pb-3 text-right">Ganancia</th>
                    <th class="pb-3 text-right pr-3">Margen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($productProfitability as $i => $product)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-3 pl-3">
                        <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-50 text-gray-400' }} inline-flex items-center justify-center text-xs font-bold">{{ $i + 1 }}</span>
                    </td>
                    <td class="py-3">
                        <span class="text-sm font-medium text-gray-800">{{ $product->product_name }}</span>
                    </td>
                    <td class="py-3 text-right">
                        <span class="text-sm text-gray-600">{{ number_format($product->units_sold) }}</span>
                    </td>
                    <td class="py-3 text-right">
                        <span class="text-sm text-emerald-600 font-semibold">S/ {{ number_format($product->total_revenue, 2) }}</span>
                    </td>
                    <td class="py-3 text-right">
                        <span class="text-sm text-rose-500 font-semibold">S/ {{ number_format($product->total_cost, 2) }}</span>
                    </td>
                    <td class="py-3 text-right">
                        <span class="text-sm font-bold {{ $product->profit >= 0 ? 'text-emerald-600' : 'text-red-500' }}">S/ {{ number_format($product->profit, 2) }}</span>
                    </td>
                    <td class="py-3 text-right pr-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $product->margin >= 30 ? 'bg-emerald-50 text-emerald-600' : ($product->margin >= 15 ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-600') }}">
                            {{ $product->margin }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-sm text-gray-400">No hay datos de ventas en este per&iacute;odo</td>
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
    const monthData = @json($revenueVsCostByMonth);
    const ctx = document.getElementById('revenueVsCostChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthData.map(d => {
                const [y, m] = d.month.split('-');
                return new Date(y, m - 1).toLocaleDateString('es-PE', { month: 'short', year: '2-digit' });
            }),
            datasets: [{
                label: 'Ingresos',
                data: monthData.map(d => d.revenue),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }, {
                label: 'Costos',
                data: monthData.map(d => d.cost),
                borderColor: '#f43f5e',
                backgroundColor: 'rgba(244, 63, 94, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#f43f5e',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: { usePointStyle: true, pointStyle: 'circle', padding: 20, font: { size: 12 } }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: S/ ${ctx.parsed.y.toLocaleString('es-PE', { minimumFractionDigits: 2 })}`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#94a3b8' } },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11 }, color: '#94a3b8', callback: v => 'S/ ' + v.toLocaleString() }
                }
            }
        }
    });
</script>
@endsection
