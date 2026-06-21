@extends('layouts.admin')
@section('title','Nueva Cita')
@section('header','Crear Cita')

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.appointments.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
    @csrf

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Paciente *</label>
            <select name="patient_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                <option value="">Seleccionar paciente</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}" {{ old('patient_id')==$p->id?'selected':'' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Servicio *</label>
            <select name="service_id" required id="service-select"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                <option value="">Seleccionar servicio</option>
                @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ old('service_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y Hora *</label>
            <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Staff asignado</label>
            <select name="staff_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                <option value="">Sin asignar</option>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}" {{ old('staff_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select name="appointment_status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @foreach(['pending'=>'Pendiente','confirmed'=>'Confirmada','completed'=>'Completada','cancelled'=>'Cancelada'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('appointment_status','pending')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estado de pago</label>
            <select name="payment_status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @foreach(['pending'=>'Pendiente','paid_in_clinic'=>'Pagado presencialmente','paid_online'=>'Pagado online','waived'=>'Exonerado'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('payment_status','pending')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio total</label>
            <input type="number" name="total_price" value="{{ old('total_price') }}" min="0" step="0.01"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Duración (min)</label>
            <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" min="1"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notas internas (admin)</label>
        <textarea name="admin_notes" rows="2"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('admin_notes') }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Crear Cita</button>
        <a href="{{ route('admin.appointments.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Cancelar</a>
    </div>
</form>
</div>
@endsection
