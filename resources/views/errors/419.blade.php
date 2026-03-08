@extends('errors.layout', [
    'title' => 'Sesión expirada - ' . config('app.name'),
    'icon' => 'fa-clock',
    'iconColor' => '[#C39563]',
])

@section('code', '419')
@section('heading', 'Sesión expirada')
@section('message', 'Tu sesión ha expirado por inactividad. Por favor recarga la página e intenta nuevamente.')

@section('extra_button')
    <button onclick="window.location.reload()"
       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-white text-gray-700 px-8 py-4 rounded-xl font-semibold text-sm border border-gray-200 hover:border-[#D4A574]/30 hover:text-[#D4A574] hover:shadow-lg hover:shadow-[#D4A574]/5 transition-all duration-300 hover:-translate-y-0.5">
        <i class="fas fa-rotate-right text-xs"></i>
        Recargar Página
    </button>
@endsection
