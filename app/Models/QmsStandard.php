<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsStandard extends Model
{
    protected $guarded = [];

    protected $casts = [
        'effective_date' => 'date',
        'transition_deadline' => 'date',
        'change_history' => 'array',
    ];
}
