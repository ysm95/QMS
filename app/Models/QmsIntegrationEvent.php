<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsIntegrationEvent extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'available_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
