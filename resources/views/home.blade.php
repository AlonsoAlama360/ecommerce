@extends('layouts.app')

@section('title', 'Romantic Gifts - Detalles Románticos')

@section('content')
    <!-- Hero Section -->
    <section class="hero-gradient py-20 md:py-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h1 class="font-serif text-5xl md:text-7xl font-bold text-gray-900 leading-tight text-balance">
                        Detalles que hablan de amor
                    </h1>
                    <p class="text-xl text-gray-600 leading-relaxed text-pretty">
                        Descubre nuestra colección exclusiva de regalos románticos diseñados para expresar tus
                        sentimientos más profundos.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <button class="btn-primary px-8 py-4 rounded-full font-medium">
                            Explorar Colección
                        </button>
                        <button class="btn-secondary px-8 py-4 rounded-full font-medium bg-transparent">
                            Ver Ofertas
                        </button>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://i.pinimg.com/1200x/e0/4d/5e/e04d5ea05adc67cae665c023676b5ee6.jpg" alt="Hero"
                        class="rounded-3xl shadow-2xl">
                    <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-heart text-rose-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Envío Gratis</p>
                                <p class="font-semibold">En compras +$50</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categorías Destacadas -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">Explora por Categoría</h2>
                <p class="text-lg text-gray-600">Encuentra el regalo perfecto para cada ocasión</p>
            </div>

            @php
                $gradients = [
                    'from-rose-50 to-pink-50',
                    'from-purple-50 to-pink-50',
                    'from-amber-50 to-orange-50',
                    'from-red-50 to-rose-50',
                    'from-yellow-50 to-amber-50',
                ];
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($categories as $index => $cat)
                    <a href="#" class="group">
                        <div class="bg-gradient-to-br {{ $gradients[$index % count($gradients)] }} rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <i class="{{ $cat->icon }} text-3xl accent-color"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $cat->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Banner Promocional -->
    <section class="py-20 bg-gradient-to-r from-rose-100 via-pink-50 to-purple-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-3xl overflow-hidden shadow-xl group cursor-pointer">
                    <div class="relative overflow-hidden">
                        <img src="https://i.pinimg.com/1200x/e0/4d/5e/e04d5ea05adc67cae665c023676b5ee6.jpg" alt="Promo"
                            class="w-full h-80 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 right-4 bg-rose-500 text-white px-4 py-2 rounded-full font-semibold">-30% OFF</div>
                    </div>
                    <div class="p-8">
                        <h3 class="font-serif text-3xl font-bold mb-2">Colección San Valentín</h3>
                        <p class="text-gray-600 mb-4">Expresa tu amor con nuestros diseños exclusivos</p>
                        <button class="text-gray-900 font-semibold flex items-center gap-2 group-hover:gap-4 transition-all">
                            Comprar Ahora <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-white rounded-3xl overflow-hidden shadow-xl group cursor-pointer">
                    <div class="relative overflow-hidden">
                        <img src="https://i.pinimg.com/736x/cb/ef/6e/cbef6e099db2a2b90825505936e65985.jpg" alt="Promo"
                            class="w-full h-80 object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute top-4 right-4 bg-amber-500 text-white px-4 py-2 rounded-full font-semibold">Nuevo</div>
                    </div>
                    <div class="p-8">
                        <h3 class="font-serif text-3xl font-bold mb-2">Rosas Eternas</h3>
                        <p class="text-gray-600 mb-4">Belleza que perdura para siempre</p>
                        <button class="text-gray-900 font-semibold flex items-center gap-2 group-hover:gap-4 transition-all">
                            Descubrir <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Productos Destacados con Slider -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-2">Más Vendidos</h2>
                    <p class="text-lg text-gray-600">Los favoritos de nuestros clientes</p>
                </div>
                <div class="flex gap-3">
                    <button class="w-12 h-12 rounded-full border-2 border-gray-300 hover:border-gray-900 transition flex items-center justify-center" id="prevBtn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="w-12 h-12 rounded-full border-2 border-gray-300 hover:border-gray-900 transition flex items-center justify-center" id="nextBtn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="slider-container">
                <div class="slider-track" id="sliderTrack">
                    @foreach($featuredProducts as $product)
                        <div class="min-w-[300px] px-3">
                            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-lg">
                                <div class="relative">
                                    <img src="{{ $product->primaryImage?->image_url ?? '' }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-80 object-cover" loading="lazy">
                                    @if($product->discount_percentage)
                                        <div class="absolute top-4 left-4 badge-discount text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            -{{ $product->discount_percentage }}%
                                        </div>
                                    @endif
                                    <button class="absolute top-4 right-4 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-rose-500 hover:text-white transition">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                                <div class="p-6">
                                    <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="text-2xl font-bold text-gray-900">${{ $product->current_price }}</span>
                                        @if($product->sale_price)
                                            <span class="text-lg text-gray-400 line-through">${{ $product->price }}</span>
                                        @endif
                                    </div>
                                    <button class="w-full bg-gray-900 text-white py-3 rounded-full hover:bg-gray-800 transition flex items-center justify-center gap-2">
                                        <i class="fas fa-shopping-cart"></i> Agregar al Carrito
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonios -->
    <section class="py-20 bg-gradient-to-br from-rose-50 to-pink-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-gray-900 mb-4">Lo que dicen nuestros clientes</h2>
                <p class="text-lg text-gray-600">Miles de personas han expresado su amor con nuestros detalles</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"El collar que compré superó todas mis expectativas. La calidad es excepcional y mi pareja quedó encantada. ¡Definitivamente volveré a comprar!"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-rose-400 to-pink-300 rounded-full"></div>
                        <div>
                            <p class="font-semibold">María González</p>
                            <p class="text-sm text-gray-500">Cliente Verificada</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"La rosa eterna es simplemente hermosa. Llegó perfectamente empaquetada y el detalle es impresionante. Un regalo perfecto para aniversarios."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-300 rounded-full"></div>
                        <div>
                            <p class="font-semibold">Carlos Ramírez</p>
                            <p class="text-sm text-gray-500">Cliente Verificado</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="flex gap-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"Excelente servicio al cliente y envío rápido. Los productos son de alta calidad y los precios muy competitivos. ¡Totalmente recomendado!"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-300 rounded-full"></div>
                        <div>
                            <p class="font-semibold">Ana Martínez</p>
                            <p class="text-sm text-gray-500">Cliente Verificada</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-20 bg-gray-900 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-8">
                <i class="fas fa-envelope text-5xl accent-color mb-4"></i>
                <h2 class="font-serif text-4xl md:text-5xl font-bold mb-4">Suscríbete a nuestro newsletter</h2>
                <p class="text-xl text-gray-300">Recibe ofertas exclusivas y novedades directamente en tu correo</p>
            </div>
            <form class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
                <input type="email" placeholder="Tu correo electrónico"
                    class="flex-1 px-6 py-4 rounded-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-rose-400">
                <button class="bg-accent hover:bg-opacity-90 px-8 py-4 rounded-full font-semibold transition">
                    Suscribirse
                </button>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Slider
    const sliderTrack = document.getElementById('sliderTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let currentSlide = 0;
    const slideWidth = 300 + 24;

    nextBtn.addEventListener('click', () => {
        const maxSlide = sliderTrack.children.length - 3;
        if (currentSlide < maxSlide) {
            currentSlide++;
            sliderTrack.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentSlide > 0) {
            currentSlide--;
            sliderTrack.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
        }
    });

    setInterval(() => {
        const maxSlide = sliderTrack.children.length - 3;
        if (currentSlide < maxSlide) {
            currentSlide++;
        } else {
            currentSlide = 0;
        }
        sliderTrack.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
    }, 5000);
</script>
@endsection
