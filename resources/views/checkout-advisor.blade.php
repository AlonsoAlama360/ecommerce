@extends('layouts.app')

@section('title', 'Compra con asesor - ' . ($settings['business_name'] ?? 'Arixna'))

@section('styles')
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    .animate-fade-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease forwards;
    }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }

    .checkout-card {
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        border: 1px solid rgba(0,0,0,0.04);
    }
    .checkout-card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
        border-color: rgba(212,165,116,0.15);
    }

    .checkout-input {
        transition: all 0.3s ease;
    }
    .checkout-input:focus {
        border-color: #D4A574;
        box-shadow: 0 0 0 4px rgba(212,165,116,0.08);
    }

    .product-row {
        transition: all 0.25s ease;
    }
    .product-row:hover {
        background: linear-gradient(135deg, rgba(212,165,116,0.04), rgba(232,180,184,0.04));
    }

    .advisor-btn {
        background: linear-gradient(135deg, #D4A574 0%, #C39563 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .advisor-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(212,165,116,0.35);
    }
    .advisor-btn:active:not(:disabled) {
        transform: translateY(0);
    }
    .advisor-btn::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        background-size: 200% 100%;
        animation: shimmer 3s ease infinite;
    }

    .step-line {
        background: linear-gradient(90deg, #D4A574, #e5e7eb);
    }

    .trust-badge {
        transition: all 0.3s ease;
    }
    .trust-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }
@endsection

@section('content')
    {{-- Header --}}
    <section class="relative bg-gradient-to-br from-[#F5E6D3] via-[#FAF8F5] to-[#E8D4C0] overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-8 right-16 w-48 h-48 bg-[#D4A574]/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-4 left-8 w-32 h-32 bg-[#E8B4B8]/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10 relative z-10">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-fade-up">
                <a href="{{ route('home') }}" class="hover:text-[#D4A574] transition">
                    <i class="fas fa-home text-xs"></i> Inicio
                </a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <a href="{{ route('cart') }}" class="hover:text-[#D4A574] transition">Carrito</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-gray-900 font-medium">Compra con asesor</span>
            </div>

            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-8 animate-fade-up delay-1">Compra con asesor</h1>

            {{-- Steps indicator --}}
            <div class="flex items-center justify-center gap-0 mb-2 animate-fade-up delay-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-full bg-[#D4A574] flex items-center justify-center shadow-md shadow-[#D4A574]/20">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                    <span class="text-sm font-semibold text-[#D4A574] hidden sm:inline">Carrito</span>
                </div>

                <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-3 rounded-full step-line"></div>

                <div class="flex items-center gap-2.5 relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D4A574] to-[#C39563] flex items-center justify-center shadow-lg shadow-[#D4A574]/30" style="box-shadow: 0 0 0 4px rgba(212,165,116,0.15), 0 10px 15px -3px rgba(212,165,116,0.3)">
                        <i class="fas fa-headset text-white text-xs"></i>
                    </div>
                    <span class="text-sm font-bold text-gray-900 hidden sm:inline">Datos</span>
                </div>

                <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-3 rounded-full bg-gray-200"></div>

                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                        <i class="fas fa-box-open text-gray-400 text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-400 hidden sm:inline">Confirmación</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Main content --}}
    <div class="bg-[#FAF8F5] min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

            <form id="advisorForm" method="POST" action="{{ route('checkout.advisor.process') }}">
                @csrf
                <input type="hidden" name="shipping_method" id="shippingMethodHidden" value="{{ $shippingMode === 'both' ? 'agency' : $shippingMode }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    {{-- Left column: form --}}
                    <div class="lg:col-span-7 space-y-6">

                        {{-- Contact info --}}
                        <div class="checkout-card bg-white rounded-2xl p-6 sm:p-7 animate-fade-up delay-1">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#D4A574] to-[#C39563] flex items-center justify-center shadow-sm">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">Datos de contacto</h2>
                                    <p class="text-xs text-gray-400">Para que el asesor pueda contactarte</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label for="customer_name" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nombre completo</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                                        <input type="text" name="customer_name" id="customer_name"
                                            value="{{ old('customer_name', $user->full_name) }}"
                                            class="checkout-input w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none text-sm font-medium text-gray-900 placeholder:text-gray-400"
                                            required>
                                    </div>
                                    @error('customer_name')
                                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                                    <div class="relative">
                                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                                        <input type="email" name="customer_email" id="customer_email"
                                            value="{{ old('customer_email', $user->email) }}"
                                            class="checkout-input w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none text-sm font-medium text-gray-900 placeholder:text-gray-400"
                                            required>
                                    </div>
                                    @error('customer_email')
                                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Teléfono</label>
                                    <div class="relative">
                                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                                        <input type="tel" name="customer_phone" id="customer_phone"
                                            value="{{ old('customer_phone', $user->phone) }}"
                                            class="checkout-input w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none text-sm font-medium text-gray-900 placeholder:text-gray-400"
                                            placeholder="999 999 999"
                                            required>
                                    </div>
                                    @error('customer_phone')
                                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Shipping address --}}
                        <div class="checkout-card bg-white rounded-2xl p-6 sm:p-7 animate-fade-up delay-2">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#E8B4B8] to-[#D4A574] flex items-center justify-center shadow-sm">
                                    <i class="fas fa-map-marker-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">Dirección de envío</h2>
                                    <p class="text-xs text-gray-400">A dónde enviamos tu pedido</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                @if($shippingMode === 'both')
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Método de envío</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="shipping-method-option relative flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition" id="optAgency">
                                            <input type="radio" name="shipping_method" value="agency" class="sr-only" checked onchange="toggleShippingMethod()">
                                            <div class="w-8 h-8 rounded-lg bg-[#D4A574]/10 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-building text-[#D4A574] text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">Agencia</p>
                                                <p class="text-[10px] text-gray-400">Recojo en sede</p>
                                            </div>
                                        </label>
                                        <label class="shipping-method-option relative flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition" id="optAddress">
                                            <input type="radio" name="shipping_method" value="address" class="sr-only" onchange="toggleShippingMethod()">
                                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-home text-blue-500 text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">Domicilio</p>
                                                <p class="text-[10px] text-gray-400">Courier solo a Lima</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @endif

                                {{-- Agency fields --}}
                                <div id="agencyFields" class="{{ $shippingMode === 'address' ? 'hidden' : '' }}">
                                    <div class="space-y-4">
                                        <div>
                                            <label for="shipping_agency" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Agencia de transporte</label>
                                            <div class="relative">
                                                <i class="fas fa-truck absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                                                <select name="shipping_agency" id="shipping_agency"
                                                    class="checkout-input w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none text-sm font-medium text-gray-900 appearance-none bg-white">
                                                    @foreach($shippingAgencies as $agency)
                                                        <option value="{{ $agency->name }}" {{ $loop->first ? 'selected' : '' }}>{{ $agency->name }}</option>
                                                    @endforeach
                                                </select>
                                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs pointer-events-none"></i>
                                            </div>
                                            <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1.5">
                                                <i class="fas fa-info-circle text-[#D4A574]"></i>
                                                Tu pedido será enviado a la sede de agencia que indiques.
                                            </p>
                                        </div>

                                        <div>
                                            <label for="shipping_agency_address" id="shipping_agency_address_label" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dirección de agencia</label>
                                            <div class="relative">
                                                <i class="fas fa-building absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                                                <input type="text" name="shipping_agency_address" id="shipping_agency_address"
                                                    list="agencyAddressList"
                                                    class="checkout-input w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none text-sm font-medium text-gray-900 placeholder:text-gray-400"
                                                    placeholder="Escribe o selecciona la sede donde recibirás tu pedido"
                                                    value="{{ old('shipping_agency_address') }}">
                                                <datalist id="agencyAddressList"></datalist>
                                            </div>
                                            @error('shipping_agency_address')
                                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Address fields --}}
                                <div id="addressFields" class="{{ $shippingMode === 'agency' ? 'hidden' : '' }}">
                                    <div>
                                        <label for="shipping_address" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dirección completa</label>
                                        <div class="relative">
                                            <i class="fas fa-home absolute left-4 top-4 text-gray-300 text-xs"></i>
                                            <textarea name="shipping_address" id="shipping_address" rows="3"
                                                class="checkout-input w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none text-sm font-medium text-gray-900 placeholder:text-gray-400 resize-none"
                                                placeholder="Av. / Jr. / Calle, Número, Distrito, Ciudad">{{ old('shipping_address', $user->full_address) }}</textarea>
                                        </div>
                                        @error('shipping_address')
                                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="customer_notes" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                        Notas <span class="text-gray-300 font-normal normal-case">(opcional)</span>
                                    </label>
                                    <div class="relative">
                                        <i class="fas fa-comment-alt absolute left-4 top-4 text-gray-300 text-xs"></i>
                                        <textarea name="customer_notes" id="customer_notes" rows="2"
                                            class="checkout-input w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none text-sm font-medium text-gray-900 placeholder:text-gray-400 resize-none"
                                            placeholder="Referencias, horario de entrega preferido, etc.">{{ old('customer_notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Trust badges --}}
                        <div class="grid grid-cols-3 gap-3 animate-fade-up delay-3">
                            <div class="trust-badge bg-white rounded-xl p-4 text-center border border-gray-50">
                                <div class="w-9 h-9 rounded-lg bg-[#D4A574]/10 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-headset text-[#D4A574] text-sm"></i>
                                </div>
                                <p class="text-[11px] font-semibold text-gray-700">Asesor personal</p>
                                <p class="text-[10px] text-gray-400">Te contactamos</p>
                            </div>
                            <div class="trust-badge bg-white rounded-xl p-4 text-center border border-gray-50">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-truck text-blue-500 text-sm"></i>
                                </div>
                                <p class="text-[11px] font-semibold text-gray-700">Envío a todo Perú</p>
                                <p class="text-[10px] text-gray-400">Seguimiento incluido</p>
                            </div>
                            <div class="trust-badge bg-white rounded-xl p-4 text-center border border-gray-50">
                                <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-undo text-amber-500 text-sm"></i>
                                </div>
                                <p class="text-[11px] font-semibold text-gray-700">Devoluciones</p>
                                <p class="text-[10px] text-gray-400">Hasta 7 días</p>
                            </div>
                        </div>
                    </div>

                    {{-- Right column: summary --}}
                    <div class="lg:col-span-5">
                        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden sticky top-24 animate-fade-up delay-2">

                            {{-- Summary header --}}
                            <div class="bg-gradient-to-r from-[#D4A574] to-[#C39563] px-6 py-5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center">
                                            <i class="fas fa-shopping-bag text-white text-xs"></i>
                                        </div>
                                        <h2 class="text-base font-bold text-white">Resumen del pedido</h2>
                                    </div>
                                    <span class="text-xs text-white/60">{{ $totalItems }} {{ $totalItems === 1 ? 'artículo' : 'artículos' }}</span>
                                </div>
                            </div>

                            <div class="p-6">
                                {{-- Products --}}
                                <div class="space-y-0 mb-5 max-h-72 overflow-y-auto">
                                    @foreach($cartItems as $item)
                                        @php $product = $item['product']; @endphp
                                        <div class="product-row flex items-center gap-3.5 py-3 px-2 rounded-xl">
                                            <div class="relative flex-shrink-0">
                                                <img src="{{ $product->primaryImage?->image_url ? asset(ltrim($product->primaryImage->image_url, '/')) : asset('images/placeholder.png') }}"
                                                    alt="{{ $product->name }}"
                                                    class="w-16 h-16 object-cover rounded-xl border border-gray-100 shadow-sm">
                                                <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-gradient-to-br from-[#D4A574] to-[#C39563] text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">
                                                    {{ $item['quantity'] }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</h4>
                                                <p class="text-xs text-gray-400 mt-0.5">S/ {{ number_format($product->current_price, 2) }} c/u</p>
                                            </div>
                                            <span class="text-sm font-bold text-gray-900 flex-shrink-0">
                                                S/ {{ number_format($item['line_total'], 2) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Totals --}}
                                <div class="border-t border-dashed border-gray-200 pt-4 space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Subtotal</span>
                                        <span class="text-gray-900 font-medium">S/ {{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    @if($totalDiscount > 0)
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Descuento</span>
                                            <span class="font-semibold text-emerald-600">-S/ {{ number_format($totalDiscount, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Envío</span>
                                        <span class="font-semibold text-emerald-600 flex items-center gap-1">
                                            <i class="fas fa-gift text-[10px]"></i> Gratis
                                        </span>
                                    </div>
                                </div>

                                {{-- Total --}}
                                <div class="mt-4 bg-gradient-to-r from-[#FAF8F5] to-[#F5E6D3] rounded-xl p-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700 font-semibold">Total del pedido</span>
                                        <span class="text-2xl font-bold text-gray-900 font-serif">S/ {{ number_format($total, 2) }}</span>
                                    </div>
                                </div>

                                {{-- Submit button --}}
                                <button type="submit" id="advisorSubmitBtn"
                                    class="advisor-btn w-full mt-5 text-white py-4 rounded-xl font-semibold flex items-center justify-center gap-2.5 text-[15px] disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-shopping-bag text-sm relative z-10"></i>
                                    <span id="advisorBtnText" class="relative z-10">Realizar pedido</span>
                                </button>

                                {{-- Advisor info --}}
                                <div class="mt-5 pt-4 border-t border-gray-100">
                                    <div class="bg-gradient-to-r from-[#D4A574]/5 to-[#E8B4B8]/5 rounded-xl p-4 text-center">
                                        <div class="w-10 h-10 bg-[#D4A574]/10 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <i class="fas fa-comments text-[#D4A574]"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-700 mb-1">Sin pago en este momento</p>
                                        <p class="text-xs text-gray-500 leading-relaxed">Un asesor de venta te contactará por email o WhatsApp para coordinar el pago y envío de tu pedido.</p>
                                    </div>
                                </div>

                                <p class="text-center text-[11px] text-gray-400 mt-4 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-headset text-[9px]"></i>
                                    Atención personalizada para tu compra
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var advisorForm = document.getElementById('advisorForm');
        var submitBtn = document.getElementById('advisorSubmitBtn');
        var btnText = document.getElementById('advisorBtnText');
        var shippingMode = '{{ $shippingMode }}';

        // Agency data and elements
        var agenciesData = @json($shippingAgencies->mapWithKeys(fn($a) => [$a->name => $a->addresses->pluck('address')]));
        var agencySelect = document.getElementById('shipping_agency');
        var agencyAddressInput = document.getElementById('shipping_agency_address');
        var agencyAddressList = document.getElementById('agencyAddressList');
        var agencyLabel = document.getElementById('shipping_agency_address_label');

        function updateAgencyAddresses() {
            if (!agencySelect) return;
            var name = agencySelect.value;
            agencyAddressList.innerHTML = '';
            agencyLabel.textContent = name ? 'Dirección de agencia ' + name : 'Dirección de agencia';
            if (name && agenciesData[name]) {
                agenciesData[name].forEach(function(addr) {
                    var opt = document.createElement('option');
                    opt.value = addr;
                    agencyAddressList.appendChild(opt);
                });
            }
        }
        if (agencySelect) {
            agencySelect.addEventListener('change', function() {
                agencyAddressInput.value = '';
                updateAgencyAddresses();
            });
            updateAgencyAddresses();
        }

        // Toggle shipping method
        window.toggleShippingMethod = function() {
            var selected = document.querySelector('input[name="shipping_method"]:checked');
            if (!selected) return;
            var isAgency = selected.value === 'agency';
            var agencyFields = document.getElementById('agencyFields');
            var addressFields = document.getElementById('addressFields');
            var optAgency = document.getElementById('optAgency');
            var optAddress = document.getElementById('optAddress');

            agencyFields.classList.toggle('hidden', !isAgency);
            addressFields.classList.toggle('hidden', isAgency);

            optAgency.classList.toggle('border-[#D4A574]', isAgency);
            optAgency.classList.toggle('bg-[#D4A574]/5', isAgency);
            optAgency.classList.toggle('border-gray-200', !isAgency);
            optAddress.classList.toggle('border-blue-400', !isAgency);
            optAddress.classList.toggle('bg-blue-50/50', !isAgency);
            optAddress.classList.toggle('border-gray-200', isAgency);

            document.getElementById('shippingMethodHidden').value = selected.value;
        };
        if (shippingMode === 'both') toggleShippingMethod();

        // Form submit with loading state
        advisorForm.addEventListener('submit', function() {
            submitBtn.disabled = true;
            btnText.innerHTML = '<i class="fas fa-spinner fa-spin text-sm mr-1"></i> Procesando...';
        });
    });
</script>
@endsection
