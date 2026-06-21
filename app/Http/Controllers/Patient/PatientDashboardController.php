<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\PatientProfile;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\WellnessPlan;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $upcomingAppointments = Appointment::withoutGlobalScope('tenant')
            ->where('patient_id', $user->id)
            ->where('scheduled_at', '>=', now())
            ->where('appointment_status', '!=', 'cancelled')
            ->with('service')
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $activePlan = WellnessPlan::withoutGlobalScope('tenant')
            ->where('patient_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        $profile = $user->profile;
        $profileComplete = $profile &&
            $profile->birth_date &&
            $profile->gender &&
            $profile->weight_kg &&
            $profile->height_cm &&
            $profile->goal;

        return view('patient.dashboard', compact('upcomingAppointments', 'activePlan', 'profileComplete'));
    }

    public function appointments()
    {
        $appointments = Appointment::withoutGlobalScope('tenant')
            ->where('patient_id', auth()->id())
            ->with('service')
            ->latest('scheduled_at')
            ->paginate(15);

        return view('patient.appointments', compact('appointments'));
    }

    public function book()
    {
        $tenant = Tenant::where('active', true)->first();
        $services = Service::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenant?->id)
            ->where('active', true)
            ->with('customFields')
            ->get();

        return view('patient.book', compact('services', 'tenant'));
    }

    public function storeAppointment(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'service_id'   => 'required|exists:services,id',
            'scheduled_at' => 'required|date|after:now',
            'notes'        => 'nullable|string',
        ]);

        $service = Service::withoutGlobalScope('tenant')->findOrFail($request->service_id);

        $appointment = Appointment::withoutGlobalScope('tenant')->create([
            'tenant_id'          => $service->tenant_id,
            'service_id'         => $request->service_id,
            'patient_id'         => auth()->id(),
            'scheduled_at'       => $request->scheduled_at,
            'duration_minutes'   => $service->duration_minutes,
            'appointment_status' => 'pending',
            'payment_status'     => 'pending',
            'total_price'        => $service->price,
            'notes'              => $request->notes,
        ]);

        // Campos dinámicos
        foreach ($request->input('fields', []) as $fieldId => $value) {
            \App\Models\AppointmentFieldValue::create([
                'appointment_id'      => $appointment->id,
                'field_definition_id' => $fieldId,
                'value'               => $value,
            ]);
        }

        return redirect()->route('patient.appointments')
            ->with('success', 'Cita reservada exitosamente. Te contactaremos para confirmarla.');
    }

    public function wellness()
    {
        $plans = WellnessPlan::withoutGlobalScope('tenant')
            ->where('patient_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('patient.wellness', compact('plans'));
    }

    public function wellnessShow(WellnessPlan $plan)
    {
        abort_unless($plan->patient_id === auth()->id() && $plan->status === 'active', 403);

        return view('patient.wellness-show', compact('plan'));
    }

    public function profile()
    {
        $user    = auth()->user();
        $profile = $user->profile;

        return view('patient.profile', compact('profile'));
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender'     => ['nullable', 'in:female,male,other'],
            'weight_kg'  => ['nullable', 'numeric', 'min:1', 'max:500'],
            'height_cm'  => ['nullable', 'numeric', 'min:50', 'max:300'],
            'goal'       => ['nullable', 'in:weight_loss,toning,wellness,rehabilitation'],
            'trains_at'  => ['nullable', 'in:none,gym,home,both'],
            'allergies'  => ['nullable', 'string', 'max:500'],
            'medical_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user   = auth()->user();
        $tenant = Tenant::where('active', true)->first();

        $data = array_filter($request->only([
            'birth_date', 'gender', 'weight_kg', 'height_cm',
            'goal', 'trains_at', 'allergies', 'medical_notes',
        ]), fn($v) => $v !== null && $v !== '');

        PatientProfile::updateOrCreate(
            ['user_id' => $user->id],
            array_merge(['tenant_id' => $user->tenant_id ?? $tenant?->id], $data)
        );

        return redirect()->route('patient.profile')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
