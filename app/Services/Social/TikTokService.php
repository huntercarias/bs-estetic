<?php

namespace App\Services\Social;

use App\Models\SocialPost;

class TikTokService
{
    /**
     * TikTok Content Posting API requiere video y proceso de OAuth complejo.
     * Devolvemos el contenido listo para copiar + link directo a TikTok.
     */
    public function publish(SocialPost $post): array
    {
        $text = ($post->title ? "{$post->title}\n\n" : '') . $post->body;

        return [
            'status'     => 'manual',
            'message'    => 'TikTok requiere publicación manual. El contenido está listo para copiar.',
            'copy_text'  => $text,
            'upload_url' => 'https://www.tiktok.com/upload',
            'note'       => 'Abre TikTok, sube tu video y pega el texto como descripción.',
        ];
    }
}
