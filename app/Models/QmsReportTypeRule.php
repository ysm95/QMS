<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsReportTypeRule extends Model
{
    protected $guarded = [];

    protected $casts = [
        'published' => 'boolean',
        'requires_auth' => 'boolean',
        'supports_anonymous' => 'boolean',
        'allowed_roles' => 'array',
        'allowed_departments' => 'array',
        'effective_from' => 'date',
        'effective_until' => 'date',
    ];
}
