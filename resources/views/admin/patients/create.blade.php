@extends('layouts.admin')
@section('title','Nuevo Paciente')
@section('header','Registrar Paciente')

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.patients.store') }}" class="bg-white rounded-xl shadow p-6 space-y-5">
    @csrf
    <h3 class="font-semibold text-gray-700 border-b pb-2">Cuenta de acceso</h3>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
        <input type="password" name="password" required
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
    </div>

    <h3 class="font-semibold text-gray-700 border-b pb-2 pt-2">Perfil del paciente (opcional)</h3>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
            <select name="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">—</option>
                <option value="female" {{ old('gender')==='female'?'selected':'' }}>Femenino</option>
                <option value="male" {{ old('gender')==='male'?'selected':'' }}>Masculino</option>
                <option value="other" {{ old('gender')==='other'?'selected':'' }}>Otro</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo</label>
            <select name="goal" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">—</option>
                @foreach(['weight_loss'=>'Pérdida de peso','toning'=>'Tonificación','wellness'=>'Bienestar','rehabilitation'=>'Rehabilitación'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('goal')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
            <input type="number" name="weight_kg" value="{{ old('weight_kg') }}" min="0" step="0.1"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Altura (cm)</label>
            <input type="number" name="height_cm" value="{{ old('height_cm') }}" min="0" step="0.1"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Entrena en</label>
            <select name="trains_at" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @foreach(['none'=>'No entrena','gym'=>'Gimnasio','home'=>'Casa','both'=>'Ambos'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('trains_at')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Alergias / Restricciones</label>
        <input type="text" name="allergies" value="{{ old('allergies') }}" placeholder="Ej: gluten, lactosa, ninguna"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notas médicas</label>
        <textarea name="medical_notes" rows="2"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('medical_notes') }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Registrar Paciente</button>
        <a href="{{ route('admin.patients.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Cancelar</a>
    </div>
</form>
</div>
@endsection
