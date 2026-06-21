<x-guest-layout>
    <h2 class="font-display text-2xl font-bold text-gray-800 mb-1">Crea tu cuenta</h2>
    <p class="text-sm text-gray-400 mb-6">Regístrate y accede a tu portal de bienestar estético.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Datos de acceso --}}
        <div class="bg-rose-50 rounded-xl p-4 space-y-3">
            <p class="text-xs font-semibold text-rose-600 uppercase tracking-widest mb-1">
                <i class="fa-solid fa-lock mr-1"></i>Datos de acceso
            </p>

            <div>
                <x-input-label for="name" value="Nombre completo *" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                              :value="old('name')" required autofocus autocomplete="name"
                              placeholder="Tu nombre completo" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="email" value="Correo electrónico *" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                              :value="old('email')" required autocomplete="username"
                              placeholder="tucorreo@ejemplo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="password" value="Contraseña *" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                                  required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" value="Confirmar *" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                                  class="mt-1 block w-full" required autocomplete="new-password"
                                  placeholder="Repite la contraseña" />
                </div>
            </div>
        </div>

        {{-- Perfil personal (opcional) --}}
        <div class="border border-gray-100 rounded-xl overflow-hidden">
            <button type="button" onclick="toggleProfile()"
                    class="w-full flex justify-between items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-600 transition">
                <span><i class="fa-solid fa-user-pen mr-2 text-rose-400"></i>Perfil personal <span class="text-gray-300 font-normal">(opcional)</span></span>
                <i id="profile-arrow" class="fa-solid fa-chevron-down text-gray-300 transition-transform"></i>
            </button>

            <div id="profile-section" class="hidden px-4 pb-4 pt-3 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="birth_date" value="Fecha de nacimiento" />
                        <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full"
                                      :value="old('birth_date')" />
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="gender" value="Género" />
                        <select id="gender" name="gender"
                                class="mt-1 block w-full border-gray-300 focus:border-rose-400 focus:ring-rose-400 rounded-md shadow-sm text-sm">
                            <option value="">— Seleccionar —</option>
                            <option value="female" {{ old('gender')==='female'?'selected':'' }}>Femenino</option>
                            <option value="male"   {{ old('gender')==='male'?'selected':'' }}>Masculino</option>
                            <option value="other"  {{ old('gender')==='other'?'selected':'' }}>Otro</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="weight_kg" value="Peso (kg)" />
                        <x-text-input id="weight_kg" name="weight_kg" type="number" class="mt-1 block w-full"
                                      :value="old('weight_kg')" min="0" step="0.1" placeholder="Ej: 65.0" />
                        <x-input-error :messages="$errors->get('weight_kg')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="height_cm" value="Altura (cm)" />
                        <x-text-input id="height_cm" name="height_cm" type="number" class="mt-1 block w-full"
                                      :value="old('height_cm')" min="0" step="0.1" placeholder="Ej: 162" />
                        <x-input-error :messages="$errors->get('height_cm')" class="mt-1" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="goal" value="Objetivo principal" />
                        <select id="goal" name="goal"
                                class="mt-1 block w-full border-gray-300 focus:border-rose-400 focus:ring-rose-400 rounded-md shadow-sm text-sm">
                            <option value="">— Seleccionar —</option>
                            <option value="weight_loss"     {{ old('goal')==='weight_loss'?'selected':'' }}>Pérdida de peso</option>
                            <option value="toning"          {{ old('goal')==='toning'?'selected':'' }}>Tonificación</option>
                            <option value="wellness"        {{ old('goal')==='wellness'?'selected':'' }}>Bienestar general</option>
                            <option value="rehabilitation"  {{ old('goal')==='rehabilitation'?'selected':'' }}>Rehabilitación</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="trains_at" value="¿Dónde entrenas?" />
                        <select id="trains_at" name="trains_at"
                                class="mt-1 block w-full border-gray-300 focus:border-rose-400 focus:ring-rose-400 rounded-md shadow-sm text-sm">
                            <option value="none" {{ old('trains_at','none')==='none'?'selected':'' }}>No entreno</option>
                            <option value="gym"  {{ old('trains_at')==='gym'?'selected':'' }}>Gimnasio</option>
                            <option value="home" {{ old('trains_at')==='home'?'selected':'' }}>Casa</option>
                            <option value="both" {{ old('trains_at')==='both'?'selected':'' }}>Ambos</option>
                        </select>
                    </div>
                </div>

                <div>
                    <x-input-label for="allergies" value="Alergias o restricciones" />
                    <x-text-input id="allergies" name="allergies" type="text" class="mt-1 block w-full"
                                  :value="old('allergies')" placeholder="Ej: gluten, lactosa, ninguna" />
                </div>
            </div>
        </div>

        @if($errors->hasAny(['birth_date','gender','weight_kg','height_cm','goal','trains_at','allergies']))
        <script>document.addEventListener('DOMContentLoaded', () => openProfile());</script>
        @endif

        <button type="submit"
                class="w-full gradient-rose text-white py-3 rounded-xl font-semibold hover:opacity-90 transition shadow-sm text-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Crear mi cuenta
        </button>

        <p class="text-center text-sm text-gray-400">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-rose-600 font-medium hover:underline">Inicia sesión</a>
        </p>
    </form>
</x-guest-layout>

<script>
function toggleProfile() {
    const section = document.getElementById('profile-section');
    const arrow   = document.getElementById('profile-arrow');
    section.classList.toggle('hidden');
    arrow.style.transform = section.classList.contains('hidden') ? '' : 'rotate(180deg)';
}
function openProfile() {
    const section = document.getElementById('profile-section');
    const arrow   = document.getElementById('profile-arrow');
    section.classList.remove('hidden');
    arrow.style.transform = 'rotate(180deg)';
}
</script>
