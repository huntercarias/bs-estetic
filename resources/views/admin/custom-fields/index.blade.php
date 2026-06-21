@extends('layouts.admin')
@section('title','Campos Personalizados')
@section('header','Campos Personalizados por Servicio')

@section('content')
<div class="flex justify-between items-center mb-5">
    <p class="text-sm text-gray-500">Define qué datos capturar en cada tipo de cita</p>
    <a href="{{ route('admin.custom-fields.create') }}"
       class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700">
        <i class="fa-solid fa-plus mr-1"></i> Nuevo Campo
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 text-left">Etiqueta</th>
                <th class="px-5 py-3 text-left">Servicio</th>
                <th class="px-5 py-3 text-left">Tipo</th>
                <th class="px-5 py-3 text-left">Obligatorio</th>
                <th class="px-5 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($fields as $field)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <div class="font-medium">{{ $field->label }}</div>
                    <div class="text-xs text-gray-400 font-mono">{{ $field->name }}</div>
                </td>
                <td class="px-5 py-3 text-gray-500">{{ $field->service?->name ?? 'Todos los servicios' }}</td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 bg-teal-100 text-teal-700 rounded text-xs font-medium">{{ $field->type }}</span>
                </td>
                <td class="px-5 py-3">
                    <span class="{{ $field->required ? 'text-red-500' : 'text-gray-400' }} text-xs">
                        {{ $field->required ? '✓ Sí' : 'No' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-right">
                    <a href="{{ route('admin.custom-fields.edit', $field) }}" class="text-teal-600 hover:text-teal-800 mr-2">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.custom-fields.destroy', $field) }}" class="inline"
                          onsubmit="return confirm('¿Eliminar este campo?')">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">No hay campos personalizados aún.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $fields->links() }}</div>
@endsection
