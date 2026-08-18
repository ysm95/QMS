<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsSystemSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'value' => 'array',
        'is_sensitive' => 'boolean',
    ];
}
