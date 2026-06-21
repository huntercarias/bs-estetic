@extends('layouts.admin')

@section('title', 'Productos')
@section('header', 'Gestión de Productos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-gray-600 text-sm">{{ $products->total() }} productos registrados</h2>
    <a href="{{ route('admin.products.create') }}"
       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
        <i class="fa-solid fa-plus mr-1"></i> Nuevo Producto
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Producto</th>
                <th class="px-6 py-3 text-left">SKU</th>
                <th class="px-6 py-3 text-left">Categoría</th>
                <th class="px-6 py-3 text-left">Precio</th>
                <th class="px-6 py-3 text-left">Stock</th>
                <th class="px-6 py-3 text-left">Estado</th>
                <th class="px-6 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-box text-green-400"></i>
                                </div>
                            @endif
                            <span class="font-medium text-gray-800">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $product->sku ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->category?->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $product->price ? '$' . number_format($product->price, 2) : '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="font-medium {{ $product->stock > 0 ? 'text-gray-700' : 'text-red-500' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $product->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $product->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="text-indigo-600 hover:text-indigo-800 mr-3">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline"
                              onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        No hay productos registrados.
                        <a href="{{ route('admin.products.create') }}" class="text-green-600 ml-1">Crear el primero</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection
