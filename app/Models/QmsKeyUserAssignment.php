<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsKeyUserAssignment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'capabilities' => 'array',
        'effective_from' => 'date',
        'effective_until' => 'date',
    ];
}
