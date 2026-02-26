@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

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
                    <svg class="w-10 h-10 text-[#D4A574]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                </div>

                <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-[#FFE5E5] rounded-2xl shadow-lg flex items-center justify-center" style="animation: float 5s ease-in-out infinite;">
                    <svg class="w-8 h-8 text-[#C39563]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 11.75A2.25 2.25 0 1 1 11.25 14 2.25 2.25 0 0 1 9 11.75zm6 0A2.25 2.25 0 1 1 17.25 14 2.25 2.25 0 0 1 15 11.75z" />
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z" />
                    </svg>
                </div>
            </div>

            <div class="text-center space-y-4">
                <h2 class="text-4xl font-serif font-bold gradient-text">Detalles que enamoran</h2>
                <p class="text-gray-600 text-lg max-w-md">Más de 10,000 clientes felices han encontrado el regalo perfecto</p>

                <div class="flex items-center justify-center gap-8 pt-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#D4A574]">10K+</div>
                        <div class="text-sm text-gray-600">Clientes</div>
                    </div>
                    <div class="w-px h-12 bg-gray-300"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#D4A574]">500+</div>
                        <div class="text-sm text-gray-600">Productos</div>
                    </div>
                    <div class="w-px h-12 bg-gray-300"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-[#D4A574]">4.9★</div>
                        <div class="text-sm text-gray-600">Valoración</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full max-w-md mx-auto">

            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center space-x-2 mb-6 group">
                    <img src="https://aztrosperu.com/cdn/shop/files/Logo_Aztros_copia.png?v=1669076562&width=500"
                        alt="Logo" class="h-10">
                </a>
            </div>

            <!-- Login Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">

                <!-- Social Login Buttons -->
                <div class="space-y-3 mb-6">
                    <a href="{{ route('auth.google') }}"
                        class="w-full flex items-center justify-center px-6 py-4 border-2 border-gray-200 rounded-xl hover:border-[#D4A574] hover:bg-[#FAF8F5] hover:shadow-lg transition-all duration-300 group">
                        <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span class="font-semibold text-gray-700 group-hover:text-gray-900">Continuar con Google</span>
                    </a>

                    <a href="{{ route('auth.facebook') }}"
                        class="w-full flex items-center justify-center px-6 py-4 border-2 border-gray-200 rounded-xl hover:border-[#D4A574] hover:bg-[#FAF8F5] hover:shadow-lg transition-all duration-300 group">
                        <svg class="w-6 h-6 mr-3" fill="#1877F2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                        <span class="font-semibold text-gray-700 group-hover:text-gray-900">Continuar con Facebook</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-semibold">O con tu email</span>
                    </div>
                </div>

                <!-- Status Message -->
                @if(session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <p class="text-green-700 text-sm font-medium">{{ session('status') }}</p>
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

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" required autofocus
                            class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 placeholder:text-gray-400 font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Contraseña
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="loginPassword" placeholder="••••••••" required
                                class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 placeholder:text-gray-400 font-medium">
                            <button type="button" onclick="togglePassword('loginPassword')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D4A574] transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember"
                                class="w-5 h-5 text-[#D4A574] border-gray-300 rounded focus:ring-[#D4A574] cursor-pointer">
                            <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900">Recordarme</span>
                        </label>
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-[#D4A574] hover:text-[#C39563] font-bold transition-colors">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#D4A574] via-[#C39563] to-[#D4A574] text-white py-4 rounded-xl font-bold text-lg hover:shadow-2xl hover:scale-[1.02] transition-all duration-500 shadow-lg">
                        Iniciar Sesión
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600 font-medium">
                        ¿No tienes una cuenta?
                        <a href="{{ route('register') }}" class="text-[#D4A574] hover:text-[#C39563] font-bold transition-colors">Regístrate gratis</a>
                    </p>
                </div>
            </div>

            <!-- Back to Store -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 font-semibold transition-colors group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver a la tienda
                </a>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            button.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`;
        } else {
            input.type = 'password';
            button.innerHTML = `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
        }
    }
</script>
@endsection
