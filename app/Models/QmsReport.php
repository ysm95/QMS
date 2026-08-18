<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'anonymous' => 'boolean',
        'confidential' => 'boolean',
        'mandatory' => 'boolean',
        'payload' => 'array',
        'reported_at' => 'datetime',
        'submitted_at' => 'datetime',
        'screened_at' => 'datetime',
    ];

    public function incident()
    {
        return $this->hasOne(QmsIncident::class, 'source_report_id');
    }
}
