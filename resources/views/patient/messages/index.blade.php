@extends('layouts.patient')
@section('title','Mensajes')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-2">Mensajes</h1>
<p class="text-sm text-gray-500 mb-5">Comunicación directa con el equipo de la clínica.</p>

@if(!$admin)
<div class="bg-amber-50 border border-amber-300 text-amber-800 rounded-xl p-5 text-center">
    <i class="fa-solid fa-circle-exclamation text-2xl mb-2"></i>
    <p>No hay administradores disponibles en este momento.</p>
</div>
@else

{{-- Área de mensajes --}}
<div id="chat-box" class="bg-white rounded-xl shadow p-4 mb-4 overflow-y-auto flex flex-col gap-3"
     style="min-height:380px; max-height:55vh;">
    @if($messages->isEmpty())
        <div class="flex-1 flex items-center justify-center text-gray-300 text-sm">
            <div class="text-center py-10">
                <i class="fa-regular fa-comment-dots text-5xl mb-3"></i>
                <p class="font-medium">Aún no hay mensajes.</p>
                <p class="text-xs mt-1">Envíanos tu consulta y te responderemos pronto.</p>
            </div>
        </div>
    @else
        @foreach($messages as $msg)
        @php $isMe = $msg->sender_id === auth()->id(); @endphp
        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-xs md:max-w-sm">
                @if(!$isMe)
                <div class="text-xs text-gray-400 mb-1 ml-1">
                    <i class="fa-solid fa-user-nurse text-indigo-400"></i> Clínica
                </div>
                @endif
                <div class="px-4 py-2 rounded-2xl text-sm
                    {{ $isMe
                        ? 'bg-indigo-600 text-white rounded-br-sm'
                        : 'bg-gray-100 text-gray-800 rounded-bl-sm' }}">
                    {{ $msg->body }}
                </div>
                <div class="text-xs text-gray-400 mt-1 {{ $isMe ? 'text-right' : 'text-left' }}">
                    {{ $msg->created_at->format('d/m H:i') }}
                    @if($isMe && $msg->read_at)
                        <i class="fa-solid fa-check-double text-indigo-400 ml-1" title="Leído por la clínica"></i>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

{{-- Formulario de envío --}}
<form method="POST" action="{{ route('patient.messages.store') }}"
      class="bg-white rounded-xl shadow p-4 flex gap-3 items-end">
    @csrf
    <div class="flex-1">
        <textarea name="body" rows="2"
            class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm resize-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
            placeholder="Escribe tu mensaje aquí..." required>{{ old('body') }}</textarea>
        @error('body')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <button type="submit"
            class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition flex items-center gap-2 text-sm font-medium">
        <i class="fa-solid fa-paper-plane"></i> Enviar
    </button>
</form>

<p class="text-xs text-gray-400 text-center mt-3">
    <i class="fa-solid fa-shield-halved"></i>
    Todos los mensajes quedan registrados de forma segura.
    La clínica también recibe una notificación por WhatsApp.
</p>
@endif

<script>
    const chatBox = document.getElementById('chat-box');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
    setTimeout(() => location.reload(), 30000);
</script>
@endsection
