<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsFeedbackItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];
}
