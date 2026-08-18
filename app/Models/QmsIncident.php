<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsIncident extends Model
{
    protected $guarded = [];

    protected $casts = [
        'investigation_required' => 'boolean',
        'closure_blocked' => 'boolean',
        'source_snapshot' => 'array',
        'accepted_at' => 'datetime',
    ];

    public function sourceReport()
    {
        return $this->belongsTo(QmsReport::class, 'source_report_id');
    }
}
