<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Envía un mensaje de WhatsApp usando la API gratuita de CallMeBot.
     *
     * Para activarla: enviar "I allow callmebot to send me messages" al
     * número +34 644 63 55 78 en WhatsApp. Recibirás tu apikey.
     *
     * Variables .env necesarias:
     *   CALLMEBOT_API_KEY=tu_apikey
     *   ADMIN_WHATSAPP_PHONE=50312345678  (con código de país, sin +)
     */
    public function sendToAdmin(string $message): bool
    {
        $apiKey = config('services.callmebot.key');
        $phone  = config('services.callmebot.phone');

        if (!$apiKey || !$phone) {
            return false;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        try {
            $response = Http::timeout(10)->get('https://api.callmebot.com/whatsapp.php', [
                'phone'  => $phone,
                'text'   => $message,
                'apikey' => $apiKey,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
