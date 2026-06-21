@extends('layouts.patient')
@section('title','Mi Plan de Bienestar')

@section('content')
<h1 class="text-xl font-bold text-gray-800 mb-5">Mi Plan de Bienestar</h1>

@forelse($plans as $plan)
<div class="bg-white rounded-xl shadow p-5 mb-4">
    <div class="flex justify-between items-start">
        <div>
            <h2 class="font-bold text-lg text-purple-700">{{ $plan->type_label }}</h2>
            <p class="text-sm text-gray-400">
                Asignado el {{ $plan->created_at->format('d/m/Y') }}
                @if($plan->valid_until) — válido hasta {{ $plan->valid_until->format('d/m/Y') }} @endif
            </p>
        </div>
        <a href="{{ route('patient.wellness.show', $plan) }}"
           class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">
            Ver Plan Completo
        </a>
    </div>

    @if(!empty($plan->content['resumen']))
    <p class="text-gray-600 text-sm mt-3">{{ $plan->content['resumen'] }}</p>
    @endif
</div>
@empty
<div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
    <i class="fa-solid fa-dumbbell text-4xl mb-4 text-gray-300"></i>
    <p class="text-lg">Aún no tienes un plan de bienestar asignado.</p>
    <p class="text-sm mt-2">El administrador generará tu plan personalizado con IA próximamente.</p>
</div>
@endforelse
@endsection
