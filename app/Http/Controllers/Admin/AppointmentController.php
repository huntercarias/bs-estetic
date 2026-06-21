<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentFieldValue;
use App\Models\PatientRecord;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'service', 'staff'])
            ->latest('scheduled_at');

        if ($request->filled('status')) {
            $query->where('appointment_status', $request->status);
        }
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }
        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $appointments = $query->paginate(20);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $services = Service::where('active', true)->get();
        $patients = User::role('patient')->get();
        $staff    = User::role(['admin', 'staff'])->get();

        return view('admin.appointments.create', compact('services', 'patients', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id'         => 'required|exists:services,id',
            'patient_id'         => 'required|exists:users,id',
            'staff_id'           => 'nullable|exists:users,id',
            'scheduled_at'       => 'required|date',
            'duration_minutes'   => 'nullable|integer|min:1',
            'appointment_status' => 'required|in:pending,confirmed,cancelled,completed',
            'payment_status'     => 'required|in:pending,paid_online,paid_in_clinic,waived',
            'total_price'        => 'nullable|numeric|min:0',
            'notes'              => 'nullable|string',
            'admin_notes'        => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);

        // Guardar valores de campos dinámicos
        foreach ($request->input('fields', []) as $fieldId => $value) {
            AppointmentFieldValue::create([
                'appointment_id'      => $appointment->id,
                'field_definition_id' => $fieldId,
                'value'               => $value,
            ]);
        }

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', 'Cita creada exitosamente.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.profile', 'service.customFields', 'staff', 'fieldValues.fieldDefinition', 'records.recordedBy']);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $services = Service::where('active', true)->get();
        $patients = User::role('patient')->get();
        $staff    = User::role(['admin', 'staff'])->get();
        $appointment->load(['fieldValues.fieldDefinition', 'service.customFields']);

        return view('admin.appointments.edit', compact('appointment', 'services', 'patients', 'staff'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'service_id'         => 'required|exists:services,id',
            'patient_id'         => 'required|exists:users,id',
            'staff_id'           => 'nullable|exists:users,id',
            'scheduled_at'       => 'required|date',
            'duration_minutes'   => 'nullable|integer|min:1',
            'appointment_status' => 'required|in:pending,confirmed,cancelled,completed',
            'payment_status'     => 'required|in:pending,paid_online,paid_in_clinic,waived',
            'total_price'        => 'nullable|numeric|min:0',
            'notes'              => 'nullable|string',
            'admin_notes'        => 'nullable|string',
        ]);

        $appointment->update($validated);

        // Actualizar campos dinámicos
        foreach ($request->input('fields', []) as $fieldId => $value) {
            AppointmentFieldValue::updateOrCreate(
                ['appointment_id' => $appointment->id, 'field_definition_id' => $fieldId],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.appointments.show', $appointment)
            ->with('success', 'Cita actualizada.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita eliminada.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'appointment_status' => 'nullable|in:pending,confirmed,cancelled,completed',
            'payment_status'     => 'nullable|in:pending,paid_online,paid_in_clinic,waived',
        ]);

        if ($request->filled('appointment_status')) {
            $appointment->update(['appointment_status' => $request->appointment_status]);
        }
        if ($request->filled('payment_status')) {
            $appointment->update(['payment_status' => $request->payment_status]);
        }

        return back()->with('success', 'Estado actualizado.');
    }

    public function addRecord(Request $request, Appointment $appointment)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'notes' => 'required|string',
        ]);

        PatientRecord::create([
            'tenant_id'      => auth()->user()->tenant_id,
            'patient_id'     => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'recorded_by'    => auth()->id(),
            'title'          => $request->title,
            'notes'          => $request->notes,
        ]);

        return back()->with('success', 'Nota clínica agregada.');
    }
}
