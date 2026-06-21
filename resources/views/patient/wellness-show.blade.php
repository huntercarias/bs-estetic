@extends('layouts.patient')
@section('title', $plan->type_label)

@section('content')
<div class="mb-5">
    <a href="{{ route('patient.wellness') }}" class="text-sm text-gray-400 hover:text-gray-600">← Volver a mis planes</a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $plan->type_label }}</h1>
</div>

@if(!empty($plan->content['resumen']))
<div class="bg-purple-50 border border-purple-200 rounded-xl p-5 mb-5">
    <p class="text-purple-800">{{ $plan->content['resumen'] }}</p>
</div>
@endif

{{-- Ejercicios --}}
@if(!empty($plan->content['ejercicios']))
<div class="bg-white rounded-xl shadow p-5 mb-5">
    <h2 class="font-bold text-lg text-gray-700 mb-4"><i class="fa-solid fa-dumbbell text-indigo-500 mr-2"></i>Rutina de Ejercicios</h2>
    <div class="space-y-4">
        @foreach($plan->content['ejercicios'] as $dia)
        <div class="border border-gray-100 rounded-lg overflow-hidden">
            <div class="bg-indigo-50 px-4 py-2 flex justify-between">
                <span class="font-medium text-indigo-700">{{ $dia['dia'] }}</span>
                <span class="text-sm text-indigo-400">{{ $dia['grupo_muscular'] ?? '' }}</span>
            </div>
            <div class="p-3 space-y-2">
                @foreach($dia['ejercicios'] ?? [] as $ej)
                <div class="flex justify-between text-sm">
                    <span class="font-medium">{{ $ej['nombre'] }}</span>
                    <span class="text-gray-400">{{ $ej['series'] }} × {{ $ej['repeticiones'] }} | {{ $ej['descanso'] }}</span>
                </div>
                @if(!empty($ej['nota']))<p class="text-xs text-gray-400 italic">{{ $ej['nota'] }}</p>@endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Alimentación --}}
@if(!empty($plan->content['alimentacion']))
@php $alim = $plan->content['alimentacion']; @endphp
<div class="bg-white rounded-xl shadow p-5 mb-5">
    <h2 class="font-bold text-lg text-gray-700 mb-4"><i class="fa-solid fa-apple-whole text-green-500 mr-2"></i>Plan de Alimentación</h2>

    <div class="grid grid-cols-4 gap-3 mb-5">
        <div class="bg-green-50 rounded-lg p-3 text-center">
            <div class="text-xl font-bold text-green-600">{{ $alim['calorias_diarias'] ?? '—' }}</div>
            <div class="text-xs text-gray-400">kcal/día</div>
        </div>
        <div class="bg-blue-50 rounded-lg p-3 text-center">
            <div class="text-xl font-bold text-blue-600">{{ $alim['macros']['proteinas_g'] ?? '—' }}g</div>
            <div class="text-xs text-gray-400">Proteínas</div>
        </div>
        <div class="bg-yellow-50 rounded-lg p-3 text-center">
            <div class="text-xl font-bold text-yellow-600">{{ $alim['macros']['carbohidratos_g'] ?? '—' }}g</div>
            <div class="text-xs text-gray-400">Carbohidratos</div>
        </div>
        <div class="bg-orange-50 rounded-lg p-3 text-center">
            <div class="text-xl font-bold text-orange-600">{{ $alim['macros']['grasas_g'] ?? '—' }}g</div>
            <div class="text-xs text-gray-400">Grasas</div>
        </div>
    </div>

    @foreach($alim['plan_semanal'] ?? [] as $dia)
    <div class="border border-gray-100 rounded-lg p-3 mb-2">
        <p class="font-medium text-sm text-green-700 mb-2">{{ $dia['dia'] }}</p>
        <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
            <div><span class="text-gray-400">Desayuno:</span> {{ $dia['desayuno'] }}</div>
            <div><span class="text-gray-400">Almuerzo:</span> {{ $dia['almuerzo'] }}</div>
            <div><span class="text-gray-400">Cena:</span> {{ $dia['cena'] }}</div>
            <div><span class="text-gray-400">Snacks:</span> {{ $dia['snacks'] ?? '—' }}</div>
        </div>
    </div>
    @endforeach

    @if(!empty($alim['recomendaciones']))
    <div class="mt-4 bg-green-50 rounded-lg p-3">
        <p class="font-medium text-sm text-green-700 mb-2">Recomendaciones:</p>
        <ul class="list-disc list-inside text-sm text-green-800 space-y-1">
            @foreach($alim['recomendaciones'] as $r)<li>{{ $r }}</li>@endforeach
        </ul>
    </div>
    @endif
</div>
@endif

{{-- Consejos --}}
@if(!empty($plan->content['consejos_generales']))
<div class="bg-white rounded-xl shadow p-5">
    <h2 class="font-bold text-gray-700 mb-3">Consejos Generales</h2>
    <ul class="space-y-2">
        @foreach($plan->content['consejos_generales'] as $tip)
        <li class="flex items-start gap-2 text-sm text-gray-600">
            <i class="fa-solid fa-circle-check text-green-400 mt-0.5 flex-shrink-0"></i>{{ $tip }}
        </li>
        @endforeach
    </ul>
</div>
@endif
@endsection
