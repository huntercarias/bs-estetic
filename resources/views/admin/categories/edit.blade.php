@extends('layouts.admin')

@section('title', 'Editar Categoría')
@section('header', 'Editar Categoría')

@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}"
          class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
            <select name="type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                <option value="service" {{ old('type', $category->type) === 'service' ? 'selected' : '' }}>Servicio</option>
                <option value="product" {{ old('type', $category->type) === 'product' ? 'selected' : '' }}>Producto</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea name="description" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="active" id="active" value="1" {{ old('active', $category->active) ? 'checked' : '' }}
                   class="w-4 h-4 text-purple-600 rounded">
            <label for="active" class="text-sm text-gray-700">Activa</label>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                Actualizar Categoría
            </button>
            <a href="{{ route('admin.categories.index') }}"
               class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
