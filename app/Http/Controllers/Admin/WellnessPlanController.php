<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WellnessPlan;
use App\Services\GroqAiService;
use Illuminate\Http\Request;

class WellnessPlanController extends Controller
{
    public function __construct(private GroqAiService $ai) {}

    public function index()
    {
        $plans = WellnessPlan::with(['patient', 'createdBy'])->latest()->paginate(20);

        return view('admin.wellness.index', compact('plans'));
    }

    public function create(Request $request)
    {
        $patients = User::role('patient')->with('profile')->get();
        $selected = $request->filled('patient_id')
            ? User::with('profile')->find($request->patient_id)
            : null;

        return view('admin.wellness.create', compact('patients', 'selected'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'type'       => 'required|in:exercise,nutrition,both',
        ]);

        $patient = User::with('profile')->findOrFail($request->patient_id);
        $profile = $patient->profile;

        $patientData = [
            'name'         => $patient->name,
            'age'          => $profile?->age ?? 'desconocida',
            'gender'       => match($profile?->gender) { 'male' => 'masculino', 'female' => 'femenino', default => 'no especificado' },
            'weight'       => $profile?->weight_kg ?? 'no especificado',
            'height'       => $profile?->height_cm ?? 'no especificado',
            'trains_at'    => $profile?->trains_at ?? 'none',
            'goal'         => $profile?->goal ?? 'wellness',
            'allergies'    => $profile?->allergies ?? 'ninguna',
            'medical_notes'=> $profile?->medical_notes ?? 'ninguna',
        ];

        $content = $this->ai->generateWellnessPlan($patientData, $request->type);

        return response()->json([
            'success' => !isset($content['error']),
            'content' => $content,
            'prompt'  => "Paciente: {$patient->name}, Tipo: {$request->type}",
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'  => 'required|exists:users,id',
            'type'        => 'required|in:exercise,nutrition,both',
            'content'     => 'required|string',
            'ai_prompt'   => 'nullable|string',
            'status'      => 'required|in:draft,active,inactive',
            'valid_from'  => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $content = json_decode($validated['content'], true);
        if (!$content) {
            return back()->with('error', 'El contenido del plan no es válido.');
        }

        WellnessPlan::create([
            'tenant_id'   => auth()->user()->tenant_id,
            'patient_id'  => $validated['patient_id'],
            'created_by'  => auth()->id(),
            'type'        => $validated['type'],
            'ai_prompt'   => $validated['ai_prompt'],
            'content'     => $content,
            'status'      => $validated['status'],
            'valid_from'  => $validated['valid_from'] ?? null,
            'valid_until' => $validated['valid_until'] ?? null,
        ]);

        return redirect()->route('admin.wellness.index')
            ->with('success', 'Plan de bienestar guardado exitosamente.');
    }

    public function show(WellnessPlan $wellness)
    {
        $wellness->load(['patient.profile', 'createdBy']);

        return view('admin.wellness.show', compact('wellness'));
    }

    public function updateStatus(Request $request, WellnessPlan $wellness)
    {
        $request->validate(['status' => 'required|in:draft,active,inactive']);
        $wellness->update(['status' => $request->status]);

        return back()->with('success', 'Estado del plan actualizado.');
    }

    public function destroy(WellnessPlan $wellness)
    {
        $wellness->delete();

        return redirect()->route('admin.wellness.index')
            ->with('success', 'Plan eliminado.');
    }
}
