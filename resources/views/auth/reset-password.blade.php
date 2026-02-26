@extends('layouts.auth')

@section('title', 'Restablecer Contraseña')

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-[#FFE5E5] rounded-2xl shadow-lg flex items-center justify-center" style="animation: float 5s ease-in-out infinite;">
                    <svg class="w-8 h-8 text-[#C39563]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <div class="text-center space-y-4">
                <h2 class="text-4xl font-serif font-bold gradient-text">Crea una Contraseña Segura</h2>
                <p class="text-gray-600 text-lg max-w-md">Protege tu cuenta con una contraseña fuerte y única</p>
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

            <!-- Reset Password Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">

                <!-- Icon -->
                <div class="w-16 h-16 bg-gradient-to-br from-[#D4A574] to-[#C39563] rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-serif font-semibold text-gray-900 mb-2">Restablecer Contraseña</h1>
                    <p class="text-gray-600">Ingresa tu nueva contraseña segura</p>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                        @foreach($errors->all() as $error)
                            <p class="text-red-600 text-sm font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6" id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div>
                        <label class="flex items-center text-sm font-bold text-gray-900 mb-2">
                            <svg class="w-4 h-4 mr-2 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Nueva Contraseña
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="newPassword" placeholder="••••••••" required
                                class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 placeholder:text-gray-400 font-medium">
                            <button type="button" onclick="togglePassword('newPassword')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D4A574] transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-3 space-y-2">
                            <div class="flex gap-1">
                                <div class="h-2 flex-1 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="strengthBar" class="h-full bg-gray-300 transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>
                            <p id="strengthText" class="text-xs text-gray-500 font-medium">Fortaleza: Débil</p>
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center text-sm font-bold text-gray-900 mb-2">
                            <svg class="w-4 h-4 mr-2 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Confirmar Nueva Contraseña
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="confirmPassword" placeholder="••••••••" required
                                class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 placeholder:text-gray-400 font-medium">
                            <button type="button" onclick="togglePassword('confirmPassword')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D4A574] transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <p id="matchError" class="hidden mt-2 text-xs text-red-600 font-medium">Las contraseñas no coinciden</p>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-gradient-to-r from-[#FAF8F5] to-[#F5E6D3] rounded-xl p-5 space-y-2 border border-gray-200">
                        <p class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-[#D4A574]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tu contraseña debe contener:
                        </p>
                        <div class="space-y-2">
                            <div id="req-length" class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Mínimo 8 caracteres</span>
                            </div>
                            <div id="req-uppercase" class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Al menos una letra mayúscula</span>
                            </div>
                            <div id="req-lowercase" class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Al menos una letra minúscula</span>
                            </div>
                            <div id="req-number" class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Al menos un número</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#D4A574] via-[#C39563] to-[#D4A574] text-white py-4 rounded-xl font-bold text-lg hover:shadow-2xl hover:scale-[1.02] transition-all duration-500 shadow-lg">
                        Restablecer Contraseña
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

            <!-- Back to Store -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900 font-semibold transition-colors group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
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

    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const matchError = document.getElementById('matchError');

    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');

    function updateRequirement(element, isValid) {
        if (isValid) {
            element.classList.remove('text-gray-600');
            element.classList.add('text-green-600');
            element.querySelector('svg').innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>`;
            element.querySelector('svg').classList.remove('text-gray-400');
            element.querySelector('svg').classList.add('text-green-600');
        } else {
            element.classList.remove('text-green-600');
            element.classList.add('text-gray-600');
            element.querySelector('svg').innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>`;
            element.querySelector('svg').classList.remove('text-green-600');
            element.querySelector('svg').classList.add('text-gray-400');
        }
    }

    newPasswordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;

        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);

        updateRequirement(reqLength, hasLength);
        updateRequirement(reqUppercase, hasUppercase);
        updateRequirement(reqLowercase, hasLowercase);
        updateRequirement(reqNumber, hasNumber);

        if (hasLength) strength += 25;
        if (hasUppercase) strength += 25;
        if (hasLowercase) strength += 25;
        if (hasNumber) strength += 25;

        strengthBar.style.width = strength + '%';

        if (strength <= 25) {
            strengthBar.className = 'h-full bg-red-500 transition-all duration-300';
            strengthText.textContent = 'Fortaleza: Muy débil';
            strengthText.className = 'text-xs text-red-600 font-medium';
        } else if (strength <= 50) {
            strengthBar.className = 'h-full bg-orange-500 transition-all duration-300';
            strengthText.textContent = 'Fortaleza: Débil';
            strengthText.className = 'text-xs text-orange-600 font-medium';
        } else if (strength <= 75) {
            strengthBar.className = 'h-full bg-yellow-500 transition-all duration-300';
            strengthText.textContent = 'Fortaleza: Buena';
            strengthText.className = 'text-xs text-yellow-600 font-medium';
        } else {
            strengthBar.className = 'h-full bg-green-500 transition-all duration-300';
            strengthText.textContent = 'Fortaleza: Excelente';
            strengthText.className = 'text-xs text-green-600 font-medium';
        }

        if (confirmPasswordInput.value) {
            checkPasswordMatch();
        }
    });

    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    function checkPasswordMatch() {
        if (confirmPasswordInput.value && newPasswordInput.value !== confirmPasswordInput.value) {
            matchError.classList.remove('hidden');
            confirmPasswordInput.classList.add('border-red-500');
        } else {
            matchError.classList.add('hidden');
            confirmPasswordInput.classList.remove('border-red-500');
        }
    }
</script>
@endsection
