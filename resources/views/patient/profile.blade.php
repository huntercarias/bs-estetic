@extends('layouts.patient')
@section('title','Mi Perfil')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-user-pen text-xl text-indigo-500"></i>
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Mi Perfil Personal</h1>
            <p class="text-sm text-gray-400">Mantén tu información actualizada para una mejor atención.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('patient.profile.update') }}" class="space-y-5">
        @csrf

        {{-- Datos personales --}}
        <div class="bg-white rounded-xl shadow p-5 space-y-4">
            <h2 class="font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-id-card text-indigo-400"></i> Datos personales
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</label>
                    <input type="date" name="birth_date"
                           value="{{ old('birth_date', $profile?->birth_date?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 @error('birth_date') border-red-400 @enderror">
                    @error('birth_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
                    <select name="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">— Seleccionar —</option>
                        <option value="female" {{ old('gender', $profile?->gender) === 'female' ? 'selected' : '' }}>Femenino</option>
                        <option value="male"   {{ old('gender', $profile?->gender) === 'male'   ? 'selected' : '' }}>Masculino</option>
                        <option value="other"  {{ old('gender', $profile?->gender) === 'other'  ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Datos físicos --}}
        <div class="bg-white rounded-xl shadow p-5 space-y-4">
            <h2 class="font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-weight-scale text-indigo-400"></i> Datos físicos
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso actual (kg)</label>
                    <input type="number" name="weight_kg" min="1" max="500" step="0.1"
                           value="{{ old('weight_kg', $profile?->weight_kg) }}"
                           placeholder="Ej: 65.0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 @error('weight_kg') border-red-400 @enderror">
                    @error('weight_kg')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Altura (cm)</label>
                    <input type="number" name="height_cm" min="50" max="300" step="0.1"
                           value="{{ old('height_cm', $profile?->height_cm) }}"
                           placeholder="Ej: 162"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 @error('height_cm') border-red-400 @enderror">
                    @error('height_cm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- IMC calculado en tiempo real --}}
            @if($profile?->weight_kg && $profile?->height_cm)
            @php
                $imc = round($profile->weight_kg / pow($profile->height_cm / 100, 2), 1);
                $imcLabel = match(true) {
                    $imc < 18.5 => ['Bajo peso', 'yellow'],
                    $imc < 25   => ['Peso normal', 'green'],
                    $imc < 30   => ['Sobrepeso', 'orange'],
                    default     => ['Obesidad', 'red'],
                };
            @endphp
            <div class="bg-gray-50 rounded-lg px-4 py-3 flex items-center justify-between text-sm">
                <span class="text-gray-500">Tu IMC actual:</span>
                <span class="font-bold text-{{ $imcLabel[1] }}-600">{{ $imc }} — {{ $imcLabel[0] }}</span>
            </div>
            @endif
        </div>

        {{-- Objetivos y actividad --}}
        <div class="bg-white rounded-xl shadow p-5 space-y-4">
            <h2 class="font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-dumbbell text-indigo-400"></i> Objetivo y actividad
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo principal</label>
                    <select name="goal" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">— Seleccionar —</option>
                        <option value="weight_loss"    {{ old('goal', $profile?->goal) === 'weight_loss'    ? 'selected' : '' }}>Pérdida de peso</option>
                        <option value="toning"         {{ old('goal', $profile?->goal) === 'toning'         ? 'selected' : '' }}>Tonificación</option>
                        <option value="wellness"       {{ old('goal', $profile?->goal) === 'wellness'       ? 'selected' : '' }}>Bienestar general</option>
                        <option value="rehabilitation" {{ old('goal', $profile?->goal) === 'rehabilitation' ? 'selected' : '' }}>Rehabilitación</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">¿Dónde entrenas?</label>
                    <select name="trains_at" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="none" {{ old('trains_at', $profile?->trains_at ?? 'none') === 'none' ? 'selected' : '' }}>No entreno</option>
                        <option value="gym"  {{ old('trains_at', $profile?->trains_at) === 'gym'  ? 'selected' : '' }}>Gimnasio</option>
                        <option value="home" {{ old('trains_at', $profile?->trains_at) === 'home' ? 'selected' : '' }}>Casa</option>
                        <option value="both" {{ old('trains_at', $profile?->trains_at) === 'both' ? 'selected' : '' }}>Ambos</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Información médica --}}
        <div class="bg-white rounded-xl shadow p-5 space-y-4">
            <h2 class="font-semibold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-notes-medical text-indigo-400"></i> Información médica
            </h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alergias o restricciones alimenticias</label>
                <input type="text" name="allergies"
                       value="{{ old('allergies', $profile?->allergies) }}"
                       placeholder="Ej: gluten, lactosa, mariscos... o escribe 'ninguna'"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas médicas relevantes</label>
                <textarea name="medical_notes" rows="3"
                          placeholder="Condiciones, medicamentos, cirugías previas u otro dato importante para tu atención..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">{{ old('medical_notes', $profile?->medical_notes) }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 font-medium text-sm">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar cambios
            </button>
            <a href="{{ route('patient.dashboard') }}"
               class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50 text-sm">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
