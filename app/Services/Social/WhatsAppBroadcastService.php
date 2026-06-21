<?php

namespace App\Services\Social;

use App\Models\Message;
use App\Models\SocialPost;
use App\Models\User;

class WhatsAppBroadcastService
{
    /**
     * Envía el post como mensaje a todos los pacientes del tenant
     * y devuelve un link wa.me para compartir manualmente.
     */
    public function publish(SocialPost $post): array
    {
        $admin = User::where('tenant_id', $post->tenant_id)
            ->whereHas('roles', fn($q) => $q->where('name', 'admin'))
            ->first();

        if (!$admin) {
            return ['status' => 'error', 'message' => 'No se encontró administrador del tenant.'];
        }

        $patients = User::where('tenant_id', $post->tenant_id)
            ->whereHas('roles', fn($q) => $q->where('name', 'patient'))
            ->get();

        $sent = 0;
        foreach ($patients as $patient) {
            Message::create([
                'tenant_id'   => $post->tenant_id,
                'sender_id'   => $admin->id,
                'receiver_id' => $patient->id,
                'body'        => ($post->title ? "📢 *{$post->title}*\n\n" : '') . $post->body,
            ]);
            $sent++;
        }

        $text = ($post->title ? "📢 {$post->title}\n\n" : '') . $post->body;
        $shareUrl = 'https://wa.me/?text=' . rawurlencode($text);

        return [
            'status'    => 'success',
            'message'   => $sent > 0
                ? "Enviado a {$sent} paciente(s) via mensajería interna."
                : 'No hay pacientes registrados. Usa el link para compartir manualmente.',
            'sent'      => $sent,
            'share_url' => $shareUrl,
        ];
    }
}
