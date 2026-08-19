<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsCapaCase extends Model
{
    protected $guarded = [];

    protected $casts = [
        'root_cause_tools' => 'array',
        'due_date' => 'date',
        'effectiveness_due_date' => 'date',
    ];
}
