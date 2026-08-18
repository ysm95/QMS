<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsModuleLicense extends Model
{
    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
        'expires_on' => 'date',
        'limits' => 'array',
    ];
}
