@extends('errors.layout', [
    'title' => 'Página no encontrada - ' . config('app.name'),
    'icon' => 'fa-map-signs',
    'iconColor' => '[#D4A574]',
])

@section('code', '404')
@section('heading', 'Página no encontrada')
@section('message', 'Lo sentimos, la página que buscas no existe o ha sido movida. Pero no te preocupes, hay mucho por descubrir.')
