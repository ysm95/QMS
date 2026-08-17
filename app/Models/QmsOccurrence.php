<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsOccurrence extends Model
{
    protected $guarded = [];

    protected $casts = [
        'confidential' => 'boolean',
        'reported_at' => 'datetime',
    ];
}
