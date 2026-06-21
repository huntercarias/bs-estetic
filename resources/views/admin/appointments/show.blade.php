@extends('layouts.admin')
@section('title','Cita #'.$appointment->id)
@section('header','Detalle de Cita')

@section('content')
<div class="grid md:grid-cols-3 gap-5">

    {{-- Info principal --}}
    <div class="md:col-span-2 space-y-5">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $appointment->service->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $appointment->scheduled_at->format('d/m/Y \a \l\a\s H:i') }}</p>
                </div>
                <div class="flex gap-2">
                    @php $c = $appointment->appointment_status_color; $pc = $appointment->payment_status_color; @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $c }}-100 text-{{ $c }}-700">
                        {{ $appointment->appointment_status_label }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $pc }}-100 text-{{ $pc }}-700">
                        {{ $appointment->payment_status_label }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-400">Paciente:</span> <span class="font-medium">{{ $appointment->patient->name }}</span></div>
                <div><span class="text-gray-400">Staff:</span> <span class="font-medium">{{ $appointment->staff?->name ?? 'Sin asignar' }}</span></div>
                <div><span class="text-gray-400">Duración:</span> <span>{{ $appointment->duration_minutes ? $appointment->duration_minutes.' min' : '—' }}</span></div>
                <div><span class="text-gray-400">Precio:</span> <span class="font-semibold text-indigo-600">{{ $appointment->total_price ? '$'.number_format($appointment->total_price,2) : '—' }}</span></div>
            </div>

            @if($appointment->notes)
            <div class="mt-4 bg-gray-50 rounded-lg p-3">
                <p class="text-xs text-gray-400 mb-1">Notas del paciente</p>
                <p class="text-sm">{{ $appointment->notes }}</p>
            </div>
            @endif
            @if($appointment->admin_notes)
            <div class="mt-3 bg-indigo-50 rounded-lg p-3">
                <p class="text-xs text-indigo-400 mb-1">Notas internas</p>
                <p class="text-sm">{{ $appointment->admin_notes }}</p>
            </div>
            @endif
        </div>

        {{-- Campos dinámicos --}}
        @if($appointment->fieldValues->count())
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-bold text-gray-700 mb-4">Datos del Servicio</h3>
            <div class="grid grid-cols-2 gap-3">
                @foreach($appointment->fieldValues as $fv)
                <div>
                    <p class="text-xs text-gray-400">{{ $fv->fieldDefinition->label }}</p>
                    <p class="text-sm font-medium">{{ $fv->value ?: '—' }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Historial clínico --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-bold text-gray-700 mb-4">Notas de Consulta</h3>
            @forelse($appointment->records as $record)
            <div class="border-l-4 border-indigo-300 pl-4 mb-4">
                <div class="flex justify-between">
                    <span class="font-medium text-sm">{{ $record->title ?: 'Sin título' }}</span>
                    <span class="text-xs text-gray-400">{{ $record->created_at->format('d/m/Y H:i') }} — {{ $record->recordedBy->name }}</span>
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ $record->notes }}</p>
            </div>
            @empty
            <p class="text-gray-400 text-sm">No hay notas de consulta aún.</p>
            @endforelse

            <form method="POST" action="{{ route('admin.appointments.record', $appointment) }}" class="mt-4 border-t pt-4">
                @csrf
                <input type="text" name="title" placeholder="Título (opcional)"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:ring-2 focus:ring-indigo-500">
                <textarea name="notes" rows="3" required placeholder="Agregar nota de consulta..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"></textarea>
                <button class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    Guardar Nota
                </button>
            </form>
        </div>
    </div>

    {{-- Sidebar acciones --}}
    <div class="space-y-4">

        {{-- Cambiar estado cita --}}
        <div class="bg-white rounded-xl shadow p-4">
            <h4 class="font-bold text-gray-700 mb-3 text-sm">Estado de la Cita</h4>
            <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                @csrf
                <select name="appointment_status" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach(['pending'=>'Pendiente','confirmed'=>'Confirmada','completed'=>'Completada','cancelled'=>'Cancelada'] as $v=>$l)
                        <option value="{{ $v }}" {{ $appointment->appointment_status===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Cambiar estado pago --}}
        <div class="bg-white rounded-xl shadow p-4">
            <h4 class="font-bold text-gray-700 mb-3 text-sm">Estado de Pago</h4>
            <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                @csrf
                <select name="payment_status" onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach(['pending'=>'Pendiente','paid_in_clinic'=>'Pagado presencialmente','paid_online'=>'Pagado online','waived'=>'Exonerado'] as $v=>$l)
                        <option value="{{ $v }}" {{ $appointment->payment_status===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </form>
            <p class="text-xs text-gray-400 mt-2">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Marca "Pagado presencialmente" cuando el paciente pague en efectivo o tarjeta física.
            </p>
        </div>

        <a href="{{ route('admin.appointments.edit', $appointment) }}"
           class="block w-full text-center border border-indigo-600 text-indigo-600 px-4 py-2 rounded-lg text-sm hover:bg-indigo-50 transition">
            <i class="fa-solid fa-pen mr-1"></i> Editar Cita
        </a>
        <a href="{{ route('admin.patients.show', $appointment->patient) }}"
           class="block w-full text-center border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            <i class="fa-solid fa-user mr-1"></i> Ver Perfil del Paciente
        </a>
        <a href="{{ route('admin.appointments.index') }}"
           class="block w-full text-center text-gray-400 text-sm hover:text-gray-600">
            ← Volver a citas
        </a>
    </div>
</div>
@endsection
