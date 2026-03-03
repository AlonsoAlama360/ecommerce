@extends('admin.layouts.app')
@section('title', 'Reporte de Compras')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Reporte de Compras</h1>
    <p class="text-gray-500 mt-1">Inversiones y proveedores del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
</div>
@include('admin.reports._tabs')
@include('admin.reports._filters')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    {{-- Total Spending --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center shadow-lg shadow-rose-500/20">
                <i class="fas fa-file-invoice-dollar text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($totalSpending, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Gasto total</p>
    </div>

    {{-- Total Purchases --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-cart-shopping text-white text-sm"></i>
            </div>
            @if($cancelledPurchases > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-red-50 text-red-500">
                {{ $cancelledPurchases }} canceladas
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalPurchases) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Compras totales</p>
    </div>

    {{-- Average Purchase --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-calculator text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($avgPurchase, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Compra promedio</p>
    </div>

    {{-- Pending --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-clock text-white text-sm"></i>
            </div>
            @if($pendingPurchases > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-50 text-amber-600">
                <i class="fas fa-hourglass-half text-[9px]"></i> Pendiente
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($pendingPurchases) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Compras pendientes</p>
    </div>
</div>

{{-- Chart + Status --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    {{-- Spending Chart --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Gasto por d&iacute;a</h3>
        <div style="height: 300px;">
            <canvas id="spendingChart"></canvas>
        </div>
    </div>

    {{-- By Status --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Compras por estado</h3>
        <div class="space-y-3">
            @php
                $statusLabels = ['pendiente' => 'Pendiente', 'aprobado' => 'Aprobado', 'en_transito' => 'En tr&aacute;nsito', 'recibido' => 'Recibido', 'cancelado' => 'Cancelado'];
                $statusColors = ['pendiente' => 'bg-amber-100 text-amber-700', 'aprobado' => 'bg-blue-100 text-blue-700', 'en_transito' => 'bg-violet-100 text-violet-700', 'recibido' => 'bg-emerald-100 text-emerald-700', 'cancelado' => 'bg-red-100 text-red-700'];
            @endphp
            @forelse($byStatus as $status)
            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/50">
                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $statusColors[$status->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {!! $statusLabels[$status->status] ?? $status->status !!}
                </span>
                <div class="text-right">
                    <span class="text-sm font-bold text-gray-800">{{ $status->count }}</span>
                    <span class="text-xs text-gray-400 ml-2">S/ {{ number_format($status->total, 2) }}</span>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No hay compras en este per&iacute;odo</p>
            @endforelse
        </div>

        @if($totalTax > 0)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Impuestos totales</span>
                <span class="text-sm font-bold text-gray-800">S/ {{ number_format($totalTax, 2) }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Top Suppliers + Top Products --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    {{-- Top Suppliers --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Top proveedores</h3>
        <div class="space-y-3">
            @forelse($topSuppliers as $i => $supplier)
            <div class="flex items-center gap-3 p-3 rounded-xl {{ $i < 3 ? 'bg-indigo-50/50' : 'bg-gray-50/50' }}">
                <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $supplier->business_name }}</p>
                    <p class="text-xs text-gray-400">{{ $supplier->purchases_count }} compras</p>
                </div>
                <span class="text-sm font-bold text-gray-800 flex-shrink-0">S/ {{ number_format($supplier->total_amount, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No hay datos de proveedores</p>
            @endforelse
        </div>
    </div>

    {{-- Top Products Purchased --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Productos m&aacute;s comprados</h3>
        <div class="space-y-3">
            @forelse($topProductsPurchased as $i => $product)
            <div class="flex items-center gap-3 p-3 rounded-xl {{ $i < 3 ? 'bg-violet-50/50' : 'bg-gray-50/50' }}">
                <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-violet-100 text-violet-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $product->product_name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->product_sku ?? '-' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-gray-800">{{ number_format($product->total_quantity) }} uds</p>
                    <p class="text-xs text-rose-500 font-semibold">S/ {{ number_format($product->total_cost, 2) }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No hay compras en este per&iacute;odo</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Pending Deliveries --}}
@if($pendingDeliveries->count() > 0)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
            <i class="fas fa-truck text-amber-500 text-sm"></i>
        </div>
        <h3 class="text-sm font-semibold text-gray-800">Entregas pendientes</h3>
        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-600">{{ $pendingDeliveries->count() }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="pb-3 pl-3">N&deg; Compra</th>
                    <th class="pb-3">Proveedor</th>
                    <th class="pb-3 text-center">Estado</th>
                    <th class="pb-3 text-center">Fecha esperada</th>
                    <th class="pb-3 text-right pr-3">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($pendingDeliveries as $purchase)
                @php
                    $isOverdue = $purchase->expected_date && \Carbon\Carbon::parse($purchase->expected_date)->isPast();
                @endphp
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-3 pl-3">
                        <span class="text-sm font-semibold text-indigo-600">{{ $purchase->purchase_number }}</span>
                    </td>
                    <td class="py-3">
                        <span class="text-sm text-gray-700">{{ $purchase->supplier->business_name ?? 'Sin proveedor' }}</span>
                    </td>
                    <td class="py-3 text-center">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $statusColors[$purchase->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {!! $statusLabels[$purchase->status] ?? $purchase->status !!}
                        </span>
                    </td>
                    <td class="py-3 text-center">
                        @if($purchase->expected_date)
                        <span class="text-sm {{ $isOverdue ? 'text-red-500 font-semibold' : 'text-gray-600' }}">
                            {{ \Carbon\Carbon::parse($purchase->expected_date)->format('d/m/Y') }}
                            @if($isOverdue)
                            <i class="fas fa-exclamation-circle text-xs ml-1"></i>
                            @endif
                        </span>
                        @else
                        <span class="text-xs text-gray-400">Sin fecha</span>
                        @endif
                    </td>
                    <td class="py-3 text-right pr-3">
                        <span class="text-sm font-bold text-gray-800">S/ {{ number_format($purchase->total, 2) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Recent Purchases --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h3 class="text-sm font-semibold text-gray-800 mb-4">&Uacute;ltimas compras</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="pb-3 pl-3">N&deg; Compra</th>
                    <th class="pb-3">Proveedor</th>
                    <th class="pb-3 text-center">Estado</th>
                    <th class="pb-3 text-center">Fecha</th>
                    <th class="pb-3 text-right">Subtotal</th>
                    <th class="pb-3 text-right pr-3">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentPurchases as $purchase)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-3 pl-3">
                        <a href="{{ route('admin.purchases.show', $purchase) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">{{ $purchase->purchase_number }}</a>
                    </td>
                    <td class="py-3">
                        <span class="text-sm text-gray-700">{{ $purchase->supplier->business_name ?? 'Sin proveedor' }}</span>
                    </td>
                    <td class="py-3 text-center">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $statusColors[$purchase->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {!! $statusLabels[$purchase->status] ?? $purchase->status !!}
                        </span>
                    </td>
                    <td class="py-3 text-center">
                        <span class="text-sm text-gray-600">{{ $purchase->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="py-3 text-right">
                        <span class="text-sm text-gray-500">S/ {{ number_format($purchase->subtotal, 2) }}</span>
                    </td>
                    <td class="py-3 text-right pr-3">
                        <span class="text-sm font-bold text-gray-800">S/ {{ number_format($purchase->total, 2) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-sm text-gray-400">No hay compras en este per&iacute;odo</td>
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
    const spendingData = @json($spendingByDay);
    const spendingCtx = document.getElementById('spendingChart').getContext('2d');

    new Chart(spendingCtx, {
        type: 'line',
        data: {
            labels: spendingData.map(d => {
                const date = new Date(d.date + 'T00:00:00');
                return date.toLocaleDateString('es-PE', { day: '2-digit', month: 'short' });
            }),
            datasets: [{
                label: 'Gasto (S/)',
                data: spendingData.map(d => parseFloat(d.spending)),
                borderColor: '#e11d48',
                backgroundColor: 'rgba(225, 29, 72, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#e11d48',
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
</script>
@endsection
