<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsNotificationTemplate extends Model
{
    protected $guarded = [];

    protected $casts = [
        'allowed_variables' => 'array',
    ];
}
