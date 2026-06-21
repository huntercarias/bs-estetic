<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('patient')
            ->with('profile')
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $patients = $query->paginate(20);

        return view('admin.patients.index', compact('patients'));
    }

    public function show(User $patient)
    {
        $patient->load(['profile', 'appointments.service', 'records.recordedBy', 'wellnessPlans']);

        return view('admin.patients.show', compact('patient'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'tenant_id'         => auth()->user()->tenant_id,
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('patient');

        // Crear perfil vacío
        PatientProfile::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id'   => $user->id,
        ]);

        // Guardar perfil si se envió
        $this->saveProfile($request, $user);

        return redirect()->route('admin.patients.show', $user)
            ->with('success', 'Paciente creado exitosamente.');
    }

    public function edit(User $patient)
    {
        $patient->load('profile');

        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, User $patient)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->id,
        ]);

        $patient->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $patient->update(['password' => Hash::make($request->password)]);
        }

        $this->saveProfile($request, $patient);

        return redirect()->route('admin.patients.show', $patient)
            ->with('success', 'Paciente actualizado.');
    }

    private function saveProfile(Request $request, User $user): void
    {
        $profileData = $request->only([
            'birth_date', 'gender', 'weight_kg', 'height_cm',
            'trains_at', 'goal', 'medical_notes', 'allergies',
        ]);

        $profileData = array_filter($profileData, fn($v) => $v !== null && $v !== '');

        if (!empty($profileData)) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                array_merge($profileData, ['tenant_id' => $user->tenant_id ?? auth()->user()->tenant_id])
            );
        }
    }
}
