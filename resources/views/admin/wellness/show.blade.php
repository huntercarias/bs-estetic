@extends('layouts.admin')
@section('title','Plan de Bienestar')
@section('header','Plan de Bienestar')

@section('content')
<div class="grid md:grid-cols-3 gap-5">

    {{-- Sidebar --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-bold text-gray-700 mb-3">Información</h3>
            <ul class="space-y-2 text-sm">
                <li><span class="text-gray-400">Paciente:</span> <a href="{{ route('admin.patients.show', $wellness->patient) }}" class="text-indigo-600 font-medium">{{ $wellness->patient->name }}</a></li>
                <li><span class="text-gray-400">Tipo:</span> {{ $wellness->type_label }}</li>
                <li><span class="text-gray-400">Creado por:</span> {{ $wellness->createdBy->name }}</li>
                <li><span class="text-gray-400">Vigencia:</span> {{ $wellness->valid_from?->format('d/m/Y') ?? '—' }} → {{ $wellness->valid_until?->format('d/m/Y') ?? '—' }}</li>
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <h4 class="font-bold text-gray-700 mb-3 text-sm">Estado del Plan</h4>
            <form method="POST" action="{{ route('admin.wellness.status', $wellness) }}">
                @csrf
                <select name="status" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach(['draft'=>'Borrador','active'=>'Activo (visible al paciente)','inactive'=>'Inactivo'] as $v=>$l)
                        <option value="{{ $v }}" {{ $wellness->status===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <form method="POST" action="{{ route('admin.wellness.destroy', $wellness) }}"
              onsubmit="return confirm('¿Eliminar este plan?')">
            @csrf @method('DELETE')
            <button class="w-full border border-red-300 text-red-500 px-4 py-2 rounded-lg text-sm hover:bg-red-50">
                <i class="fa-solid fa-trash mr-1"></i> Eliminar Plan
            </button>
        </form>
    </div>

    {{-- Contenido del plan --}}
    <div class="md:col-span-2 space-y-5">

        @if(!empty($wellness->content['resumen']))
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-5">
            <h3 class="font-bold text-purple-700 mb-2">Resumen del Plan</h3>
            <p class="text-purple-800">{{ $wellness->content['resumen'] }}</p>
        </div>
        @endif

        {{-- Ejercicios --}}
        @if(!empty($wellness->content['ejercicios']))
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-bold text-gray-700 mb-4"><i class="fa-solid fa-dumbbell text-indigo-500 mr-2"></i>Rutina de Ejercicios</h3>
            <div class="space-y-4">
                @foreach($wellness->content['ejercicios'] as $dia)
                <div class="border border-gray-100 rounded-lg overflow-hidden">
                    <div class="bg-indigo-50 px-4 py-2 flex justify-between">
                        <span class="font-medium text-indigo-700">{{ $dia['dia'] }}</span>
                        <span class="text-sm text-indigo-500">{{ $dia['grupo_muscular'] ?? '' }}</span>
                    </div>
                    <div class="p-3">
                        @foreach($dia['ejercicios'] ?? [] as $ej)
                        <div class="flex justify-between text-sm py-1 border-b border-gray-50 last:border-0">
                            <span class="font-medium">{{ $ej['nombre'] }}</span>
                            <span class="text-gray-400">{{ $ej['series'] ?? '' }} series × {{ $ej['repeticiones'] ?? '' }} | Desc: {{ $ej['descanso'] ?? '' }}</span>
                        </div>
                        @if(!empty($ej['nota']))
                        <p class="text-xs text-gray-400 italic">{{ $ej['nota'] }}</p>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Alimentación --}}
        @if(!empty($wellness->content['alimentacion']))
        @php $alim = $wellness->content['alimentacion']; @endphp
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-bold text-gray-700 mb-4"><i class="fa-solid fa-apple-whole text-green-500 mr-2"></i>Plan de Alimentación</h3>

            <div class="grid grid-cols-4 gap-3 mb-4">
                <div class="bg-green-50 rounded-lg p-3 text-center">
                    <div class="text-lg font-bold text-green-600">{{ $alim['calorias_diarias'] ?? '—' }}</div>
                    <div class="text-xs text-gray-400">kcal/día</div>
                </div>
                <div class="bg-blue-50 rounded-lg p-3 text-center">
                    <div class="text-lg font-bold text-blue-600">{{ $alim['macros']['proteinas_g'] ?? '—' }}g</div>
                    <div class="text-xs text-gray-400">Proteínas</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-3 text-center">
                    <div class="text-lg font-bold text-yellow-600">{{ $alim['macros']['carbohidratos_g'] ?? '—' }}g</div>
                    <div class="text-xs text-gray-400">Carbohidratos</div>
                </div>
                <div class="bg-orange-50 rounded-lg p-3 text-center">
                    <div class="text-lg font-bold text-orange-600">{{ $alim['macros']['grasas_g'] ?? '—' }}g</div>
                    <div class="text-xs text-gray-400">Grasas</div>
                </div>
            </div>

            @if(!empty($alim['plan_semanal']))
            <div class="space-y-2">
                @foreach($alim['plan_semanal'] as $dia)
                <div class="border border-gray-100 rounded-lg p-3">
                    <p class="font-medium text-sm text-green-700 mb-1">{{ $dia['dia'] }}</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                        <span><strong>Desayuno:</strong> {{ $dia['desayuno'] }}</span>
                        <span><strong>Almuerzo:</strong> {{ $dia['almuerzo'] }}</span>
                        <span><strong>Cena:</strong> {{ $dia['cena'] }}</span>
                        <span><strong>Snacks:</strong> {{ $dia['snacks'] ?? '—' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if(!empty($alim['recomendaciones']))
            <div class="mt-4">
                <p class="font-medium text-sm text-gray-600 mb-2">Recomendaciones:</p>
                <ul class="list-disc list-inside text-sm text-gray-500 space-y-1">
                    @foreach($alim['recomendaciones'] as $rec)
                    <li>{{ $rec }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @endif

        {{-- Consejos generales --}}
        @if(!empty($wellness->content['consejos_generales']))
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-bold text-gray-700 mb-3">Consejos Generales</h3>
            <ul class="space-y-2">
                @foreach($wellness->content['consejos_generales'] as $tip)
                <li class="flex items-start gap-2 text-sm text-gray-600">
                    <i class="fa-solid fa-circle-check text-green-400 mt-0.5 flex-shrink-0"></i>
                    {{ $tip }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection
