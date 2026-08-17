<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsAudit extends Model
{
    protected $guarded = [];

    protected $casts = [
        'scheduled_date' => 'date',
    ];
}
