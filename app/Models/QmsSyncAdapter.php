<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsSyncAdapter extends Model
{
    protected $guarded = [];

    protected $casts = [
        'field_mapping' => 'array',
        'sync_policy' => 'array',
        'last_success_at' => 'datetime',
    ];
}
