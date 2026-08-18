<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsNotificationDelivery extends Model
{
    protected $guarded = [];

    protected $casts = [
        'queued_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];
}
