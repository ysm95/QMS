<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsNonconformance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'root_cause_required' => 'boolean',
        'corrective_action_required' => 'boolean',
        'effectiveness_required' => 'boolean',
    ];
}
