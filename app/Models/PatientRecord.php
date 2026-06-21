<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'patient_id', 'appointment_id',
        'recorded_by', 'title', 'notes',
    ];

    public function patient()     { return $this->belongsTo(User::class, 'patient_id'); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function recordedBy()  { return $this->belongsTo(User::class, 'recorded_by'); }
}
