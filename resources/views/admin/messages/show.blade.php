@extends('layouts.admin')
@section('title', 'Chat con '.$patient->name)
@section('header', 'Mensajes')

@section('content')
{{-- Header de la conversación --}}
<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('admin.messages.index') }}" class="text-gray-400 hover:text-gray-600">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center">
        <span class="text-indigo-700 font-bold text-sm">{{ strtoupper(substr($patient->name, 0, 2)) }}</span>
    </div>
    <div>
        <div class="font-semibold text-gray-800">{{ $patient->name }}</div>
        <div class="text-xs text-gray-400">{{ $patient->email }}</div>
    </div>
</div>

{{-- Área de mensajes --}}
<div id="chat-box" class="bg-white rounded-xl shadow p-4 mb-4 overflow-y-auto flex flex-col gap-3"
     style="min-height:400px; max-height:60vh;">
    @if($messages->isEmpty())
        <div class="flex-1 flex items-center justify-center text-gray-300 text-sm">
            <div class="text-center">
                <i class="fa-regular fa-comment-dots text-4xl mb-2"></i>
                <p>Inicia la conversación enviando un mensaje.</p>
            </div>
        </div>
    @else
        @foreach($messages as $msg)
        @php $isMe = $msg->sender_id === auth()->id(); @endphp
        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-xs md:max-w-md lg:max-w-lg">
                <div class="px-4 py-2 rounded-2xl text-sm
                    {{ $isMe
                        ? 'bg-indigo-600 text-white rounded-br-sm'
                        : 'bg-gray-100 text-gray-800 rounded-bl-sm' }}">
                    {{ $msg->body }}
                </div>
                <div class="text-xs text-gray-400 mt-1 {{ $isMe ? 'text-right' : 'text-left' }}">
                    {{ $msg->created_at->format('d/m H:i') }}
                    @if($isMe && $msg->read_at)
                        <i class="fa-solid fa-check-double text-indigo-400 ml-1" title="Leído"></i>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

{{-- Formulario de envío --}}
<form method="POST" action="{{ route('admin.messages.store', $patient) }}"
      class="bg-white rounded-xl shadow p-4 flex gap-3 items-end">
    @csrf
    <div class="flex-1">
        <textarea name="body" rows="2"
            class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm resize-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
            placeholder="Escribe un mensaje..." required>{{ old('body') }}</textarea>
        @error('body')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <button type="submit"
            class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition flex items-center gap-2 text-sm font-medium">
        <i class="fa-solid fa-paper-plane"></i> Enviar
    </button>
</form>

{{-- Información de auditoría --}}
<p class="text-xs text-gray-400 text-center mt-3">
    <i class="fa-solid fa-shield-halved"></i>
    Todos los mensajes quedan registrados con fecha y hora para auditoría.
</p>

<script>
    // Scroll al final del chat al cargar
    const chatBox = document.getElementById('chat-box');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    // Auto-refresh cada 30 segundos para recibir nuevos mensajes
    setTimeout(() => location.reload(), 30000);
</script>
@endsection
