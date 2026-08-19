<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsComplianceChange extends Model
{
    protected $guarded = [];

    protected $casts = [
        'impacted_areas' => 'array',
        'actions_required' => 'array',
        'due_date' => 'date',
    ];
}
