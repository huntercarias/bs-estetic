@extends('layouts.admin')
@section('title','Citas')
@section('header','Gestión de Citas')

@section('content')
{{-- Filtros --}}
<form method="GET" class="bg-white rounded-xl shadow p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div>
        <label class="text-xs text-gray-500 block mb-1">Fecha</label>
        <input type="date" name="date" value="{{ request('date') }}"
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="text-xs text-gray-500 block mb-1">Estado cita</label>
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            <option value="">Todos</option>
            @foreach(['pending'=>'Pendiente','confirmed'=>'Confirmada','completed'=>'Completada','cancelled'=>'Cancelada'] as $v=>$l)
                <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs text-gray-500 block mb-1">Estado pago</label>
        <select name="payment" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            <option value="">Todos</option>
            @foreach(['pending'=>'Pendiente','paid_online'=>'Pagado online','paid_in_clinic'=>'Pagado presencialmente','waived'=>'Exonerado'] as $v=>$l)
                <option value="{{ $v }}" {{ request('payment')===$v?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
    </div>
    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filtrar</button>
    <a href="{{ route('admin.appointments.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Limpiar</a>
    <div class="ml-auto">
        <a href="{{ route('admin.appointments.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            <i class="fa-solid fa-plus mr-1"></i> Nueva Cita
        </a>
    </div>
</form>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">Paciente</th>
                <th class="px-4 py-3 text-left">Servicio</th>
                <th class="px-4 py-3 text-left">Fecha y Hora</th>
                <th class="px-4 py-3 text-left">Estado</th>
                <th class="px-4 py-3 text-left">Pago</th>
                <th class="px-4 py-3 text-left">Precio</th>
                <th class="px-4 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($appointments as $apt)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $apt->patient->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $apt->service->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $apt->scheduled_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">
                    @php $color = $apt->appointment_status_color @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-700">
                        {{ $apt->appointment_status_label }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    @php $pcolor = $apt->payment_status_color @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-{{ $pcolor }}-100 text-{{ $pcolor }}-700">
                        {{ $apt->payment_status_label }}
                    </span>
                </td>
                <td class="px-4 py-3">{{ $apt->total_price ? '$'.number_format($apt->total_price,2) : '—' }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.appointments.show', $apt) }}" class="text-indigo-600 hover:text-indigo-800 mr-2">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.appointments.edit', $apt) }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400">No hay citas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $appointments->links() }}</div>
@endsection
