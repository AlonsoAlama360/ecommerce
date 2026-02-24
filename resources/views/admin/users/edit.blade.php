@extends('admin.layouts.app')
@section('title', 'Editar Usuario')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
        <i class="fas fa-arrow-left text-xs"></i> Volver a usuarios
    </a>
    <h1 class="text-2xl font-bold text-gray-900 mt-2">Editar Usuario</h1>
    <p class="text-gray-500 mt-1">{{ $user->full_name }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 max-w-2xl">
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-4 sm:p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            <!-- Nombre -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('first_name') border-red-400 @enderror">
                @error('first_name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Apellido -->
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Apellido <span class="text-red-500">*</span></label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('last_name') border-red-400 @enderror">
                @error('last_name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="sm:col-span-2">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Teléfono -->
            <div class="sm:col-span-2">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('phone') border-red-400 @enderror">
                @error('phone')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                <input type="password" name="password" id="password"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm @error('password') border-red-400 @enderror"
                       placeholder="Dejar vacío para mantener">
                @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm"
                       placeholder="Confirmar nueva contraseña">
            </div>
        </div>

        <!-- Rol y opciones -->
        <div class="mt-5 pt-5 border-t border-gray-100 space-y-4">
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-500">*</span></label>
                <select name="role" id="role" required
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none text-sm bg-white @error('role') border-red-400 @enderror">
                    <option value="cliente" {{ old('role', $user->role) === 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="vendedor" {{ old('role', $user->role) === 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
                @error('role')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500">
                <div>
                    <span class="text-sm font-medium text-gray-700">Usuario activo</span>
                    <p class="text-xs text-gray-400">Puede iniciar sesión en la plataforma</p>
                </div>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="newsletter" value="0">
                <input type="checkbox" name="newsletter" value="1" {{ old('newsletter', $user->newsletter) ? 'checked' : '' }}
                       class="w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500">
                <div>
                    <span class="text-sm font-medium text-gray-700">Newsletter</span>
                    <p class="text-xs text-gray-400">Recibir correos promocionales</p>
                </div>
            </label>
        </div>

        <!-- Info -->
        <div class="mt-5 pt-5 border-t border-gray-100">
            <div class="flex flex-wrap gap-4 text-xs text-gray-400">
                <span><i class="fas fa-calendar-alt mr-1"></i> Registro: {{ $user->created_at->format('d/m/Y H:i') }}</span>
                <span><i class="fas fa-clock mr-1"></i> Última actualización: {{ $user->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-6 pt-5 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-center">
                Cancelar
            </a>
            <button type="submit"
                    class="px-6 py-2.5 text-sm bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition font-medium">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
