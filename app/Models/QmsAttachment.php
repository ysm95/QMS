<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsAttachment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'quarantined' => 'boolean',
        'metadata' => 'array',
    ];
}
