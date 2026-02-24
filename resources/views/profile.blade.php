@extends('layouts.app')

@section('title', 'Mi Perfil - Romantic Gifts')

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-gray-900 transition">Inicio</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium">Mi Perfil</span>
            </div>
        </div>
    </div>

    <section class="py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Sidebar -->
                <aside class="lg:w-72 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden sticky top-28">
                        <!-- User Info Header -->
                        <div class="bg-gradient-to-r from-[#D4A574] to-[#C39563] p-6 text-center">
                            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                            </div>
                            <h3 class="text-white font-bold text-lg">{{ $user->full_name }}</h3>
                            <p class="text-white/80 text-sm">{{ $user->email }}</p>
                        </div>

                        <!-- Navigation -->
                        <nav class="p-4 space-y-1">
                            <a href="{{ route('profile.show') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#FAF8F5] text-[#D4A574] font-semibold transition">
                                <i class="fas fa-user w-5 text-center"></i>
                                <span>Mi Perfil</span>
                            </a>
                            <a href="{{ route('wishlist.index') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-heart w-5 text-center"></i>
                                <span>Lista de Deseos</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </nav>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="flex-1 space-y-8">

                    <!-- Toast Notifications -->
                    @if(session('success'))
                        <div id="toast-success" class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                            <p class="text-green-700 font-medium">{{ session('success') }}</p>
                            <button onclick="document.getElementById('toast-success').remove()" class="ml-auto text-green-400 hover:text-green-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Personal Data Section -->
                    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-100 px-6 py-5">
                            <h2 class="font-serif text-2xl font-bold text-gray-900 flex items-center gap-3">
                                <i class="fas fa-user-edit text-[#D4A574]"></i>
                                Datos Personales
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Actualiza tu información personal</p>
                        </div>

                        <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-5">
                            @csrf
                            @method('PUT')

                            @if($errors->has('first_name') || $errors->has('last_name') || $errors->has('email') || $errors->has('phone'))
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                    @foreach($errors->all() as $error)
                                        @if(!str_contains($error, 'contraseña'))
                                            <p class="text-red-600 text-sm font-medium">{{ $error }}</p>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                                        <i class="fas fa-user text-[#D4A574] mr-2 text-sm"></i>
                                        Nombre
                                    </label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                           class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 font-medium @error('first_name') border-red-400 @enderror">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">Apellido</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                           class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 font-medium @error('last_name') border-red-400 @enderror">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-envelope text-[#D4A574] mr-2 text-sm"></i>
                                    Email
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 font-medium @error('email') border-red-400 @enderror">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-phone text-[#D4A574] mr-2 text-sm"></i>
                                    Teléfono <span class="text-gray-400 font-normal ml-1">(opcional)</span>
                                </label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                       placeholder="+51 999 999 999"
                                       class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 font-medium placeholder:text-gray-400">
                            </div>

                            <div class="pt-2">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="checkbox" name="newsletter" {{ $user->newsletter ? 'checked' : '' }}
                                           class="w-5 h-5 text-[#D4A574] border-gray-300 rounded focus:ring-[#D4A574] cursor-pointer">
                                    <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900 font-medium">
                                        Recibir ofertas exclusivas y novedades por email
                                    </span>
                                </label>
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit"
                                        class="bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white px-8 py-3.5 rounded-xl font-bold hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
                                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Section -->
                    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-100 px-6 py-5">
                            <h2 class="font-serif text-2xl font-bold text-gray-900 flex items-center gap-3">
                                <i class="fas fa-lock text-[#D4A574]"></i>
                                Cambiar Contraseña
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Mantén tu cuenta segura actualizando tu contraseña</p>
                        </div>

                        <form method="POST" action="{{ route('profile.password') }}" class="p-6 space-y-5">
                            @csrf
                            @method('PUT')

                            @if($errors->has('current_password') || $errors->has('password'))
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                    @foreach(['current_password', 'password'] as $field)
                                        @error($field)
                                            <p class="text-red-600 text-sm font-medium">{{ $message }}</p>
                                        @enderror
                                    @endforeach
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-key text-[#D4A574] mr-2 text-sm"></i>
                                    Contraseña Actual
                                </label>
                                <div class="relative">
                                    <input type="password" name="current_password" id="currentPassword" required
                                           class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 font-medium @error('current_password') border-red-400 @enderror">
                                    <button type="button" onclick="togglePassword('currentPassword')"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D4A574] transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-lock text-[#D4A574] mr-2 text-sm"></i>
                                    Nueva Contraseña
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" id="newPassword" required
                                           class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 font-medium @error('password') border-red-400 @enderror">
                                    <button type="button" onclick="togglePassword('newPassword')"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D4A574] transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Mínimo 8 caracteres, incluye mayúsculas y números
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-900 mb-2">Confirmar Nueva Contraseña</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="confirmNewPassword" required
                                           class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-300 text-gray-900 font-medium">
                                    <button type="button" onclick="togglePassword('confirmNewPassword')"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D4A574] transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit"
                                        class="bg-gray-900 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-gray-800 hover:shadow-lg transition-all duration-300">
                                    <i class="fas fa-shield-alt mr-2"></i>Actualizar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            button.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            input.type = 'password';
            button.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }

    // Auto-hide success toast after 5 seconds
    const toast = document.getElementById('toast-success');
    if (toast) {
        setTimeout(() => {
            toast.style.transition = 'opacity 0.5s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    }
</script>
@endsection
