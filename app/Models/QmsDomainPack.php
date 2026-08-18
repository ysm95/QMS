<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsDomainPack extends Model
{
    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
        'capabilities' => 'array',
        'shared_engines' => 'array',
    ];
}
