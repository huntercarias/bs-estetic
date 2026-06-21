@extends('layouts.admin')
@section('title', 'Configurar Redes Sociales')
@section('header', 'Redes Sociales')

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.social-posts.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Configurar Cuentas de Redes Sociales</h2>
    </div>

    <form method="POST" action="{{ route('admin.social-posts.settings.save') }}">
        @csrf

        {{-- ── FACEBOOK ──────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow p-6 mb-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fa-brands fa-facebook text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Facebook</h3>
                        <p class="text-xs text-gray-400">Meta Graph API — publica en tu Página</p>
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-600">Activo</span>
                    <input type="checkbox" name="facebook[active]" value="1"
                           {{ $accounts['facebook']->active ? 'checked' : '' }}
                           class="rounded accent-blue-600">
                </label>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nombre de la cuenta</label>
                    <input type="text" name="facebook[account_name]"
                           value="{{ old('facebook.account_name', $accounts['facebook']->account_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                           placeholder="Mi Página de Facebook">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Page ID</label>
                    <input type="text" name="facebook[page_id]"
                           value="{{ old('facebook.page_id', $accounts['facebook']->page_id) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                           placeholder="123456789012345">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Page Access Token
                        @if($accounts['facebook']->access_token)
                            <span class="text-green-600 ml-2"><i class="fa-solid fa-lock"></i> Guardado</span>
                        @endif
                    </label>
                    <input type="password" name="facebook[access_token]"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                           placeholder="{{ $accounts['facebook']->access_token ? 'Dejar vacío para mantener el actual' : 'EAABwz...' }}">
                </div>
            </div>

            <details class="mt-4">
                <summary class="text-xs text-blue-600 cursor-pointer hover:underline">
                    ¿Cómo obtener el Page ID y Access Token?
                </summary>
                <div class="mt-3 bg-blue-50 rounded-lg p-4 text-xs text-blue-800 space-y-2">
                    <p><strong>1.</strong> Ve a <code class="bg-blue-100 px-1 rounded">developers.facebook.com</code> y crea una App (tipo Negocio).</p>
                    <p><strong>2.</strong> En tu App → Herramientas → Explorador de la API Graph.</p>
                    <p><strong>3.</strong> Selecciona tu Página y genera un token con permisos: <code class="bg-blue-100 px-1 rounded">pages_manage_posts</code> + <code class="bg-blue-100 px-1 rounded">pages_read_engagement</code>.</p>
                    <p><strong>4.</strong> El Page ID lo encuentras en: Configuración de tu Página → Información de la página.</p>
                    <p><strong>5.</strong> Convierte el token a <em>long-lived</em> (60 días) usando la herramienta de depuración de tokens de Facebook.</p>
                </div>
            </details>
        </div>

        {{-- ── INSTAGRAM ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow p-6 mb-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center">
                        <i class="fa-brands fa-instagram text-pink-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Instagram</h3>
                        <p class="text-xs text-gray-400">Instagram Graph API — requiere cuenta de negocio + imagen</p>
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-600">Activo</span>
                    <input type="checkbox" name="instagram[active]" value="1"
                           {{ $accounts['instagram']->active ? 'checked' : '' }}
                           class="rounded accent-pink-600">
                </label>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nombre de la cuenta</label>
                    <input type="text" name="instagram[account_name]"
                           value="{{ old('instagram.account_name', $accounts['instagram']->account_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                           placeholder="@mi_estetica">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Instagram User ID</label>
                    <input type="text" name="instagram[ig_user_id]"
                           value="{{ old('instagram.ig_user_id', $accounts['instagram']->ig_user_id) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                           placeholder="17841400000000000">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Page Access Token (el mismo de Facebook)
                        @if($accounts['instagram']->access_token)
                            <span class="text-green-600 ml-2"><i class="fa-solid fa-lock"></i> Guardado</span>
                        @endif
                    </label>
                    <input type="password" name="instagram[access_token]"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono"
                           placeholder="{{ $accounts['instagram']->access_token ? 'Dejar vacío para mantener el actual' : 'EAABwz...' }}">
                </div>
            </div>

            <details class="mt-4">
                <summary class="text-xs text-pink-600 cursor-pointer hover:underline">
                    ¿Cómo obtener el Instagram User ID?
                </summary>
                <div class="mt-3 bg-pink-50 rounded-lg p-4 text-xs text-pink-800 space-y-2">
                    <p><strong>Requisito:</strong> Tu cuenta de Instagram debe ser <em>Cuenta de Negocio</em> y estar vinculada a una Página de Facebook.</p>
                    <p><strong>1.</strong> Usa el Graph API Explorer con el Access Token de tu Página.</p>
                    <p><strong>2.</strong> Llama a: <code class="bg-pink-100 px-1 rounded">GET /me/accounts</code> → obtén el page_id.</p>
                    <p><strong>3.</strong> Luego: <code class="bg-pink-100 px-1 rounded">GET /{page_id}?fields=instagram_business_account</code> → obtienes el IG User ID.</p>
                    <p><strong>Nota:</strong> Instagram requiere URL pública para imágenes. En producción funciona automáticamente.</p>
                </div>
            </details>
        </div>

        {{-- ── WHATSAPP ──────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow p-6 mb-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fa-brands fa-whatsapp text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">WhatsApp</h3>
                        <p class="text-xs text-gray-400">Envía a todos tus pacientes registrados + link para compartir</p>
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-600">Activo</span>
                    <input type="checkbox" name="whatsapp[active]" value="1"
                           {{ $accounts['whatsapp']->active ? 'checked' : '' }}
                           class="rounded accent-green-600">
                </label>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-sm text-green-800">
                <i class="fa-solid fa-circle-info mr-2"></i>
                Al publicar en WhatsApp, el mensaje se enviará automáticamente a todos tus pacientes a través del sistema de mensajería interno. También recibirás un enlace <strong>wa.me</strong> para compartirlo manualmente.
            </div>
            <div class="mt-3">
                <label class="block text-xs font-medium text-gray-600 mb-1">Nombre para identificar (opcional)</label>
                <input type="text" name="whatsapp[account_name]"
                       value="{{ old('whatsapp.account_name', $accounts['whatsapp']->account_name) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                       placeholder="WhatsApp Clínica">
            </div>
        </div>

        {{-- ── TIKTOK ────────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fa-brands fa-tiktok text-gray-800 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">TikTok</h3>
                        <p class="text-xs text-gray-400">Publicación manual asistida — contenido listo para copiar</p>
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-600">Activo</span>
                    <input type="checkbox" name="tiktok[active]" value="1"
                           {{ $accounts['tiktok']->active ? 'checked' : '' }}
                           class="rounded accent-gray-600">
                </label>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                <i class="fa-solid fa-circle-info mr-2 text-gray-500"></i>
                TikTok Content Posting API requiere video y un proceso de autorización empresarial complejo.
                Al seleccionar TikTok, el sistema preparará el texto y te abrirá TikTok para que puedas publicar rápidamente de forma manual.
            </div>
            <div class="mt-3">
                <label class="block text-xs font-medium text-gray-600 mb-1">Nombre de cuenta (referencia)</label>
                <input type="text" name="tiktok[account_name]"
                       value="{{ old('tiktok.account_name', $accounts['tiktok']->account_name) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                       placeholder="@mi_cuenta_tiktok">
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.social-posts.index') }}"
               class="border border-gray-300 text-gray-600 px-5 py-2.5 rounded-xl hover:bg-gray-50 transition text-sm">
                Cancelar
            </a>
            <button type="submit"
                    class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Guardar configuración
            </button>
        </div>
    </form>
</div>
@endsection
