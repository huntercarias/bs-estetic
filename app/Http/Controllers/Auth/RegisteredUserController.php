<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PatientProfile;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'birth_date'  => ['nullable', 'date', 'before:today'],
            'gender'      => ['nullable', 'in:female,male,other'],
            'weight_kg'   => ['nullable', 'numeric', 'min:1', 'max:500'],
            'height_cm'   => ['nullable', 'numeric', 'min:50', 'max:300'],
            'goal'        => ['nullable', 'in:weight_loss,toning,wellness,rehabilitation'],
            'trains_at'   => ['nullable', 'in:none,gym,home,both'],
            'allergies'   => ['nullable', 'string', 'max:500'],
        ]);

        $tenant = Tenant::where('active', true)->first();

        $user = User::create([
            'tenant_id'         => $tenant?->id,
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('patient');

        $profileData = array_filter($request->only([
            'birth_date', 'gender', 'weight_kg', 'height_cm',
            'goal', 'trains_at', 'allergies',
        ]), fn($v) => $v !== null && $v !== '');

        PatientProfile::create(array_merge(
            ['tenant_id' => $tenant?->id, 'user_id' => $user->id],
            $profileData
        ));

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('patient.dashboard');
    }
}
