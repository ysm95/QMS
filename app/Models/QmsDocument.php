<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsDocument extends Model
{
    protected $guarded = [];

    protected $casts = [
        'review_date' => 'date',
    ];
}
