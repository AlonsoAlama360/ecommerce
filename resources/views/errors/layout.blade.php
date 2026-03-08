@extends('layouts.app')

@section('title', $title ?? 'Error')

@section('styles')
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
    }
    @keyframes floatReverse {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(-3deg); }
    }
    @keyframes pulse-soft {
        0%, 100% { opacity: 0.4; }
        50% { opacity: 0.8; }
    }
    .animate-fade-up {
        opacity: 0;
        animation: fadeInUp 0.7s ease forwards;
    }
    .delay-1 { animation-delay: 0.15s; }
    .delay-2 { animation-delay: 0.3s; }
    .delay-3 { animation-delay: 0.45s; }
    .delay-4 { animation-delay: 0.6s; }
    .delay-5 { animation-delay: 0.75s; }
@endsection

@section('content')
    <div class="relative min-h-[85vh] bg-gradient-to-br from-[#FAF8F5] via-white to-[#F5E6D3] flex items-center justify-center overflow-hidden">

        {{-- Decorative background --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[10%] left-[5%] w-72 h-72 bg-{{ $accentBg ?? '[#D4A574]' }}/5 rounded-full blur-3xl" style="animation: pulse-soft 6s ease-in-out infinite;"></div>
            <div class="absolute bottom-[15%] right-[10%] w-96 h-96 bg-{{ $accentBg2 ?? '[#E8B4B8]' }}/5 rounded-full blur-3xl" style="animation: pulse-soft 8s ease-in-out 2s infinite;"></div>
            <div class="absolute top-[20%] right-[20%] w-48 h-48 bg-{{ $accentBg ?? '[#D4A574]' }}/3 rounded-full blur-2xl" style="animation: pulse-soft 5s ease-in-out 1s infinite;"></div>

            <div class="absolute top-[15%] left-[15%] w-3 h-3 bg-{{ $accentBg ?? '[#D4A574]' }}/20 rounded-full" style="animation: float 7s ease-in-out infinite;"></div>
            <div class="absolute top-[25%] right-[25%] w-2 h-2 bg-{{ $accentBg2 ?? '[#E8B4B8]' }}/30 rounded-full" style="animation: floatReverse 5s ease-in-out 1s infinite;"></div>
            <div class="absolute bottom-[30%] left-[20%] w-4 h-4 bg-{{ $accentBg ?? '[#D4A574]' }}/10 rounded-full" style="animation: float 9s ease-in-out 2s infinite;"></div>
            <div class="absolute bottom-[20%] right-[15%] w-2.5 h-2.5 bg-{{ $accentBg2 ?? '[#E8B4B8]' }}/20 rounded-full" style="animation: floatReverse 6s ease-in-out infinite;"></div>
        </div>

        <div class="relative z-10 max-w-2xl mx-auto px-4 sm:px-6 text-center py-16">

            {{-- Error Code --}}
            <div class="animate-fade-up mb-6">
                <div class="relative inline-block">
                    <span class="text-[120px] sm:text-[160px] lg:text-[200px] font-serif font-bold leading-none bg-gradient-to-br {{ $gradientFrom ?? 'from-[#D4A574]' }} {{ $gradientVia ?? 'via-[#C39563]' }} {{ $gradientTo ?? 'to-[#E8B4B8]' }} bg-clip-text text-transparent select-none" style="filter: drop-shadow(0 4px 30px rgba(212,165,116,0.15));">
                        @yield('code')
                    </span>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-transparent via-{{ $accentBg ?? '[#D4A574]' }}/40 to-transparent rounded-full"></div>
                </div>
            </div>

            {{-- Icon --}}
            <div class="animate-fade-up delay-1 mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100" style="animation: float 6s ease-in-out infinite;">
                    <i class="fas {{ $icon ?? 'fa-exclamation-triangle' }} text-{{ $iconColor ?? '[#D4A574]' }} text-2xl"></i>
                </div>
            </div>

            {{-- Title --}}
            <h1 class="animate-fade-up delay-2 font-serif text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                @yield('heading')
            </h1>

            {{-- Description --}}
            <p class="animate-fade-up delay-3 text-gray-500 text-sm sm:text-base max-w-md mx-auto mb-10 leading-relaxed">
                @yield('message')
            </p>

            {{-- Action Buttons --}}
            <div class="animate-fade-up delay-4 flex flex-col sm:flex-row items-center justify-center gap-3 mb-12">
                <a href="{{ route('home') }}"
                   class="group relative w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-gradient-to-r from-[#1a1a1a] to-[#2d2d2d] text-white px-8 py-4 rounded-xl font-semibold text-sm hover:shadow-xl hover:shadow-gray-900/15 transition-all duration-300 hover:-translate-y-0.5 overflow-hidden">
                    <i class="fas fa-home text-xs relative z-10"></i>
                    <span class="relative z-10">Volver al Inicio</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </a>
                @hasSection('extra_button')
                    @yield('extra_button')
                @else
                    <a href="{{ route('catalog') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-white text-gray-700 px-8 py-4 rounded-xl font-semibold text-sm border border-gray-200 hover:border-[#D4A574]/30 hover:text-[#D4A574] hover:shadow-lg hover:shadow-[#D4A574]/5 transition-all duration-300 hover:-translate-y-0.5">
                        <i class="fas fa-shopping-bag text-xs"></i>
                        Explorar Catálogo
                    </a>
                @endif
            </div>

            {{-- Quick Links --}}
            <div class="animate-fade-up delay-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Enlaces útiles</p>
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white border border-gray-100 text-xs font-medium text-gray-500 hover:text-[#D4A574] hover:border-[#D4A574]/20 hover:shadow-sm transition-all duration-200">
                        <i class="fas fa-home text-[10px]"></i> Inicio
                    </a>
                    <a href="{{ route('catalog') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white border border-gray-100 text-xs font-medium text-gray-500 hover:text-[#D4A574] hover:border-[#D4A574]/20 hover:shadow-sm transition-all duration-200">
                        <i class="fas fa-store text-[10px]"></i> Catálogo
                    </a>
                    <a href="{{ route('ofertas') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white border border-gray-100 text-xs font-medium text-gray-500 hover:text-[#D4A574] hover:border-[#D4A574]/20 hover:shadow-sm transition-all duration-200">
                        <i class="fas fa-tag text-[10px]"></i> Ofertas
                    </a>
                    @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-white border border-gray-100 text-xs font-medium text-gray-500 hover:text-[#D4A574] hover:border-[#D4A574]/20 hover:shadow-sm transition-all duration-200">
                        <i class="fas fa-user text-[10px]"></i> Iniciar Sesión
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endsection
