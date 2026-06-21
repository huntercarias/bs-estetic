@extends('layouts.public')

@section('title', 'Inicio')

@section('content')

<!-- Hero Section -->
<section class="relative overflow-hidden" style="background: linear-gradient(135deg, #4a0520 0%, #831843 40%, #9d174d 70%, #be185d 100%);">
    <!-- Decorative circles -->
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-10" style="background: radial-gradient(circle, #f9a8d4, transparent); transform: translate(30%, -30%);"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full opacity-10" style="background: radial-gradient(circle, #fda4af, transparent); transform: translate(-30%, 30%);"></div>

    <div class="max-w-6xl mx-auto px-4 py-28 md:py-36 text-center relative z-10">
        <p class="text-rose-300 text-sm font-medium tracking-widest uppercase mb-4">Bienvenida a</p>
        <h1 class="font-display text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">{{ $tenant->name }}</h1>
        <p class="text-rose-200 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed font-light">
            {{ $tenant->description ?? 'Estética corporal profesional para hombres y mujeres. A domicilio o en nuestro local — tu mejor versión comienza aquí.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('services') }}"
               class="bg-white text-rose-800 px-8 py-3.5 rounded-full font-semibold hover:bg-rose-50 transition shadow-lg text-sm">
                <i class="fa-solid fa-spa mr-2"></i>Ver Servicios
            </a>
            <a href="{{ route('products') }}"
               class="border-2 border-white/60 text-white px-8 py-3.5 rounded-full font-semibold hover:bg-white/10 transition text-sm">
                <i class="fa-solid fa-gem mr-2"></i>Ver Productos
            </a>
        </div>
    </div>
</section>

<!-- Franja dorada -->
<div class="bg-amber-50 border-y border-amber-100 py-5">
    <div class="max-w-4xl mx-auto px-4 flex flex-wrap justify-center gap-8 text-amber-800 text-sm font-medium">
        <span class="flex items-center gap-2"><i class="fa-solid fa-venus-mars text-amber-600"></i> Servicios unisex</span>
        <span class="flex items-center gap-2"><i class="fa-solid fa-house text-amber-600"></i> Atención a domicilio</span>
        <span class="flex items-center gap-2"><i class="fa-solid fa-store text-amber-600"></i> Local físico disponible</span>
        <span class="flex items-center gap-2"><i class="fa-solid fa-shield-heart text-amber-600"></i> Profesionales certificados</span>
    </div>
</div>

<!-- Info Cards -->
<section class="py-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="text-rose-600 text-sm font-medium tracking-widest uppercase mb-2">Por qué elegirnos</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-gray-800">Cuidado que transforma</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-rose-50 text-center hover:shadow-md transition group">
                <div class="w-16 h-16 rounded-full bg-rose-50 flex items-center justify-center mx-auto mb-5 group-hover:bg-rose-100 transition">
                    <i class="fa-solid fa-spa text-3xl text-rose-500"></i>
                </div>
                <h3 class="font-display font-bold text-lg mb-2 text-gray-800">Atención Especializada</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Esteticistas profesionales para hombres y mujeres, comprometidos con tu transformación.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-rose-50 text-center hover:shadow-md transition group">
                <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-5 group-hover:bg-amber-100 transition">
                    <i class="fa-solid fa-jar text-3xl text-amber-500"></i>
                </div>
                <h3 class="font-display font-bold text-lg mb-2 text-gray-800">Productos Premium</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Cosméticos y tratamientos de las mejores marcas especializadas en estética corporal.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-rose-50 text-center hover:shadow-md transition group">
                <div class="w-16 h-16 rounded-full bg-pink-50 flex items-center justify-center mx-auto mb-5 group-hover:bg-pink-100 transition">
                    <i class="fa-solid fa-calendar-heart text-3xl text-pink-500"></i>
                </div>
                <h3 class="font-display font-bold text-lg mb-2 text-gray-800">Reserva Fácil</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Elige si te atendemos en nuestro local o en la comodidad de tu hogar. Agenda en minutos.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Services -->
@if($featuredServices->count())
<section class="py-16 bg-stone-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="text-rose-600 text-sm font-medium tracking-widest uppercase mb-2">Lo que ofrecemos</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-gray-800">Nuestros Servicios</h2>
            <p class="text-gray-400 mt-3 text-sm">Tratamientos diseñados para realzar tu belleza natural</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($featuredServices as $service)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100 group">
                    @if($service->image)
                        <div class="overflow-hidden h-52">
                            <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                    @else
                        <div class="w-full h-52 bg-gradient-to-br from-rose-50 to-pink-100 flex flex-col items-center justify-center">
                            <i class="fa-solid fa-spa text-5xl text-rose-300 mb-2"></i>
                        </div>
                    @endif
                    <div class="p-5">
                        <h3 class="font-display font-bold text-lg text-gray-800">{{ $service->name }}</h3>
                        @if($service->description)
                            <p class="text-gray-400 text-sm mt-2 line-clamp-2 leading-relaxed">{{ $service->description }}</p>
                        @endif
                        @if($service->duration_minutes)
                        <div class="mt-4 pt-4 border-t border-gray-50">
                            <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full">
                                <i class="fa-regular fa-clock mr-1"></i>aprox. {{ $service->duration_minutes }} min
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('services') }}"
               class="inline-block gradient-rose text-white px-8 py-3.5 rounded-full hover:opacity-90 transition shadow-md text-sm font-medium">
                Ver todos los servicios <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count())
<section class="py-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="text-amber-600 text-sm font-medium tracking-widest uppercase mb-2">Tienda</p>
            <h2 class="font-display text-3xl md:text-4xl font-bold text-gray-800">Nuestros Productos</h2>
            <p class="text-gray-400 mt-3 text-sm">Cosméticos y tratamientos seleccionados para tu cuidado diario</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100 group">
                    @if($product->image)
                        <div class="overflow-hidden h-52">
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                    @else
                        <div class="w-full h-52 bg-gradient-to-br from-amber-50 to-yellow-100 flex items-center justify-center">
                            <i class="fa-solid fa-jar text-5xl text-amber-300"></i>
                        </div>
                    @endif
                    <div class="p-5">
                        <h3 class="font-display font-bold text-lg text-gray-800">{{ $product->name }}</h3>
                        @if($product->description)
                            <p class="text-gray-400 text-sm mt-2 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('products') }}"
               class="inline-block bg-amber-600 text-white px-8 py-3.5 rounded-full hover:bg-amber-700 transition shadow-md text-sm font-medium">
                Ver todos los productos <i class="fa-solid fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Banner reserva -->
<section class="py-20" style="background: linear-gradient(135deg, #1c1917 0%, #292524 100%);">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <i class="fa-solid fa-spa text-rose-400 text-4xl mb-5"></i>
        <h2 class="font-display text-3xl md:text-4xl font-bold text-white mb-4">¿Lista para tu transformación?</h2>
        <p class="text-stone-400 mb-8 max-w-xl mx-auto leading-relaxed">Agenda tu cita hoy y déjate consentir por nuestros especialistas. Tu bienestar merece lo mejor.</p>
        <a href="{{ route('login') }}"
           class="inline-block gradient-rose text-white px-10 py-4 rounded-full font-semibold hover:opacity-90 transition shadow-lg">
            Reservar mi cita <i class="fa-solid fa-calendar-plus ml-2"></i>
        </a>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-rose-800 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="font-display text-3xl font-bold mb-3">¿Necesitas más información?</h2>
        <p class="text-rose-200 mb-8 text-sm">Estamos aquí para ayudarte. Contáctanos por cualquiera de nuestros canales.</p>
        <div class="flex flex-wrap justify-center gap-8 text-sm">
            @if(!empty($tenant->phone))
                <div class="flex items-center gap-3 bg-white/10 px-5 py-3 rounded-full">
                    <i class="fa-solid fa-phone text-rose-300"></i>
                    <span>{{ $tenant->phone }}</span>
                </div>
            @endif
            @if(!empty($tenant->email))
                <div class="flex items-center gap-3 bg-white/10 px-5 py-3 rounded-full">
                    <i class="fa-solid fa-envelope text-rose-300"></i>
                    <span>{{ $tenant->email }}</span>
                </div>
            @endif
            @if(!empty($tenant->address))
                <div class="flex items-center gap-3 bg-white/10 px-5 py-3 rounded-full">
                    <i class="fa-solid fa-location-dot text-rose-300"></i>
                    <span>{{ $tenant->address }}</span>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
