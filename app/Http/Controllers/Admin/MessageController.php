<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Lista de pacientes que tienen conversación con el admin
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $adminId  = auth()->id();

        // Pacientes que han enviado o recibido mensajes en este tenant
        $patientIds = Message::where('tenant_id', $tenantId)
            ->where(fn($q) => $q->where('sender_id', '!=', $adminId)
                                ->orWhere('receiver_id', '!=', $adminId))
            ->selectRaw('CASE WHEN sender_id != ? THEN sender_id ELSE receiver_id END as patient_id', [$adminId])
            ->distinct()
            ->pluck('patient_id');

        $patients = User::whereIn('id', $patientIds)
            ->where('tenant_id', $tenantId)
            ->get()
            ->map(function (User $patient) use ($tenantId, $adminId) {
                $patient->unread_count = Message::where('tenant_id', $tenantId)
                    ->where('sender_id', $patient->id)
                    ->where('receiver_id', $adminId)
                    ->whereNull('read_at')
                    ->count();
                $patient->last_message = Message::where('tenant_id', $tenantId)
                    ->where(fn($q) => $q->where(fn($q2) =>
                        $q2->where('sender_id', $patient->id)->where('receiver_id', $adminId))
                        ->orWhere(fn($q2) =>
                        $q2->where('sender_id', $adminId)->where('receiver_id', $patient->id)))
                    ->latest()
                    ->first();
                return $patient;
            })
            ->sortByDesc(fn($p) => optional($p->last_message)->created_at);

        $totalUnread = $patients->sum('unread_count');

        return view('admin.messages.index', compact('patients', 'totalUnread'));
    }

    // Conversación con un paciente específico
    public function show(User $patient)
    {
        $tenantId = auth()->user()->tenant_id;
        $adminId  = auth()->id();

        abort_if($patient->tenant_id !== $tenantId, 403);

        $messages = Message::where('tenant_id', $tenantId)
            ->where(fn($q) => $q
                ->where(fn($q2) => $q2->where('sender_id', $patient->id)->where('receiver_id', $adminId))
                ->orWhere(fn($q2) => $q2->where('sender_id', $adminId)->where('receiver_id', $patient->id))
            )
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        // Marcar como leídos los mensajes del paciente al admin
        Message::where('tenant_id', $tenantId)
            ->where('sender_id', $patient->id)
            ->where('receiver_id', $adminId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.messages.show', compact('patient', 'messages'));
    }

    // Enviar mensaje al paciente
    public function store(Request $request, User $patient)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $tenantId = auth()->user()->tenant_id;

        abort_if($patient->tenant_id !== $tenantId, 403);

        Message::create([
            'tenant_id'   => $tenantId,
            'sender_id'   => auth()->id(),
            'receiver_id' => $patient->id,
            'body'        => $request->body,
        ]);

        return redirect()->route('admin.messages.show', $patient)
            ->with('success', 'Mensaje enviado.');
    }
}
