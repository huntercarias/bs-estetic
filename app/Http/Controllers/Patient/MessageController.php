<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $patientId = auth()->id();

        // Encontrar el admin del tenant
        $admin = User::where('tenant_id', $tenantId)
            ->whereHas('roles', fn($q) => $q->where('name', 'admin'))
            ->first();

        $messages = collect();

        if ($admin) {
            $messages = Message::where('tenant_id', $tenantId)
                ->where(fn($q) => $q
                    ->where(fn($q2) => $q2->where('sender_id', $patientId)->where('receiver_id', $admin->id))
                    ->orWhere(fn($q2) => $q2->where('sender_id', $admin->id)->where('receiver_id', $patientId))
                )
                ->with('sender')
                ->orderBy('created_at')
                ->get();

            // Marcar como leídos los mensajes del admin al paciente
            Message::where('tenant_id', $tenantId)
                ->where('sender_id', $admin->id)
                ->where('receiver_id', $patientId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return view('patient.messages.index', compact('messages', 'admin'));
    }

    public function store(Request $request, WhatsAppService $whatsapp)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        $tenantId  = auth()->user()->tenant_id;
        $patientId = auth()->id();

        $admin = User::where('tenant_id', $tenantId)
            ->whereHas('roles', fn($q) => $q->where('name', 'admin'))
            ->first();

        abort_unless($admin, 404, 'No hay administrador disponible.');

        Message::create([
            'tenant_id'   => $tenantId,
            'sender_id'   => $patientId,
            'receiver_id' => $admin->id,
            'body'        => $request->body,
        ]);

        // Notificación WhatsApp al administrador
        $patient = auth()->user();
        $excerpt = mb_strimwidth($request->body, 0, 80, '...');
        $whatsapp->sendToAdmin(
            "💬 Nuevo mensaje de {$patient->name}:\n\"{$excerpt}\"\n\nResponde en el panel: " . url('/admin/mensajes')
        );

        return redirect()->route('patient.messages')->with('success', 'Mensaje enviado.');
    }
}
