@extends('layouts.app')

@section('title', 'Mi Perfil - Arixna')

@php
    $inputClass = "w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 text-sm font-medium placeholder:text-gray-400 focus:bg-white focus:border-[#D4A574] focus:ring-4 focus:ring-[#D4A574]/10 focus:outline-none transition-all duration-200";
    $selectClass = "$inputClass appearance-none cursor-pointer";
    $selectArrow = "background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%239ca3af'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E\"); background-position: right 0.875rem center; background-repeat: no-repeat; background-size: 1rem;";
    $labelClass = "block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2";
@endphp

@section('content')
    <!-- Hero Header -->
    <div class="relative bg-gradient-to-br from-[#D4A574] via-[#C39563] to-[#B8845A] overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full -translate-y-1/2 translate-x-1/3"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full translate-y-1/2 -translate-x-1/4"></div>
        </div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 relative">
            <div class="flex items-center gap-2 text-sm text-white/70 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition">Inicio</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-white font-medium">Mi Cuenta</span>
            </div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30 shadow-lg flex-shrink-0">
                    <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $user->full_name }}</h1>
                    <p class="text-white/70 text-sm mt-1 flex items-center gap-3">
                        <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-xs"></i> {{ $user->email }}</span>
                        @if($user->phone)
                        <span class="hidden sm:flex items-center gap-1.5"><i class="fas fa-phone text-xs"></i> {{ $user->phone }}</span>
                        @endif
                    </p>
                </div>
                <div class="sm:ml-auto flex items-center gap-2">
                    <a href="{{ route('wishlist.index') }}" class="px-4 py-2.5 bg-white/15 backdrop-blur-sm text-white text-sm font-medium rounded-xl border border-white/20 hover:bg-white/25 transition">
                        <i class="fas fa-heart mr-1.5 text-xs"></i> Mis Favoritos
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-white/10 backdrop-blur-sm text-white/80 text-sm font-medium rounded-xl border border-white/15 hover:bg-white/20 hover:text-white transition">
                            <i class="fas fa-sign-out-alt mr-1.5 text-xs"></i> <span class="hidden sm:inline">Salir</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white border-b border-gray-200 sticky top-[64px] z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex gap-1 -mb-px overflow-x-auto scrollbar-hide">
                <button onclick="switchTab('personal')" id="tab-personal"
                    class="tab-btn active-tab px-5 py-4 text-sm font-semibold border-b-2 transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-user mr-2 text-xs"></i> Datos Personales
                </button>
                <button onclick="switchTab('address')" id="tab-address"
                    class="tab-btn px-5 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-map-marker-alt mr-2 text-xs"></i> Dirección de Envío
                </button>
                <button onclick="switchTab('security')" id="tab-security"
                    class="tab-btn px-5 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-shield-alt mr-2 text-xs"></i> Seguridad
                </button>
            </nav>
        </div>
    </div>

    <section class="py-8 lg:py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Toast Notifications -->
            @if(session('success'))
            <div id="toast-success" class="mb-6 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3 animate-slide-down">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-emerald-600"></i>
                </div>
                <p class="text-emerald-700 font-medium text-sm flex-1">{{ session('success') }}</p>
                <button onclick="document.getElementById('toast-success').remove()" class="text-emerald-400 hover:text-emerald-600 transition p-1">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            @endif

            {{-- ==================== TAB: DATOS PERSONALES ==================== --}}
            <div id="panel-personal" class="tab-panel">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    @if($errors->has('first_name') || $errors->has('last_name') || $errors->has('email') || $errors->has('phone'))
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
                        @foreach($errors->all() as $error)
                            @if(!str_contains($error, 'contraseña'))
                            <p class="text-red-600 text-sm font-medium flex items-center gap-2">
                                <i class="fas fa-exclamation-circle text-xs"></i> {{ $error }}
                            </p>
                            @endif
                        @endforeach
                    </div>
                    @endif

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left: Personal Info -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="px-6 py-5 border-b border-gray-100">
                                    <h2 class="text-lg font-bold text-gray-900">Información Personal</h2>
                                    <p class="text-sm text-gray-400 mt-0.5">Tu información básica de cuenta</p>
                                </div>
                                <div class="p-6 space-y-5">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div>
                                            <label class="{{ $labelClass }}">Nombre</label>
                                            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                                                class="{{ $inputClass }} @error('first_name') !border-red-400 @enderror">
                                        </div>
                                        <div>
                                            <label class="{{ $labelClass }}">Apellido</label>
                                            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                                                class="{{ $inputClass }} @error('last_name') !border-red-400 @enderror">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">Email</label>
                                        <div class="relative">
                                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                                class="{{ $inputClass }} !pl-10 @error('email') !border-red-400 @enderror">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">Teléfono</label>
                                        <div class="relative">
                                            <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+51 999 999 999"
                                                class="{{ $inputClass }} !pl-10">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Document + Preferences -->
                        <div class="space-y-6">
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="px-6 py-5 border-b border-gray-100">
                                    <h2 class="text-lg font-bold text-gray-900">Documento</h2>
                                    <p class="text-sm text-gray-400 mt-0.5">Para facturación</p>
                                </div>
                                <div class="p-6 space-y-5">
                                    <div>
                                        <label class="{{ $labelClass }}">Tipo</label>
                                        <select name="document_type" class="{{ $selectClass }}" style="{{ $selectArrow }}">
                                            <option value="">Seleccionar</option>
                                            <option value="DNI" {{ old('document_type', $user->document_type) === 'DNI' ? 'selected' : '' }}>DNI</option>
                                            <option value="CE" {{ old('document_type', $user->document_type) === 'CE' ? 'selected' : '' }}>Carnet de Extranjería</option>
                                            <option value="RUC" {{ old('document_type', $user->document_type) === 'RUC' ? 'selected' : '' }}>RUC</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">Número</label>
                                        <input type="text" name="document_number" value="{{ old('document_number', $user->document_number) }}"
                                            placeholder="Ej: 12345678" class="{{ $inputClass }}">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="p-6">
                                    <label class="flex items-start cursor-pointer group gap-3">
                                        <input type="checkbox" name="newsletter" {{ $user->newsletter ? 'checked' : '' }}
                                            class="w-5 h-5 mt-0.5 text-[#D4A574] border-gray-300 rounded focus:ring-[#D4A574] cursor-pointer flex-shrink-0">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800 group-hover:text-gray-900">Newsletter</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Recibe ofertas exclusivas y novedades</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="group px-8 py-3.5 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white rounded-xl font-bold text-sm shadow-lg shadow-[#D4A574]/25 hover:shadow-xl hover:shadow-[#D4A574]/30 hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fas fa-check mr-2 text-xs group-hover:scale-110 transition-transform"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            {{-- ==================== TAB: DIRECCIÓN DE ENVÍO ==================== --}}
            <div id="panel-address" class="tab-panel hidden">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Hidden fields to preserve personal data -->
                    <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                    <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="phone" value="{{ $user->phone }}">
                    <input type="hidden" name="document_type" value="{{ $user->document_type }}">
                    <input type="hidden" name="document_number" value="{{ $user->document_number }}">
                    @if($user->newsletter)<input type="hidden" name="newsletter" value="1">@endif

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900">Dirección de Envío</h2>
                            <p class="text-sm text-gray-400 mt-0.5">Tu dirección principal para recibir pedidos</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Ubigeo -->
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 bg-[#D4A574]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-map text-[#D4A574] text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Ubicación</p>
                                        <p class="text-xs text-gray-400">Departamento, provincia y distrito</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="{{ $labelClass }}">Departamento</label>
                                        <select name="department_id" id="department_id" onchange="loadProvinces(this.value)"
                                            class="{{ $selectClass }}" style="{{ $selectArrow }}">
                                            <option value="">Seleccionar</option>
                                            @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">Provincia</label>
                                        <select name="province_id" id="province_id" onchange="loadDistricts(this.value)"
                                            class="{{ $selectClass }}" style="{{ $selectArrow }}">
                                            <option value="">Selecciona departamento</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">Distrito</label>
                                        <select name="district_id" id="district_id"
                                            class="{{ $selectClass }}" style="{{ $selectArrow }}">
                                            <option value="">Selecciona provincia</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-gray-100"></div>

                            <!-- Street Address -->
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 bg-[#D4A574]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-home text-[#D4A574] text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Dirección</p>
                                        <p class="text-xs text-gray-400">Calle, número, piso y departamento</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="{{ $labelClass }}">Dirección completa</label>
                                        <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                            placeholder="Av. / Jr. / Calle, Número, Piso, Dpto..."
                                            class="{{ $inputClass }}">
                                    </div>
                                    <div>
                                        <label class="{{ $labelClass }}">Referencia <span class="text-gray-300 font-normal normal-case">(opcional)</span></label>
                                        <input type="text" name="address_reference" value="{{ old('address_reference', $user->address_reference) }}"
                                            placeholder="Cerca de..., frente a..., al costado de..."
                                            class="{{ $inputClass }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Current Address Preview -->
                            @if($user->address && $user->district)
                            <div class="bg-[#FAF8F5] rounded-xl p-4 flex items-start gap-3">
                                <div class="w-8 h-8 bg-[#D4A574]/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-location-dot text-[#D4A574] text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Dirección actual</p>
                                    <p class="text-sm text-gray-800 font-medium">{{ $user->full_address }}</p>
                                    @if($user->address_reference)
                                    <p class="text-xs text-gray-400 mt-1">Ref: {{ $user->address_reference }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="group px-8 py-3.5 bg-gradient-to-r from-[#D4A574] to-[#C39563] text-white rounded-xl font-bold text-sm shadow-lg shadow-[#D4A574]/25 hover:shadow-xl hover:shadow-[#D4A574]/30 hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fas fa-check mr-2 text-xs group-hover:scale-110 transition-transform"></i> Guardar Dirección
                        </button>
                    </div>
                </form>
            </div>

            {{-- ==================== TAB: SEGURIDAD ==================== --}}
            <div id="panel-security" class="tab-panel hidden">
                <div class="max-w-2xl">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900">Cambiar Contraseña</h2>
                            <p class="text-sm text-gray-400 mt-0.5">Mantén tu cuenta segura con una contraseña fuerte</p>
                        </div>

                        <form method="POST" action="{{ route('profile.password') }}" class="p-6 space-y-5">
                            @csrf
                            @method('PUT')

                            @if($errors->has('current_password') || $errors->has('password'))
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                @foreach(['current_password', 'password'] as $field)
                                    @error($field)
                                    <p class="text-red-600 text-sm font-medium flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle text-xs"></i> {{ $message }}
                                    </p>
                                    @enderror
                                @endforeach
                            </div>
                            @endif

                            <div>
                                <label class="{{ $labelClass }}">Contraseña actual</label>
                                <div class="relative">
                                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    <input type="password" name="current_password" id="currentPassword" required
                                        class="{{ $inputClass }} !pl-10 @error('current_password') !border-red-400 @enderror">
                                    <button type="button" onclick="togglePassword('currentPassword')"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D4A574] transition-colors"
                                        aria-label="Mostrar contraseña">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-5">
                                <label class="{{ $labelClass }}">Nueva contraseña</label>
                                <div class="relative">
                                    <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    <input type="password" name="password" id="newPassword" required
                                        class="{{ $inputClass }} !pl-10 @error('password') !border-red-400 @enderror">
                                    <button type="button" onclick="togglePassword('newPassword')"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D4A574] transition-colors"
                                        aria-label="Mostrar contraseña">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                </div>
                                <div class="flex items-center gap-4 mt-3">
                                    <div class="flex-1 flex gap-1">
                                        <div class="h-1 rounded-full bg-gray-200 flex-1" id="str-1"></div>
                                        <div class="h-1 rounded-full bg-gray-200 flex-1" id="str-2"></div>
                                        <div class="h-1 rounded-full bg-gray-200 flex-1" id="str-3"></div>
                                        <div class="h-1 rounded-full bg-gray-200 flex-1" id="str-4"></div>
                                    </div>
                                    <span class="text-xs text-gray-400" id="str-text"></span>
                                </div>
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">Confirmar nueva contraseña</label>
                                <div class="relative">
                                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    <input type="password" name="password_confirmation" id="confirmNewPassword" required
                                        class="{{ $inputClass }} !pl-10">
                                    <button type="button" onclick="togglePassword('confirmNewPassword')"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D4A574] transition-colors"
                                        aria-label="Mostrar contraseña">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="pt-3 flex justify-end">
                                <button type="submit"
                                    class="group px-8 py-3.5 bg-gray-900 text-white rounded-xl font-bold text-sm shadow-lg shadow-gray-900/20 hover:shadow-xl hover:bg-gray-800 hover:-translate-y-0.5 transition-all duration-300">
                                    <i class="fas fa-shield-alt mr-2 text-xs"></i> Actualizar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Tips -->
                    <div class="mt-6 bg-amber-50/70 border border-amber-200/50 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-amber-800 flex items-center gap-2 mb-3">
                            <i class="fas fa-lightbulb text-amber-500"></i> Consejos de seguridad
                        </h3>
                        <ul class="space-y-2">
                            <li class="text-xs text-amber-700 flex items-start gap-2">
                                <i class="fas fa-check-circle text-amber-400 mt-0.5 flex-shrink-0"></i>
                                Usa al menos 8 caracteres con mayúsculas y números
                            </li>
                            <li class="text-xs text-amber-700 flex items-start gap-2">
                                <i class="fas fa-check-circle text-amber-400 mt-0.5 flex-shrink-0"></i>
                                No reutilices contraseñas de otros sitios
                            </li>
                            <li class="text-xs text-amber-700 flex items-start gap-2">
                                <i class="fas fa-check-circle text-amber-400 mt-0.5 flex-shrink-0"></i>
                                Cambia tu contraseña regularmente
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <style>
        .tab-btn.active-tab { color: #D4A574; border-color: #D4A574; }
        .tab-panel { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: translateY(0); } }
        .animate-slide-down { animation: slideDown 0.4s ease; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
@endsection

@section('scripts')
<script>
    // ==================== TAB SYSTEM ====================
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active-tab');
            b.classList.add('border-transparent', 'text-gray-400');
        });

        document.getElementById('panel-' + tab).classList.remove('hidden');
        const btn = document.getElementById('tab-' + tab);
        btn.classList.add('active-tab');
        btn.classList.remove('border-transparent', 'text-gray-400');
    }

    // Show address tab if there are address-related errors or hash
    @if($errors->has('department_id') || $errors->has('province_id') || $errors->has('district_id') || $errors->has('address'))
        switchTab('address');
    @elseif($errors->has('current_password') || $errors->has('password'))
        switchTab('security');
    @endif

    if (window.location.hash === '#address') switchTab('address');
    if (window.location.hash === '#security') switchTab('security');

    // ==================== PASSWORD ====================
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            button.innerHTML = '<i class="fas fa-eye-slash text-sm"></i>';
        } else {
            input.type = 'password';
            button.innerHTML = '<i class="fas fa-eye text-sm"></i>';
        }
    }

    // Password strength meter
    document.getElementById('newPassword').addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = ['', 'bg-red-400', 'bg-amber-400', 'bg-emerald-400', 'bg-emerald-500'];
        const labels = ['', 'Débil', 'Regular', 'Buena', 'Fuerte'];

        for (let i = 1; i <= 4; i++) {
            const bar = document.getElementById('str-' + i);
            bar.className = 'h-1 rounded-full flex-1 transition-all duration-300 ' + (i <= score ? colors[score] : 'bg-gray-200');
        }
        document.getElementById('str-text').textContent = val.length > 0 ? labels[score] : '';
    });

    // ==================== TOAST ====================
    const toast = document.getElementById('toast-success');
    if (toast) {
        setTimeout(() => {
            toast.style.transition = 'opacity 0.5s, transform 0.5s';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-12px)';
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    }

    // ==================== UBIGEO SELECTS ====================
    const userProvinceId = {{ $user->province_id ?? 'null' }};
    const userDistrictId = {{ $user->district_id ?? 'null' }};

    function loadProvinces(departmentId) {
        const provinceSelect = document.getElementById('province_id');
        const districtSelect = document.getElementById('district_id');

        provinceSelect.innerHTML = '<option value="">Cargando...</option>';
        districtSelect.innerHTML = '<option value="">Selecciona provincia</option>';

        if (!departmentId) {
            provinceSelect.innerHTML = '<option value="">Selecciona departamento</option>';
            return;
        }

        fetch('/api/departments/' + departmentId + '/provinces')
            .then(r => r.json())
            .then(provinces => {
                provinceSelect.innerHTML = '<option value="">Seleccionar provincia</option>';
                provinces.forEach(p => {
                    const selected = userProvinceId == p.id ? ' selected' : '';
                    provinceSelect.innerHTML += '<option value="' + p.id + '"' + selected + '>' + p.name + '</option>';
                });
                if (provinceSelect.value) {
                    loadDistricts(provinceSelect.value);
                }
            });
    }

    function loadDistricts(provinceId) {
        const districtSelect = document.getElementById('district_id');
        districtSelect.innerHTML = '<option value="">Cargando...</option>';

        if (!provinceId) {
            districtSelect.innerHTML = '<option value="">Selecciona provincia</option>';
            return;
        }

        fetch('/api/provinces/' + provinceId + '/districts')
            .then(r => r.json())
            .then(districts => {
                districtSelect.innerHTML = '<option value="">Seleccionar distrito</option>';
                districts.forEach(d => {
                    const selected = userDistrictId == d.id ? ' selected' : '';
                    districtSelect.innerHTML += '<option value="' + d.id + '"' + selected + '>' + d.name + '</option>';
                });
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const deptSelect = document.getElementById('department_id');
        if (deptSelect && deptSelect.value) {
            loadProvinces(deptSelect.value);
        }
    });
</script>
@endsection
