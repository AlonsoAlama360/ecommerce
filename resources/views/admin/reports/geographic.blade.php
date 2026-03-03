@extends('admin.layouts.app')
@section('title', 'Reporte Geográfico')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Reporte Geogr&aacute;fico</h1>
    <p class="text-gray-500 mt-1">Distribuci&oacute;n geogr&aacute;fica del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
</div>
@include('admin.reports._tabs')
@include('admin.reports._filters')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-map-location-dot text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $departmentsWithOrders }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Departamentos con pedidos</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-trophy text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $topDepartment->department ?? 'N/A' }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Departamento l&iacute;der</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-tag text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($avgTicketNational, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Ticket promedio nacional</p>
    </div>
</div>

{{-- Revenue by Department Chart --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <h3 class="text-sm font-semibold text-gray-800 mb-4">Ingresos por departamento</h3>
    <div style="height: 400px;">
        <canvas id="departmentRevenueChart"></canvas>
    </div>
</div>

{{-- Department Sales Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <h3 class="text-sm font-semibold text-gray-800 mb-4">Detalle por departamento</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="pb-3 pl-3">#</th>
                    <th class="pb-3">Departamento</th>
                    <th class="pb-3 text-right">Pedidos</th>
                    <th class="pb-3 text-right">Clientes</th>
                    <th class="pb-3 text-right">Ingresos</th>
                    <th class="pb-3 text-right">Ticket Prom.</th>
                    <th class="pb-3 text-right pr-3">% del total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($departmentSales as $i => $dept)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-3 pl-3">
                        <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-50 text-gray-400' }} inline-flex items-center justify-center text-xs font-bold">{{ $i + 1 }}</span>
                    </td>
                    <td class="py-3"><span class="text-sm font-medium text-gray-800">{{ $dept->department }}</span></td>
                    <td class="py-3 text-right">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-600">{{ number_format($dept->orders_count) }}</span>
                    </td>
                    <td class="py-3 text-right"><span class="text-sm text-gray-600">{{ number_format($dept->customers_count) }}</span></td>
                    <td class="py-3 text-right"><span class="text-sm font-bold text-emerald-600">S/ {{ number_format($dept->revenue, 2) }}</span></td>
                    <td class="py-3 text-right"><span class="text-sm text-gray-600">S/ {{ number_format($dept->avg_ticket, 2) }}</span></td>
                    <td class="py-3 text-right pr-3">
                        <span class="text-sm font-semibold text-gray-700">{{ $totalRevenue > 0 ? number_format(($dept->revenue / $totalRevenue) * 100, 1) : 0 }}%</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-sm text-gray-400">No hay datos geogr&aacute;ficos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Customer Distribution --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h3 class="text-sm font-semibold text-gray-800 mb-4">Clientes por departamento</h3>
    <div class="space-y-3">
        @php $maxCustomers = $customerDistribution->max('count'); @endphp
        @forelse($customerDistribution as $dept)
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-sm text-gray-600">{{ $dept->department }}</span>
                <span class="text-sm font-bold text-gray-800">{{ number_format($dept->count) }}</span>
            </div>
            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full transition-all" style="width: {{ $maxCustomers > 0 ? ($dept->count / $maxCustomers) * 100 : 0 }}%"></div>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-400 text-center py-8">No hay datos de ubicaci&oacute;n</p>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const deptData = @json($departmentSales);

    new Chart(document.getElementById('departmentRevenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: deptData.map(d => d.department),
            datasets: [{
                label: 'Ingresos (S/)',
                data: deptData.map(d => parseFloat(d.revenue)),
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: '#6366f1',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 50,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => `S/ ${ctx.parsed.x.toLocaleString('es-PE', { minimumFractionDigits: 2 })}`
                    }
                }
            },
            scales: {
                y: { grid: { display: false }, ticks: { font: { size: 12 }, color: '#475569' } },
                x: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11 }, color: '#94a3b8', callback: v => 'S/ ' + v.toLocaleString() }
                }
            }
        }
    });
</script>
@endsection
