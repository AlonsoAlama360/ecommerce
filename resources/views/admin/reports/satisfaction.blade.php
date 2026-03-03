@extends('admin.layouts.app')
@section('title', 'Reporte de Satisfacción')

@section('content')
{{-- Header --}}
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Reporte de Satisfacci&oacute;n</h1>
    <p class="text-gray-500 mt-1">Rese&ntilde;as y quejas del {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</p>
</div>
@include('admin.reports._tabs')
@include('admin.reports._filters')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="fas fa-star text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $avgRating }} <span class="text-sm text-gray-400 font-normal">/ 5</span></h3>
        <p class="text-sm text-gray-400 mt-0.5">Rating promedio ({{ $totalReviews }} rese&ntilde;as)</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-check-circle text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $approvalRate }}%</h3>
        <p class="text-sm text-gray-400 mt-0.5">Tasa de aprobaci&oacute;n</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                <i class="fas fa-triangle-exclamation text-white text-sm"></i>
            </div>
            @if($complaintRate > 5)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-red-50 text-red-500">
                <i class="fas fa-exclamation text-[9px]"></i> Alto
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $complaintRate }}%</h3>
        <p class="text-sm text-gray-400 mt-0.5">Tasa de quejas ({{ $totalComplaints }})</p>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-clock text-white text-sm"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-gray-900">{{ $avgResolutionDays }} d&iacute;as</h3>
        <p class="text-sm text-gray-400 mt-0.5">Tiempo prom. resoluci&oacute;n</p>
    </div>
</div>

{{-- Rating Distribution + Rating Trend --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Distribuci&oacute;n de calificaciones</h3>
        <div style="height: 260px;">
            <canvas id="ratingDistChart"></canvas>
        </div>
        <div class="mt-4 space-y-2">
            @php $totalRatingCount = array_sum($ratingDistribution->toArray()); @endphp
            @foreach($ratingDistribution->reverse() as $stars => $count)
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-0.5 w-20">
                    @for($s = 0; $s < $stars; $s++)
                    <i class="fas fa-star text-amber-400 text-[10px]"></i>
                    @endfor
                    @for($s = $stars; $s < 5; $s++)
                    <i class="fas fa-star text-gray-200 text-[10px]"></i>
                    @endfor
                </div>
                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-400 rounded-full" style="width: {{ $totalRatingCount > 0 ? ($count / $totalRatingCount) * 100 : 0 }}%"></div>
                </div>
                <span class="text-xs font-semibold text-gray-600 w-8 text-right">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Tendencia del rating por mes</h3>
        <div style="height: 300px;">
            <canvas id="ratingTrendChart"></canvas>
        </div>
    </div>
</div>

{{-- Complaint Types + Contact Volume --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Tipos de queja</h3>
        @if($complaintTypes->count() > 0)
        <div class="flex items-center justify-center mb-6" style="height: 200px;">
            <canvas id="complaintTypesChart"></canvas>
        </div>
        <div class="space-y-2">
            @php
                $complaintColors = ['#6366f1', '#f43f5e', '#f59e0b', '#10b981', '#8b5cf6', '#06b6d4', '#ec4899'];
            @endphp
            @foreach($complaintTypes as $i => $type)
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $complaintColors[$i % count($complaintColors)] }}"></div>
                    <span class="text-gray-600">{{ $type->complaint_type ?? 'Sin tipo' }}</span>
                </div>
                <span class="font-semibold text-gray-800">{{ $type->count }}</span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-400 text-center py-8">No hay quejas en este per&iacute;odo</p>
        @endif
    </div>

    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Volumen de mensajes de contacto por mes</h3>
        <div style="height: 300px;">
            <canvas id="contactVolumeChart"></canvas>
        </div>
    </div>
</div>

{{-- Pending Complaints --}}
@if($pendingComplaints->count() > 0)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
            <i class="fas fa-clock text-red-500 text-sm"></i>
        </div>
        <h3 class="text-sm font-semibold text-gray-800">Quejas pendientes antiguas (+7 d&iacute;as)</h3>
        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-red-50 text-red-600">{{ $pendingComplaints->count() }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="pb-3 pl-3">N&deg; Queja</th>
                    <th class="pb-3">Tipo</th>
                    <th class="pb-3 text-center">Estado</th>
                    <th class="pb-3 text-center">D&iacute;as abierta</th>
                    <th class="pb-3 text-right pr-3">Fecha</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($pendingComplaints as $complaint)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-3 pl-3"><span class="text-sm font-semibold text-indigo-600">{{ $complaint->complaint_number }}</span></td>
                    <td class="py-3"><span class="text-sm text-gray-700">{{ $complaint->complaint_type ?? 'Sin tipo' }}</span></td>
                    <td class="py-3 text-center">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700">{{ $complaint->status ?? 'Pendiente' }}</span>
                    </td>
                    <td class="py-3 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $complaint->days_open > 14 ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $complaint->days_open }}d
                        </span>
                    </td>
                    <td class="py-3 text-right pr-3"><span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($complaint->created_at)->format('d/m/Y') }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    // Rating distribution
    const ratingDist = @json($ratingDistribution);
    new Chart(document.getElementById('ratingDistChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(ratingDist).map(k => k + ' estrella' + (k > 1 ? 's' : '')),
            datasets: [{
                data: Object.values(ratingDist),
                backgroundColor: ['#ef4444', '#f97316', '#f59e0b', '#84cc16', '#10b981'],
                borderWidth: 0,
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 40,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 10 } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#94a3b8' } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 1 } }
            }
        }
    });

    // Rating trend
    const ratingTrend = @json($ratingTrend);
    new Chart(document.getElementById('ratingTrendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: ratingTrend.map(d => {
                const [y, m] = d.month.split('-');
                return new Date(y, m - 1).toLocaleDateString('es-PE', { month: 'short', year: '2-digit' });
            }),
            datasets: [{
                label: 'Rating promedio',
                data: ratingTrend.map(d => parseFloat(d.avg_rating)),
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#f59e0b',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 10, callbacks: { label: ctx => `Rating: ${ctx.parsed.y.toFixed(1)}` } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#94a3b8' } },
                y: { min: 0, max: 5, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 1 } }
            }
        }
    });

    // Complaint types doughnut
    const complaintData = @json($complaintTypes);
    const complaintColors = ['#6366f1', '#f43f5e', '#f59e0b', '#10b981', '#8b5cf6', '#06b6d4', '#ec4899'];
    if (complaintData.length > 0) {
        new Chart(document.getElementById('complaintTypesChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: complaintData.map(d => d.complaint_type || 'Sin tipo'),
                datasets: [{
                    data: complaintData.map(d => d.count),
                    backgroundColor: complaintData.map((_, i) => complaintColors[i % complaintColors.length]),
                    borderWidth: 0, spacing: 3, borderRadius: 4,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '68%',
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 10 } }
            }
        });
    }

    // Contact volume
    const contactData = @json($contactVolume);
    new Chart(document.getElementById('contactVolumeChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: contactData.map(d => {
                const [y, m] = d.month.split('-');
                return new Date(y, m - 1).toLocaleDateString('es-PE', { month: 'short', year: '2-digit' });
            }),
            datasets: [{
                label: 'Mensajes',
                data: contactData.map(d => d.count),
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
            plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 10 } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#94a3b8' } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 }, color: '#94a3b8', stepSize: 1 } }
            }
        }
    });
</script>
@endsection
