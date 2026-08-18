<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsSystemMonitor extends Model
{
    protected $guarded = [];

    protected $casts = [
        'checks' => 'array',
        'checked_at' => 'datetime',
    ];
}
