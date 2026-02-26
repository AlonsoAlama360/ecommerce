@extends('layouts.app')

@section('title', 'Carrito de Compras - Romantic Gifts')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition">Inicio</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">Carrito de Compras</span>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <h1 class="text-3xl lg:text-4xl font-serif font-semibold text-gray-900 mb-8">Carrito de Compras</h1>

        @if(empty($cartItems))
            <!-- Empty Cart -->
            <div class="text-center py-20">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-6"></i>
                <h3 class="font-serif text-2xl font-bold text-gray-900 mb-3">Tu carrito está vacío</h3>
                <p class="text-gray-600 mb-6">Agrega productos para comenzar tu compra.</p>
                <a href="{{ route('catalog') }}" class="inline-block bg-gray-900 text-white px-8 py-3 rounded-full hover:bg-gray-800 transition font-medium">
                    Explorar Catálogo
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        @php $product = $item['product']; @endphp
                        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 cart-item" id="cart-item-{{ $product->id }}">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <!-- Image -->
                                <a href="{{ route('product.show', $product->slug) }}" class="flex-shrink-0">
                                    <img src="{{ $product->primaryImage?->image_url ?? 'https://via.placeholder.com/120x120?text=Sin+Imagen' }}"
                                         alt="{{ $product->name }}"
                                         class="w-full sm:w-24 h-40 sm:h-24 object-cover rounded-lg">
                                </a>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('product.show', $product->slug) }}" class="hover:text-[#D4A574] transition">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $product->name }}</h3>
                                    </a>
                                    @if($product->material)
                                        <p class="text-sm text-gray-600 mb-2">Material: {{ $product->material }}</p>
                                    @endif
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl font-bold text-gray-900">S/ {{ number_format($product->current_price, 2) }}</span>
                                        @if($product->discount_percentage)
                                            <span class="text-sm text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between sm:justify-end gap-4">
                                    <!-- Quantity -->
                                    <div class="flex items-center border-2 border-gray-300 rounded-lg">
                                        <button type="button"
                                                class="qty-btn px-3 py-2 hover:bg-gray-100 transition"
                                                data-product-id="{{ $product->id }}"
                                                data-action="decrease">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <input type="text"
                                               value="{{ $item['quantity'] }}"
                                               readonly
                                               class="qty-input w-12 text-center border-x-2 border-gray-300 py-2 font-semibold"
                                               id="qty-{{ $product->id }}">
                                        <button type="button"
                                                class="qty-btn px-3 py-2 hover:bg-gray-100 transition"
                                                data-product-id="{{ $product->id }}"
                                                data-action="increase"
                                                data-max="{{ $product->stock }}">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>

                                    <!-- Remove -->
                                    <button type="button"
                                            class="remove-btn text-red-500 hover:text-red-700 transition"
                                            data-product-id="{{ $product->id }}">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Continue Shopping -->
                    <div class="pt-4">
                        <a href="{{ route('catalog') }}" class="inline-flex items-center accent-color hover:opacity-80 font-semibold transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Continuar Comprando
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-md p-6 sticky top-24">
                        <h2 class="text-2xl font-serif font-semibold text-gray-900 mb-6">Resumen del Pedido</h2>

                        <div class="space-y-4 mb-6" id="cartSummary">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal (<span id="summaryCount">{{ $totalItems }}</span> artículos)</span>
                                <span class="font-semibold" id="summarySubtotal">S/ {{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($totalDiscount > 0)
                                <div class="flex justify-between text-gray-700">
                                    <span>Descuento</span>
                                    <span class="font-semibold text-green-600" id="summaryDiscount">-S/ {{ number_format($totalDiscount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-gray-700">
                                <span>Envío</span>
                                <span class="font-semibold text-green-600">Gratis</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-xl font-bold text-gray-900">
                                    <span>Total</span>
                                    <span id="summaryTotal">S/ {{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Buttons -->
                        <div class="space-y-3">
                            <button type="button" class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold text-lg hover:bg-gray-800 transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                <i class="fas fa-lock text-sm"></i>
                                Proceder al Pago
                            </button>

                            <button type="button"
                                    id="whatsappCartBtn"
                                    class="w-full bg-green-500 text-white py-4 rounded-full font-semibold text-lg hover:bg-green-600 transition flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-xl"></i>
                                Pedir por WhatsApp
                            </button>
                        </div>

                        <!-- Payment Methods -->
                        <div class="text-center mt-6">
                            <p class="text-xs text-gray-500 mb-3">Métodos de pago aceptados</p>
                            <div class="flex justify-center gap-3">
                                <div class="w-12 h-8 bg-gray-100 rounded flex items-center justify-center text-xs font-bold text-blue-700">VISA</div>
                                <div class="w-12 h-8 bg-gray-100 rounded flex items-center justify-center text-xs font-bold text-red-600">MC</div>
                                <div class="w-12 h-8 bg-gray-100 rounded flex items-center justify-center text-xs font-bold text-blue-900">AMEX</div>
                            </div>
                        </div>

                        <!-- Security -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-shield-alt text-green-600"></i>
                                <span>Compra 100% Segura</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Suggested Products -->
        @if($suggestedProducts->isNotEmpty())
            <div class="mt-16">
                <h2 class="text-3xl font-serif font-semibold text-gray-900 mb-8">También te Puede Gustar</h2>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($suggestedProducts as $suggested)
                        <a href="{{ route('product.show', $suggested->slug) }}" class="group">
                            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow">
                                <div class="relative aspect-square overflow-hidden">
                                    <img src="{{ $suggested->primaryImage?->image_url ?? 'https://via.placeholder.com/300x300' }}"
                                         alt="{{ $suggested->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                         loading="lazy">
                                    @if($suggested->discount_percentage)
                                        <span class="absolute top-3 right-3 bg-[#E8B4B8] text-white px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">-{{ $suggested->discount_percentage }}%</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 text-sm sm:text-base line-clamp-2">{{ $suggested->name }}</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-gray-900">S/ {{ number_format($suggested->current_price, 2) }}</span>
                                        @if($suggested->sale_price && $suggested->sale_price < $suggested->price)
                                            <span class="text-sm text-gray-400 line-through">S/ {{ number_format($suggested->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </main>
@endsection

@section('scripts')
<script>
    const CART_UPDATE_URL = '{{ route("cart.update") }}';
    const CART_REMOVE_URL = '{{ route("cart.remove") }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Quantity buttons
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const action = this.dataset.action;
            const input = document.getElementById('qty-' + productId);
            let qty = parseInt(input.value);

            if (action === 'increase') {
                const max = parseInt(this.dataset.max || 99);
                if (qty < max) qty++;
            } else {
                if (qty > 1) qty--;
            }

            input.value = qty;

            fetch(CART_UPDATE_URL, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: qty })
            }).then(r => r.json()).then(data => {
                updateCartBadge(data.cart_count);
                location.reload();
            });
        });
    });

    // Remove buttons
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const item = document.getElementById('cart-item-' + productId);

            item.style.opacity = '0';
            item.style.transform = 'translateX(100px)';
            item.style.transition = 'all 0.3s ease';

            fetch(CART_REMOVE_URL, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId })
            }).then(r => r.json()).then(data => {
                updateCartBadge(data.cart_count);
                setTimeout(() => location.reload(), 300);
            });
        });
    });

    // WhatsApp cart button
    const whatsappCartBtn = document.getElementById('whatsappCartBtn');
    if (whatsappCartBtn) {
        whatsappCartBtn.addEventListener('click', function() {
            const phone = '{{ config("app.whatsapp_phone") }}';
            let message = '🛒 *Mi Pedido - Romantic Gifts*\n\n';

            @foreach($cartItems as $item)
                message += '▸ *{{ $item["product"]->name }}*\n';
                message += '  Cantidad: {{ $item["quantity"] }}\n';
                message += '  Precio: S/ {{ number_format($item["product"]->current_price * $item["quantity"], 2) }}\n\n';
            @endforeach

            message += '━━━━━━━━━━━━━━━\n';
            message += '💰 *Total: S/ {{ number_format($total, 2) }}*\n\n';
            message += 'Hola, me gustaría realizar este pedido. ¿Podrían confirmar disponibilidad?';

            const url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message);
            window.open(url, '_blank');
        });
    }

    function updateCartBadge(count) {
        const badges = document.querySelectorAll('.cart-badge');
        badges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        });
    }
</script>
@endsection
