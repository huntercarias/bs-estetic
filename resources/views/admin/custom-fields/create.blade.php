@extends('layouts.admin')
@section('title','Nuevo Campo')
@section('header','Crear Campo Personalizado')

@section('content')
<div class="max-w-lg">
<form method="POST" action="{{ route('admin.custom-fields.store') }}" class="bg-white rounded-xl shadow p-6 space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Etiqueta (visible al usuario) *</label>
        <input type="text" name="label" value="{{ old('label') }}" required placeholder="Ej: Peso actual"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre interno (snake_case) *</label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ej: peso_actual"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500 font-mono text-sm">
        <p class="text-xs text-gray-400 mt-1">Solo letras, números y guiones bajos.</p>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Servicio</label>
            <select name="service_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500">
                <option value="">Todos los servicios</option>
                @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ old('service_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de campo *</label>
            <select name="type" id="field-type" onchange="toggleOptions()" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-teal-500">
                @foreach(['text'=>'Texto','number'=>'Número','date'=>'Fecha','select'=>'Lista de opciones','boolean'=>'Sí/No','textarea'=>'Texto largo'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('type')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="options-section" class="{{ old('type')==='select'?'':'hidden' }}">
        <label class="block text-sm font-medium text-gray-700 mb-1">Opciones (una por línea)</label>
        <textarea name="options" rows="4" placeholder="Opción 1&#10;Opción 2&#10;Opción 3"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono text-sm focus:ring-2 focus:ring-teal-500">{{ old('options') }}</textarea>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="required" id="required" value="1" {{ old('required')?'checked':'' }}
               class="w-4 h-4 text-teal-600 rounded">
        <label for="required" class="text-sm text-gray-700">Campo obligatorio</label>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700">Guardar Campo</button>
        <a href="{{ route('admin.custom-fields.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2 rounded-lg hover:bg-gray-50">Cancelar</a>
    </div>
</form>
</div>

<script>
function toggleOptions() {
    const type = document.getElementById('field-type').value;
    document.getElementById('options-section').classList.toggle('hidden', type !== 'select');
}
</script>
@endsection
