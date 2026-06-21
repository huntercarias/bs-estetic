@extends('layouts.public')

@section('title', 'Servicios')

@section('content')

<!-- Header -->
<section class="relative overflow-hidden py-20" style="background: linear-gradient(135deg, #4a0520 0%, #9d174d 60%, #be185d 100%);">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-8 right-16 w-40 h-40 rounded-full border-2 border-white"></div>
        <div class="absolute bottom-4 left-10 w-24 h-24 rounded-full border border-white"></div>
        <div class="absolute top-1/2 right-1/4 w-16 h-16 rounded-full bg-white"></div>
    </div>
    <div class="max-w-6xl mx-auto px-4 text-center relative z-10">
        <p class="text-rose-300 text-xs font-medium tracking-widest uppercase mb-3">Lo que ofrecemos</p>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-white mb-3">Nuestros Servicios</h1>
        <p class="text-rose-200 text-base max-w-xl mx-auto">Tratamientos estéticos unisex para hombres y mujeres — disponibles en nuestro local o a domicilio</p>
    </div>
</section>

<!-- Barra de beneficios -->
<div class="bg-white border-b border-gray-100 py-4 shadow-sm">
    <div class="max-w-5xl mx-auto px-4 flex flex-wrap justify-center gap-6 text-sm text-gray-500">
        <span class="flex items-center gap-2"><i class="fa-solid fa-venus-mars text-rose-500"></i> Servicios unisex</span>
        <span class="flex items-center gap-2"><i class="fa-solid fa-house text-rose-500"></i> A domicilio</span>
        <span class="flex items-center gap-2"><i class="fa-solid fa-store text-rose-500"></i> Local físico</span>
        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-rose-500"></i> Profesionales certificados</span>
    </div>
</div>

<!-- Servicios por categoría -->
<section class="py-16">
    <div class="max-w-6xl mx-auto px-4">
        @forelse($categories as $category)
            <div class="mb-14">
                <div class="flex items-center gap-4 mb-7">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                        <i class="fa-solid fa-spa text-rose-600"></i>
                    </div>
                    <h2 class="font-display text-2xl font-bold text-gray-800">{{ $category->name }}</h2>
                    <div class="flex-1 h-px bg-rose-100"></div>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($category->services as $service)
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100 group">
                            @if($service->image)
                                <div class="overflow-hidden h-48">
                                    <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                            @else
                                <div class="w-full h-44 bg-gradient-to-br from-rose-50 to-pink-100 flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-spa text-4xl text-rose-300 mb-1"></i>
                                </div>
                            @endif
                            <div class="p-5">
                                <h3 class="font-display font-bold text-gray-800 text-lg">{{ $service->name }}</h3>
                                @if($service->description)
                                    <p class="text-gray-400 text-sm mt-2 leading-relaxed">{{ $service->description }}</p>
                                @endif
                                @if($service->duration_minutes)
                                <div class="mt-4 pt-4 border-t border-gray-50">
                                    <span class="text-xs text-gray-400 bg-rose-50 px-3 py-1 rounded-full">
                                        <i class="fa-regular fa-clock mr-1"></i>aprox. {{ $service->duration_minutes }} min
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-24 text-gray-300">
                <i class="fa-solid fa-spa text-7xl mb-5"></i>
                <p class="text-xl text-gray-400 font-display">Próximamente servicios disponibles</p>
                <p class="text-sm text-gray-300 mt-2">Estamos preparando algo especial para ti</p>
            </div>
        @endforelse
    </div>
</section>

<!-- CTA -->
<section class="py-14 bg-rose-50 border-t border-rose-100">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h3 class="font-display text-2xl font-bold text-gray-800 mb-3">¿Lista para reservar?</h3>
        <p class="text-gray-500 text-sm mb-6">Agenda tu cita en minutos desde tu portal personal.</p>
        <a href="{{ route('login') }}" class="inline-block gradient-rose text-white px-8 py-3.5 rounded-full font-medium hover:opacity-90 transition shadow-md text-sm">
            <i class="fa-solid fa-calendar-plus mr-2"></i>Reservar mi cita
        </a>
    </div>
</section>
@endsection
