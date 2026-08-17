<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsAiProvider extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_enabled' => 'boolean',
    ];
}
