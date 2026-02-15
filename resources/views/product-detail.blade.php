@extends('layouts.app')

@section('title', $product->name . ' - Romantic Gifts')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition">Inicio</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('catalog') }}" class="hover:text-gray-900 transition">Catálogo</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('catalog', ['categories' => [$product->category->slug]]) }}" class="hover:text-gray-900 transition">{{ $product->category->name }}</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium truncate max-w-[200px]">{{ $product->name }}</span>
            </div>
        </div>
    </div>

    <!-- Product Detail -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="aspect-square bg-white rounded-2xl overflow-hidden shadow-lg">
                    <img id="mainImage"
                         src="{{ $product->images->first()?->image_url ?? 'https://via.placeholder.com/600x600?text=Sin+Imagen' }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                </div>

                <!-- Thumbnail Images -->
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($product->images as $index => $image)
                            <button type="button"
                                    class="thumb-btn aspect-square bg-white rounded-lg overflow-hidden border-2 shadow-sm hover:shadow-md transition-all {{ $index === 0 ? 'border-[#D4A574]' : 'border-transparent hover:border-[#D4A574]' }}"
                                    data-image="{{ $image->image_url }}">
                                <img src="{{ $image->image_url }}" alt="{{ $image->alt_text ?? $product->name }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Category -->
                <a href="{{ route('catalog', ['categories' => [$product->category->slug]]) }}" class="inline-block text-sm text-[#D4A574] font-medium hover:underline">
                    {{ $product->category->name }}
                </a>

                <!-- Title -->
                <h1 class="text-3xl lg:text-4xl font-serif font-semibold text-gray-900">{{ $product->name }}</h1>

                <!-- SKU -->
                <p class="text-sm text-gray-400">SKU: {{ $product->sku }}</p>

                <!-- Price -->
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl lg:text-4xl font-bold text-gray-900">S/ {{ number_format($product->current_price, 2) }}</span>
                    @if($product->discount_percentage)
                        <span class="text-xl lg:text-2xl text-gray-400 line-through">S/ {{ number_format($product->price, 2) }}</span>
                        <span class="bg-[#E8B4B8] text-white px-3 py-1 rounded-full text-sm font-semibold">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>

                <!-- Description -->
                <div class="border-t border-b border-gray-200 py-6">
                    <p class="text-gray-700 leading-relaxed">{{ $product->short_description ?? $product->description }}</p>
                </div>

                <!-- Material -->
                @if($product->material)
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-3">Material</label>
                        <span class="inline-block px-6 py-3 border-2 border-[#D4A574] bg-[#D4A574] text-white rounded-lg font-medium">
                            {{ $product->material }}
                        </span>
                    </div>
                @endif

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">Cantidad</label>
                    <div class="flex items-center gap-4">
                        <button type="button" id="decreaseQty" class="w-12 h-12 border-2 border-gray-300 rounded-lg flex items-center justify-center hover:border-[#D4A574] hover:text-[#D4A574] transition">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input id="quantity" type="text" value="1" readonly class="w-20 h-12 text-center border-2 border-gray-300 rounded-lg font-semibold text-lg">
                        <button type="button" id="increaseQty" class="w-12 h-12 border-2 border-gray-300 rounded-lg flex items-center justify-center hover:border-[#D4A574] hover:text-[#D4A574] transition">
                            <i class="fas fa-plus"></i>
                        </button>
                        @if($product->stock > 0)
                            <span class="text-sm text-green-600 font-medium"><i class="fas fa-check-circle mr-1"></i>En stock ({{ $product->stock }})</span>
                        @else
                            <span class="text-sm text-red-500 font-medium"><i class="fas fa-times-circle mr-1"></i>Agotado</span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3 pt-4">
                    <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1" id="cartQtyInput">
                        <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-full font-semibold text-lg hover:bg-gray-800 transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2 {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            Agregar al Carrito
                        </button>
                    </form>
                    <button type="button" id="whatsappBtn" class="w-full bg-green-500 text-white py-4 rounded-full font-semibold text-lg hover:bg-green-600 transition flex items-center justify-center gap-2">
                        <i class="fab fa-whatsapp text-xl"></i>
                        Consultar por WhatsApp
                    </button>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shipping-fast text-[#D4A574]"></i>
                        <span class="text-sm text-gray-700">Envío Gratis</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shield-alt text-[#D4A574]"></i>
                        <span class="text-sm text-gray-700">Garantía 1 Año</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-[#D4A574]"></i>
                        <span class="text-sm text-gray-700">Entrega 2-3 días</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-credit-card text-[#D4A574]"></i>
                        <span class="text-sm text-gray-700">Pago Seguro</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="mt-16">
            <div class="border-b border-gray-200">
                <div class="flex gap-6 sm:gap-8 overflow-x-auto">
                    <button type="button" data-tab="description" class="tab-btn pb-4 border-b-2 border-[#D4A574] text-[#D4A574] font-semibold whitespace-nowrap">
                        Descripción
                    </button>
                    @if($product->specifications)
                        <button type="button" data-tab="specifications" class="tab-btn pb-4 border-b-2 border-transparent text-gray-600 hover:text-[#D4A574] font-semibold whitespace-nowrap">
                            Especificaciones
                        </button>
                    @endif
                </div>
            </div>

            <!-- Tab Content -->
            <div class="py-8">
                <!-- Description Tab -->
                <div id="tab-content-description" class="tab-content space-y-4">
                    <h3 class="text-2xl font-serif font-semibold text-gray-900">Detalles del Producto</h3>
                    <div class="text-gray-700 leading-relaxed prose max-w-none">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Specifications Tab -->
                @if($product->specifications)
                    <div id="tab-content-specifications" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                            @foreach($product->specifications as $label => $value)
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-900">{{ $label }}:</span>
                                    <span class="text-gray-700">{{ $value }}</span>
                                </div>
                            @endforeach
                            @if($product->material)
                                <div class="flex justify-between py-3 border-b border-gray-200">
                                    <span class="font-semibold text-gray-900">Material:</span>
                                    <span class="text-gray-700">{{ $product->material }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-900">SKU:</span>
                                <span class="text-gray-700">{{ $product->sku }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-16">
                <h2 class="text-3xl font-serif font-semibold text-gray-900 mb-8">Productos Relacionados</h2>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('product.show', $related->slug) }}" class="group">
                            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow">
                                <div class="relative aspect-square overflow-hidden">
                                    <img src="{{ $related->primaryImage?->image_url ?? 'https://via.placeholder.com/300x300?text=Sin+Imagen' }}"
                                         alt="{{ $related->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                         loading="lazy">
                                    @if($related->discount_percentage)
                                        <span class="absolute top-3 right-3 bg-[#E8B4B8] text-white px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">-{{ $related->discount_percentage }}%</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 text-sm sm:text-base line-clamp-2">{{ $related->name }}</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-gray-900">S/ {{ number_format($related->current_price, 2) }}</span>
                                        @if($related->sale_price && $related->sale_price < $related->price)
                                            <span class="text-sm text-gray-400 line-through">S/ {{ number_format($related->price, 2) }}</span>
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
    // Image Gallery
    const mainImage = document.getElementById('mainImage');
    const thumbBtns = document.querySelectorAll('.thumb-btn');

    thumbBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            mainImage.src = this.dataset.image;
            thumbBtns.forEach(b => {
                b.classList.remove('border-[#D4A574]');
                b.classList.add('border-transparent');
            });
            this.classList.remove('border-transparent');
            this.classList.add('border-[#D4A574]');
        });
    });

    // Quantity Controls
    const qtyInput = document.getElementById('quantity');
    const cartQtyInput = document.getElementById('cartQtyInput');
    const maxStock = {{ $product->stock }};

    document.getElementById('decreaseQty').addEventListener('click', () => {
        const val = parseInt(qtyInput.value);
        if (val > 1) {
            qtyInput.value = val - 1;
            cartQtyInput.value = val - 1;
        }
    });

    document.getElementById('increaseQty').addEventListener('click', () => {
        const val = parseInt(qtyInput.value);
        if (val < maxStock) {
            qtyInput.value = val + 1;
            cartQtyInput.value = val + 1;
        }
    });

    // Add to Cart via AJAX
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const btn = form.querySelector('button[type="submit"]');
        const originalHTML = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Agregando...';
        btn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: {{ $product->id }},
                quantity: parseInt(qtyInput.value),
            })
        })
        .then(r => r.json())
        .then(data => {
            btn.innerHTML = '<i class="fas fa-check"></i> ¡Agregado!';
            btn.classList.remove('bg-gray-900');
            btn.classList.add('bg-green-600');

            // Update cart badge
            document.querySelectorAll('.cart-badge').forEach(b => {
                b.textContent = data.cart_count;
                b.style.display = data.cart_count > 0 ? 'flex' : 'none';
            });

            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-gray-900');
                btn.disabled = false;
            }, 2000);
        })
        .catch(() => {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        });
    });

    // WhatsApp button
    document.getElementById('whatsappBtn').addEventListener('click', function() {
        const phone = '{{ config("app.whatsapp_phone", "51999999999") }}';
        const qty = parseInt(qtyInput.value);
        const productUrl = window.location.href;

        let message = '🎁 *Consulta sobre producto - Romantic Gifts*\n\n';
        message += '━━━━━━━━━━━━━━━\n';
        message += '📦 *{{ $product->name }}*\n';
        message += '━━━━━━━━━━━━━━━\n\n';
        @if($product->material)
        message += '✨ Material: {{ $product->material }}\n';
        @endif
        message += '🏷️ SKU: {{ $product->sku }}\n';
        message += '💰 Precio: S/ {{ number_format($product->current_price, 2) }}';
        @if($product->discount_percentage)
        message += ' (antes S/ {{ number_format($product->price, 2) }} — {{ $product->discount_percentage }}% OFF)';
        @endif
        message += '\n';
        message += '📦 Cantidad: ' + qty + '\n';
        message += '💵 Total: S/ ' + ({{ $product->current_price }} * qty).toFixed(2) + '\n\n';
        message += '🔗 Ver producto: ' + productUrl + '\n\n';
        @if($product->primaryImage)
        message += '📸 Imagen: {{ $product->primaryImage->image_url }}\n\n';
        @endif
        message += 'Hola, estoy interesado/a en este producto. ¿Está disponible? Me gustaría más información. 🙏';

        window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(message), '_blank');
    });

    // Tabs
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.dataset.tab;

            tabContents.forEach(c => c.classList.add('hidden'));
            tabBtns.forEach(b => {
                b.classList.remove('border-[#D4A574]', 'text-[#D4A574]');
                b.classList.add('border-transparent', 'text-gray-600');
            });

            document.getElementById('tab-content-' + target).classList.remove('hidden');
            this.classList.remove('border-transparent', 'text-gray-600');
            this.classList.add('border-[#D4A574]', 'text-[#D4A574]');
        });
    });
</script>
@endsection
