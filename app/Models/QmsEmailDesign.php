<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsEmailDesign extends Model
{
    protected $guarded = [];

    protected $casts = [
        'builder_schema' => 'array',
        'variables' => 'array',
    ];
}
