@extends('layouts.admin')
@section('title', 'Mensajes')
@section('header', 'Mensajes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">
        Conversaciones con Pacientes
        @if($totalUnread > 0)
            <span class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $totalUnread }} sin leer</span>
        @endif
    </h2>
</div>

@if($patients->isEmpty())
<div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
    <i class="fa-solid fa-comments text-5xl mb-4"></i>
    <p class="text-lg font-medium">Sin conversaciones aún</p>
    <p class="text-sm mt-1">Cuando un paciente te envíe un mensaje, aparecerá aquí.</p>
</div>
@else
<div class="bg-white rounded-xl shadow divide-y divide-gray-100">
    @foreach($patients as $patient)
    <a href="{{ route('admin.messages.show', $patient) }}"
       class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition">
        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <span class="text-indigo-700 font-bold text-sm">{{ strtoupper(substr($patient->name, 0, 2)) }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-gray-800">{{ $patient->name }}</span>
                @if($patient->unread_count > 0)
                    <span class="bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $patient->unread_count }}</span>
                @endif
            </div>
            @if($patient->last_message)
            <p class="text-sm text-gray-500 truncate mt-0.5">
                @if($patient->last_message->sender_id === auth()->id())
                    <span class="text-indigo-400">Tú:</span>
                @endif
                {{ $patient->last_message->body }}
            </p>
            @endif
        </div>
        <div class="text-xs text-gray-400 flex-shrink-0">
            @if($patient->last_message)
                {{ $patient->last_message->created_at->diffForHumans() }}
            @endif
        </div>
        <i class="fa-solid fa-chevron-right text-gray-300"></i>
    </a>
    @endforeach
</div>
@endif
@endsection
