@extends('layouts.app')

@section('title', 'Pedido ' . $order->order_number . ' - Romantic Gifts')

@section('styles')
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    @keyframes pulse-ring {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(1.6); opacity: 0; }
    }
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease forwards;
    }
    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-float-delay { animation: float 8s ease-in-out 1s infinite; }

    .detail-card {
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        border: 1px solid rgba(0,0,0,0.04);
    }
    .detail-card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
        border-color: rgba(212,165,116,0.15);
    }

    .product-item {
        transition: all 0.3s ease;
    }
    .product-item:hover {
        background: linear-gradient(135deg, rgba(212,165,116,0.03) 0%, rgba(232,180,184,0.03) 100%);
    }
    .product-item:hover .product-img {
        transform: scale(1.05);
    }
    .product-img {
        transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .tracker-step .pulse-dot {
        animation: pulse-ring 2s ease infinite;
    }

    .step-connector {
        transition: all 0.5s ease;
    }

    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    .summary-row {
        transition: background 0.2s ease;
    }
    .summary-row:hover {
        background: rgba(212,165,116,0.03);
    }
@endsection

@section('content')
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

        $steps = ['pendiente', 'confirmado', 'en_preparacion', 'enviado', 'entregado'];
        $currentStep = array_search($order->status, $steps);
        $isCancelled = $order->status === 'cancelado';
        $isDelivered = $order->status === 'entregado';

        $stepLabels = [
            'pendiente' => 'Pedido recibido',
            'confirmado' => 'Confirmado',
            'en_preparacion' => 'Preparando tu pedido',
            'enviado' => 'En camino',
            'entregado' => 'Entregado',
        ];
        $stepDescriptions = [
            'pendiente' => 'Tu pedido ha sido registrado exitosamente',
            'confirmado' => 'Hemos confirmado tu pedido y pago',
            'en_preparacion' => 'Estamos preparando tus productos con cariño',
            'enviado' => 'Tu pedido está en camino hacia ti',
            'entregado' => 'Has recibido tu pedido. ¡Disfrútalo!',
        ];
        $stepIcons = [
            'pendiente' => 'fa-receipt',
            'confirmado' => 'fa-check-circle',
            'en_preparacion' => 'fa-box-open',
            'enviado' => 'fa-truck',
            'entregado' => 'fa-gift',
        ];
    @endphp

    {{-- Hero Header --}}
    <div class="relative bg-gradient-to-br from-[#D4A574] via-[#C39563] to-[#B8845A] overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-80 h-80 bg-white rounded-full -translate-y-1/2 translate-x-1/3 animate-float"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full translate-y-1/2 -translate-x-1/4 animate-float-delay"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 relative">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-white/60 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition">Inicio</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('orders.index') }}" class="hover:text-white transition">Mis Pedidos</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-white font-medium">{{ $order->order_number }}</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3 flex-wrap">
                        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-white">{{ $order->order_number }}</h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-white/20 backdrop-blur-sm text-white border border-white/20">
                            <i class="fas {{ $statusIcon }} text-[10px]"></i>
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <p class="text-white/70 text-sm flex items-center gap-2">
                        <i class="far fa-calendar-alt text-xs"></i>
                        Realizado el {{ $order->created_at->format('d \d\e F \d\e Y, h:i A') }}
                    </p>
                </div>
                <div class="lg:text-right">
                    <p class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-1">Total del pedido</p>
                    <p class="text-3xl sm:text-4xl font-bold text-white">S/ {{ number_format($order->total, 2) }}</p>
                    <p class="text-white/50 text-xs mt-1">{{ $order->items->sum('quantity') }} {{ $order->items->sum('quantity') === 1 ? 'producto' : 'productos' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Progress Tracker --}}
    @if(!$isCancelled)
        <div class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                {{-- Desktop: Horizontal tracker --}}
                <div class="hidden sm:block">
                    <div class="flex items-start justify-between relative">
                        {{-- Background line --}}
                        <div class="absolute top-6 left-[10%] right-[10%] h-[3px] bg-gray-100 rounded-full"></div>
                        {{-- Active line --}}
                        @php $progress = $currentStep !== false ? ($currentStep / (count($steps) - 1)) * 100 : 0; @endphp
                        <div class="absolute top-6 left-[10%] h-[3px] bg-gradient-to-r from-[#D4A574] to-[#E8B4B8] rounded-full transition-all duration-700 ease-out" style="width: calc({{ $progress }}% * 0.8);"></div>

                        @foreach($steps as $i => $step)
                            @php
                                $isCompleted = $currentStep !== false && $i <= $currentStep;
                                $isCurrent = $i === $currentStep;
                            @endphp
                            <div class="tracker-step flex flex-col items-center relative z-10" style="width: {{ 100 / count($steps) }}%">
                                <div class="relative mb-3">
                                    @if($isCurrent)
                                        <div class="absolute inset-0 w-12 h-12 bg-[#D4A574]/20 rounded-full pulse-dot" style="margin: -2px;"></div>
                                    @endif
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm transition-all duration-500
                                        {{ $isCompleted ? 'bg-gradient-to-br from-[#D4A574] to-[#C39563] text-white shadow-lg shadow-[#D4A574]/25' : 'bg-gray-100 text-gray-300' }}
                                        {{ $isCurrent ? 'ring-4 ring-[#D4A574]/20 scale-110' : '' }}">
                                        @if($isCompleted && !$isCurrent)
                                            <i class="fas fa-check"></i>
                                        @else
                                            <i class="fas {{ $stepIcons[$step] }}"></i>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-center {{ $isCompleted ? 'text-gray-900' : 'text-gray-400' }}">{{ $stepLabels[$step] }}</span>
                                @if($isCurrent)
                                    <span class="text-[10px] text-[#D4A574] font-medium text-center mt-0.5 max-w-[120px]">{{ $stepDescriptions[$step] }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Mobile: Vertical tracker --}}
                <div class="sm:hidden">
                    <div class="space-y-0">
                        @foreach($steps as $i => $step)
                            @php
                                $isCompleted = $currentStep !== false && $i <= $currentStep;
                                $isCurrent = $i === $currentStep;
                                $isLast = $i === count($steps) - 1;
                            @endphp
                            <div class="flex items-start gap-3 {{ !$isLast ? 'pb-5' : '' }} relative">
                                {{-- Connector line --}}
                                @if(!$isLast)
                                    <div class="absolute left-[15px] top-[32px] bottom-0 w-0.5 {{ $isCompleted && ($currentStep > $i) ? 'bg-[#D4A574]' : 'bg-gray-200' }}"></div>
                                @endif
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs flex-shrink-0 relative z-10
                                    {{ $isCompleted ? 'bg-gradient-to-br from-[#D4A574] to-[#C39563] text-white' : 'bg-gray-100 text-gray-300' }}
                                    {{ $isCurrent ? 'ring-3 ring-[#D4A574]/20' : '' }}">
                                    @if($isCompleted && !$isCurrent)
                                        <i class="fas fa-check text-[10px]"></i>
                                    @else
                                        <i class="fas {{ $stepIcons[$step] }} text-[10px]"></i>
                                    @endif
                                </div>
                                <div class="pt-1">
                                    <p class="text-sm font-semibold {{ $isCompleted ? 'text-gray-900' : 'text-gray-400' }}">{{ $stepLabels[$step] }}</p>
                                    @if($isCurrent)
                                        <p class="text-xs text-[#D4A574] mt-0.5">{{ $stepDescriptions[$step] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Cancelled banner --}}
        <div class="bg-red-50 border-b border-red-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-times-circle text-red-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-red-800">Pedido cancelado</h3>
                        <p class="text-sm text-red-600/70 mt-0.5">Este pedido fue cancelado y no será procesado.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
            {{-- Products (left column, larger) --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Products Card --}}
                <div class="detail-card bg-white rounded-2xl overflow-hidden animate-fade-in-up">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h2 class="font-bold text-gray-900 flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#D4A574]/10 to-[#E8B4B8]/10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box-open text-[#D4A574] text-sm"></i>
                                </div>
                                Productos del pedido
                            </h2>
                        </div>
                        <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-3 py-1.5 rounded-full">{{ $order->items->count() }} {{ $order->items->count() === 1 ? 'artículo' : 'artículos' }}</span>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                            @php
                                $img = $item->product?->primaryImage?->image_url;
                                $productUrl = $item->product ? route('product.show', $item->product->slug) : null;
                            @endphp
                            <div class="product-item px-5 sm:px-6 py-5 flex items-center gap-4 sm:gap-5">
                                {{-- Product Image --}}
                                @if($productUrl)
                                <a href="{{ $productUrl }}" class="flex-shrink-0">
                                @else
                                <div class="flex-shrink-0">
                                @endif
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 shadow-sm border border-gray-100">
                                        @if($img)
                                            <img src="{{ $img }}" alt="{{ $item->product_name }}" class="product-img w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <i class="fas fa-image text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                @if($productUrl)
                                </a>
                                @else
                                </div>
                                @endif

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    @if($productUrl)
                                        <a href="{{ $productUrl }}" class="font-semibold text-gray-900 hover:text-[#D4A574] transition text-sm sm:text-base line-clamp-2">{{ $item->product_name }}</a>
                                    @else
                                        <p class="font-semibold text-gray-900 text-sm sm:text-base line-clamp-2">{{ $item->product_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-2">
                                        <span>SKU: {{ $item->product_sku }}</span>
                                    </p>
                                    <div class="flex items-center gap-2 mt-2.5">
                                        <span class="text-xs text-gray-500 bg-gray-50 px-2.5 py-1 rounded-md font-medium">
                                            S/ {{ number_format($item->unit_price, 2) }} x {{ $item->quantity }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Line Total --}}
                                <div class="text-right flex-shrink-0">
                                    <p class="text-lg font-bold text-gray-900">S/ {{ number_format($item->line_total, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Order Totals --}}
                    <div class="bg-gradient-to-b from-gray-50/50 to-gray-50 px-6 py-5 space-y-3">
                        <div class="summary-row flex justify-between text-sm text-gray-500 px-2 py-1 rounded-lg">
                            <span>Subtotal</span>
                            <span class="font-medium">S/ {{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="summary-row flex justify-between text-sm px-2 py-1 rounded-lg">
                                <span class="text-emerald-600 flex items-center gap-1.5">
                                    <i class="fas fa-tag text-[10px]"></i> Descuento
                                </span>
                                <span class="text-emerald-600 font-medium">- S/ {{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="summary-row flex justify-between text-sm text-gray-500 px-2 py-1 rounded-lg">
                            <span class="flex items-center gap-1.5">
                                <i class="fas fa-truck text-[10px]"></i> Envío
                            </span>
                            <span class="font-medium">
                                @if($order->shipping_cost > 0)
                                    S/ {{ number_format($order->shipping_cost, 2) }}
                                @else
                                    <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-xs font-semibold">GRATIS</span>
                                @endif
                            </span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-1">
                            <div class="flex justify-between items-center px-2">
                                <span class="text-base font-bold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-gray-900">S/ {{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Customer Notes --}}
                @if($order->customer_notes)
                    <div class="detail-card bg-white rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.1s">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-sticky-note text-amber-500 text-sm"></i>
                            </div>
                            Notas del pedido
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed bg-amber-50/50 rounded-xl p-4 border border-amber-100/50">{{ $order->customer_notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Shipping Info --}}
                <div class="detail-card bg-white rounded-2xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm">
                            <div class="w-7 h-7 bg-gradient-to-br from-[#D4A574]/10 to-[#E8B4B8]/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-[#D4A574] text-xs"></i>
                            </div>
                            Datos de envío
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Destinatario</p>
                                <p class="font-semibold text-gray-900 text-sm mt-0.5">{{ $order->customer_name }}</p>
                            </div>
                        </div>
                        @if($order->customer_phone)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-phone text-gray-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Teléfono</p>
                                    <p class="text-sm text-gray-700 mt-0.5">{{ $order->customer_phone }}</p>
                                </div>
                            </div>
                        @endif
                        @if($order->customer_email)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope text-gray-400 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Email</p>
                                    <p class="text-sm text-gray-700 mt-0.5 break-all">{{ $order->customer_email }}</p>
                                </div>
                            </div>
                        @endif
                        @if($order->shipping_address)
                            <div class="pt-3 border-t border-gray-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-[#D4A574]/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fas fa-location-dot text-[#D4A574] text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Dirección</p>
                                        <p class="text-sm text-gray-700 leading-relaxed mt-0.5">{{ $order->shipping_address }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Info --}}
                <div class="detail-card bg-white rounded-2xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.15s">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm">
                            <div class="w-7 h-7 bg-gradient-to-br from-[#D4A574]/10 to-[#E8B4B8]/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-credit-card text-[#D4A574] text-xs"></i>
                            </div>
                            Información de pago
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @php
                            $paymentIcons = [
                                'efectivo' => 'fa-money-bill-wave',
                                'transferencia' => 'fa-building-columns',
                                'yape_plin' => 'fa-mobile-screen',
                                'tarjeta' => 'fa-credit-card',
                            ];
                            $paymentColors = [
                                'pendiente' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'dot' => 'bg-amber-400'],
                                'pagado' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-400'],
                                'fallido' => ['bg' => 'bg-red-50', 'text' => 'text-red-500', 'dot' => 'bg-red-400'],
                            ];
                            $pColor = $paymentColors[$order->payment_status] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-500', 'dot' => 'bg-gray-400'];
                            $paymentLabel = \App\Models\Order::PAYMENT_STATUS_LABELS[$order->payment_status] ?? $order->payment_status;
                        @endphp

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Método</span>
                            <span class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                <i class="fas {{ $paymentIcons[$order->payment_method] ?? 'fa-circle' }} text-[#D4A574] text-xs"></i>
                                {{ $order->payment_method_label }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Estado</span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold {{ $pColor['bg'] }} {{ $pColor['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $pColor['dot'] }}"></span>
                                {{ $paymentLabel }}
                            </span>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-600">Total pagado</span>
                            <span class="text-xl font-bold text-gray-900">S/ {{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Need Help --}}
                <div class="relative bg-gradient-to-br from-[#FAF8F5] via-white to-[#F5E6D3] rounded-2xl p-6 border border-[#D4A574]/10 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-[#D4A574]/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-[#D4A574] to-[#C39563] rounded-xl flex items-center justify-center shadow-lg shadow-[#D4A574]/20">
                                <i class="fas fa-headset text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-sm">¿Necesitas ayuda?</h3>
                                <p class="text-xs text-gray-500">Estamos aquí para ti</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Si tienes dudas sobre tu pedido, contáctanos por WhatsApp.</p>
                        <a href="https://wa.me/{{ config('app.whatsapp_phone') }}?text=Hola, tengo una consulta sobre mi pedido {{ $order->order_number }}" target="_blank"
                           class="flex items-center justify-center gap-2 bg-[#25D366] text-white px-4 py-3 rounded-xl text-sm font-semibold hover:bg-[#20bd5a] transition-all hover:shadow-lg hover:shadow-[#25D366]/25 w-full">
                            <i class="fab fa-whatsapp text-lg"></i>
                            Escribir por WhatsApp
                        </a>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="space-y-2">
                    @if($isDelivered)
                        <a href="{{ route('catalog') }}" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-4 py-3 rounded-xl text-sm font-semibold hover:shadow-lg hover:shadow-[#D4A574]/25 transition-all">
                            <i class="fas fa-redo"></i>
                            Volver a comprar
                        </a>
                    @endif
                    <a href="{{ route('orders.index') }}" class="flex items-center justify-center gap-2 text-gray-500 hover:text-[#D4A574] transition text-sm py-3 font-medium">
                        <i class="fas fa-arrow-left text-xs"></i>
                        Volver a mis pedidos
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
