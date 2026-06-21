<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $fillable = [
        'tenant_id', 'platform', 'account_name',
        'page_id', 'ig_user_id', 'access_token', 'active',
    ];

    protected function casts(): array
    {
        return [
            'active'       => 'boolean',
            'access_token' => 'encrypted',
        ];
    }

    public function tenant() { return $this->belongsTo(Tenant::class); }

    public function isConfigured(): bool
    {
        return match ($this->platform) {
            'facebook'  => !empty($this->page_id) && !empty($this->access_token),
            'instagram' => !empty($this->ig_user_id) && !empty($this->access_token),
            'whatsapp'  => true, // usa el sistema interno de mensajes
            'tiktok'    => true, // publicación manual
            default     => false,
        };
    }

    public static function forTenant(int $tenantId, string $platform): ?self
    {
        return static::where('tenant_id', $tenantId)
            ->where('platform', $platform)
            ->where('active', true)
            ->first();
    }
}
