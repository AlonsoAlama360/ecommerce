@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-500 mt-1">Resumen general de tu tienda</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Usuarios</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fas fa-users text-blue-500 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Nuevos esta semana</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['new_users_week']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fas fa-user-plus text-green-500 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Productos</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_products']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                <i class="fas fa-box text-purple-500 text-lg"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Categorías</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_categories']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="fas fa-tags text-amber-500 text-lg"></i>
            </div>
        </div>
    </div>
</div>
@endsection
