@extends('layouts.admin')
@section('title','Generar Plan IA')
@section('header','Generar Plan de Bienestar con IA')

@section('content')
<div class="grid md:grid-cols-2 gap-5">

    {{-- Formulario de generación --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">1. Datos para la IA</h3>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Paciente *</label>
                <select id="patient-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="">Seleccionar paciente</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}"
                                data-age="{{ $p->profile?->age ?? 'N/A' }}"
                                data-goal="{{ $p->profile?->goal_label ?? 'N/A' }}"
                                data-trains="{{ $p->profile?->trains_at_label ?? 'N/A' }}"
                                {{ request('patient_id')==$p->id?'selected':'' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Info del paciente --}}
            <div id="patient-info" class="bg-purple-50 rounded-lg p-3 text-sm hidden">
                <p class="font-medium text-purple-700 mb-1">Datos del paciente:</p>
                <p>Edad: <span id="info-age"></span> • Objetivo: <span id="info-goal"></span> • Entrena en: <span id="info-trains"></span></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de plan *</label>
                <select id="plan-type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="both">Ejercicio + Alimentación</option>
                    <option value="exercise">Solo Rutina de Ejercicio</option>
                    <option value="nutrition">Solo Plan de Alimentación</option>
                </select>
            </div>

            <button id="generate-btn" onclick="generatePlan()"
                    class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition font-medium">
                <i class="fa-solid fa-robot mr-2"></i> Generar con IA
            </button>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-700">
                <strong>Nota:</strong> Si no tienes API Key de Groq configurada, se generará un plan de demostración. Obtén tu key gratuita en <strong>console.groq.com</strong>
            </div>
        </div>
    </div>

    {{-- Resultado y guardado --}}
    <div id="result-section" class="hidden">
        <div class="bg-white rounded-xl shadow p-6 mb-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-700">2. Plan Generado</h3>
                <span id="plan-badge" class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">✓ Listo</span>
            </div>
            <div id="plan-preview" class="text-sm text-gray-600 max-h-64 overflow-y-auto bg-gray-50 rounded-lg p-3"></div>
        </div>

        <form method="POST" action="{{ route('admin.wellness.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf
            <h3 class="font-bold text-gray-700">3. Guardar Plan</h3>

            <input type="hidden" name="patient_id" id="form-patient-id">
            <input type="hidden" name="type" id="form-type">
            <input type="hidden" name="content" id="form-content">
            <input type="hidden" name="ai_prompt" id="form-prompt">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                    <option value="active">Activo (visible para el paciente)</option>
                    <option value="draft">Borrador (solo admin)</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Válido desde</label>
                    <input type="date" name="valid_from" value="{{ date('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Válido hasta</label>
                    <input type="date" name="valid_until"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-medium">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar y Asignar al Paciente
            </button>
        </form>
    </div>

    <div id="loading" class="hidden md:col-span-1 flex items-center justify-center bg-white rounded-xl shadow p-12">
        <div class="text-center text-purple-600">
            <i class="fa-solid fa-robot text-4xl animate-pulse mb-3"></i>
            <p class="font-medium">La IA está generando el plan...</p>
            <p class="text-sm text-gray-400 mt-1">Esto puede tomar unos segundos</p>
        </div>
    </div>
</div>

<script>
async function generatePlan() {
    const patientId = document.getElementById('patient-select').value;
    const type = document.getElementById('plan-type').value;

    if (!patientId) {
        alert('Selecciona un paciente primero.');
        return;
    }

    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('result-section').classList.add('hidden');
    document.getElementById('generate-btn').disabled = true;

    try {
        const response = await fetch('{{ route("admin.wellness.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ patient_id: patientId, type: type }),
        });

        const data = await response.json();

        document.getElementById('loading').classList.add('hidden');

        if (data.success && data.content) {
            const c = data.content;
            let preview = `<strong>${c.resumen ?? ''}</strong><br><br>`;

            if (c.ejercicios?.length) {
                preview += '<strong>Rutina de ejercicios:</strong><br>';
                c.ejercicios.forEach(d => {
                    preview += `• ${d.dia}: ${d.grupo_muscular}<br>`;
                });
                preview += '<br>';
            }

            if (c.alimentacion?.calorias_diarias) {
                preview += `<strong>Alimentación:</strong> ${c.alimentacion.calorias_diarias} kcal/día<br>`;
                preview += `Proteínas: ${c.alimentacion.macros?.proteinas_g}g | Carbos: ${c.alimentacion.macros?.carbohidratos_g}g | Grasas: ${c.alimentacion.macros?.grasas_g}g<br>`;
            }

            if (c.consejos_generales?.length) {
                preview += '<br><strong>Consejos:</strong><br>';
                c.consejos_generales.forEach(tip => { preview += `• ${tip}<br>`; });
            }

            document.getElementById('plan-preview').innerHTML = preview;
            document.getElementById('form-patient-id').value = patientId;
            document.getElementById('form-type').value = type;
            document.getElementById('form-content').value = JSON.stringify(data.content);
            document.getElementById('form-prompt').value = data.prompt ?? '';
            document.getElementById('result-section').classList.remove('hidden');
        } else {
            alert('Error: ' + (data.content?.error ?? 'No se pudo generar el plan.'));
        }
    } catch (e) {
        document.getElementById('loading').classList.add('hidden');
        alert('Error de conexión con la IA.');
    }

    document.getElementById('generate-btn').disabled = false;
}

// Mostrar info del paciente al seleccionar
document.getElementById('patient-select').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (this.value) {
        document.getElementById('info-age').textContent = opt.dataset.age;
        document.getElementById('info-goal').textContent = opt.dataset.goal;
        document.getElementById('info-trains').textContent = opt.dataset.trains;
        document.getElementById('patient-info').classList.remove('hidden');
    } else {
        document.getElementById('patient-info').classList.add('hidden');
    }
});

// Preseleccionar si viene con patient_id en URL
window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('patient-select');
    if (sel.value) sel.dispatchEvent(new Event('change'));
});
</script>
@endsection
