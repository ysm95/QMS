<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsManagementReview extends Model
{
    protected $guarded = [];

    protected $casts = [
        'meeting_date' => 'date',
        'inputs' => 'array',
    ];
}
