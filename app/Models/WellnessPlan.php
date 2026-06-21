<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class WellnessPlan extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'patient_id', 'created_by',
        'type', 'ai_prompt', 'content', 'status',
        'valid_from', 'valid_until',
    ];

    protected function casts(): array
    {
        return [
            'content'    => 'array',
            'valid_from' => 'date',
            'valid_until'=> 'date',
        ];
    }

    public function patient()   { return $this->belongsTo(User::class, 'patient_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'exercise'  => 'Rutina de Ejercicio',
            'nutrition' => 'Plan de Alimentación',
            default     => 'Ejercicio + Alimentación',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'    => 'Borrador',
            'active'   => 'Activo',
            'inactive' => 'Inactivo',
            default    => $this->status,
        };
    }
}
