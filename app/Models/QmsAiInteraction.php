<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsAiInteraction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'controls_applied' => 'array',
    ];
}
