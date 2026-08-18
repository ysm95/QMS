<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsAction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'evidence_required' => 'boolean',
        'assigned_at' => 'datetime',
        'notified_at' => 'datetime',
        'viewed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
        'verified_at' => 'datetime',
        'closed_at' => 'datetime',
    ];
}
