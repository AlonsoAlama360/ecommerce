@php
    $currentRoute = request()->route()->getName();
@endphp
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route($currentRoute) }}" class="flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Desde</label>
            <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-300 outline-none">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Hasta</label>
            <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-300 outline-none">
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-500 text-white text-sm font-medium rounded-xl hover:bg-indigo-600 transition shadow-sm">
            <i class="fas fa-filter mr-1.5 text-xs"></i> Filtrar
        </button>
        <div class="flex gap-1.5 ml-auto">
            @php
                $quickFilters = [
                    ['label' => '7 d', 'from' => now()->subDays(7)->toDateString()],
                    ['label' => '30 d', 'from' => now()->subDays(30)->toDateString()],
                    ['label' => '90 d', 'from' => now()->subDays(90)->toDateString()],
                    ['label' => 'Este año', 'from' => now()->startOfYear()->toDateString()],
                ];
            @endphp
            @foreach($quickFilters as $qf)
                <a href="{{ route($currentRoute, ['from' => $qf['from'], 'to' => now()->toDateString()]) }}"
                    class="px-3 py-2 text-xs font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition {{ $from == $qf['from'] ? 'bg-indigo-50 border-indigo-200 text-indigo-600' : 'text-gray-500' }}">
                    {{ $qf['label'] }}
                </a>
            @endforeach
        </div>
    </form>
</div>
