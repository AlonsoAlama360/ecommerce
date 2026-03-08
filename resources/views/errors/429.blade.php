@extends('errors.layout', [
    'title' => 'Demasiadas solicitudes - ' . config('app.name'),
    'icon' => 'fa-hand',
    'iconColor' => '[#D4A574]',
])

@section('code', '429')
@section('heading', 'Demasiadas solicitudes')
@section('message', 'Has realizado muchas solicitudes en poco tiempo. Por favor espera unos momentos antes de intentar nuevamente.')

@section('extra_button')
    <button onclick="window.location.reload()"
       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-white text-gray-700 px-8 py-4 rounded-xl font-semibold text-sm border border-gray-200 hover:border-[#D4A574]/30 hover:text-[#D4A574] hover:shadow-lg hover:shadow-[#D4A574]/5 transition-all duration-300 hover:-translate-y-0.5">
        <i class="fas fa-rotate-right text-xs"></i>
        Reintentar
    </button>
@endsection
