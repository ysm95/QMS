<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsSupplier extends Model
{
    protected $guarded = [];

    protected $casts = [
        'next_review_date' => 'date',
    ];
}
