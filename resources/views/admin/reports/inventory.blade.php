@extends('admin.layouts.app')
@section('title', 'Reporte de Inventario')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Reporte de Inventario</h1>
    <p class="text-gray-500 mt-1">Control de existencias del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
</div>
@include('admin.reports._tabs')
@include('admin.reports._filters')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-warehouse text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">S/ {{ number_format($inventoryValue, 2) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Valor del inventario</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-box text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalActiveProducts) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Productos activos</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fas fa-rotate text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $turnoverRate }}x</h3>
        <p class="text-sm text-gray-400 mt-0.5">Tasa de rotaci&oacute;n</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                <i class="fas fa-skull-crossbones text-white text-sm"></i>
            </div>
            @if($deadStockCount > 0)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-red-50 text-red-500">
                <i class="fas fa-exclamation text-[9px]"></i> Alerta
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($deadStockCount) }}</h3>
        <p class="text-sm text-gray-400 mt-0.5">Stock muerto (60+ d&iacute;as)</p>
    </div>
</div>

{{-- Movements Chart + Top by Value --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Movimientos por tipo</h3>
        <div class="flex items-center justify-center mb-6" style="height: 200px;">
            <canvas id="movementsChart"></canvas>
        </div>
        @php
            $typeLabels = ['entrada' => 'Entradas', 'salida' => 'Salidas', 'ajuste' => 'Ajustes'];
            $typeColors = ['entrada' => '#10b981', 'salida' => '#f43f5e', 'ajuste' => '#f59e0b'];
        @endphp
        <div class="space-y-2">
            @foreach($movementsByType as $mov)
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $typeColors[$mov->type] ?? '#94a3b8' }}"></div>
                    <span class="text-gray-600">{{ $typeLabels[$mov->type] ?? $mov->type }}</span>
                </div>
                <div>
                    <span class="font-semibold text-gray-800">{{ number_format($mov->total_units) }} uds</span>
                    <span class="text-xs text-gray-400 ml-1">({{ $mov->count }})</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Top 10 por valor de inventario</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="pb-3 pl-3">#</th>
                        <th class="pb-3">Producto</th>
                        <th class="pb-3">SKU</th>
                        <th class="pb-3 text-right">Stock</th>
                        <th class="pb-3 text-right">Precio</th>
                        <th class="pb-3 text-right pr-3">Valor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($topByValue as $i => $product)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-3 pl-3">
                            <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-50 text-gray-400' }} inline-flex items-center justify-center text-xs font-bold">{{ $i + 1 }}</span>
                        </td>
                        <td class="py-3"><span class="text-sm font-medium text-gray-800">{{ $product->name }}</span></td>
                        <td class="py-3"><span class="text-xs text-gray-400 font-mono">{{ $product->sku ?? '-' }}</span></td>
                        <td class="py-3 text-right"><span class="text-sm font-semibold text-gray-700">{{ number_format($product->stock) }}</span></td>
                        <td class="py-3 text-right"><span class="text-sm text-gray-500">S/ {{ number_format($product->unit_price, 2) }}</span></td>
                        <td class="py-3 text-right pr-3"><span class="text-sm font-bold text-emerald-600">S/ {{ number_format($product->inventory_value, 2) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-8 text-center text-sm text-gray-400">No hay productos con stock</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Dead Stock Alert --}}
@if($deadStock->count() > 0)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
            <i class="fas fa-skull-crossbones text-red-500 text-sm"></i>
        </div>
        <h3 class="text-sm font-semibold text-gray-800">Stock muerto</h3>
        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-red-50 text-red-600">{{ $deadStock->count() }} productos sin ventas en 60+ d&iacute;as</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="pb-3 pl-3">Producto</th>
                    <th class="pb-3 text-center">Stock</th>
                    <th class="pb-3 text-right pr-3">&Uacute;ltima venta</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($deadStock as $product)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-3 pl-3">
                        <div class="flex items-center gap-3">
                            @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="" class="w-9 h-9 rounded-lg object-cover">
                            @else
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center"><i class="fas fa-image text-gray-300 text-xs"></i></div>
                            @endif
                            <span class="text-sm font-medium text-gray-800">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600">{{ $product->stock }}</span>
                    </td>
                    <td class="py-3 text-right pr-3">
                        <span class="text-sm text-gray-500">{{ $product->last_sale_date ? \Carbon\Carbon::parse($product->last_sale_date)->format('d/m/Y') : 'Nunca' }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Fast Movers + Slow Movers --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">R&aacute;pido movimiento (Fast Movers)</h3>
        <div class="space-y-3">
            @forelse($fastMovers as $i => $product)
            <div class="flex items-center gap-3 p-3 rounded-xl {{ $i < 3 ? 'bg-emerald-50/50' : 'bg-gray-50/50' }}">
                <span class="w-7 h-7 rounded-lg {{ $i < 3 ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->daily_rate }} uds/d&iacute;a</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-gray-800">{{ number_format($product->units_sold) }} uds</p>
                    @if($product->days_of_stock !== null)
                    <p class="text-xs {{ $product->days_of_stock < 15 ? 'text-red-500' : 'text-gray-400' }} font-semibold">{{ $product->days_of_stock }}d stock</p>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No hay ventas en este per&iacute;odo</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Lento movimiento (Slow Movers)</h3>
        <div class="space-y-3">
            @forelse($slowMovers as $i => $product)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50/50">
                <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-gray-400">Stock: {{ $product->stock }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-gray-800">{{ number_format($product->units_sold) }} uds vendidas</p>
                    @if($product->days_of_stock !== null)
                    <p class="text-xs text-amber-500 font-semibold">{{ number_format($product->days_of_stock) }}d stock</p>
                    @else
                    <p class="text-xs text-red-500 font-semibold">Sin movimiento</p>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No hay productos con stock</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const movData = @json($movementsByType);
    const typeLabels = { 'entrada': 'Entradas', 'salida': 'Salidas', 'ajuste': 'Ajustes' };
    const typeColors = { 'entrada': '#10b981', 'salida': '#f43f5e', 'ajuste': '#f59e0b' };

    new Chart(document.getElementById('movementsChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: movData.map(d => typeLabels[d.type] || d.type),
            datasets: [{
                data: movData.map(d => d.total_units),
                backgroundColor: movData.map(d => typeColors[d.type] || '#94a3b8'),
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
                tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 10 }
            }
        }
    });
</script>
@endsection
