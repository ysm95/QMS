<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsPermissionTemplate extends Model
{
    protected $guarded = [];

    protected $casts = [
        'permissions' => 'array',
        'default_scopes' => 'array',
    ];
}
