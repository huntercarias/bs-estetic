<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialPost extends Model
{
    protected $fillable = [
        'tenant_id', 'user_id', 'title', 'body',
        'image_path', 'platforms', 'status', 'results', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'platforms'    => 'array',
            'results'      => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function author() { return $this->belongsTo(User::class, 'user_id'); }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'published'  => 'Publicado',
            'partial'    => 'Parcial',
            'failed'     => 'Fallido',
            'publishing' => 'Publicando...',
            default      => 'Borrador',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'published'  => 'green',
            'partial'    => 'amber',
            'failed'     => 'red',
            'publishing' => 'blue',
            default      => 'gray',
        };
    }

    public function platformResult(string $platform): ?array
    {
        return $this->results[$platform] ?? null;
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
