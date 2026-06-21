@extends('layouts.admin')
@section('title','Editar Paciente')
@section('header','Editar Paciente')

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.patients.update', $patient) }}" class="bg-white rounded-xl shadow p-6 space-y-5">
    @csrf @method('PUT')
    <h3 class="font-semibold text-gray-700 border-b pb-2">Cuenta de acceso</h3>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
            <input type="text" name="name" value="{{ old('name',$patient->name) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input type="email" name="email" value="{{ old('email',$patient->email) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña (dejar vacío para no cambiar)</label>
        <input type="password" name="password"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
    </div>

    <h3 class="font-semibold text-gray-700 border-b pb-2 pt-2">Perfil clínico</h3>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</label>
            <input type="date" name="birth_date" value="{{ old('birth_date',$patient->profile?->birth_date?->format('Y-m-d')) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
            <select name="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">—</option>
                @foreach(['female'=>'Femenino','male'=>'Masculino','other'=>'Otro'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('gender',$patient->profile?->gender)===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo</label>
            <select name="goal" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">—</option>
                @foreach(['weight_loss'=>'Pérdida de peso','toning'=>'Tonificación','wellness'=>'Bienestar','rehabilitation'=>'Rehabilitación'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('goal',$patient->profile?->goal)===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
            <input type="number" name="weight_kg" value="{{ old('weight_kg',$patient->profile?->weight_kg) }}" step="0.1"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Altura (cm)</label>
            <input type="number" name="height_cm" value="{{ old('height_cm',$patient->profile?->height_cm) }}" step="0.1"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Entrena en</label>
            <select name="trains_at" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @foreach(['none'=>'No entrena','gym'=>'Gimnasio','home'=>'Casa','both'=>'Ambos'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('trains_at',$patient->profile?->trains_at)===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Alergias</label>
        <input type="text" name="allergies" value="{{ old('allergies',$patient->profile?->allergies) }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notas médicas</label>
        <textarea name="medical_notes" rows="2"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('medical_notes',$patient->profile?->medical_notes) }}</textarea>
    </div>
    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Guardar Cambios</button>
        <a href="{{ route('admin.patients.show', $patient) }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Cancelar</a>
    </div>
</form>
</div>
@endsection
