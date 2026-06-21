<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class CustomFieldDefinition extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'service_id', 'name', 'label',
        'type', 'options', 'required', 'order',
    ];

    protected function casts(): array
    {
        return [
            'options'  => 'array',
            'required' => 'boolean',
        ];
    }

    public function service()      { return $this->belongsTo(Service::class); }
    public function fieldValues()  { return $this->hasMany(AppointmentFieldValue::class, 'field_definition_id'); }
}
