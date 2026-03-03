@extends('admin.layouts.app')
@section('title', 'Reporte de Clientes')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Reporte de Clientes</h1>
    <p class="text-gray-500 mt-1">Comportamiento de clientes del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
</div>
@include('admin.reports._tabs')
@include('admin.reports._filters')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-users text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Total clientes</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-user-plus text-white text-sm"></i>
            </div>
            @if($newCustomers > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-600">
                <i class="fas fa-arrow-trend-up text-[9px]"></i> Nuevo
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($newCustomers) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Nuevos en el per&iacute;odo</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-bag-shopping text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($withOrders) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Con pedidos realizados</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-envelope text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($newsletterSubs) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Suscritos al newsletter</p>
    </div>
</div>

{{-- Registration Chart + Recurring Stats --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Registration Chart --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Nuevos registros por d&iacute;a</h3>
        <div style="height: 300px;">
            <canvas id="registrationsChart"></canvas>
        </div>
    </div>

    {{-- Recurring vs Unique --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Tipo de cliente</h3>

        @php
            $totalWithPurchases = $customersWithMultipleOrders + $customersWithOneOrder;
            $recurringPct = $totalWithPurchases > 0 ? round(($customersWithMultipleOrders / $totalWithPurchases) * 100, 1) : 0;
        @endphp

        <div class="flex items-center justify-center mb-6" style="height: 180px;">
            <canvas id="customerTypeChart"></canvas>
        </div>

        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 rounded-xl bg-indigo-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                    <span class="text-sm text-gray-600">Recurrentes</span>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-gray-800">{{ number_format($customersWithMultipleOrders) }}</span>
                    <span class="text-xs text-gray-400 ml-1">({{ $recurringPct }}%)</span>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                    <span class="text-sm text-gray-600">&Uacute;nica compra</span>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-gray-800">{{ number_format($customersWithOneOrder) }}</span>
                    <span class="text-xs text-gray-400 ml-1">({{ $totalWithPurchases > 0 ? round(100 - $recurringPct, 1) : 0 }}%)</span>
                </div>
            </div>
            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/50">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-gray-200"></div>
                    <span class="text-sm text-gray-600">Sin compras</span>
                </div>
                <span class="text-sm font-bold text-gray-800">{{ number_format($totalCustomers - $totalWithPurchases) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Top Buyers + Top Departments --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Top Buyers --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Top 10 compradores</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="pb-3 pl-3">#</th>
                        <th class="pb-3">Cliente</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3 text-center">Pedidos</th>
                        <th class="pb-3 text-right pr-3">Total gastado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($topBuyers as $i => $buyer)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-3 pl-3">
                            <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-50 text-gray-400' }} inline-flex items-center justify-center text-xs font-bold">{{ $i + 1 }}</span>
                        </td>
                        <td class="py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0">
                                    {{ strtoupper(substr($buyer->first_name, 0, 1)) }}{{ strtoupper(substr($buyer->last_name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $buyer->full_name }}</span>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="text-xs text-gray-400">{{ $buyer->email }}</span>
                        </td>
                        <td class="py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-600">{{ $buyer->orders_count }}</span>
                        </td>
                        <td class="py-3 text-right pr-3">
                            <span class="text-sm font-bold text-emerald-600">S/ {{ number_format($buyer->orders_sum_total, 2) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-sm text-gray-400">No hay compradores en este per&iacute;odo</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Departments --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Clientes por departamento</h3>
        <div class="space-y-3">
            @forelse($topDepartments as $dept)
            @php
                $maxCount = $topDepartments->max('count');
                $pct = $maxCount > 0 ? ($dept->count / $maxCount) * 100 : 0;
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-sm text-gray-600">{{ $dept->name }}</span>
                    <span class="text-sm font-bold text-gray-800">{{ $dept->count }}</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No hay datos de ubicaci&oacute;n</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    // Registrations by day chart
    const regData = @json($registrationsByDay);
    const regCtx = document.getElementById('registrationsChart').getContext('2d');

    new Chart(regCtx, {
        type: 'bar',
        data: {
            labels: regData.map(d => {
                const date = new Date(d.date + 'T00:00:00');
                return date.toLocaleDateString('es-PE', { day: '2-digit', month: 'short' });
            }),
            datasets: [{
                label: 'Registros',
                data: regData.map(d => d.count),
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: '#6366f1',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 40,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 10,
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
                        stepSize: 1,
                    }
                }
            }
        }
    });

    // Customer type doughnut chart
    const typeCtx = document.getElementById('customerTypeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Recurrentes', 'Única compra', 'Sin compras'],
            datasets: [{
                data: [
                    {{ $customersWithMultipleOrders }},
                    {{ $customersWithOneOrder }},
                    {{ $totalCustomers - $customersWithMultipleOrders - $customersWithOneOrder }}
                ],
                backgroundColor: ['#6366f1', '#94a3b8', '#e2e8f0'],
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
