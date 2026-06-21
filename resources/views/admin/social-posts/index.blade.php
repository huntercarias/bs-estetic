@extends('layouts.admin')
@section('title', 'Redes Sociales')
@section('header', 'Redes Sociales')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Publicaciones</h2>
    <div class="flex gap-2">
        <a href="{{ route('admin.social-posts.settings') }}"
           class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition flex items-center gap-2">
            <i class="fa-solid fa-gear"></i> Configurar cuentas
        </a>
        <a href="{{ route('admin.social-posts.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nueva publicación
        </a>
    </div>
</div>

@if($posts->isEmpty())
<div class="bg-white rounded-xl shadow p-16 text-center text-gray-400">
    <i class="fa-solid fa-share-nodes text-6xl mb-4"></i>
    <p class="text-lg font-medium">Sin publicaciones aún</p>
    <p class="text-sm mt-1">Crea tu primera publicación para todas tus redes sociales desde aquí.</p>
    <a href="{{ route('admin.social-posts.create') }}"
       class="mt-5 inline-block bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm hover:bg-indigo-700 transition">
        Crear primera publicación
    </a>
</div>
@else
<div class="space-y-4">
    @foreach($posts as $post)
    @php $color = $post->statusColor(); @endphp
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-start gap-4">
            {{-- Imagen --}}
            @if($post->image_path)
            <img src="{{ $post->imageUrl() }}" alt=""
                 class="w-20 h-20 object-cover rounded-lg flex-shrink-0 border border-gray-100">
            @else
            <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-image text-gray-300 text-2xl"></i>
            </div>
            @endif

            {{-- Contenido --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        @if($post->title)
                        <h3 class="font-semibold text-gray-800">{{ $post->title }}</h3>
                        @endif
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $post->body }}</p>
                    </div>
                    <span class="flex-shrink-0 px-2.5 py-1 rounded-full text-xs font-medium
                        bg-{{ $color }}-100 text-{{ $color }}-700">
                        {{ $post->statusLabel() }}
                    </span>
                </div>

                {{-- Plataformas --}}
                <div class="flex items-center gap-3 mt-3">
                    @foreach($post->platforms as $platform)
                    @php
                        $result  = $post->platformResult($platform);
                        $pStatus = $result['status'] ?? 'pending';
                        $icon = match($platform) {
                            'facebook'  => 'fa-facebook',
                            'instagram' => 'fa-instagram',
                            'whatsapp'  => 'fa-whatsapp',
                            'tiktok'    => 'fa-tiktok',
                            default     => 'fa-share',
                        };
                        $pColor = match($pStatus) {
                            'success' => 'text-green-600',
                            'warning' => 'text-amber-500',
                            'manual'  => 'text-blue-500',
                            'error'   => 'text-red-500',
                            default   => 'text-gray-400',
                        };
                    @endphp
                    <span class="flex items-center gap-1 text-sm {{ $pColor }}" title="{{ $result['message'] ?? $platform }}">
                        <i class="fa-brands {{ $icon }}"></i>
                        @if($pStatus === 'success') <i class="fa-solid fa-check text-xs"></i>
                        @elseif($pStatus === 'error') <i class="fa-solid fa-xmark text-xs"></i>
                        @elseif($pStatus === 'manual') <i class="fa-solid fa-hand-pointer text-xs"></i>
                        @elseif($pStatus === 'warning') <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                        @endif
                    </span>
                    @endforeach

                    <span class="text-xs text-gray-400 ml-auto">
                        {{ $post->author->name }} · {{ $post->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
            <a href="{{ route('admin.social-posts.show', $post) }}"
               class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                <i class="fa-solid fa-eye"></i> Ver resultados
            </a>
            <form method="POST" action="{{ route('admin.social-posts.destroy', $post) }}"
                  onsubmit="return confirm('¿Eliminar esta publicación?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 flex items-center gap-1">
                    <i class="fa-solid fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">{{ $posts->links() }}</div>
@endif
@endsection
