@php
    $tabs = [
        ['route' => 'admin.reports.sales', 'label' => 'Ventas', 'icon' => 'fa-dollar-sign', 'color' => 'emerald'],
        ['route' => 'admin.reports.products', 'label' => 'Productos', 'icon' => 'fa-box', 'color' => 'violet'],
        ['route' => 'admin.reports.customers', 'label' => 'Clientes', 'icon' => 'fa-users', 'color' => 'blue'],
        ['route' => 'admin.reports.purchases', 'label' => 'Compras', 'icon' => 'fa-cart-shopping', 'color' => 'rose'],
        ['route' => 'admin.reports.profitability', 'label' => 'Rentabilidad', 'icon' => 'fa-coins', 'color' => 'amber'],
        ['route' => 'admin.reports.inventory', 'label' => 'Inventario', 'icon' => 'fa-warehouse', 'color' => 'cyan'],
        ['route' => 'admin.reports.geographic', 'label' => 'Geográfico', 'icon' => 'fa-map-location-dot', 'color' => 'indigo'],
        ['route' => 'admin.reports.trends', 'label' => 'Tendencias', 'icon' => 'fa-chart-line', 'color' => 'purple'],
        ['route' => 'admin.reports.satisfaction', 'label' => 'Satisfacción', 'icon' => 'fa-face-smile', 'color' => 'orange'],
    ];

    $activeGradients = [
        'emerald' => 'from-emerald-500 to-emerald-600',
        'violet'  => 'from-violet-500 to-purple-600',
        'blue'    => 'from-blue-500 to-blue-600',
        'rose'    => 'from-rose-500 to-rose-600',
        'amber'   => 'from-amber-500 to-orange-500',
        'cyan'    => 'from-cyan-500 to-teal-600',
        'indigo'  => 'from-indigo-500 to-indigo-600',
        'purple'  => 'from-purple-500 to-fuchsia-600',
        'orange'  => 'from-orange-500 to-red-500',
    ];

    $activeShadows = [
        'emerald' => 'shadow-emerald-500/30',
        'violet'  => 'shadow-violet-500/30',
        'blue'    => 'shadow-blue-500/30',
        'rose'    => 'shadow-rose-500/30',
        'amber'   => 'shadow-amber-500/30',
        'cyan'    => 'shadow-cyan-500/30',
        'indigo'  => 'shadow-indigo-500/30',
        'purple'  => 'shadow-purple-500/30',
        'orange'  => 'shadow-orange-500/30',
    ];

    $iconBgActive = [
        'emerald' => 'bg-emerald-400/30',
        'violet'  => 'bg-violet-400/30',
        'blue'    => 'bg-blue-400/30',
        'rose'    => 'bg-rose-400/30',
        'amber'   => 'bg-amber-400/30',
        'cyan'    => 'bg-cyan-400/30',
        'indigo'  => 'bg-indigo-400/30',
        'purple'  => 'bg-purple-400/30',
        'orange'  => 'bg-orange-400/30',
    ];

    $hoverBg = [
        'emerald' => 'hover:bg-emerald-50 hover:text-emerald-700',
        'violet'  => 'hover:bg-violet-50 hover:text-violet-700',
        'blue'    => 'hover:bg-blue-50 hover:text-blue-700',
        'rose'    => 'hover:bg-rose-50 hover:text-rose-700',
        'amber'   => 'hover:bg-amber-50 hover:text-amber-700',
        'cyan'    => 'hover:bg-cyan-50 hover:text-cyan-700',
        'indigo'  => 'hover:bg-indigo-50 hover:text-indigo-700',
        'purple'  => 'hover:bg-purple-50 hover:text-purple-700',
        'orange'  => 'hover:bg-orange-50 hover:text-orange-700',
    ];

    $hoverIcon = [
        'emerald' => 'group-hover:text-emerald-500 group-hover:bg-emerald-100',
        'violet'  => 'group-hover:text-violet-500 group-hover:bg-violet-100',
        'blue'    => 'group-hover:text-blue-500 group-hover:bg-blue-100',
        'rose'    => 'group-hover:text-rose-500 group-hover:bg-rose-100',
        'amber'   => 'group-hover:text-amber-500 group-hover:bg-amber-100',
        'cyan'    => 'group-hover:text-cyan-500 group-hover:bg-cyan-100',
        'indigo'  => 'group-hover:text-indigo-500 group-hover:bg-indigo-100',
        'purple'  => 'group-hover:text-purple-500 group-hover:bg-purple-100',
        'orange'  => 'group-hover:text-orange-500 group-hover:bg-orange-100',
    ];
@endphp

<div class="relative mb-6" id="tabSliderWrapper">
    {{-- Arrow Left --}}
    <button type="button" id="tabArrowLeft"
        class="absolute -left-3 top-1/2 -translate-y-1/2 z-30 w-9 h-9 rounded-full bg-white/95 backdrop-blur border border-gray-200/80 shadow-lg shadow-gray-900/10 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:border-indigo-200 hover:shadow-xl active:scale-90 transition-all duration-200 opacity-0 pointer-events-none" aria-label="Anterior">
        <i class="fas fa-chevron-left text-[10px]"></i>
    </button>

    {{-- Arrow Right --}}
    <button type="button" id="tabArrowRight"
        class="absolute -right-3 top-1/2 -translate-y-1/2 z-30 w-9 h-9 rounded-full bg-white/95 backdrop-blur border border-gray-200/80 shadow-lg shadow-gray-900/10 flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:border-indigo-200 hover:shadow-xl active:scale-90 transition-all duration-200 opacity-0 pointer-events-none" aria-label="Siguiente">
        <i class="fas fa-chevron-right text-[10px]"></i>
    </button>

    {{-- Fade left --}}
    <div class="absolute left-0 top-0 bottom-0 w-12 bg-gradient-to-r from-white via-white/70 to-transparent z-20 pointer-events-none rounded-l-2xl opacity-0 transition-opacity duration-300" id="tabFadeLeft"></div>
    {{-- Fade right --}}
    <div class="absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-white via-white/70 to-transparent z-20 pointer-events-none rounded-r-2xl opacity-0 transition-opacity duration-300" id="tabFadeRight"></div>

    {{-- Slider container --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-1 p-2 overflow-x-auto scroll-smooth tab-slider-track" id="reportTabs">
            @foreach($tabs as $i => $tab)
                @if($i === 4)
                    <div class="flex-shrink-0 self-stretch flex items-center px-1">
                        <div class="w-px h-6 bg-gradient-to-b from-transparent via-gray-200 to-transparent rounded-full"></div>
                    </div>
                @endif

                @php $isActive = request()->routeIs($tab['route']); @endphp

                <a href="{{ route($tab['route'], request()->query()) }}"
                    class="group relative flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-[13px] font-medium whitespace-nowrap transition-all duration-200 flex-shrink-0
                    {{ $isActive
                        ? 'bg-gradient-to-r ' . $activeGradients[$tab['color']] . ' text-white shadow-md ' . $activeShadows[$tab['color']]
                        : 'text-gray-500 ' . $hoverBg[$tab['color']]
                    }}"
                    @if($isActive) data-active="true" @endif>

                    {{-- Icon badge --}}
                    <span class="w-6 h-6 rounded-lg flex items-center justify-center transition-all duration-200
                        {{ $isActive
                            ? $iconBgActive[$tab['color']]
                            : 'bg-gray-100/80 ' . $hoverIcon[$tab['color']]
                        }}">
                        <i class="fas {{ $tab['icon'] }} text-[10px] {{ $isActive ? 'text-white' : 'text-gray-400' }} transition-colors duration-200"></i>
                    </span>

                    {{-- Label --}}
                    <span class="tracking-wide">{{ $tab['label'] }}</span>

                    {{-- Active dot indicator --}}
                    @if($isActive)
                        <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-white/40 rounded-full"></span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

<style>
    .tab-slider-track {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .tab-slider-track::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const track = document.getElementById('reportTabs');
        const btnL  = document.getElementById('tabArrowLeft');
        const btnR  = document.getElementById('tabArrowRight');
        const fadeL = document.getElementById('tabFadeLeft');
        const fadeR = document.getElementById('tabFadeRight');
        if (!track) return;

        const SCROLL_STEP = 260;

        function refresh() {
            const canLeft  = track.scrollLeft > 6;
            const canRight = track.scrollLeft < track.scrollWidth - track.clientWidth - 6;

            btnL.style.opacity = canLeft ? '1' : '0';
            btnL.style.pointerEvents = canLeft ? 'auto' : 'none';
            fadeL.style.opacity = canLeft ? '1' : '0';

            btnR.style.opacity = canRight ? '1' : '0';
            btnR.style.pointerEvents = canRight ? 'auto' : 'none';
            fadeR.style.opacity = canRight ? '1' : '0';
        }

        btnL.addEventListener('click', () => track.scrollBy({ left: -SCROLL_STEP, behavior: 'smooth' }));
        btnR.addEventListener('click', () => track.scrollBy({ left:  SCROLL_STEP, behavior: 'smooth' }));

        track.addEventListener('scroll', refresh, { passive: true });
        window.addEventListener('resize', refresh);

        // Center the active tab on load
        const active = track.querySelector('[data-active="true"]');
        if (active) {
            const offset = active.offsetLeft - (track.clientWidth / 2) + (active.offsetWidth / 2);
            track.scrollLeft = Math.max(0, offset);
        }

        refresh();

        // Touch drag support
        let isDown = false, startX, scrollStart;
        track.addEventListener('mousedown', e => { isDown = true; startX = e.pageX - track.offsetLeft; scrollStart = track.scrollLeft; track.style.cursor = 'grabbing'; });
        track.addEventListener('mouseleave', () => { isDown = false; track.style.cursor = ''; });
        track.addEventListener('mouseup', () => { isDown = false; track.style.cursor = ''; });
        track.addEventListener('mousemove', e => { if (!isDown) return; e.preventDefault(); const x = e.pageX - track.offsetLeft; track.scrollLeft = scrollStart - (x - startX); });
    });
</script>
