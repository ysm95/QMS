<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsRetentionRule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'legal_hold_allowed' => 'boolean',
    ];
}
