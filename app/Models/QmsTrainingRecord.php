<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsTrainingRecord extends Model
{
    protected $guarded = [];

    protected $casts = [
        'completed_on' => 'date',
        'expires_on' => 'date',
    ];
}
