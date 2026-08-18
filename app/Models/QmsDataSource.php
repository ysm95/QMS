<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsDataSource extends Model
{
    protected $guarded = [];

    protected $casts = [
        'secondary_display_fields' => 'array',
        'search_fields' => 'array',
        'filters' => 'array',
    ];
}
