<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsSavedView extends Model
{
    protected $guarded = [];

    protected $casts = [
        'filters' => 'array',
        'shared' => 'boolean',
    ];
}
