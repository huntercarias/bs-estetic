<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
            .font-display { font-family: 'Playfair Display', serif; }
            .gradient-rose { background: linear-gradient(135deg, #be185d 0%, #9d174d 100%); }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex" style="background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 50%, #ffe4e6 100%);">

            <!-- Panel izquierdo decorativo (solo escritorio) -->
            <div class="hidden lg:flex lg:w-1/2 gradient-rose items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-16 left-16 w-48 h-48 rounded-full border-2 border-white"></div>
                    <div class="absolute bottom-20 right-10 w-64 h-64 rounded-full border border-white"></div>
                    <div class="absolute top-1/2 left-1/3 w-20 h-20 rounded-full bg-white"></div>
                </div>
                <div class="text-center text-white relative z-10 px-12">
                    <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-spa text-white text-3xl"></i>
                    </div>
                    <h1 class="font-display text-4xl font-bold mb-4">{{ config('app.name') }}</h1>
                    <p class="text-rose-200 text-base leading-relaxed max-w-sm">Tu transformación y bienestar corporal comienzan aquí. Expertos en estética a tu servicio.</p>
                    <div class="mt-8 flex flex-col gap-3 text-sm text-rose-200">
                        <span class="flex items-center gap-2 justify-center"><i class="fa-solid fa-circle-check text-rose-300"></i> Tratamientos personalizados</span>
                        <span class="flex items-center gap-2 justify-center"><i class="fa-solid fa-circle-check text-rose-300"></i> Profesionales certificados</span>
                        <span class="flex items-center gap-2 justify-center"><i class="fa-solid fa-circle-check text-rose-300"></i> Agenda en minutos</span>
                    </div>
                </div>
            </div>

            <!-- Panel derecho: formulario -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12">
                <div class="w-full max-w-md">
                    <div class="mb-8 text-center">
                        <a href="/" class="inline-flex items-center gap-2 mb-2">
                            <div class="w-10 h-10 rounded-full gradient-rose flex items-center justify-center">
                                <i class="fa-solid fa-spa text-white"></i>
                            </div>
                            <span class="font-display text-xl font-bold text-rose-800">{{ config('app.name') }}</span>
                        </a>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-rose-100 px-8 py-8">
                        {{ $slot }}
                    </div>

                    <p class="mt-6 text-center text-xs text-gray-400">
                        &copy; {{ date('Y') }} {{ config('app.name') }} · Belleza &amp; Bienestar Corporal
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
