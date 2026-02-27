@extends('layouts.app')

@section('title', 'Lista de Deseos - Arixna')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition">Inicio</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">Lista de Deseos</span>
            </div>
        </div>
    </div>

    <section class="py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-heart text-[#E8B4B8] mr-2"></i>Mi Lista de Deseos
                    </h1>
                    <p class="text-gray-600">
                        {{ $products->total() }} {{ $products->total() === 1 ? 'producto guardado' : 'productos guardados' }}
                    </p>
                </div>
            </div>

            @if($products->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-20">
                    <i class="far fa-heart text-6xl text-gray-300 mb-6"></i>
                    <h3 class="font-serif text-2xl font-bold text-gray-900 mb-3">Tu lista de deseos esta vacia</h3>
                    <p class="text-gray-600 mb-6">Explora nuestro catalogo y guarda los productos que te encanten.</p>
                    <a href="{{ route('catalog') }}" class="inline-block bg-gray-900 text-white px-8 py-3 rounded-full hover:bg-gray-800 transition font-medium">
                        Explorar Catalogo
                    </a>
                </div>
            @else
                <!-- Products Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-12">
                    @foreach($products as $product)
                        <div class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-gray-100" id="wishlist-card-{{ $product->id }}">
                            <a href="{{ route('product.show', $product->slug) }}" class="block relative overflow-hidden">
                                <img src="{{ $product->primaryImage?->image_url ?? 'https://via.placeholder.com/400x300?text=Sin+Imagen' }}"
                                     alt="{{ $product->primaryImage?->alt_text ?? $product->name }}"
                                     class="w-full h-52 sm:h-72 lg:h-80 object-cover group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                @if($product->discount_percentage)
                                    <div class="absolute top-3 left-3 sm:top-4 sm:left-4 bg-rose-500 text-white px-2.5 py-1.5 rounded-full text-xs font-bold tracking-wide shadow-lg">
                                        -{{ $product->discount_percentage }}%
                                    </div>
                                @endif
                                <button type="button" class="wishlist-btn absolute top-3 right-3 sm:top-4 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 bg-rose-500 text-white backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-rose-600 transition-all duration-200 shadow-md" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-heart text-sm sm:text-base"></i>
                                </button>
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <div class="absolute bottom-3 left-3 sm:bottom-4 sm:left-4 bg-amber-500 text-white px-2.5 py-1 rounded-full text-xs font-semibold shadow-md">
                                        Ultimas {{ $product->stock }} uds!
                                    </div>
                                @endif
                            </a>
                            <div class="p-4 sm:p-5">
                                <p class="text-xs text-gray-400 mb-1">{{ $product->category?->name }}</p>
                                <a href="{{ route('product.show', $product->slug) }}" class="block">
                                    <h3 class="font-semibold text-sm sm:text-base mb-2 line-clamp-2 group-hover:text-[#D4A574] transition-colors duration-200">{{ $product->name }}</h3>
                                </a>
                                <div class="flex items-end gap-2 mb-3 sm:mb-4">
                                    <span class="text-lg sm:text-2xl font-bold text-gray-900 leading-none">S/ {{ number_format($product->current_price, 2) }}</span>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="text-xs sm:text-sm text-gray-400 line-through leading-none pb-0.5">S/ {{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <button type="button"
                                        class="add-to-cart-btn w-full bg-gray-900 text-white py-2.5 sm:py-3 rounded-full hover:bg-gray-800 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 text-sm sm:text-base font-medium"
                                        data-product-id="{{ $product->id }}">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span class="hidden sm:inline">Agregar al Carrito</span>
                                    <span class="sm:hidden">Agregar</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="flex items-center justify-center gap-1 sm:gap-2">
                        @if($products->onFirstPage())
                            <span class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-300 cursor-not-allowed">
                                <i class="fas fa-chevron-left text-sm"></i>
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition">
                                <i class="fas fa-chevron-left text-sm"></i>
                            </a>
                        @endif

                        @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                            @if($page == $products->currentPage())
                                <span class="w-10 h-10 bg-gray-900 text-white rounded-lg flex items-center justify-center font-medium text-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition text-sm">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($products->currentPage() + 2 < $products->lastPage())
                            <span class="px-1 text-gray-400">...</span>
                            <a href="{{ $products->url($products->lastPage()) }}" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition text-sm">{{ $products->lastPage() }}</a>
                        @endif

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-100 transition">
                                <i class="fas fa-chevron-right text-sm"></i>
                            </a>
                        @else
                            <span class="w-10 h-10 border border-gray-200 rounded-lg flex items-center justify-center text-gray-300 cursor-not-allowed">
                                <i class="fas fa-chevron-right text-sm"></i>
                            </span>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Add to Cart AJAX
    document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var button = this;
            var productId = button.dataset.productId;
            var originalHTML = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                button.innerHTML = '<i class="fas fa-check"></i> <span>Agregado!</span>';
                button.classList.remove('bg-gray-900');
                button.classList.add('bg-green-600');

                document.querySelectorAll('.cart-badge').forEach(function(b) {
                    b.textContent = data.cart_count;
                    b.style.display = data.cart_count > 0 ? 'flex' : 'none';
                });

                if (typeof showToast === 'function') {
                    showToast('Producto agregado al carrito!');
                }

                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-gray-900');
                    button.disabled = false;
                }, 1500);
            })
            .catch(function() {
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        });
    });
</script>
@endsection
