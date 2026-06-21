@extends('layouts.admin')

@section('title', 'Configuración')
@section('header', 'Configuración de la Clínica')

@section('content')
<div class="max-w-2xl">

    <p class="text-gray-500 text-sm mb-6">
        Esta información aparece en el sitio web público: footer, página de inicio y contacto.
    </p>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Nombre --}}
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
            <h2 class="font-semibold text-gray-700 border-b pb-2">Datos generales</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la clínica</label>
                <input type="text" name="name" value="{{ old('name', $tenant->name) }}"
                       class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"
                       required>
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción corta</label>
                <textarea name="description" rows="3"
                          class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Breve descripción que aparece en el sitio web...">{{ old('description', $tenant->description) }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Contacto --}}
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
            <h2 class="font-semibold text-gray-700 border-b pb-2">
                <i class="fa-solid fa-address-book text-indigo-400 mr-2"></i>Información de contacto
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fa-solid fa-phone text-gray-400 mr-1"></i>Teléfono / WhatsApp
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}"
                           placeholder="Ej: +504 9999-9999"
                           class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fa-solid fa-envelope text-gray-400 mr-1"></i>Correo de contacto
                    </label>
                    <input type="email" name="email" value="{{ old('email', $tenant->email) }}"
                           placeholder="contacto@ejemplo.com"
                           class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fa-solid fa-location-dot text-gray-400 mr-1"></i>Dirección
                </label>
                <input type="text" name="address" value="{{ old('address', $tenant->address) }}"
                       placeholder="Ej: Col. Kennedy, Tegucigalpa, Honduras"
                       class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Logo --}}
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <h2 class="font-semibold text-gray-700 border-b pb-2">
                <i class="fa-solid fa-image text-indigo-400 mr-2"></i>Logo
            </h2>

            @if($tenant->logo)
                <div class="flex items-center gap-4">
                    <img src="{{ Storage::url($tenant->logo) }}" alt="Logo actual" class="h-16 rounded">
                    <span class="text-xs text-gray-400">Logo actual. Sube uno nuevo para reemplazarlo.</span>
                </div>
            @endif

            <div>
                <input type="file" name="logo" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-400 mt-1">PNG, JPG o SVG. Máximo 2MB.</p>
                @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-indigo-600 text-white px-8 py-2.5 rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                <i class="fa-solid fa-floppy-disk mr-2"></i>Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection
