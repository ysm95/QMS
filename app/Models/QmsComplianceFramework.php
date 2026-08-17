<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsComplianceFramework extends Model
{
    protected $guarded = [];

    protected $casts = [
        'requirements' => 'array',
    ];
}
