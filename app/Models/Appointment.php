<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'service_id', 'patient_id', 'staff_id',
        'scheduled_at', 'duration_minutes',
        'appointment_status', 'payment_status',
        'total_price', 'notes', 'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'total_price'  => 'decimal:2',
        ];
    }

    public function service()    { return $this->belongsTo(Service::class); }
    public function patient()    { return $this->belongsTo(User::class, 'patient_id'); }
    public function staff()      { return $this->belongsTo(User::class, 'staff_id'); }
    public function fieldValues(){ return $this->hasMany(AppointmentFieldValue::class); }
    public function records()    { return $this->hasMany(PatientRecord::class); }

    public function getAppointmentStatusLabelAttribute(): string
    {
        return match($this->appointment_status) {
            'pending'   => 'Pendiente',
            'confirmed' => 'Confirmada',
            'cancelled' => 'Cancelada',
            'completed' => 'Completada',
            default     => $this->appointment_status,
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'pending'         => 'Pendiente de pago',
            'paid_online'     => 'Pagado en línea',
            'paid_in_clinic'  => 'Pagado en clínica',
            'waived'          => 'Exonerado',
            default           => $this->payment_status,
        };
    }

    public function getAppointmentStatusColorAttribute(): string
    {
        return match($this->appointment_status) {
            'pending'   => 'yellow',
            'confirmed' => 'blue',
            'cancelled' => 'red',
            'completed' => 'green',
            default     => 'gray',
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match($this->payment_status) {
            'pending'        => 'yellow',
            'paid_online'    => 'green',
            'paid_in_clinic' => 'emerald',
            'waived'         => 'gray',
            default          => 'gray',
        };
    }
}
