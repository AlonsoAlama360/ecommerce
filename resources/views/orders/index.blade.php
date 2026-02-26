@extends('layouts.app')

@section('title', 'Mis Pedidos - Romantic Gifts')

@section('styles')
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    @keyframes pulse-soft {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease forwards;
    }
    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-float-delay { animation: float 8s ease-in-out 1s infinite; }
    .animate-pulse-soft { animation: pulse-soft 3s ease-in-out infinite; }

    .order-card {
        transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        border: 1px solid rgba(0,0,0,0.04);
    }
    .order-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 60px rgba(212,165,116,0.12), 0 8px 24px rgba(0,0,0,0.06);
        border-color: rgba(212,165,116,0.2);
    }
    .order-card:hover .order-arrow {
        transform: translateX(4px);
        color: #D4A574;
    }
    .order-card:hover .order-img-wrapper {
        transform: scale(1.05);
    }

    .order-arrow {
        transition: all 0.3s ease;
    }
    .order-img-wrapper {
        transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .status-tab {
        transition: all 0.3s ease;
        position: relative;
    }
    .status-tab::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 50%;
        right: 50%;
        height: 2.5px;
        background: linear-gradient(90deg, #D4A574, #C39563);
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    .status-tab.active::after {
        left: 0;
        right: 0;
    }
    .status-tab.active {
        color: #D4A574;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .progress-bar-animated {
        background: linear-gradient(90deg, #D4A574 0%, #E8B4B8 50%, #D4A574 100%);
        background-size: 200% 100%;
        animation: shimmer 2s ease infinite;
    }
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    .empty-gift {
        filter: drop-shadow(0 10px 20px rgba(212,165,116,0.2));
    }
@endsection

@section('content')
    @php
        $totalAll = array_sum($statusCounts->toArray());
        $pending = ($statusCounts['pendiente'] ?? 0) + ($statusCounts['confirmado'] ?? 0) + ($statusCounts['en_preparacion'] ?? 0);
        $shipped = $statusCounts['enviado'] ?? 0;
        $delivered = $statusCounts['entregado'] ?? 0;
        $filterStatuses = [
            'pendiente' => ['label' => 'Pendientes', 'icon' => 'fa-clock'],
            'confirmado' => ['label' => 'Confirmados', 'icon' => 'fa-check-circle'],
            'en_preparacion' => ['label' => 'En preparación', 'icon' => 'fa-box-open'],
            'enviado' => ['label' => 'Enviados', 'icon' => 'fa-truck'],
            'entregado' => ['label' => 'Entregados', 'icon' => 'fa-gift'],
            'cancelado' => ['label' => 'Cancelados', 'icon' => 'fa-times-circle'],
        ];
    @endphp

    {{-- Hero Header --}}
    <div class="relative bg-gradient-to-br from-[#D4A574] via-[#C39563] to-[#B8845A] overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full -translate-y-1/2 translate-x-1/3 animate-float"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-white rounded-full translate-y-1/2 -translate-x-1/4 animate-float-delay"></div>
        </div>
        <div class="absolute top-8 right-12 opacity-[0.07] hidden lg:block">
            <i class="fas fa-shopping-bag text-[180px] text-white transform -rotate-12"></i>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 relative">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-white/60 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition">Inicio</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-white font-medium">Mis Pedidos</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-2">Mis Pedidos</h1>
                    <p class="text-white/70 text-sm sm:text-base">Sigue el estado de tus compras en tiempo real</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 backdrop-blur-sm hover:bg-white/25 rounded-xl text-sm text-white transition border border-white/20">
                        <i class="fas fa-user text-xs"></i>
                        <span class="hidden sm:inline">Mi Perfil</span>
                    </a>
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-[#B8845A] hover:bg-white/90 rounded-xl text-sm font-semibold transition shadow-lg shadow-black/10">
                        <i class="fas fa-shopping-bag text-xs"></i>
                        <span class="hidden sm:inline">Seguir comprando</span>
                    </a>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mt-8">
                <div class="stat-card bg-white/15 backdrop-blur-sm rounded-2xl p-4 sm:p-5 text-center border border-white/10">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-receipt text-white"></i>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $totalAll }}</p>
                    <p class="text-white/60 text-xs sm:text-sm mt-0.5">Total pedidos</p>
                </div>
                <div class="stat-card bg-white/15 backdrop-blur-sm rounded-2xl p-4 sm:p-5 text-center border border-white/10">
                    <div class="w-10 h-10 bg-amber-400/30 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-clock text-amber-200"></i>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $pending }}</p>
                    <p class="text-white/60 text-xs sm:text-sm mt-0.5">En proceso</p>
                </div>
                <div class="stat-card bg-white/15 backdrop-blur-sm rounded-2xl p-4 sm:p-5 text-center border border-white/10">
                    <div class="w-10 h-10 bg-violet-400/30 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-truck text-violet-200"></i>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $shipped }}</p>
                    <p class="text-white/60 text-xs sm:text-sm mt-0.5">En camino</p>
                </div>
                <div class="stat-card bg-white/15 backdrop-blur-sm rounded-2xl p-4 sm:p-5 text-center border border-white/10">
                    <div class="w-10 h-10 bg-emerald-400/30 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-gift text-emerald-200"></i>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $delivered }}</p>
                    <p class="text-white/60 text-xs sm:text-sm mt-0.5">Recibidos</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="bg-white border-b border-gray-200 sticky top-[80px] z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-1 overflow-x-auto py-1 -mb-px scrollbar-hide">
                <a href="{{ route('orders.index') }}"
                   class="status-tab whitespace-nowrap px-5 py-3.5 text-sm font-semibold {{ !request('status') ? 'active text-[#D4A574]' : 'text-gray-400 hover:text-gray-600' }}">
                    <i class="fas fa-layer-group mr-1.5 text-xs"></i>Todos
                    <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs {{ !request('status') ? 'bg-[#D4A574]/10 text-[#D4A574]' : 'bg-gray-100 text-gray-400' }}">{{ $totalAll }}</span>
                </a>
                @foreach($filterStatuses as $statusKey => $statusInfo)
                    @if(($statusCounts[$statusKey] ?? 0) > 0)
                        <a href="{{ route('orders.index', ['status' => $statusKey]) }}"
                           class="status-tab whitespace-nowrap px-5 py-3.5 text-sm font-semibold {{ request('status') === $statusKey ? 'active text-[#D4A574]' : 'text-gray-400 hover:text-gray-600' }}">
                            <i class="fas {{ $statusInfo['icon'] }} mr-1.5 text-xs"></i>{{ $statusInfo['label'] }}
                            <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs {{ request('status') === $statusKey ? 'bg-[#D4A574]/10 text-[#D4A574]' : 'bg-gray-100 text-gray-400' }}">{{ $statusCounts[$statusKey] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Orders Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        @if($orders->isEmpty())
            {{-- Empty State --}}
            <div class="text-center py-16 sm:py-24 animate-fade-in-up">
                <div class="relative inline-block mb-8">
                    {{-- Decorative rings --}}
                    <div class="absolute inset-0 w-40 h-40 mx-auto rounded-full border-2 border-dashed border-[#D4A574]/20 animate-pulse-soft" style="margin: -20px;"></div>
                    <div class="w-28 h-28 sm:w-32 sm:h-32 bg-gradient-to-br from-[#FAF8F5] to-[#F5E6D3] rounded-3xl flex items-center justify-center mx-auto shadow-lg empty-gift rotate-3">
                        <div class="text-center">
                            <i class="fas fa-gift text-4xl sm:text-5xl text-[#D4A574] mb-1"></i>
                            <div class="w-8 h-0.5 bg-[#D4A574]/30 mx-auto rounded-full"></div>
                        </div>
                    </div>
                </div>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
                    @if(request('status'))
                        No tienes pedidos {{ strtolower($filterStatuses[request('status')]['label'] ?? '') }}
                    @else
                        Aún no tienes pedidos
                    @endif
                </h3>
                <p class="text-gray-500 max-w-md mx-auto mb-8">Explora nuestra tienda y encuentra el detalle perfecto para esa persona especial</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-7 py-3.5 rounded-xl hover:shadow-lg hover:shadow-[#D4A574]/25 hover:-translate-y-0.5 transition-all font-semibold text-sm">
                        <i class="fas fa-shopping-bag"></i>
                        Explorar tienda
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="inline-flex items-center gap-2 bg-white border-2 border-gray-200 text-gray-700 px-7 py-3.5 rounded-xl hover:border-[#D4A574]/40 hover:text-[#D4A574] transition-all font-semibold text-sm">
                        <i class="fas fa-heart text-[#E8B4B8]"></i>
                        Mi lista de deseos
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4 sm:space-y-5">
                @foreach($orders as $index => $order)
                    @php
                        $colors = $order->status_color;
                        $statusIcons = [
                            'pendiente' => 'fa-clock',
                            'confirmado' => 'fa-check-circle',
                            'en_preparacion' => 'fa-box-open',
                            'enviado' => 'fa-truck',
                            'entregado' => 'fa-gift',
                            'cancelado' => 'fa-times-circle',
                        ];
                        $statusIcon = $statusIcons[$order->status] ?? 'fa-circle';
                        $statusGradients = [
                            'pendiente' => 'from-amber-400 to-amber-500',
                            'confirmado' => 'from-blue-400 to-blue-500',
                            'en_preparacion' => 'from-indigo-400 to-indigo-500',
                            'enviado' => 'from-violet-400 to-violet-500',
                            'entregado' => 'from-emerald-400 to-emerald-500',
                            'cancelado' => 'from-red-400 to-red-500',
                        ];
                        $gradient = $statusGradients[$order->status] ?? 'from-gray-400 to-gray-500';
                    @endphp
                    <a href="{{ route('orders.show', $order) }}"
                       class="order-card block bg-white rounded-2xl overflow-hidden animate-fade-in-up"
                       style="animation-delay: {{ $index * 0.07 }}s">

                        <div class="flex flex-col sm:flex-row">
                            {{-- Left: Product Images Mosaic --}}
                            <div class="sm:w-44 lg:w-52 flex-shrink-0 bg-gradient-to-br from-gray-50 to-gray-100 p-3 sm:p-4">
                                @if($order->items->count() === 1)
                                    @php $img = $order->items->first()->product?->primaryImage?->image_url; @endphp
                                    <div class="order-img-wrapper w-full aspect-square rounded-xl overflow-hidden bg-white shadow-sm">
                                        @if($img)
                                            <img src="{{ $img }}" alt="{{ $order->items->first()->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <i class="fas fa-image text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                @elseif($order->items->count() >= 2)
                                    <div class="order-img-wrapper grid grid-cols-2 gap-1.5 aspect-square">
                                        @foreach($order->items->take(4) as $i => $item)
                                            @php $img = $item->product?->primaryImage?->image_url; @endphp
                                            <div class="rounded-lg overflow-hidden bg-white shadow-sm {{ $order->items->count() === 2 ? 'row-span-1' : '' }} {{ $order->items->count() === 3 && $i === 0 ? 'row-span-2' : '' }}">
                                                @if($img)
                                                    <img src="{{ $img }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-300 min-h-[40px]">
                                                        <i class="fas fa-image text-sm"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($order->items->count() > 4)
                                        <p class="text-center text-xs text-gray-400 mt-1.5 font-medium">+{{ $order->items->count() - 4 }} más</p>
                                    @endif
                                @endif
                            </div>

                            {{-- Right: Order Details --}}
                            <div class="flex-1 p-4 sm:p-5 lg:p-6 flex flex-col justify-between min-w-0">
                                {{-- Top: Order info --}}
                                <div>
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div>
                                            <div class="flex items-center gap-2.5 mb-1">
                                                <h3 class="font-bold text-gray-900 text-base sm:text-lg">{{ $order->order_number }}</h3>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $colors['bg'] }} {{ $colors['text'] }}">
                                                    <i class="fas {{ $statusIcon }} text-[9px]"></i>
                                                    {{ $order->status_label }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-400 flex items-center gap-1.5">
                                                <i class="far fa-calendar text-[10px]"></i>
                                                {{ $order->created_at->format('d \d\e F Y, h:i A') }}
                                            </p>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-xl sm:text-2xl font-bold text-gray-900">S/ {{ number_format($order->total, 2) }}</p>
                                            <p class="text-xs text-gray-400">{{ $order->items->sum('quantity') }} {{ $order->items->sum('quantity') === 1 ? 'producto' : 'productos' }}</p>
                                        </div>
                                    </div>

                                    {{-- Products list --}}
                                    <div class="space-y-1 mb-3">
                                        @foreach($order->items->take(2) as $item)
                                            <p class="text-sm text-gray-600 truncate">
                                                <span class="text-gray-400">{{ $item->quantity }}x</span>
                                                {{ $item->product_name }}
                                                <span class="text-gray-400 font-medium">S/ {{ number_format($item->line_total, 2) }}</span>
                                            </p>
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <p class="text-xs text-[#D4A574] font-medium">+ {{ $order->items->count() - 2 }} producto{{ $order->items->count() - 2 > 1 ? 's' : '' }} más</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Bottom: Progress + arrow --}}
                                <div class="flex items-center gap-4">
                                    @if(!in_array($order->status, ['cancelado', 'entregado']))
                                        @php
                                            $steps = ['pendiente', 'confirmado', 'en_preparacion', 'enviado', 'entregado'];
                                            $currentStep = array_search($order->status, $steps);
                                            $progress = $currentStep !== false ? (($currentStep + 1) / count($steps)) * 100 : 0;
                                        @endphp
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1.5">
                                                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Progreso</span>
                                                <span class="text-[10px] font-bold text-[#D4A574]">{{ intval($progress) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="h-full rounded-full progress-bar-animated transition-all duration-500" style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                    @elseif($order->status === 'entregado')
                                        <div class="flex-1 flex items-center gap-2">
                                            <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-emerald-500 text-[10px]"></i>
                                            </div>
                                            <span class="text-xs font-medium text-emerald-600">Entregado con éxito</span>
                                        </div>
                                    @elseif($order->status === 'cancelado')
                                        <div class="flex-1 flex items-center gap-2">
                                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-times text-red-500 text-[10px]"></i>
                                            </div>
                                            <span class="text-xs font-medium text-red-500">Pedido cancelado</span>
                                        </div>
                                    @endif

                                    <div class="order-arrow flex-shrink-0 w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-300">
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
                <div class="mt-10 flex items-center justify-center gap-1 sm:gap-2">
                    @if($orders->onFirstPage())
                        <span class="w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center text-gray-300 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-[#D4A574]/5 hover:border-[#D4A574]/30 hover:text-[#D4A574] transition text-gray-500">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </a>
                    @endif
                    @foreach($orders->withQueryString()->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                        @if($page == $orders->currentPage())
                            <span class="w-10 h-10 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-md shadow-[#D4A574]/20">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-[#D4A574]/5 hover:border-[#D4A574]/30 hover:text-[#D4A574] transition text-gray-500 text-sm">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($orders->currentPage() + 2 < $orders->lastPage())
                        <span class="px-1 text-gray-300">...</span>
                        <a href="{{ $orders->withQueryString()->url($orders->lastPage()) }}" class="w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-[#D4A574]/5 hover:border-[#D4A574]/30 hover:text-[#D4A574] transition text-gray-500 text-sm">{{ $orders->lastPage() }}</a>
                    @endif
                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-[#D4A574]/5 hover:border-[#D4A574]/30 hover:text-[#D4A574] transition text-gray-500">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </a>
                    @else
                        <span class="w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center text-gray-300 cursor-not-allowed">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </span>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection
