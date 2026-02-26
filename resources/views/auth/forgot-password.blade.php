@extends('layouts.auth')

@section('title', 'Recuperar Contraseña')

@section('content')
<main class="min-h-screen flex items-center justify-center px-4 py-12 relative">
    <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-8 items-center">

        <!-- Left Side - Image & Decorative Content -->
        <div class="hidden lg:flex flex-col items-center justify-center space-y-8 p-12">
            <div class="relative">
                <div class="relative w-96 h-96 rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://i.pinimg.com/1200x/e0/4d/5e/e04d5ea05adc67cae665c023676b5ee6.jpg"
                        alt="Romantic" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#D4A574]/30 to-transparent"></div>
                </div>

                <div class="absolute -top-6 -right-6 w-20 h-20 bg-white rounded-2xl shadow-xl flex items-center justify-center float-animation">
                    <svg class="w-10 h-10 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>

                <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-[#FFE5E5] rounded-2xl shadow-lg flex items-center justify-center" style="animation: float 5s ease-in-out infinite;">
                    <svg class="w-8 h-8 text-[#C39563]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <div class="text-center space-y-4">
                <h2 class="text-4xl font-serif font-bold gradient-text">¿Olvidaste tu contraseña?</h2>
                <p class="text-gray-600 text-lg max-w-md">No te preocupes, te ayudaremos a recuperar el acceso a tu cuenta en minutos</p>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full max-w-md mx-auto">

            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center space-x-2 mb-6 group">
                    <img src="https://aztrosperu.com/cdn/shop/files/Logo_Aztros_copia.png?v=1669076562&width=500"
                        alt="Logo" class="h-10">
                </a>
            </div>

            <!-- Form Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">

                <!-- Icon -->
                <div class="w-16 h-16 bg-gradient-to-br from-[#D4A574] to-[#C39563] rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-serif font-semibold text-gray-900 mb-2">Recuperar Contraseña</h1>
                    <p class="text-gray-600">Te enviaremos un enlace para restablecer tu contraseña</p>
                </div>

                <!-- Status Message -->
                @if(session('status'))
                    <div class="mb-6 p-5 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-semibold text-green-900">Email enviado</h3>
                                <p class="text-sm text-green-700 mt-1">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                        @foreach($errors->all() as $error)
                            <p class="text-red-600 text-sm font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="flex items-center text-sm font-bold text-gray-900 mb-2">
                            <svg class="w-4 h-4 mr-2 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" required autofocus
                            class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 placeholder:text-gray-400 font-medium">
                        <p class="mt-2 text-xs text-gray-500">Ingresa el email asociado a tu cuenta</p>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#D4A574] via-[#C39563] to-[#D4A574] text-white py-4 rounded-xl font-bold text-lg hover:shadow-2xl hover:scale-[1.02] transition-all duration-500 shadow-lg">
                        Enviar Enlace de Recuperación
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-gray-600 hover:text-[#D4A574] font-semibold transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-8 bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-white/30">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ¿Necesitas ayuda?
                </h3>
                <div class="space-y-3 text-sm text-gray-700">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-[#D4A574] flex-shrink-0 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Si no recibes el email en 5 minutos, revisa tu carpeta de spam</p>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-[#D4A574] flex-shrink-0 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p>Contacta a soporte: <a href="mailto:soporte@romanticgifts.com" class="text-[#D4A574] hover:text-[#C39563] font-semibold">soporte@romanticgifts.com</a></p>
                    </div>
                </div>
            </div>

            <!-- Back to Store -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 font-semibold transition-colors group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Volver a la tienda
                </a>
            </div>
        </div>
    </div>
</main>
@endsection
