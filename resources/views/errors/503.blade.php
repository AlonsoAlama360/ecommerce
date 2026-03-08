@extends('errors.layout', [
    'title' => 'En mantenimiento - ' . config('app.name'),
    'icon' => 'fa-wrench',
    'iconColor' => '[#D4A574]',
    'accentBg' => '[#D4A574]',
    'accentBg2' => '[#C39563]',
    'gradientFrom' => 'from-[#D4A574]',
    'gradientVia' => 'via-[#C39563]',
    'gradientTo' => 'to-[#E8B4B8]',
])

@section('code', '503')
@section('heading', 'Estamos en mantenimiento')
@section('message', 'Estamos realizando mejoras para brindarte una mejor experiencia. Volveremos en breve. ¡Gracias por tu paciencia!')

@section('extra_button')
    <button onclick="window.location.reload()"
       class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-white text-gray-700 px-8 py-4 rounded-xl font-semibold text-sm border border-gray-200 hover:border-[#D4A574]/30 hover:text-[#D4A574] hover:shadow-lg hover:shadow-[#D4A574]/5 transition-all duration-300 hover:-translate-y-0.5">
        <i class="fas fa-rotate-right text-xs"></i>
        Verificar estado
    </button>
@endsection
