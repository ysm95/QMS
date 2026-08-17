<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsAction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
    ];
}
