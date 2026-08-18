<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsNumberingRule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'reset_annually' => 'boolean',
    ];
}
