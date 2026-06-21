@extends('layouts.public')

@section('title', 'Productos')

@section('content')

<!-- Header -->
<section class="relative overflow-hidden py-20" style="background: linear-gradient(135deg, #451a03 0%, #92400e 50%, #d97706 100%);">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-6 left-12 w-48 h-48 rounded-full border-2 border-white"></div>
        <div class="absolute bottom-2 right-8 w-28 h-28 rounded-full border border-white"></div>
    </div>
    <div class="max-w-6xl mx-auto px-4 text-center relative z-10">
        <p class="text-amber-300 text-xs font-medium tracking-widest uppercase mb-3">Tienda</p>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-white mb-3">Nuestros Productos</h1>
        <p class="text-amber-100 text-base max-w-xl mx-auto">Cosméticos y tratamientos premium seleccionados para el cuidado de tu piel y cuerpo</p>
    </div>
</section>

<!-- Barra de beneficios -->
<div class="bg-white border-b border-gray-100 py-4 shadow-sm">
    <div class="max-w-5xl mx-auto px-4 flex flex-wrap justify-center gap-6 text-sm text-gray-500">
        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-500"></i> Marcas Premium</span>
        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-500"></i> 100% Originales</span>
        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-500"></i> Dermatológicamente probados</span>
        <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-amber-500"></i> Asesoría personalizada</span>
    </div>
</div>

<!-- Productos por categoría -->
<section class="py-16">
    <div class="max-w-6xl mx-auto px-4">
        @forelse($categories as $category)
            <div class="mb-14">
                <div class="flex items-center gap-4 mb-7">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                        <i class="fa-solid fa-jar text-amber-600"></i>
                    </div>
                    <h2 class="font-display text-2xl font-bold text-gray-800">{{ $category->name }}</h2>
                    <div class="flex-1 h-px bg-amber-100"></div>
                </div>
                <div class="grid md:grid-cols-4 gap-5">
                    @foreach($category->products as $product)
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden border border-gray-100 group">
                            @if($product->image)
                                <div class="overflow-hidden h-44">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                            @else
                                <div class="w-full h-44 bg-gradient-to-br from-amber-50 to-yellow-100 flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-jar text-4xl text-amber-300 mb-1"></i>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 leading-tight">{{ $product->name }}</h3>
                                @if($product->sku)
                                    <p class="text-xs text-gray-300 mt-1">Ref: {{ $product->sku }}</p>
                                @endif
                                @if($product->description)
                                    <p class="text-gray-400 text-xs mt-2 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-24 text-gray-300">
                <i class="fa-solid fa-jar text-7xl mb-5"></i>
                <p class="text-xl text-gray-400 font-display">Próximamente productos disponibles</p>
                <p class="text-sm text-gray-300 mt-2">Estamos seleccionando lo mejor para ti</p>
            </div>
        @endforelse
    </div>
</section>

<!-- CTA -->
<section class="py-14 bg-amber-50 border-t border-amber-100">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <i class="fa-solid fa-leaf text-amber-500 text-3xl mb-4"></i>
        <h3 class="font-display text-2xl font-bold text-gray-800 mb-3">¿Necesitas asesoría?</h3>
        <p class="text-gray-500 text-sm mb-6">Contáctanos y te ayudamos a elegir el producto ideal para tu tipo de piel.</p>
        <a href="{{ route('login') }}" class="inline-block bg-amber-600 text-white px-8 py-3.5 rounded-full font-medium hover:bg-amber-700 transition shadow-md text-sm">
            <i class="fa-solid fa-comments mr-2"></i>Hablar con un especialista
        </a>
    </div>
</section>
@endsection
