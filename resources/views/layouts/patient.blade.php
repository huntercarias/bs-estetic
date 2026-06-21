<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mi Cuenta') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .gradient-rose { background: linear-gradient(135deg, #be185d 0%, #9d174d 100%); }
    </style>
</head>
<body class="bg-rose-50/30 font-sans min-h-screen">

<!-- Navbar del paciente -->
<nav class="bg-white shadow-sm border-b border-rose-100 sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('patient.dashboard') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full gradient-rose flex items-center justify-center">
                <i class="fa-solid fa-spa text-white text-xs"></i>
            </div>
            <span class="font-display font-bold text-rose-800 text-base">Mi Cuenta</span>
        </a>

        <div class="flex items-center gap-1 text-sm overflow-x-auto">
            <a href="{{ route('patient.dashboard') }}"
               class="px-3 py-1.5 rounded-lg transition whitespace-nowrap {{ request()->routeIs('patient.dashboard') ? 'bg-rose-50 text-rose-700 font-semibold' : 'text-gray-500 hover:text-rose-700 hover:bg-rose-50' }}">
                <i class="fa-solid fa-house-heart mr-1 md:inline hidden"></i>Inicio
            </a>
            <a href="{{ route('patient.appointments') }}"
               class="px-3 py-1.5 rounded-lg transition whitespace-nowrap {{ request()->routeIs('patient.appointments') ? 'bg-rose-50 text-rose-700 font-semibold' : 'text-gray-500 hover:text-rose-700 hover:bg-rose-50' }}">
                <i class="fa-solid fa-calendar-check mr-1 md:inline hidden"></i>Mis Citas
            </a>
            <a href="{{ route('patient.book') }}"
               class="px-3 py-1.5 rounded-lg transition whitespace-nowrap gradient-rose text-white font-medium {{ request()->routeIs('patient.book') ? 'opacity-90' : 'hover:opacity-90' }}">
                <i class="fa-solid fa-plus mr-1"></i>Reservar
            </a>
            <a href="{{ route('patient.wellness') }}"
               class="px-3 py-1.5 rounded-lg transition whitespace-nowrap {{ request()->routeIs('patient.wellness*') ? 'bg-rose-50 text-rose-700 font-semibold' : 'text-gray-500 hover:text-rose-700 hover:bg-rose-50' }}">
                <i class="fa-solid fa-leaf mr-1 md:inline hidden"></i>Mi Plan
            </a>
            <a href="{{ route('patient.profile') }}"
               class="px-3 py-1.5 rounded-lg transition whitespace-nowrap {{ request()->routeIs('patient.profile') ? 'bg-rose-50 text-rose-700 font-semibold' : 'text-gray-500 hover:text-rose-700 hover:bg-rose-50' }}">
                <i class="fa-solid fa-user mr-1 md:inline hidden"></i>Perfil
            </a>
            <a href="{{ route('patient.messages') }}"
               class="relative px-3 py-1.5 rounded-lg transition whitespace-nowrap {{ request()->routeIs('patient.messages*') ? 'bg-rose-50 text-rose-700 font-semibold' : 'text-gray-500 hover:text-rose-700 hover:bg-rose-50' }}">
                @php
                    $unreadPatient = \App\Models\Message::where('tenant_id', auth()->user()->tenant_id)
                        ->where('receiver_id', auth()->id())
                        ->whereNull('read_at')
                        ->count();
                @endphp
                <i class="fa-solid fa-message mr-1 md:inline hidden"></i>Mensajes
                @if($unreadPatient > 0)
                    <span class="absolute -top-0.5 -right-0.5 bg-rose-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full font-bold">{{ $unreadPatient }}</span>
                @endif
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-3 py-1.5 rounded-lg text-gray-300 hover:text-red-400 hover:bg-red-50 transition text-xs">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="max-w-5xl mx-auto px-4 py-8">
    @if(session('success'))
    <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
        {{ session('error') }}
    </div>
    @endif

    @yield('content')
</main>

</body>
</html>
