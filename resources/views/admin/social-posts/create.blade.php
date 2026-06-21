@extends('layouts.admin')
@section('title', 'Nueva Publicación')
@section('header', 'Redes Sociales')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.social-posts.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Nueva Publicación</h2>
    </div>

    <form method="POST" action="{{ route('admin.social-posts.store') }}"
          enctype="multipart/form-data" id="post-form">
        @csrf

        <div class="grid md:grid-cols-5 gap-6">

            {{-- Columna izquierda: Formulario --}}
            <div class="md:col-span-3 space-y-5">

                {{-- Título --}}
                <div class="bg-white rounded-xl shadow p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Título <span class="text-gray-400 text-xs">(opcional)</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                           placeholder="Ej: ¡Oferta especial este mes!" maxlength="200">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contenido --}}
                <div class="bg-white rounded-xl shadow p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Mensaje <span class="text-red-500">*</span>
                    </label>
                    <textarea name="body" id="body-input" rows="6"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm resize-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                              placeholder="Escribe el contenido de tu publicación..." maxlength="5000"
                              required>{{ old('body') }}</textarea>
                    <div class="flex justify-between mt-1">
                        @error('body')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <span class="text-xs text-gray-400" id="char-count">0 / 5000</span>
                    </div>
                </div>

                {{-- Imagen --}}
                <div class="bg-white rounded-xl shadow p-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Imagen <span class="text-gray-400 text-xs">(requerida para Instagram)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-400 transition cursor-pointer"
                         onclick="document.getElementById('image-input').click()">
                        <div id="image-preview-wrap">
                            <i class="fa-solid fa-image text-gray-300 text-3xl mb-2"></i>
                            <p class="text-sm text-gray-400">Haz clic para subir una imagen</p>
                            <p class="text-xs text-gray-300 mt-1">JPG, PNG, GIF · Máx 10 MB</p>
                        </div>
                        <img id="image-preview" class="hidden mx-auto max-h-48 rounded-lg object-contain" alt="Preview">
                    </div>
                    <input type="file" id="image-input" name="image" accept="image/*" class="hidden"
                           onchange="previewImage(this)">
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Columna derecha: Plataformas --}}
            <div class="md:col-span-2 space-y-5">

                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-700 mb-3">
                        Publicar en <span class="text-red-500">*</span>
                    </h3>
                    @error('platforms')
                        <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                    @enderror

                    @php
                        $platformDefs = [
                            'facebook'  => ['icon' => 'fa-facebook',  'color' => 'text-blue-600',  'bg' => 'bg-blue-50',  'label' => 'Facebook',  'desc' => 'Página de Facebook'],
                            'instagram' => ['icon' => 'fa-instagram', 'color' => 'text-pink-600',  'bg' => 'bg-pink-50',  'label' => 'Instagram', 'desc' => 'Perfil de negocio'],
                            'whatsapp'  => ['icon' => 'fa-whatsapp',  'color' => 'text-green-600', 'bg' => 'bg-green-50', 'label' => 'WhatsApp',  'desc' => 'Todos tus pacientes'],
                            'tiktok'    => ['icon' => 'fa-tiktok',    'color' => 'text-gray-800',  'bg' => 'bg-gray-50',  'label' => 'TikTok',   'desc' => 'Guía de publicación'],
                        ];
                    @endphp

                    <div class="space-y-2">
                        @foreach($platformDefs as $key => $def)
                        @php
                            $account     = $accounts[$key] ?? null;
                            $isActive    = $account && $account->active;
                            $isOld       = in_array($key, old('platforms', []));
                        @endphp
                        <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer
                                      hover:border-indigo-300 hover:bg-indigo-50 transition
                                      {{ $isOld ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200' }}">
                            <input type="checkbox" name="platforms[]" value="{{ $key }}"
                                   class="rounded accent-indigo-600"
                                   {{ $isOld ? 'checked' : '' }}>
                            <div class="w-8 h-8 {{ $def['bg'] }} rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-brands {{ $def['icon'] }} {{ $def['color'] }}"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-800">{{ $def['label'] }}</div>
                                <div class="text-xs text-gray-400">{{ $def['desc'] }}</div>
                            </div>
                            @if($isActive)
                                <span class="text-xs text-green-600"><i class="fa-solid fa-circle-check"></i></span>
                            @else
                                <a href="{{ route('admin.social-posts.settings') }}"
                                   class="text-xs text-amber-500 hover:underline" title="Configurar"
                                   onclick="event.stopPropagation()">
                                    Configurar
                                </a>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Preview rápido --}}
                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold text-gray-700 mb-3">Vista previa</h3>
                    <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-indigo-600 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-star text-white text-xs"></i>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-800">{{ config('app.name') }}</div>
                                <div class="text-xs text-gray-400">Ahora</div>
                            </div>
                        </div>
                        <p id="preview-body" class="text-xs text-gray-700 whitespace-pre-line">Tu mensaje aparecerá aquí...</p>
                        <img id="preview-img" class="hidden mt-2 w-full rounded object-cover max-h-32" alt="">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Publicar ahora
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Contador de caracteres
    const bodyInput = document.getElementById('body-input');
    const charCount = document.getElementById('char-count');
    bodyInput.addEventListener('input', () => {
        charCount.textContent = bodyInput.value.length + ' / 5000';
        document.getElementById('preview-body').textContent = bodyInput.value || 'Tu mensaje aparecerá aquí...';
    });

    // Preview imagen
    function previewImage(input) {
        if (!input.files.length) return;
        const file  = input.files[0];
        const url   = URL.createObjectURL(file);
        const wrap  = document.getElementById('image-preview-wrap');
        const img   = document.getElementById('image-preview');
        const pImg  = document.getElementById('preview-img');
        wrap.classList.add('hidden');
        img.src = url;
        img.classList.remove('hidden');
        pImg.src = url;
        pImg.classList.remove('hidden');
    }
</script>
@endsection
