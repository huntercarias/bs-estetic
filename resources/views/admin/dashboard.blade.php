@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-xl p-5 shadow text-center">
        <div class="text-3xl font-bold text-indigo-600">{{ $stats['services'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Servicios</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow text-center">
        <div class="text-3xl font-bold text-green-600">{{ $stats['products'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Productos</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow text-center">
        <div class="text-3xl font-bold text-purple-600">{{ $stats['categories'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Categorías</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow text-center">
        <div class="text-3xl font-bold text-blue-600">{{ $stats['active_services'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Servicios Activos</div>
    </div>
    <div class="bg-white rounded-xl p-5 shadow text-center">
        <div class="text-3xl font-bold text-emerald-600">{{ $stats['active_products'] }}</div>
        <div class="text-sm text-gray-500 mt-1">Productos Activos</div>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-4">
    <a href="{{ route('admin.services.create') }}"
       class="bg-indigo-600 text-white rounded-xl p-6 hover:bg-indigo-700 transition flex items-center gap-4">
        <i class="fa-solid fa-plus-circle text-3xl"></i>
        <div>
            <div class="font-bold text-lg">Nuevo Servicio</div>
            <div class="text-indigo-200 text-sm">Agregar al catálogo</div>
        </div>
    </a>
    <a href="{{ route('admin.products.create') }}"
       class="bg-green-600 text-white rounded-xl p-6 hover:bg-green-700 transition flex items-center gap-4">
        <i class="fa-solid fa-box-open text-3xl"></i>
        <div>
            <div class="font-bold text-lg">Nuevo Producto</div>
            <div class="text-green-200 text-sm">Agregar al catálogo</div>
        </div>
    </a>
    <a href="{{ route('admin.categories.create') }}"
       class="bg-purple-600 text-white rounded-xl p-6 hover:bg-purple-700 transition flex items-center gap-4">
        <i class="fa-solid fa-tags text-3xl"></i>
        <div>
            <div class="font-bold text-lg">Nueva Categoría</div>
            <div class="text-purple-200 text-sm">Organizar el catálogo</div>
        </div>
    </a>
</div>
@endsection
