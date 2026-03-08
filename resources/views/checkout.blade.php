@extends('layouts.app')

@section('title', 'Checkout - ' . ($settings['business_name'] ?? 'Arixna'))

@section('styles')
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    @keyframes pulse-subtle {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
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

    .pay-btn {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .pay-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    .pay-btn:active:not(:disabled) {
        transform: translateY(0);
    }
    .pay-btn::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
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
    {{-- ═══════════ HEADER CON BREADCRUMB Y STEPS ═══════════ --}}
    <section class="relative bg-gradient-to-br from-[#F5E6D3] via-[#FAF8F5] to-[#E8D4C0] overflow-hidden">
        {{-- Decoraciones --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-8 right-16 w-48 h-48 bg-[#D4A574]/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-4 left-8 w-32 h-32 bg-[#E8B4B8]/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10 relative z-10">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 animate-fade-up">
                <a href="{{ route('home') }}" class="hover:text-[#D4A574] transition">
                    <i class="fas fa-home text-xs"></i> Inicio
                </a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <a href="{{ route('cart') }}" class="hover:text-[#D4A574] transition">Carrito</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-gray-900 font-medium">Checkout</span>
            </div>

            {{-- Title --}}
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-8 animate-fade-up delay-1">Finalizar Compra</h1>

            {{-- Steps indicator --}}
            <div class="flex items-center justify-center gap-0 mb-2 animate-fade-up delay-2">
                {{-- Step 1: Carrito (completado) --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-full bg-[#D4A574] flex items-center justify-center shadow-md shadow-[#D4A574]/20">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                    <span class="text-sm font-semibold text-[#D4A574] hidden sm:inline">Carrito</span>
                </div>

                {{-- Línea 1 --}}
                <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-3 rounded-full step-line"></div>

                {{-- Step 2: Checkout (activo) --}}
                <div class="flex items-center gap-2.5 relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#D4A574] to-[#C39563] flex items-center justify-center shadow-lg shadow-[#D4A574]/30" style="box-shadow: 0 0 0 4px rgba(212,165,116,0.15), 0 10px 15px -3px rgba(212,165,116,0.3)">
                        <i class="fas fa-credit-card text-white text-xs"></i>
                    </div>
                    <span class="text-sm font-bold text-gray-900 hidden sm:inline">Pago</span>
                </div>

                {{-- Línea 2 --}}
                <div class="w-12 sm:w-20 h-0.5 mx-2 sm:mx-3 rounded-full bg-gray-200"></div>

                {{-- Step 3: Confirmación --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border-2 border-gray-200">
                        <i class="fas fa-box-open text-gray-400 text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-400 hidden sm:inline">Confirmación</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ CONTENIDO PRINCIPAL ═══════════ --}}
    <div class="bg-[#FAF8F5] min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">

            {{-- Mensajes de error --}}
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl px-5 py-4 flex items-center gap-3 animate-fade-up">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-sm"></i>
                    </div>
                    <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600 transition" aria-label="Cerrar notificación">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            @endif

            <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
                @csrf
                <input type="hidden" name="token" id="culqiToken">
                <input type="hidden" name="culqi_order_id" id="culqiOrderId">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    {{-- ═══════════ COLUMNA IZQUIERDA: DATOS ═══════════ --}}
                    <div class="lg:col-span-7 space-y-6">

                        {{-- Datos de contacto --}}
                        <div class="checkout-card bg-white rounded-2xl p-6 sm:p-7 animate-fade-up delay-1">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#D4A574] to-[#C39563] flex items-center justify-center shadow-sm">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">Datos de contacto</h2>
                                    <p class="text-xs text-gray-400">Información para tu pedido</p>
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

                        {{-- Dirección de envío --}}
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
                                <div>
                                    <label for="shipping_address" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dirección completa</label>
                                    <div class="relative">
                                        <i class="fas fa-home absolute left-4 top-4 text-gray-300 text-xs"></i>
                                        <textarea name="shipping_address" id="shipping_address" rows="3"
                                            class="checkout-input w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 outline-none text-sm font-medium text-gray-900 placeholder:text-gray-400 resize-none"
                                            placeholder="Av. / Jr. / Calle, Número, Distrito, Ciudad"
                                            required>{{ old('shipping_address', $user->full_address) }}</textarea>
                                    </div>
                                    @error('shipping_address')
                                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                    @enderror
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
                                <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-shield-alt text-emerald-500 text-sm"></i>
                                </div>
                                <p class="text-[11px] font-semibold text-gray-700">Pago seguro</p>
                                <p class="text-[10px] text-gray-400">SSL 256-bit</p>
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

                    {{-- ═══════════ COLUMNA DERECHA: RESUMEN ═══════════ --}}
                    <div class="lg:col-span-5">
                        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden sticky top-24 animate-fade-up delay-2">

                            {{-- Header del resumen --}}
                            <div class="bg-gradient-to-r from-[#1a1a1a] to-[#2d2d2d] px-6 py-5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                            <i class="fas fa-shopping-bag text-white text-xs"></i>
                                        </div>
                                        <h2 class="text-base font-bold text-white">Resumen del pedido</h2>
                                    </div>
                                    <span class="text-xs text-white/50">{{ $totalItems }} {{ $totalItems === 1 ? 'artículo' : 'artículos' }}</span>
                                </div>
                            </div>

                            <div class="p-6">
                                {{-- Productos --}}
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

                                {{-- Totales --}}
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
                                        <span class="text-gray-700 font-semibold">Total a pagar</span>
                                        <span class="text-2xl font-bold text-gray-900 font-serif">S/ {{ number_format($total, 2) }}</span>
                                    </div>
                                </div>

                                {{-- Botón de pago --}}
                                <button type="button" id="payWithCulqi"
                                    class="pay-btn w-full mt-5 text-white py-4 rounded-xl font-semibold flex items-center justify-center gap-2.5 text-[15px] disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-lock text-xs relative z-10"></i>
                                    <span id="payBtnText" class="relative z-10">Pagar S/ {{ number_format($total, 2) }}</span>
                                    <div id="payBtnSpinner" class="hidden relative z-10">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </button>

                                {{-- Métodos de pago --}}
                                <div class="mt-5 pt-4 border-t border-gray-100">
                                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider text-center mb-3">Métodos de pago aceptados</p>
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-12 h-8 bg-gray-50 rounded-md flex items-center justify-center border border-gray-100">
                                            <i class="fab fa-cc-visa text-xl text-gray-400"></i>
                                        </div>
                                        <div class="w-12 h-8 bg-gray-50 rounded-md flex items-center justify-center border border-gray-100">
                                            <i class="fab fa-cc-mastercard text-xl text-gray-400"></i>
                                        </div>
                                        <div class="w-12 h-8 bg-gray-50 rounded-md flex items-center justify-center border border-gray-100">
                                            <i class="fab fa-cc-amex text-xl text-gray-400"></i>
                                        </div>
                                        <div class="w-12 h-8 bg-gray-50 rounded-md flex items-center justify-center border border-gray-100">
                                            <i class="fab fa-cc-diners-club text-xl text-gray-400"></i>
                                        </div>
                                        <div class="h-8 px-2.5 bg-purple-50 rounded-md flex items-center justify-center border border-purple-100 gap-1">
                                            <i class="fas fa-mobile-alt text-purple-500 text-[10px]"></i>
                                            <span class="text-[10px] font-bold text-purple-600">Yape</span>
                                        </div>
                                        <div class="h-8 px-2.5 bg-green-50 rounded-md flex items-center justify-center border border-green-100 gap-1">
                                            <i class="fas fa-mobile-alt text-green-500 text-[10px]"></i>
                                            <span class="text-[10px] font-bold text-green-600">Plin</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-center text-[11px] text-gray-400 mt-4 flex items-center justify-center gap-1.5">
                                    <i class="fas fa-lock text-[9px]"></i>
                                    Pago seguro procesado por Culqi
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
<script src="https://checkout.culqi.com/js/v4"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var payBtn = document.getElementById('payWithCulqi');
        var payBtnText = document.getElementById('payBtnText');
        var payBtnSpinner = document.getElementById('payBtnSpinner');
        var checkoutForm = document.getElementById('checkoutForm');
        var culqiTokenInput = document.getElementById('culqiToken');
        var isProcessing = false;
        var currentCulqiOrderId = null;
        var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function validateForm() {
            var name = document.getElementById('customer_name').value.trim();
            var email = document.getElementById('customer_email').value.trim();
            var phone = document.getElementById('customer_phone').value.trim();
            var address = document.getElementById('shipping_address').value.trim();

            if (!name || !email || !phone || !address) {
                checkoutForm.reportValidity();
                return false;
            }
            return true;
        }

        function setLoading(loading) {
            payBtn.disabled = loading;
            payBtnText.textContent = loading ? 'Preparando pago...' : 'Pagar S/ {{ number_format($total, 2) }}';
            if (loading) {
                payBtnSpinner.classList.remove('hidden');
            } else {
                payBtnSpinner.classList.add('hidden');
            }
        }

        function setProcessing() {
            isProcessing = true;
            payBtn.disabled = true;
            payBtnText.textContent = 'Procesando pago...';
            payBtnSpinner.classList.remove('hidden');
        }

        // Configure Culqi
        Culqi.publicKey = '{{ $culqiPublicKey }}';

        Culqi.options({
            lang: 'auto',
            style: {
                logo: '{{ asset("images/logo_arixna.png") }}',
                bannerColor: '#1a1a1a',
                buttonBackground: '#D4A574',
                buttonText: '#ffffff',
                buttonTextColor: '#ffffff',
                menuColor: '#D4A574',
                linksColor: '#D4A574',
                priceColor: '#1a1a1a',
            }
        });

        payBtn.addEventListener('click', function () {
            if (isProcessing) return;
            if (!validateForm()) return;

            setLoading(true);

            // Create Culqi order first (required for Yape support)
            fetch('{{ route("checkout.culqi-order") }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    customer_name: document.getElementById('customer_name').value.trim(),
                    customer_email: document.getElementById('customer_email').value.trim(),
                    customer_phone: document.getElementById('customer_phone').value.trim(),
                })
            })
            .then(function(r) {
                if (!r.ok) {
                    return r.text().then(function(t) {
                        console.error('Server response:', r.status, t);
                        throw new Error('Error del servidor: ' + r.status);
                    });
                }
                return r.json();
            })
            .then(function(data) {
                if (!data || data.error) {
                    setLoading(false);
                    alert(data ? data.error : 'Error desconocido');
                    return;
                }

                if (!data.order_id) {
                    setLoading(false);
                    console.error('No order_id in response:', data);
                    alert('No se pudo crear la orden de pago.');
                    return;
                }

                currentCulqiOrderId = data.order_id;

                Culqi.settings({
                    title: '{{ $settings["business_name"] ?? "Arixna" }}',
                    currency: 'PEN',
                    amount: {{ (int) round($total * 100) }},
                    order: currentCulqiOrderId,
                });

                setLoading(false);
                Culqi.open();
            })
            .catch(function(err) {
                console.error('Error creating Culqi order:', err);
                setLoading(false);
                alert('Error al iniciar el pago. Intenta nuevamente.');
            });
        });

        // Culqi callback
        window.culqi = function () {
            // Card payment — token generated
            if (Culqi.token) {
                setProcessing();
                culqiTokenInput.value = Culqi.token.id;
                document.getElementById('culqiOrderId').value = currentCulqiOrderId || '';
                checkoutForm.submit();
                return;
            }

            // Yape/wallet payment — order is paid directly
            if (Culqi.order) {
                var state = Culqi.order.state || Culqi.order.status;
                if (state === 'paid') {
                    setProcessing();

                    fetch('{{ route("checkout.process-yape") }}', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            culqi_order_id: currentCulqiOrderId,
                            customer_name: document.getElementById('customer_name').value.trim(),
                            customer_email: document.getElementById('customer_email').value.trim(),
                            customer_phone: document.getElementById('customer_phone').value.trim(),
                            shipping_address: document.getElementById('shipping_address').value.trim(),
                            customer_notes: document.getElementById('customer_notes').value.trim(),
                        })
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            isProcessing = false;
                            setLoading(false);
                            alert(data.error || 'Error al procesar el pago.');
                        }
                    })
                    .catch(function() {
                        isProcessing = false;
                        setLoading(false);
                        alert('Error al procesar el pago. Contacta soporte.');
                    });
                }
                return;
            }

            if (Culqi.error) {
                console.error('Culqi error:', Culqi.error);
            }
        };
    });
</script>
@endsection
