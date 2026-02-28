@extends('layouts.app')

@section('title', 'Carrito de Compras - Arixna')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition">Inicio</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-gray-900 font-medium">Carrito</span>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

        @if(empty($cartItems))
            <!-- Empty Cart -->
            <div class="text-center py-24">
                <div class="w-28 h-28 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h1 class="font-serif text-3xl font-bold text-gray-900 mb-3">Tu carrito está vacío</h1>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">Descubre nuestros productos y encuentra algo especial para ti</p>
                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-8 py-3.5 rounded-xl hover:bg-gray-800 transition font-medium shadow-lg shadow-gray-900/10">
                    <i class="fas fa-store text-sm"></i>
                    Explorar Catálogo
                </a>
            </div>
        @else
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-serif font-bold text-gray-900">Carrito de Compras</h1>
                    <p class="text-sm text-gray-400 mt-1">{{ $totalItems }} {{ $totalItems === 1 ? 'artículo' : 'artículos' }}</p>
                </div>
                <a href="{{ route('catalog') }}" class="hidden sm:inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 transition font-medium">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Seguir comprando
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-8 space-y-3">
                    @foreach($cartItems as $item)
                        @php $product = $item['product']; @endphp
                        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 cart-item transition-all duration-300" id="cart-item-{{ $product->id }}">
                            <div class="flex gap-4">
                                <!-- Image -->
                                <a href="{{ route('product.show', $product->slug) }}" class="flex-shrink-0">
                                    <img src="{{ $product->primaryImage?->image_url ?? 'https://via.placeholder.com/120x120?text=Sin+Imagen' }}"
                                         alt="{{ $product->name }}"
                                         class="w-24 h-24 sm:w-28 sm:h-28 object-cover rounded-xl border border-gray-100">
                                </a>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-[#D4A574] transition">
                                                <h2 class="text-base sm:text-lg font-semibold text-gray-900 line-clamp-2 leading-snug">{{ $product->name }}</h2>
                                            </a>
                                            @if($product->material)
                                                <p class="text-xs text-gray-400 mt-1">{{ $product->material }}</p>
                                            @endif
                                            @if($product->category)
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $product->category->name }}</p>
                                            @endif
                                        </div>
                                        <!-- Remove -->
                                        <button type="button"
                                                class="remove-btn w-8 h-8 rounded-lg hover:bg-red-50 flex items-center justify-center text-gray-300 hover:text-red-500 transition flex-shrink-0"
                                                data-product-id="{{ $product->id }}"
                                                aria-label="Eliminar {{ $product->name }}">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </div>

                                    <!-- Price + Quantity -->
                                    <div class="flex items-end justify-between mt-3">
                                        <div class="flex items-center bg-gray-100 rounded-xl overflow-hidden">
                                            <button type="button"
                                                    class="qty-btn w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition"
                                                    data-product-id="{{ $product->id }}"
                                                    data-action="decrease"
                                                    aria-label="Disminuir cantidad">
                                                <i class="fas fa-minus text-[10px]"></i>
                                            </button>
                                            <input type="text"
                                                   value="{{ $item['quantity'] }}"
                                                   readonly
                                                   class="qty-input w-9 h-9 text-center bg-transparent text-sm font-bold text-gray-900"
                                                   id="qty-{{ $product->id }}"
                                                   aria-label="Cantidad">
                                            <button type="button"
                                                    class="qty-btn w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-200 transition"
                                                    data-product-id="{{ $product->id }}"
                                                    data-action="increase"
                                                    data-max="{{ $product->stock }}"
                                                    aria-label="Aumentar cantidad">
                                                <i class="fas fa-plus text-[10px]"></i>
                                            </button>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-900">S/ {{ number_format($product->current_price * $item['quantity'], 2) }}</span>
                                            @if($product->discount_percentage)
                                                <p class="text-xs text-gray-400 line-through">S/ {{ number_format($product->price * $item['quantity'], 2) }}</p>
                                            @elseif($item['quantity'] > 1)
                                                <p class="text-xs text-gray-400">S/ {{ number_format($product->current_price, 2) }} c/u</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Mobile continue shopping -->
                    <div class="pt-2 sm:hidden">
                        <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 font-medium transition">
                            <i class="fas fa-arrow-left text-xs"></i>
                            Seguir comprando
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-900 mb-5">Resumen del pedido</h2>

                        <div class="space-y-3 text-sm" id="cartSummary">
                            <div class="flex justify-between text-gray-500">
                                <span>Subtotal (<span id="summaryCount">{{ $totalItems }}</span> artículos)</span>
                                <span class="text-gray-900 font-medium" id="summarySubtotal">S/ {{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($totalDiscount > 0)
                                <div class="flex justify-between text-gray-500">
                                    <span>Descuento</span>
                                    <span class="font-semibold text-emerald-600" id="summaryDiscount">-S/ {{ number_format($totalDiscount, 2) }}</span>
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
                                <span class="text-2xl font-bold text-gray-900" id="summaryTotal">S/ {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Checkout Buttons -->
                        <div class="mt-6 space-y-3">
                            <button type="button" class="w-full bg-gray-900 text-white py-3.5 rounded-xl font-semibold hover:bg-gray-800 transition shadow-lg shadow-gray-900/10 flex items-center justify-center gap-2">
                                <i class="fas fa-lock text-xs"></i>
                                Proceder al Pago
                            </button>

                            <button type="button"
                                    id="whatsappCartBtn"
                                    class="w-full bg-[#25D366] text-white py-3.5 rounded-xl font-semibold hover:bg-[#20bd5a] transition flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-lg"></i>
                                Pedir por WhatsApp
                            </button>
                        </div>

                        <!-- Trust badges -->
                        <div class="mt-6 pt-5 border-t border-gray-100 space-y-3">
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <i class="fas fa-shield-alt text-emerald-500"></i>
                                <span>Compra 100% segura y protegida</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <i class="fas fa-truck text-emerald-500"></i>
                                <span>Envío gratis a todo el Perú</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <i class="fas fa-undo text-emerald-500"></i>
                                <span>Devoluciones en 30 días</span>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mt-5 pt-5 border-t border-gray-100">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold mb-3">Métodos de pago</p>
                            <div class="flex gap-2">
                                <div class="h-7 px-2 bg-gray-50 rounded flex items-center justify-center text-[10px] font-bold text-blue-700 border border-gray-100">VISA</div>
                                <div class="h-7 px-2 bg-gray-50 rounded flex items-center justify-center text-[10px] font-bold text-red-600 border border-gray-100">MC</div>
                                <div class="h-7 px-2 bg-gray-50 rounded flex items-center justify-center text-[10px] font-bold text-blue-900 border border-gray-100">AMEX</div>
                                <div class="h-7 px-2 bg-gray-50 rounded flex items-center justify-center text-[10px] font-bold text-indigo-600 border border-gray-100">YAPE</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Suggested Products -->
        @if($suggestedProducts->isNotEmpty())
            <div class="mt-16 pt-10 border-t border-gray-100">
                <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">También te puede gustar</h2>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                    @foreach($suggestedProducts as $suggested)
                        <a href="{{ route('product.show', $suggested->slug) }}" class="group">
                            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg hover:border-gray-200 transition-all duration-300">
                                <div class="relative aspect-square overflow-hidden">
                                    <img src="{{ $suggested->primaryImage?->image_url ?? 'https://via.placeholder.com/300x300' }}"
                                         alt="{{ $suggested->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         loading="lazy">
                                    @if($suggested->discount_percentage)
                                        <span class="absolute top-3 left-3 bg-red-500 text-white px-2.5 py-1 rounded-lg text-xs font-bold">-{{ $suggested->discount_percentage }}%</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-medium text-gray-900 text-sm line-clamp-2 mb-2 group-hover:text-[#D4A574] transition leading-snug">{{ $suggested->name }}</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900">S/ {{ number_format($suggested->current_price, 2) }}</span>
                                        @if($suggested->sale_price && $suggested->sale_price < $suggested->price)
                                            <span class="text-xs text-gray-400 line-through">S/ {{ number_format($suggested->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
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
            var e = { cart: '\uD83D\uDED2', dot: '\u25B8', money: '\uD83D\uDCB0', line: '\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500\u2500' };
            let message = e.cart + ' *Mi Pedido - Arixna*\n\n';

            @foreach($cartItems as $item)
                message += e.dot + ' *{{ $item["product"]->name }}*\n';
                message += '  Cantidad: {{ $item["quantity"] }}\n';
                message += '  Precio: S/ {{ number_format($item["product"]->current_price * $item["quantity"], 2) }}\n\n';
            @endforeach

            message += e.line + '\n';
            message += e.money + ' *Total: S/ {{ number_format($total, 2) }}*\n\n';
            message += 'Hola, me gustar\u00EDa realizar este pedido. \u00BFPodr\u00EDan confirmar disponibilidad? \uD83D\uDE4F';

            const url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message);
            window.open(url, '_blank');
        });
    }

    function updateCartBadge(count) {
        document.querySelectorAll('.cart-badge').forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        });
    }
</script>
@endsection
