@extends('errors.layout', [
    'title' => 'Error del servidor - ' . config('app.name'),
    'icon' => 'fa-triangle-exclamation',
    'iconColor' => '[#e74c3c]',
    'accentBg' => '[#e74c3c]',
    'accentBg2' => '[#E8B4B8]',
    'gradientFrom' => 'from-[#e74c3c]',
    'gradientVia' => 'via-[#D4A574]',
    'gradientTo' => 'to-[#E8B4B8]',
])

@section('code', '500')
@section('heading', 'Error del servidor')
@section('message', 'Algo salió mal de nuestro lado. Estamos trabajando para solucionarlo. Por favor intenta nuevamente en unos momentos.')

@section('extra_button')
    <button onclick="window.location.reload()"
       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-white text-gray-700 px-8 py-4 rounded-xl font-semibold text-sm border border-gray-200 hover:border-[#D4A574]/30 hover:text-[#D4A574] hover:shadow-lg hover:shadow-[#D4A574]/5 transition-all duration-300 hover:-translate-y-0.5">
        <i class="fas fa-rotate-right text-xs"></i>
        Reintentar
    </button>
@endsection
