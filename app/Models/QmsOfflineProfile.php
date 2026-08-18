<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsOfflineProfile extends Model
{
    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
        'allowed_operations' => 'array',
        'sync_rules' => 'array',
    ];
}
