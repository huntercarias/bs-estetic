@extends('layouts.patient')
@section('title','Mis Citas')

@section('content')
<div class="flex justify-between items-center mb-5">
    <h1 class="text-xl font-bold text-gray-800">Mis Citas</h1>
    <a href="{{ route('patient.book') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
        + Reservar nueva cita
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 text-left">Servicio</th>
                <th class="px-5 py-3 text-left">Fecha</th>
                <th class="px-5 py-3 text-left">Estado</th>
                <th class="px-5 py-3 text-left">Pago</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($appointments as $apt)
            <tr>
                <td class="px-5 py-3 font-medium">{{ $apt->service->name }}</td>
                <td class="px-5 py-3 text-gray-500">{{ $apt->scheduled_at->format('d/m/Y H:i') }}</td>
                <td class="px-5 py-3">
                    @php $c = $apt->appointment_status_color @endphp
                    <span class="px-2 py-1 rounded-full text-xs bg-{{ $c }}-100 text-{{ $c }}-700">{{ $apt->appointment_status_label }}</span>
                </td>
                <td class="px-5 py-3">
                    @php $pc = $apt->payment_status_color @endphp
                    <span class="px-2 py-1 rounded-full text-xs bg-{{ $pc }}-100 text-{{ $pc }}-700">{{ $apt->payment_status_label }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-5 py-12 text-center text-gray-400">No tienes citas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $appointments->links() }}</div>
@endsection
