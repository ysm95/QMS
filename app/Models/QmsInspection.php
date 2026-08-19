<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsInspection extends Model
{
    protected $guarded = [];

    protected $casts = [
        'checklist_snapshot' => 'array',
        'evidence_summary' => 'array',
        'scheduled_date' => 'date',
        'completed_at' => 'datetime',
    ];
}
