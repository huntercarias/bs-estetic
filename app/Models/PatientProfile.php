<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    protected $fillable = [
        'tenant_id', 'user_id', 'birth_date', 'gender',
        'weight_kg', 'height_cm', 'trains_at', 'goal',
        'medical_notes', 'allergies',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    public function getTrainsAtLabelAttribute(): string
    {
        return match($this->trains_at) {
            'gym'  => 'Gimnasio',
            'home' => 'Casa',
            'both' => 'Gimnasio y Casa',
            default => 'No entrena',
        };
    }

    public function getGoalLabelAttribute(): string
    {
        return match($this->goal) {
            'weight_loss'    => 'Pérdida de peso',
            'toning'         => 'Tonificación',
            'wellness'       => 'Bienestar general',
            'rehabilitation' => 'Rehabilitación',
            default          => 'Otro',
        };
    }
}
