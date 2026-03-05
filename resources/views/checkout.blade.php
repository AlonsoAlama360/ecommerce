@extends('layouts.app')

@section('title', 'Checkout - ' . ($settings['business_name'] ?? 'Arixna'))

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition">Inicio</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('cart') }}" class="hover:text-gray-900 transition">Carrito</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-gray-900 font-medium">Checkout</span>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

        {{-- Mensajes de error --}}
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-5 py-4 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-400 hover:text-red-600">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        @endif

        {{-- Steps indicator --}}
        <div class="flex items-center justify-center gap-3 mb-10">
            <div class="flex items-center gap-2 text-gray-400">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-check text-xs text-white"></i>
                </div>
                <span class="text-sm font-medium hidden sm:inline">Carrito</span>
            </div>
            <div class="w-8 sm:w-12 h-px bg-gray-200"></div>
            <div class="flex items-center gap-2 text-[#D4A574]">
                <div class="w-8 h-8 rounded-full bg-[#D4A574] flex items-center justify-center">
                    <span class="text-xs font-bold text-white">2</span>
                </div>
                <span class="text-sm font-semibold hidden sm:inline">Checkout</span>
            </div>
            <div class="w-8 sm:w-12 h-px bg-gray-200"></div>
            <div class="flex items-center gap-2 text-gray-300">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                    <span class="text-xs font-bold text-gray-400">3</span>
                </div>
                <span class="text-sm font-medium text-gray-400 hidden sm:inline">Confirmación</span>
            </div>
        </div>

        <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
            @csrf
            <input type="hidden" name="token" id="culqiToken">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- ═══════════ COLUMNA IZQUIERDA: DATOS ═══════════ --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Datos de contacto --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-9 h-9 rounded-xl bg-[#E8B4B8]/15 flex items-center justify-center">
                                <i class="fas fa-user text-[#D4A574] text-sm"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">Datos de contacto</h2>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nombre completo</label>
                                <input type="text" name="customer_name" id="customer_name"
                                    value="{{ old('customer_name', $user->full_name) }}"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 outline-none transition text-sm"
                                    required>
                                @error('customer_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                <input type="email" name="customer_email" id="customer_email"
                                    value="{{ old('customer_email', $user->email) }}"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 outline-none transition text-sm"
                                    required>
                                @error('customer_email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Teléfono</label>
                                <input type="tel" name="customer_phone" id="customer_phone"
                                    value="{{ old('customer_phone', $user->phone) }}"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 outline-none transition text-sm"
                                    placeholder="999 999 999"
                                    required>
                                @error('customer_phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Dirección de envío --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-9 h-9 rounded-xl bg-[#E8B4B8]/15 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-[#D4A574] text-sm"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">Dirección de envío</h2>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1.5">Dirección completa</label>
                                <textarea name="shipping_address" id="shipping_address" rows="3"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 outline-none transition text-sm resize-none"
                                    placeholder="Av. / Jr. / Calle, Número, Distrito, Ciudad"
                                    required>{{ old('shipping_address', $user->full_address) }}</textarea>
                                @error('shipping_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Notas del pedido <span class="text-gray-400 font-normal">(opcional)</span>
                                </label>
                                <textarea name="customer_notes" id="customer_notes" rows="2"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#D4A574] focus:ring-2 focus:ring-[#D4A574]/10 outline-none transition text-sm resize-none"
                                    placeholder="Referencias, horario de entrega preferido, etc.">{{ old('customer_notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Seguridad --}}
                    <div class="flex items-center gap-3 px-2 text-gray-400">
                        <i class="fas fa-shield-alt text-sm"></i>
                        <p class="text-xs">Tus datos están protegidos. El pago se procesa de forma segura a través de Culqi.</p>
                    </div>
                </div>

                {{-- ═══════════ COLUMNA DERECHA: RESUMEN ═══════════ --}}
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-5">Resumen del pedido</h2>

                        {{-- Productos --}}
                        <div class="space-y-3 mb-5 max-h-64 overflow-y-auto pr-1">
                            @foreach($cartItems as $item)
                                @php $product = $item['product']; @endphp
                                <div class="flex items-center gap-3">
                                    <div class="relative flex-shrink-0">
                                        <img src="{{ $product->primaryImage?->image_url ? asset(ltrim($product->primaryImage->image_url, '/')) : asset('images/placeholder.png') }}"
                                            alt="{{ $product->name }}"
                                            class="w-14 h-14 object-cover rounded-xl border border-gray-100">
                                        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-gray-800 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                            {{ $item['quantity'] }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h4>
                                        <p class="text-xs text-gray-400">S/ {{ number_format($product->current_price, 2) }} c/u</p>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900 flex-shrink-0">
                                        S/ {{ number_format($item['line_total'], 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Totales --}}
                        <div class="border-t border-gray-100 pt-4 space-y-2.5 text-sm">
                            <div class="flex justify-between text-gray-500">
                                <span>Subtotal ({{ $totalItems }} artículos)</span>
                                <span class="text-gray-900 font-medium">S/ {{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($totalDiscount > 0)
                                <div class="flex justify-between text-gray-500">
                                    <span>Descuento</span>
                                    <span class="font-semibold text-emerald-600">-S/ {{ number_format($totalDiscount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-gray-500">
                                <span>Envío</span>
                                <span class="font-medium text-emerald-600">Gratis</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 mt-4 pt-4">
                            <div class="flex justify-between items-baseline">
                                <span class="text-gray-900 font-medium">Total</span>
                                <span class="text-2xl font-bold text-gray-900">S/ {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        {{-- Botón de pago --}}
                        <button type="button" id="payWithCulqi"
                            class="w-full mt-6 bg-gray-900 text-white py-4 rounded-xl font-semibold hover:bg-gray-800 transition shadow-lg shadow-gray-900/10 flex items-center justify-center gap-2.5 text-[15px] disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-lock text-xs"></i>
                            <span id="payBtnText">Pagar S/ {{ number_format($total, 2) }}</span>
                            <div id="payBtnSpinner" class="hidden">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </button>

                        {{-- Métodos aceptados --}}
                        <div class="mt-4">
                            <div class="flex items-center justify-center gap-3 text-gray-300">
                                <i class="fab fa-cc-visa text-2xl"></i>
                                <i class="fab fa-cc-mastercard text-2xl"></i>
                                <i class="fab fa-cc-amex text-2xl"></i>
                                <i class="fab fa-cc-diners-club text-2xl"></i>
                            </div>
                            <div class="flex items-center justify-center gap-2 mt-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-purple-50 text-purple-600 text-[10px] font-semibold">
                                    <i class="fas fa-mobile-alt"></i> Yape
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-green-600 text-[10px] font-semibold">
                                    <i class="fas fa-mobile-alt"></i> Plin
                                </span>
                            </div>
                        </div>

                        <p class="text-center text-xs text-gray-400 mt-3">
                            <i class="fas fa-lock text-[10px] mr-1"></i>
                            Pago seguro procesado por Culqi
                        </p>
                    </div>
                </div>
            </div>
        </form>
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
