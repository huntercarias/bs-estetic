<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inicio') — {{ $tenant->name ?? config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .gradient-rose { background: linear-gradient(135deg, #be185d 0%, #9d174d 50%, #701a45 100%); }
        .gradient-gold { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }
    </style>
</head>
<body class="bg-stone-50 text-gray-800">

<!-- Navbar -->
<nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-rose-100">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            @if(!empty($tenant->logo))
                <img src="{{ Storage::url($tenant->logo) }}" alt="Logo" class="h-10">
            @else
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full gradient-rose flex items-center justify-center">
                        <i class="fa-solid fa-spa text-white text-sm"></i>
                    </div>
                    <span class="font-display text-xl font-bold text-rose-800">{{ $tenant->name ?? 'BS Estética' }}</span>
                </div>
            @endif
        </a>

        <div class="hidden md:flex items-center gap-7 text-sm font-medium">
            <a href="{{ route('home') }}" class="transition {{ request()->routeIs('home') ? 'text-rose-700 font-semibold' : 'text-gray-500 hover:text-rose-700' }}">Inicio</a>
            <a href="{{ route('services') }}" class="transition {{ request()->routeIs('services') ? 'text-rose-700 font-semibold' : 'text-gray-500 hover:text-rose-700' }}">Servicios</a>
            <a href="{{ route('products') }}" class="transition {{ request()->routeIs('products') ? 'text-rose-700 font-semibold' : 'text-gray-500 hover:text-rose-700' }}">Productos</a>

            @auth
                <a href="{{ route('admin.dashboard') }}" class="gradient-rose text-white px-5 py-2 rounded-full text-sm hover:opacity-90 transition shadow-sm">Panel Admin</a>
            @else
                <a href="{{ route('login') }}" class="gradient-rose text-white px-5 py-2 rounded-full text-sm hover:opacity-90 transition shadow-sm">Iniciar Sesión</a>
            @endauth
        </div>

        <!-- Mobile menu button -->
        <button id="mobile-menu-btn" class="md:hidden text-rose-700">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden px-4 pb-4 space-y-1 border-t border-rose-50">
        <a href="{{ route('home') }}" class="block py-2.5 text-gray-600 hover:text-rose-700 border-b border-gray-50">Inicio</a>
        <a href="{{ route('services') }}" class="block py-2.5 text-gray-600 hover:text-rose-700 border-b border-gray-50">Servicios</a>
        <a href="{{ route('products') }}" class="block py-2.5 text-gray-600 hover:text-rose-700 border-b border-gray-50">Productos</a>
        @auth
            <a href="{{ route('admin.dashboard') }}" class="block py-2.5 text-rose-700 font-semibold">Panel Admin</a>
        @else
            <a href="{{ route('login') }}" class="block py-2.5 text-rose-700 font-semibold">Iniciar Sesión</a>
        @endauth
    </div>
</nav>

<!-- Content -->
@yield('content')

<!-- Footer -->
<footer class="bg-stone-900 text-stone-300 pt-14 pb-8 mt-20">
    <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-3 gap-10 mb-10">
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-full gradient-rose flex items-center justify-center">
                    <i class="fa-solid fa-spa text-white text-xs"></i>
                </div>
                <h3 class="font-display text-white font-semibold text-lg">{{ $tenant->name ?? 'BS Estética' }}</h3>
            </div>
            <p class="text-sm leading-relaxed text-stone-400">{{ $tenant->description ?? 'Tu transformación y bienestar son nuestra pasión.' }}</p>
            <div class="flex flex-wrap gap-2 mt-4">
                <span class="text-xs bg-stone-700 text-stone-300 px-3 py-1 rounded-full flex items-center gap-1.5">
                    <i class="fa-solid fa-venus-mars text-rose-400 text-xs"></i> Servicios unisex
                </span>
                <span class="text-xs bg-stone-700 text-stone-300 px-3 py-1 rounded-full flex items-center gap-1.5">
                    <i class="fa-solid fa-house text-rose-400 text-xs"></i> A domicilio
                </span>
                <span class="text-xs bg-stone-700 text-stone-300 px-3 py-1 rounded-full flex items-center gap-1.5">
                    <i class="fa-solid fa-store text-rose-400 text-xs"></i> Local físico
                </span>
            </div>
            <div class="flex gap-3 mt-4">
                <a href="#" class="w-8 h-8 rounded-full bg-stone-700 hover:bg-rose-700 flex items-center justify-center transition">
                    <i class="fa-brands fa-instagram text-sm"></i>
                </a>
                <a href="#" class="w-8 h-8 rounded-full bg-stone-700 hover:bg-rose-700 flex items-center justify-center transition">
                    <i class="fa-brands fa-facebook-f text-sm"></i>
                </a>
                <a href="#" class="w-8 h-8 rounded-full bg-stone-700 hover:bg-rose-700 flex items-center justify-center transition">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                </a>
            </div>
        </div>
        <div>
            <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-widest">Contacto</h3>
            <ul class="space-y-3 text-sm text-stone-400">
                @if(!empty($tenant->phone))
                    <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-rose-400 w-4"></i>{{ $tenant->phone }}</li>
                @endif
                @if(!empty($tenant->email))
                    <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-rose-400 w-4"></i>{{ $tenant->email }}</li>
                @endif
                @if(!empty($tenant->address))
                    <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot text-rose-400 w-4 mt-0.5"></i>{{ $tenant->address }}</li>
                @endif
            </ul>
        </div>
        <div>
            <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-widest">Navegación</h3>
            <ul class="space-y-3 text-sm text-stone-400">
                <li><a href="{{ route('home') }}" class="hover:text-rose-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-rose-600"></i>Inicio</a></li>
                <li><a href="{{ route('services') }}" class="hover:text-rose-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-rose-600"></i>Servicios</a></li>
                <li><a href="{{ route('products') }}" class="hover:text-rose-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-rose-600"></i>Productos</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-rose-300 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-rose-600"></i>Mi Cuenta</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-stone-700 pt-6 text-center text-xs text-stone-500">
        &copy; {{ date('Y') }} {{ $tenant->name ?? 'BS Estética' }}. Todos los derechos reservados.
        <span class="mx-2">·</span> Belleza &amp; Bienestar Corporal · Servicios unisex · A domicilio y local físico
    </div>
    <div class="text-center mt-2" style="font-size:10px; color:#57534e;">
        <i class="fa-solid fa-microchip" style="font-size:9px;"></i>
        Este sitio utiliza inteligencia artificial para personalizar tu experiencia de bienestar.
    </div>
</footer>

<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
</body>
</html>
