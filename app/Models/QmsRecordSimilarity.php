<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsRecordSimilarity extends Model
{
    protected $guarded = [];

    protected $casts = [
        'matched_on' => 'array',
        'decided_at' => 'datetime',
    ];
}
