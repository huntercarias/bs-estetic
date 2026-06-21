@extends('layouts.patient')
@section('title','Mi Dashboard')

@section('content')

<!-- Saludo -->
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <p class="text-rose-400 text-xs font-medium tracking-widest uppercase mb-1">Portal Personal</p>
        <h1 class="font-display text-2xl font-bold text-gray-800">Hola, {{ auth()->user()->name }}</h1>
        <p class="text-gray-400 text-sm mt-0.5">Bienvenida de vuelta. ¿Cómo te podemos ayudar hoy?</p>
    </div>
    <div class="w-12 h-12 rounded-full gradient-rose flex items-center justify-center shadow-md">
        <i class="fa-solid fa-spa text-white text-lg"></i>
    </div>
</div>

{{-- Banner perfil incompleto --}}
@if(!$profileComplete)
<a href="{{ route('patient.profile') }}"
   class="flex items-center gap-4 bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 hover:bg-amber-100 transition group">
    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-circle-exclamation text-amber-500"></i>
    </div>
    <div class="flex-1 min-w-0">
        <p class="font-semibold text-amber-800 text-sm">Completa tu perfil personal</p>
        <p class="text-amber-500 text-xs mt-0.5">Agrega tu información física para recibir una atención totalmente personalizada.</p>
    </div>
    <i class="fa-solid fa-arrow-right text-amber-300 group-hover:translate-x-1 transition-transform"></i>
</a>
@endif

<!-- Cards de acceso rápido -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('patient.appointments') }}"
       class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group text-center">
        <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center mx-auto mb-3 group-hover:bg-rose-100 transition">
            <i class="fa-solid fa-calendar-check text-rose-500 text-xl"></i>
        </div>
        <div class="font-semibold text-gray-700 text-sm">Mis Citas</div>
        <div class="text-xs text-gray-400 mt-0.5">Ver historial</div>
    </a>

    <a href="{{ route('patient.book') }}"
       class="rounded-2xl shadow-sm p-5 hover:opacity-90 transition text-center gradient-rose text-white">
        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mx-auto mb-3">
            <i class="fa-solid fa-calendar-plus text-white text-xl"></i>
        </div>
        <div class="font-semibold text-sm">Reservar</div>
        <div class="text-xs text-rose-200 mt-0.5">Nueva cita</div>
    </a>

    <a href="{{ route('patient.wellness') }}"
       class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group text-center">
        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-100 transition">
            <i class="fa-solid fa-leaf text-purple-400 text-xl"></i>
        </div>
        <div class="font-semibold text-gray-700 text-sm">Mi Plan</div>
        <div class="text-xs text-gray-400 mt-0.5">{{ $activePlan ? 'Activo ✓' : 'Sin plan' }}</div>
    </a>

    <a href="{{ route('patient.profile') }}"
       class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group text-center">
        <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center mx-auto mb-3 group-hover:bg-rose-100 transition">
            <i class="fa-solid fa-user-pen text-rose-400 text-xl"></i>
        </div>
        <div class="font-semibold text-gray-700 text-sm">Mi Perfil</div>
        <div class="text-xs text-gray-400 mt-0.5">{{ $profileComplete ? 'Completo ✓' : 'Pendiente' }}</div>
    </a>
</div>

{{-- Próximas citas --}}
@if($upcomingAppointments->count())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center">
            <i class="fa-solid fa-clock text-rose-400"></i>
        </div>
        <h2 class="font-display font-bold text-gray-800">Próximas Citas</h2>
    </div>
    <div class="space-y-3">
        @foreach($upcomingAppointments as $apt)
        <div class="flex items-center justify-between bg-rose-50/50 border border-rose-100 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-white shadow-sm flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-spa text-rose-400"></i>
                </div>
                <div>
                    <div class="font-semibold text-gray-800 text-sm">{{ $apt->service->name }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        <i class="fa-regular fa-calendar mr-1"></i>{{ $apt->scheduled_at->format('d/m/Y') }}
                        <span class="mx-1">·</span>
                        <i class="fa-regular fa-clock mr-1"></i>{{ $apt->scheduled_at->format('H:i') }}
                    </div>
                </div>
            </div>
            @php $c = $apt->appointment_status_color @endphp
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-{{ $c }}-100 text-{{ $c }}-700">
                {{ $apt->appointment_status_label }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
    <div class="w-16 h-16 rounded-2xl bg-rose-50 flex items-center justify-center mx-auto mb-4">
        <i class="fa-regular fa-calendar text-rose-300 text-2xl"></i>
    </div>
    <p class="text-gray-500 font-medium mb-1">No tienes citas próximas</p>
    <p class="text-gray-300 text-sm mb-5">¡Agenda una sesión y comienza tu transformación!</p>
    <a href="{{ route('patient.book') }}"
       class="inline-block gradient-rose text-white px-6 py-2.5 rounded-full text-sm font-medium hover:opacity-90 transition shadow-sm">
        <i class="fa-solid fa-plus mr-1"></i> Reservar ahora
    </a>
</div>
@endif

@endsection
