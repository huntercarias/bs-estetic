<?php

namespace App\Services\Social;

use App\Models\SocialPost;

class SocialPublisherService
{
    public function __construct(
        private FacebookService         $facebook,
        private InstagramService        $instagram,
        private WhatsAppBroadcastService $whatsapp,
        private TikTokService           $tiktok,
    ) {}

    public function publish(SocialPost $post): void
    {
        $post->update(['status' => 'publishing']);

        $results = [];

        foreach ($post->platforms as $platform) {
            $results[$platform] = match ($platform) {
                'facebook'  => $this->facebook->publish($post),
                'instagram' => $this->instagram->publish($post),
                'whatsapp'  => $this->whatsapp->publish($post),
                'tiktok'    => $this->tiktok->publish($post),
                default     => ['status' => 'error', 'message' => 'Plataforma no soportada.'],
            };
        }

        $statuses = collect($results)->pluck('status');

        $finalStatus = match (true) {
            $statuses->every(fn($s) => in_array($s, ['success', 'manual'])) => 'published',
            $statuses->some(fn($s)  => in_array($s, ['success', 'manual'])) => 'partial',
            default                                                           => 'failed',
        };

        $post->update([
            'status'       => $finalStatus,
            'results'      => $results,
            'published_at' => in_array($finalStatus, ['published', 'partial']) ? now() : null,
        ]);
    }
}
