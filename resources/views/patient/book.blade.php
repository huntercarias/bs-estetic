@extends('layouts.patient')
@section('title','Reservar Cita')

@section('content')
<h1 class="text-xl font-bold text-gray-800 mb-5">Reservar una Cita</h1>

<form method="POST" action="{{ route('patient.book.store') }}" class="bg-white rounded-xl shadow p-6 space-y-5 max-w-xl">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Servicio *</label>
        <select name="service_id" id="service-select" required onchange="loadFields()"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            <option value="">Seleccionar servicio</option>
            @foreach($services as $s)
                <option value="{{ $s->id }}"
                        data-price="{{ $s->price }}"
                        data-duration="{{ $s->duration_minutes }}"
                        data-fields="{{ $s->customFields->toJson() }}"
                        {{ old('service_id')==$s->id?'selected':'' }}>
                    {{ $s->name }}{{ $s->price ? ' — $'.number_format($s->price,2) : '' }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Info del servicio --}}
    <div id="service-info" class="hidden bg-indigo-50 rounded-lg p-3 text-sm text-indigo-700"></div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y hora deseada *</label>
        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required
               min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
    </div>

    {{-- Campos dinámicos del servicio --}}
    <div id="dynamic-fields"></div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notas adicionales</label>
        <textarea name="notes" rows="2" placeholder="Alguna indicación especial..."
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
    </div>

    {{-- Nota de pago --}}
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
        <i class="fa-solid fa-circle-info mr-2"></i>
        <strong>Pago:</strong> Puedes pagar directamente en el centro al momento de tu cita (efectivo o tarjeta).
        <br><span class="text-xs text-yellow-600 mt-1 block">— Pasarela de pago en línea próximamente disponible —</span>
    </div>

    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-medium">
        <i class="fa-solid fa-calendar-check mr-2"></i> Confirmar Reserva
    </button>
</form>

<script>
function loadFields() {
    const select = document.getElementById('service-select');
    const opt = select.options[select.selectedIndex];
    const container = document.getElementById('dynamic-fields');
    const info = document.getElementById('service-info');

    if (!select.value) {
        container.innerHTML = '';
        info.classList.add('hidden');
        return;
    }

    const price = opt.dataset.price;
    const duration = opt.dataset.duration;
    info.textContent = (price ? `Precio: $${parseFloat(price).toFixed(2)}` : '') +
                       (duration ? ` • Duración: ${duration} min` : '');
    info.classList.remove('hidden');

    let fields = [];
    try { fields = JSON.parse(opt.dataset.fields || '[]'); } catch(e) {}

    if (!fields.length) { container.innerHTML = ''; return; }

    let html = '<div class="border-t pt-4"><p class="font-medium text-sm text-gray-700 mb-3">Datos del servicio:</p>';
    fields.forEach(f => {
        html += `<div class="mb-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">${f.label}${f.required ? ' *' : ''}</label>`;

        if (f.type === 'text' || f.type === 'number' || f.type === 'date') {
            html += `<input type="${f.type}" name="fields[${f.id}]" ${f.required?'required':''}
                     class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">`;
        } else if (f.type === 'textarea') {
            html += `<textarea name="fields[${f.id}]" rows="2" ${f.required?'required':''}
                     class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"></textarea>`;
        } else if (f.type === 'select' && f.options) {
            html += `<select name="fields[${f.id}]" ${f.required?'required':''}
                     class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                     <option value="">Seleccionar</option>`;
            f.options.forEach(o => { html += `<option value="${o}">${o}</option>`; });
            html += '</select>';
        } else if (f.type === 'boolean') {
            html += `<select name="fields[${f.id}]" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                     <option value="Si">Sí</option><option value="No">No</option></select>`;
        }

        html += '</div>';
    });
    html += '</div>';
    container.innerHTML = html;
}
</script>
@endsection
