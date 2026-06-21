<?php

namespace App\Services\Social;

use App\Models\SocialAccount;
use App\Models\SocialPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramService
{
    private const API = 'https://graph.facebook.com/v19.0';

    public function publish(SocialPost $post): array
    {
        $account = SocialAccount::forTenant($post->tenant_id, 'instagram');

        if (!$account) {
            return $this->err('Cuenta de Instagram no configurada o inactiva.');
        }

        if (!$post->image_path) {
            return $this->err('Instagram requiere una imagen para publicar.');
        }

        // Instagram necesita una URL pública (no localhost)
        $appUrl = config('app.url');
        if (str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
            return [
                'status'  => 'warning',
                'message' => 'Instagram requiere que APP_URL sea una URL pública (no localhost). Configura tu dominio en .env para activar la publicación automática.',
            ];
        }

        try {
            // Paso 1: crear contenedor de media
            $container = Http::timeout(20)->post(self::API . "/{$account->ig_user_id}/media", [
                'image_url'    => $post->imageUrl(),
                'caption'      => $post->body,
                'access_token' => $account->access_token,
            ]);

            $containerData = $container->json();

            if (!$container->successful() || empty($containerData['id'])) {
                $error = $containerData['error']['message'] ?? 'Error al crear contenedor de media.';
                return $this->err($error);
            }

            // Paso 2: publicar el contenedor
            $publish = Http::timeout(20)->post(self::API . "/{$account->ig_user_id}/media_publish", [
                'creation_id'  => $containerData['id'],
                'access_token' => $account->access_token,
            ]);

            $publishData = $publish->json();

            if ($publish->successful() && isset($publishData['id'])) {
                return [
                    'status'   => 'success',
                    'message'  => 'Publicado correctamente en Instagram.',
                    'media_id' => $publishData['id'],
                ];
            }

            $error = $publishData['error']['message'] ?? 'Error al publicar en Instagram.';
            return $this->err($error);

        } catch (\Exception $e) {
            Log::warning('Instagram publish error: ' . $e->getMessage());
            return $this->err($e->getMessage());
        }
    }

    private function err(string $msg): array
    {
        return ['status' => 'error', 'message' => $msg];
    }
}
