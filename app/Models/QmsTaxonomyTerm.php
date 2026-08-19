<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsTaxonomyTerm extends Model
{
    protected $guarded = [];

    protected $casts = [
        'mapping' => 'array',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'active' => 'boolean',
    ];
}
