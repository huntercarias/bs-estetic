<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'tenant_id', 'name', 'email', 'password', 'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function tenant()           { return $this->belongsTo(Tenant::class); }
    public function profile()          { return $this->hasOne(PatientProfile::class); }
    public function appointments()     { return $this->hasMany(Appointment::class, 'patient_id'); }
    public function wellnessPlans()    { return $this->hasMany(WellnessPlan::class, 'patient_id'); }
    public function records()          { return $this->hasMany(PatientRecord::class, 'patient_id'); }
    public function sentMessages()     { return $this->hasMany(Message::class, 'sender_id'); }
    public function receivedMessages() { return $this->hasMany(Message::class, 'receiver_id'); }

    public function isAdmin(): bool   { return $this->hasRole('admin'); }
    public function isPatient(): bool { return $this->hasRole('patient'); }
    public function isStaff(): bool   { return $this->hasRole('staff'); }
}
