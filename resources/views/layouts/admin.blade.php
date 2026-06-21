<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<!-- Sidebar -->
<div class="flex h-screen overflow-hidden">
    <aside class="w-64 bg-indigo-900 text-white flex flex-col flex-shrink-0">
        <div class="flex items-center justify-center h-16 bg-indigo-950 px-4">
            <span class="text-xl font-bold tracking-wide">✨ ESB Admin</span>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-gauge-high w-5"></i> Dashboard
            </a>

            <div class="pt-4 pb-1 text-xs text-indigo-400 uppercase tracking-wider px-3">Gestión</div>

            <a href="{{ route('admin.appointments.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.appointments.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-calendar-check w-5"></i> Citas
            </a>

            <a href="{{ route('admin.patients.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.patients.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-users w-5"></i> Pacientes
            </a>

            <a href="{{ route('admin.wellness.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.wellness.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-robot w-5"></i> Bienestar IA
            </a>

            <a href="{{ route('admin.social-posts.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.social-posts.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-share-nodes w-5"></i> Redes Sociales
            </a>

            <a href="{{ route('admin.messages.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.messages.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-comments w-5"></i> Mensajes
                @php
                    $unreadBadge = \App\Models\Message::where('tenant_id', auth()->user()->tenant_id)
                        ->where('receiver_id', auth()->id())
                        ->whereNull('read_at')
                        ->count();
                @endphp
                @if($unreadBadge > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $unreadBadge }}</span>
                @endif
            </a>

            <div class="pt-4 pb-1 text-xs text-indigo-400 uppercase tracking-wider px-3">Catálogos</div>

            <a href="{{ route('admin.services.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.services.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-stethoscope w-5"></i> Servicios
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-box w-5"></i> Productos
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-tags w-5"></i> Categorías
            </a>

            <a href="{{ route('admin.custom-fields.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.custom-fields.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-list-check w-5"></i> Campos Dinámicos
            </a>

            <div class="pt-4 pb-1 text-xs text-indigo-400 uppercase tracking-wider px-3">Cuenta</div>

            <a href="{{ route('admin.settings.edit') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-700' : '' }}">
                <i class="fa-solid fa-gear w-5"></i> Configuración
            </a>

            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fa-solid fa-user-gear w-5"></i> Mi Perfil
            </a>

            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fa-solid fa-globe w-5"></i> Ver Sitio
            </a>
        </nav>

        <div class="p-4 border-t border-indigo-700">
            <div class="text-sm text-indigo-300 mb-2">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-700 text-sm transition">
                    <i class="fa-solid fa-right-from-bracket w-5"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white shadow flex items-center px-6 justify-between">
            <h1 class="text-lg font-semibold text-gray-700">@yield('header', 'Dashboard')</h1>
            <span class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</span>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
