<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Próximamente — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-indigo-900 text-white min-h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <div class="text-8xl mb-6">🏥</div>
        <h1 class="text-5xl font-bold mb-4">Próximamente</h1>
        <p class="text-indigo-300 text-xl mb-8">Estamos preparando algo increíble para ti.</p>
        @guest
            <a href="{{ route('login') }}"
               class="bg-white text-indigo-700 px-6 py-3 rounded-full font-semibold hover:bg-indigo-50 transition">
                Iniciar Sesión
            </a>
        @endguest
    </div>
</body>
</html>
