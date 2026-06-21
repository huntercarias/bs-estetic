@extends('layouts.admin')
@section('title', 'Resultado de Publicación')
@section('header', 'Redes Sociales')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.social-posts.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Resultado de la Publicación</h2>
    </div>

    {{-- Estado general --}}
    @php $color = $socialPost->statusColor(); @endphp
    <div class="bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-xl p-4 mb-6 flex items-center gap-3">
        <div class="w-10 h-10 bg-{{ $color }}-100 rounded-full flex items-center justify-center">
            @if($socialPost->status === 'published')
                <i class="fa-solid fa-circle-check text-{{ $color }}-600 text-xl"></i>
            @elseif($socialPost->status === 'partial')
                <i class="fa-solid fa-triangle-exclamation text-{{ $color }}-600 text-xl"></i>
            @elseif($socialPost->status === 'failed')
                <i class="fa-solid fa-circle-xmark text-{{ $color }}-600 text-xl"></i>
            @else
                <i class="fa-solid fa-clock text-{{ $color }}-600 text-xl"></i>
            @endif
        </div>
        <div>
            <div class="font-semibold text-{{ $color }}-800">{{ $socialPost->statusLabel() }}</div>
            @if($socialPost->published_at)
                <div class="text-sm text-{{ $color }}-600">
                    {{ $socialPost->published_at->format('d/m/Y \a \l\a\s H:i') }}
                </div>
            @endif
        </div>
    </div>

    <div class="grid md:grid-cols-5 gap-6">

        {{-- Contenido --}}
        <div class="md:col-span-2 space-y-4">
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-700 mb-3">Contenido publicado</h3>
                @if($socialPost->title)
                    <div class="font-medium text-gray-800 mb-2">{{ $socialPost->title }}</div>
                @endif
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $socialPost->body }}</p>
                @if($socialPost->image_path)
                    <img src="{{ $socialPost->imageUrl() }}" alt=""
                         class="mt-3 w-full rounded-lg object-cover max-h-48 border border-gray-100">
                @endif
                <div class="mt-3 pt-3 border-t border-gray-100 text-xs text-gray-400">
                    Creado por {{ $socialPost->author->name }}
                </div>
            </div>
        </div>

        {{-- Resultados por plataforma --}}
        <div class="md:col-span-3 space-y-3">
            <h3 class="font-semibold text-gray-700">Resultado por plataforma</h3>

            @php
                $platformDefs = [
                    'facebook'  => ['icon' => 'fa-facebook',  'color' => 'blue',  'label' => 'Facebook'],
                    'instagram' => ['icon' => 'fa-instagram', 'color' => 'pink',  'label' => 'Instagram'],
                    'whatsapp'  => ['icon' => 'fa-whatsapp',  'color' => 'green', 'label' => 'WhatsApp'],
                    'tiktok'    => ['icon' => 'fa-tiktok',    'color' => 'gray',  'label' => 'TikTok'],
                ];
            @endphp

            @foreach($socialPost->platforms as $platform)
            @php
                $def    = $platformDefs[$platform] ?? ['icon' => 'fa-share', 'color' => 'gray', 'label' => $platform];
                $result = $socialPost->platformResult($platform);
                $status = $result['status'] ?? 'pending';

                $statusIcon  = match($status) {
                    'success' => 'fa-circle-check text-green-600',
                    'warning' => 'fa-triangle-exclamation text-amber-500',
                    'manual'  => 'fa-hand-pointer text-blue-500',
                    'error'   => 'fa-circle-xmark text-red-500',
                    default   => 'fa-clock text-gray-400',
                };
                $statusLabel = match($status) {
                    'success' => 'Publicado',
                    'warning' => 'Aviso',
                    'manual'  => 'Manual requerido',
                    'error'   => 'Error',
                    default   => 'Pendiente',
                };
            @endphp
            <div class="bg-white rounded-xl shadow p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 bg-{{ $def['color'] }}-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fa-brands {{ $def['icon'] }} text-{{ $def['color'] }}-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-gray-800 text-sm">{{ $def['label'] }}</div>
                    </div>
                    <div class="flex items-center gap-1.5 text-sm">
                        <i class="fa-solid {{ $statusIcon }}"></i>
                        <span class="text-gray-600 text-xs">{{ $statusLabel }}</span>
                    </div>
                </div>

                @if($result)
                <p class="text-xs text-gray-500 bg-gray-50 rounded-lg px-3 py-2">
                    {{ $result['message'] }}
                </p>

                {{-- Acciones adicionales por resultado --}}
                @if($status === 'success' && isset($result['post_url']))
                <a href="{{ $result['post_url'] }}" target="_blank"
                   class="mt-2 inline-flex items-center gap-1.5 text-xs text-{{ $def['color'] }}-600 hover:underline">
                    <i class="fa-solid fa-external-link"></i> Ver publicación
                </a>
                @endif

                @if($status === 'success' && $platform === 'whatsapp' && isset($result['share_url']))
                <div class="mt-2 flex gap-2">
                    <a href="{{ $result['share_url'] }}" target="_blank"
                       class="inline-flex items-center gap-1.5 text-xs text-green-600 hover:underline">
                        <i class="fa-brands fa-whatsapp"></i> Compartir también externamente
                    </a>
                </div>
                @endif

                @if($status === 'manual' && $platform === 'tiktok')
                <div class="mt-2 space-y-2">
                    <button onclick="copyTikTok()"
                            class="w-full border border-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-xs hover:bg-gray-50 transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-copy"></i> Copiar texto
                    </button>
                    <a href="{{ $result['upload_url'] }}" target="_blank"
                       class="w-full bg-gray-900 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-gray-700 transition flex items-center justify-center gap-1.5">
                        <i class="fa-brands fa-tiktok"></i> Ir a TikTok
                    </a>
                    <textarea id="tiktok-text" class="sr-only">{{ $result['copy_text'] ?? $socialPost->body }}</textarea>
                </div>
                @endif

                @if($status === 'warning' || $status === 'error')
                <a href="{{ route('admin.social-posts.settings') }}"
                   class="mt-2 inline-flex items-center gap-1.5 text-xs text-indigo-600 hover:underline">
                    <i class="fa-solid fa-gear"></i> Revisar configuración
                </a>
                @endif
                @endif
            </div>
            @endforeach

            <form method="POST" action="{{ route('admin.social-posts.destroy', $socialPost) }}"
                  class="pt-2" onsubmit="return confirm('¿Eliminar esta publicación del historial?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="text-sm text-red-500 hover:text-red-700 flex items-center gap-1.5">
                    <i class="fa-solid fa-trash"></i> Eliminar del historial
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function copyTikTok() {
    const text = document.getElementById('tiktok-text').value;
    navigator.clipboard.writeText(text).then(() => {
        alert('Texto copiado al portapapeles.');
    });
}
</script>
@endsection
