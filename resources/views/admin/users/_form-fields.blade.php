<div class="space-y-4">
    <!-- Nombre y Apellido -->
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Nombre</label>
            <input type="text" name="first_name" id="{{ $prefix }}_first_name" value="{{ old('first_name', $user?->first_name) }}" required
                   class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition @error('first_name') border-red-400 bg-red-50 @enderror"
                   placeholder="Juan">
            @error('first_name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Apellido</label>
            <input type="text" name="last_name" id="{{ $prefix }}_last_name" value="{{ old('last_name', $user?->last_name) }}" required
                   class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition @error('last_name') border-red-400 bg-red-50 @enderror"
                   placeholder="Pérez">
            @error('last_name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Email -->
    <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Email</label>
        <div class="relative">
            <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="email" name="email" id="{{ $prefix }}_email" value="{{ old('email', $user?->email) }}" required
                   class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition @error('email') border-red-400 bg-red-50 @enderror"
                   placeholder="usuario@email.com">
        </div>
        @error('email')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Teléfono -->
    <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Teléfono <span class="text-gray-300 normal-case">(opcional)</span></label>
        <div class="relative">
            <i class="fas fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="phone" id="{{ $prefix }}_phone" value="{{ old('phone', $user?->phone) }}"
                   class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition @error('phone') border-red-400 bg-red-50 @enderror"
                   placeholder="+51 999 999 999">
        </div>
        @error('phone')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Divider: Documento -->
    <div class="relative py-2">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
        <div class="relative flex justify-center">
            <span class="bg-white px-3 text-xs text-gray-400 uppercase tracking-wider">Documento</span>
        </div>
    </div>

    <!-- Documento -->
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Tipo <span class="text-gray-300 normal-case">(opcional)</span></label>
            <select name="document_type" id="{{ $prefix }}_document_type"
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition appearance-none"
                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                <option value="">Sin documento</option>
                <option value="DNI" {{ old('document_type', $user?->document_type) === 'DNI' ? 'selected' : '' }}>DNI</option>
                <option value="CE" {{ old('document_type', $user?->document_type) === 'CE' ? 'selected' : '' }}>CE</option>
                <option value="RUC" {{ old('document_type', $user?->document_type) === 'RUC' ? 'selected' : '' }}>RUC</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Número <span class="text-gray-300 normal-case">(opcional)</span></label>
            <input type="text" name="document_number" id="{{ $prefix }}_document_number" value="{{ old('document_number', $user?->document_number) }}"
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition"
                placeholder="12345678">
        </div>
    </div>

    <!-- Divider: Dirección -->
    <div class="relative py-2">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
        <div class="relative flex justify-center">
            <span class="bg-white px-3 text-xs text-gray-400 uppercase tracking-wider">Dirección</span>
        </div>
    </div>

    <!-- Ubigeo -->
    <div class="grid grid-cols-3 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Departamento</label>
            <select name="department_id" id="{{ $prefix }}_department_id"
                onchange="loadUbigeoProvinces('{{ $prefix }}', this.value)"
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition appearance-none"
                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                <option value="">Seleccionar</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ old('department_id', $user?->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Provincia</label>
            <select name="province_id" id="{{ $prefix }}_province_id"
                onchange="loadUbigeoDistricts('{{ $prefix }}', this.value)"
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition appearance-none"
                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                <option value="">Seleccionar</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Distrito</label>
            <select name="district_id" id="{{ $prefix }}_district_id"
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition appearance-none"
                style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236b7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                <option value="">Seleccionar</option>
            </select>
        </div>
    </div>

    <!-- Dirección -->
    <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Dirección <span class="text-gray-300 normal-case">(opcional)</span></label>
        <div class="relative">
            <i class="fas fa-map-marker-alt absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="address" id="{{ $prefix }}_address" value="{{ old('address', $user?->address) }}"
                class="w-full pl-10 pr-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition"
                placeholder="Av. Principal 123, Dpto. 4B">
        </div>
    </div>

    <!-- Referencia -->
    <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Referencia <span class="text-gray-300 normal-case">(opcional)</span></label>
        <input type="text" name="address_reference" id="{{ $prefix }}_address_reference" value="{{ old('address_reference', $user?->address_reference) }}"
            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition"
            placeholder="Cerca al parque, frente a la bodega">
    </div>

    <!-- Divider: Seguridad -->
    <div class="relative py-2">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
        <div class="relative flex justify-center">
            <span class="bg-white px-3 text-xs text-gray-400 uppercase tracking-wider">Seguridad</span>
        </div>
    </div>

    <!-- Contraseña -->
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
                Contraseña @if(!$passwordRequired)<span class="text-gray-300 normal-case">(opcional)</span>@endif
            </label>
            <div class="relative">
                <input type="password" name="password" id="{{ $prefix }}_password" {{ $passwordRequired ? 'required' : '' }}
                       class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition pr-10 @error('password') border-red-400 bg-red-50 @enderror"
                       placeholder="{{ $passwordRequired ? 'Min. 8 caracteres' : 'Dejar vacío para mantener' }}">
                <button type="button" onclick="togglePass('{{ $prefix }}_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye text-xs"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Confirmar</label>
            <input type="password" name="password_confirmation" {{ $passwordRequired ? 'required' : '' }}
                   class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none text-sm transition"
                   placeholder="Repetir contraseña">
        </div>
    </div>

    <!-- Divider: Permisos -->
    <div class="relative py-2">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
        <div class="relative flex justify-center">
            <span class="bg-white px-3 text-xs text-gray-400 uppercase tracking-wider">Permisos</span>
        </div>
    </div>

    <!-- Rol -->
    <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Rol del usuario</label>
        <div class="grid grid-cols-3 gap-2">
            <label class="relative cursor-pointer">
                <input type="radio" name="role" value="cliente" class="peer sr-only" {{ old('role', $user?->role ?? 'cliente') === 'cliente' ? 'checked' : '' }}>
                <div class="px-3 py-2.5 text-center rounded-xl border-2 border-gray-200 text-sm text-gray-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 transition font-medium">
                    <i class="fas fa-user block text-base mb-1"></i>
                    Cliente
                </div>
            </label>
            <label class="relative cursor-pointer">
                <input type="radio" name="role" value="vendedor" class="peer sr-only" {{ old('role', $user?->role) === 'vendedor' ? 'checked' : '' }}>
                <div class="px-3 py-2.5 text-center rounded-xl border-2 border-gray-200 text-sm text-gray-600 peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-600 transition font-medium">
                    <i class="fas fa-store block text-base mb-1"></i>
                    Vendedor
                </div>
            </label>
            <label class="relative cursor-pointer">
                <input type="radio" name="role" value="admin" class="peer sr-only" {{ old('role', $user?->role) === 'admin' ? 'checked' : '' }}>
                <div class="px-3 py-2.5 text-center rounded-xl border-2 border-gray-200 text-sm text-gray-600 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-600 transition font-medium">
                    <i class="fas fa-shield-halved block text-base mb-1"></i>
                    Admin
                </div>
            </label>
        </div>
        @error('role')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Toggles -->
    <div class="space-y-3 pt-1">
        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Usuario activo</span>
                    <p class="text-xs text-gray-400">Puede acceder a la plataforma</p>
                </div>
            </div>
            <input type="hidden" name="is_active" value="0">
            <div class="relative">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                <div class="w-10 h-6 bg-gray-300 rounded-full peer-checked:bg-emerald-500 transition-colors"></div>
                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm peer-checked:translate-x-4 transition-transform"></div>
            </div>
        </label>

        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-envelope text-blue-500 text-sm"></i>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Newsletter</span>
                    <p class="text-xs text-gray-400">Recibir correos promocionales</p>
                </div>
            </div>
            <input type="hidden" name="newsletter" value="0">
            <div class="relative">
                <input type="checkbox" name="newsletter" value="1" {{ old('newsletter', $user?->newsletter ?? false) ? 'checked' : '' }} class="sr-only peer">
                <div class="w-10 h-6 bg-gray-300 rounded-full peer-checked:bg-blue-500 transition-colors"></div>
                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-sm peer-checked:translate-x-4 transition-transform"></div>
            </div>
        </label>
    </div>
</div>
