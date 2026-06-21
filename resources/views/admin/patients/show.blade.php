@extends('layouts.admin')
@section('title', $patient->name)
@section('header', 'Perfil del Paciente')

@section('content')
<div class="grid md:grid-cols-3 gap-5">

    {{-- Perfil --}}
    <div class="space-y-5">
        <div class="bg-white rounded-xl shadow p-5 text-center">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-user text-2xl text-indigo-400"></i>
            </div>
            <h2 class="font-bold text-lg">{{ $patient->name }}</h2>
            <p class="text-gray-400 text-sm">{{ $patient->email }}</p>
            <a href="{{ route('admin.patients.edit', $patient) }}"
               class="mt-3 inline-block border border-indigo-600 text-indigo-600 px-4 py-1 rounded-lg text-sm hover:bg-indigo-50">
                Editar perfil
            </a>
        </div>

        @if($patient->profile)
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-bold text-gray-700 mb-3">Datos Físicos</h3>
            <ul class="space-y-2 text-sm">
                <li class="flex justify-between"><span class="text-gray-400">Edad</span><span>{{ $patient->profile->age ? $patient->profile->age.' años' : '—' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-400">Género</span><span>{{ $patient->profile->gender ?? '—' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-400">Peso</span><span>{{ $patient->profile->weight_kg ? $patient->profile->weight_kg.' kg' : '—' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-400">Altura</span><span>{{ $patient->profile->height_cm ? $patient->profile->height_cm.' cm' : '—' }}</span></li>
                <li class="flex justify-between"><span class="text-gray-400">Entrena en</span><span>{{ $patient->profile->trains_at_label }}</span></li>
                <li class="flex justify-between"><span class="text-gray-400">Objetivo</span><span>{{ $patient->profile->goal_label }}</span></li>
            </ul>
            @if($patient->profile->allergies)
            <div class="mt-3 bg-yellow-50 rounded p-2 text-xs text-yellow-700">
                <strong>Alergias:</strong> {{ $patient->profile->allergies }}
            </div>
            @endif
            @if($patient->profile->medical_notes)
            <div class="mt-2 bg-gray-50 rounded p-2 text-xs text-gray-600">
                <strong>Notas médicas:</strong> {{ $patient->profile->medical_notes }}
            </div>
            @endif
        </div>
        @endif

        <a href="{{ route('admin.wellness.create', ['patient_id'=>$patient->id]) }}"
           class="block w-full text-center bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition text-sm">
            <i class="fa-solid fa-robot mr-1"></i> Generar Plan IA
        </a>
    </div>

    {{-- Historial --}}
    <div class="md:col-span-2 space-y-5">

        {{-- Citas --}}
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-700">Citas ({{ $patient->appointments->count() }})</h3>
                <a href="{{ route('admin.appointments.create') }}?patient_id={{ $patient->id }}" class="text-indigo-600 text-sm hover:underline">+ Nueva</a>
            </div>
            @forelse($patient->appointments->take(5) as $apt)
            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                <div>
                    <span class="font-medium text-sm">{{ $apt->service->name }}</span>
                    <span class="text-xs text-gray-400 ml-2">{{ $apt->scheduled_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex gap-2">
                    @php $c = $apt->appointment_status_color @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs bg-{{ $c }}-100 text-{{ $c }}-700">{{ $apt->appointment_status_label }}</span>
                    <a href="{{ route('admin.appointments.show', $apt) }}" class="text-indigo-400 hover:text-indigo-600 text-xs">Ver</a>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm">Sin citas registradas.</p>
            @endforelse
        </div>

        {{-- Planes de bienestar --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-bold text-gray-700 mb-3">Planes de Bienestar</h3>
            @forelse($patient->wellnessPlans as $plan)
            <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                <div>
                    <span class="font-medium text-sm">{{ $plan->type_label }}</span>
                    <span class="text-xs text-gray-400 ml-2">{{ $plan->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex gap-2 items-center">
                    @php $sc = match($plan->status){ 'active'=>'green','draft'=>'yellow',default=>'gray' } @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs bg-{{ $sc }}-100 text-{{ $sc }}-700">{{ $plan->status_label }}</span>
                    <a href="{{ route('admin.wellness.show', $plan) }}" class="text-indigo-400 hover:text-indigo-600 text-xs">Ver</a>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-sm">Sin planes asignados.</p>
            @endforelse
        </div>

        {{-- Historial clínico --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-bold text-gray-700 mb-3">Historial Clínico ({{ $patient->records->count() }})</h3>
            @forelse($patient->records->take(5) as $rec)
            <div class="border-l-4 border-indigo-200 pl-3 mb-3">
                <div class="flex justify-between text-xs text-gray-400">
                    <span>{{ $rec->title ?: 'Sin título' }}</span>
                    <span>{{ $rec->created_at->format('d/m/Y') }} — {{ $rec->recordedBy->name }}</span>
                </div>
                <p class="text-sm mt-1 text-gray-600">{{ Str::limit($rec->notes, 120) }}</p>
            </div>
            @empty
            <p class="text-gray-400 text-sm">Sin notas de consulta.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
