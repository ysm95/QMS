<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsPublicReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'anonymous' => 'boolean',
        'confidential' => 'boolean',
    ];
}
