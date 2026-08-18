<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsNotificationRule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'conditions' => 'array',
        'recipients' => 'array',
        'channels' => 'array',
        'timing' => 'array',
    ];
}
