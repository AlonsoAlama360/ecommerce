@extends('errors.layout', [
    'title' => 'Acceso denegado - ' . config('app.name'),
    'icon' => 'fa-lock',
    'iconColor' => '[#E8B4B8]',
    'accentBg' => '[#E8B4B8]',
    'accentBg2' => '[#D4A574]',
    'gradientFrom' => 'from-[#E8B4B8]',
    'gradientVia' => 'via-[#D4A574]',
    'gradientTo' => 'to-[#C39563]',
])

@section('code', '403')
@section('heading', 'Acceso denegado')
@section('message', 'No tienes permisos para acceder a esta página. Si crees que esto es un error, por favor contacta con nosotros.')
