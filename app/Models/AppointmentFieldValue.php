<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentFieldValue extends Model
{
    protected $fillable = ['appointment_id', 'field_definition_id', 'value'];

    public function appointment()     { return $this->belongsTo(Appointment::class); }
    public function fieldDefinition() { return $this->belongsTo(CustomFieldDefinition::class, 'field_definition_id'); }
}
