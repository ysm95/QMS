<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsNotificationDesign extends Model
{
    protected $guarded = [];

    protected $casts = [
        'recipients' => 'array',
        'conditions' => 'array',
    ];
}
