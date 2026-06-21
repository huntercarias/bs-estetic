<x-guest-layout>
    <h2 class="font-display text-2xl font-bold text-gray-800 mb-1">Bienvenida de vuelta</h2>
    <p class="text-sm text-gray-400 mb-6">Inicia sesión para acceder a tu portal personal.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autofocus autocomplete="username"
                          placeholder="tucorreo@ejemplo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                          required autocomplete="current-password" placeholder="Tu contraseña" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-rose-600 shadow-sm focus:ring-rose-400" name="remember">
                <span class="ms-2 text-sm text-gray-500">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-rose-600 hover:underline" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full gradient-rose text-white py-3 rounded-xl font-semibold hover:opacity-90 transition shadow-sm text-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
        </button>

        @if (Route::has('register'))
        <p class="text-center text-sm text-gray-400 pt-1">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-rose-600 font-medium hover:underline">Regístrate aquí</a>
        </p>
        @endif
    </form>
</x-guest-layout>
