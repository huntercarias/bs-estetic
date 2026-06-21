<?php

namespace App\Services\Social;

use App\Models\SocialAccount;
use App\Models\SocialPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookService
{
    private const API = 'https://graph.facebook.com/v19.0';

    public function publish(SocialPost $post): array
    {
        $account = SocialAccount::forTenant($post->tenant_id, 'facebook');

        if (!$account) {
            return $this->err('Cuenta de Facebook no configurada o inactiva.');
        }

        try {
            if ($post->image_path && $post->imageUrl()) {
                return $this->publishWithPhoto($account, $post);
            }

            return $this->publishText($account, $post);
        } catch (\Exception $e) {
            Log::warning('Facebook publish error: ' . $e->getMessage());
            return $this->err($e->getMessage());
        }
    }

    private function publishText(SocialAccount $account, SocialPost $post): array
    {
        $response = Http::timeout(15)->post(self::API . "/{$account->page_id}/feed", [
            'message'      => $post->body,
            'access_token' => $account->access_token,
        ]);

        return $this->parseResponse($response);
    }

    private function publishWithPhoto(SocialAccount $account, SocialPost $post): array
    {
        $response = Http::timeout(30)->post(self::API . "/{$account->page_id}/photos", [
            'url'          => $post->imageUrl(),
            'caption'      => $post->body,
            'access_token' => $account->access_token,
        ]);

        return $this->parseResponse($response);
    }

    private function parseResponse($response): array
    {
        $data = $response->json();

        if ($response->successful() && isset($data['id'])) {
            $postId = $data['id'];
            return [
                'status'   => 'success',
                'message'  => 'Publicado correctamente en Facebook.',
                'post_id'  => $postId,
                'post_url' => "https://www.facebook.com/{$postId}",
            ];
        }

        $error = $data['error']['message'] ?? 'Error desconocido de la API de Facebook.';
        return $this->err($error);
    }

    private function err(string $msg): array
    {
        return ['status' => 'error', 'message' => $msg];
    }
}
