@extends('layouts.admin')
@section('title','Planes de Bienestar')
@section('header','Planes de Bienestar IA')

@section('content')
<div class="flex justify-between items-center mb-5">
    <p class="text-sm text-gray-500">{{ $plans->total() }} planes registrados</p>
    <a href="{{ route('admin.wellness.create') }}"
       class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">
        <i class="fa-solid fa-robot mr-1"></i> Generar Plan con IA
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 text-left">Paciente</th>
                <th class="px-5 py-3 text-left">Tipo</th>
                <th class="px-5 py-3 text-left">Estado</th>
                <th class="px-5 py-3 text-left">Vigencia</th>
                <th class="px-5 py-3 text-left">Creado por</th>
                <th class="px-5 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($plans as $plan)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-medium">{{ $plan->patient->name }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $plan->type_label }}</td>
                <td class="px-5 py-3">
                    @php $sc = match($plan->status){ 'active'=>'green','draft'=>'yellow',default=>'gray' } @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-{{ $sc }}-100 text-{{ $sc }}-700">{{ $plan->status_label }}</span>
                </td>
                <td class="px-5 py-3 text-gray-400 text-xs">
                    {{ $plan->valid_from?->format('d/m/Y') ?? '—' }} → {{ $plan->valid_until?->format('d/m/Y') ?? '—' }}
                </td>
                <td class="px-5 py-3 text-gray-500">{{ $plan->createdBy->name }}</td>
                <td class="px-5 py-3 text-right">
                    <a href="{{ route('admin.wellness.show', $plan) }}" class="text-purple-600 hover:text-purple-800 mr-2">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.wellness.destroy', $plan) }}" class="inline"
                          onsubmit="return confirm('¿Eliminar este plan?')">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No hay planes generados aún.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $plans->links() }}</div>
@endsection
