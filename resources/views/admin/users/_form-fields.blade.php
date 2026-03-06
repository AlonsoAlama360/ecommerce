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
                <button type="button" onclick="togglePass('{{ $prefix }}_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Mostrar u ocultar contraseña">
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
            <span class="bg-white px-3 text-xs text-gray-400 uppercase tracking-wider">Rol y Permisos</span>
        </div>
    </div>

    <!-- Rol - Custom Select -->
    @php
        $roleColorPool = [
            ['color' => 'indigo', 'icon' => 'fa-shield-halved'],
            ['color' => 'orange', 'icon' => 'fa-store'],
            ['color' => 'teal',   'icon' => 'fa-user'],
            ['color' => 'violet', 'icon' => 'fa-user-tie'],
            ['color' => 'blue',   'icon' => 'fa-user-gear'],
            ['color' => 'emerald','icon' => 'fa-id-badge'],
            ['color' => 'rose',   'icon' => 'fa-user-pen'],
            ['color' => 'sky',    'icon' => 'fa-headset'],
            ['color' => 'amber',  'icon' => 'fa-user-lock'],
            ['color' => 'pink',   'icon' => 'fa-user-tag'],
        ];
        $roleMap = [
            'admin'    => ['color' => 'indigo', 'icon' => 'fa-shield-halved'],
            'vendedor' => ['color' => 'orange', 'icon' => 'fa-store'],
            'cliente'  => ['color' => 'teal',   'icon' => 'fa-user'],
        ];
        $poolIdx = 0;
        $selectedRole = old('role', $user?->role ?? 'cliente');

        // Build role data for JS
        $rolesData = [];
        foreach ($roles as $r) {
            if (isset($roleMap[$r->name])) {
                $rc = $roleMap[$r->name];
            } else {
                $rc = $roleColorPool[($poolIdx + 3) % count($roleColorPool)];
                $poolIdx++;
            }
            $rolesData[] = [
                'name' => $r->name,
                'display_name' => $r->display_name,
                'description' => $r->description,
                'is_admin' => $r->is_admin,
                'color' => $rc['color'],
                'icon' => $rc['icon'],
            ];
        }
    @endphp
    <div>
        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">Rol del usuario</label>
        <input type="hidden" name="role" id="{{ $prefix }}_role" value="{{ $selectedRole }}">

        {{-- Custom select trigger --}}
        <div class="role-select-wrapper relative" data-prefix="{{ $prefix }}">
            <button type="button" class="role-select-trigger w-full flex items-center gap-3 px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm transition hover:border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white outline-none"
                onclick="toggleRoleDropdown(this)">
                <span class="role-select-icon w-7 h-7 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-xs text-gray-400"></i>
                </span>
                <span class="role-select-label flex-1 text-left text-gray-700 font-medium truncate">Seleccionar rol</span>
                <span class="role-select-badge hidden items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-gray-100 text-gray-400 flex-shrink-0">
                    <i class="fas fa-lock-open text-[7px]"></i> Admin
                </span>
                <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200 flex-shrink-0"></i>
            </button>

            {{-- Dropdown --}}
            <div class="role-select-dropdown absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-lg shadow-gray-200/50 z-50 hidden overflow-hidden">
                <div class="max-h-[240px] overflow-y-auto py-1.5 role-dropdown-scroll">
                    @foreach($rolesData as $rd)
                    <button type="button"
                        class="role-option w-full flex items-center gap-3 px-3.5 py-2.5 hover:bg-gray-50 transition-colors text-left"
                        data-value="{{ $rd['name'] }}"
                        data-display="{{ $rd['display_name'] }}"
                        data-description="{{ $rd['description'] }}"
                        data-admin="{{ $rd['is_admin'] ? '1' : '0' }}"
                        data-color="{{ $rd['color'] }}"
                        data-icon="{{ $rd['icon'] }}"
                        onclick="selectRole(this)">
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 role-opt-icon"
                            style="background: var(--role-bg-{{ $rd['color'] }});">
                            <i class="fas {{ $rd['icon'] }} text-xs text-white"></i>
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="text-sm font-medium text-gray-700 block truncate">{{ $rd['display_name'] }}</span>
                            @if($rd['description'])
                            <span class="text-[11px] text-gray-400 block truncate">{{ $rd['description'] }}</span>
                            @endif
                        </span>
                        @if($rd['is_admin'])
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider text-amber-600 bg-amber-50 flex-shrink-0">
                            <i class="fas fa-lock-open text-[7px]"></i> Admin
                        </span>
                        @endif
                        <span class="role-opt-check w-4 h-4 rounded-full flex items-center justify-center flex-shrink-0 hidden">
                            <i class="fas fa-check text-[9px] text-white"></i>
                        </span>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        @error('role')
            <p class="mt-1.5 text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
        @enderror
    </div>

    {{-- Role color variables & dropdown styles --}}
    <style>
        :root {
            --role-bg-indigo: linear-gradient(135deg, #6366f1, #4f46e5);
            --role-bg-orange: linear-gradient(135deg, #f97316, #ea580c);
            --role-bg-teal: linear-gradient(135deg, #14b8a6, #0d9488);
            --role-bg-violet: linear-gradient(135deg, #8b5cf6, #7c3aed);
            --role-bg-blue: linear-gradient(135deg, #3b82f6, #2563eb);
            --role-bg-emerald: linear-gradient(135deg, #10b981, #059669);
            --role-bg-rose: linear-gradient(135deg, #f43f5e, #e11d48);
            --role-bg-sky: linear-gradient(135deg, #0ea5e9, #0284c7);
            --role-bg-amber: linear-gradient(135deg, #f59e0b, #d97706);
            --role-bg-pink: linear-gradient(135deg, #ec4899, #db2777);

            --role-text-indigo: #4f46e5; --role-light-indigo: #eef2ff;
            --role-text-orange: #ea580c; --role-light-orange: #fff7ed;
            --role-text-teal: #0d9488;   --role-light-teal: #f0fdfa;
            --role-text-violet: #7c3aed; --role-light-violet: #f5f3ff;
            --role-text-blue: #2563eb;   --role-light-blue: #eff6ff;
            --role-text-emerald: #059669; --role-light-emerald: #ecfdf5;
            --role-text-rose: #e11d48;   --role-light-rose: #fff1f2;
            --role-text-sky: #0284c7;    --role-light-sky: #f0f9ff;
            --role-text-amber: #d97706;  --role-light-amber: #fffbeb;
            --role-text-pink: #db2777;   --role-light-pink: #fdf2f8;
        }
        .role-dropdown-scroll::-webkit-scrollbar { width: 4px; }
        .role-dropdown-scroll::-webkit-scrollbar-track { background: transparent; }
        .role-dropdown-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 9999px; }
        .role-dropdown-scroll::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
        .role-option:hover .role-opt-icon { transform: scale(1.05); }
        .role-opt-icon { transition: transform 0.15s ease; }
    </style>

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
