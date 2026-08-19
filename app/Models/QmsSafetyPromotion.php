<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsSafetyPromotion extends Model
{
    protected $guarded = [];

    protected $casts = [
        'audience' => 'array',
        'published_at' => 'datetime',
    ];
}
