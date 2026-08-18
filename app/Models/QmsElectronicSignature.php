<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsElectronicSignature extends Model
{
    protected $guarded = [];

    protected $casts = [
        'auth_context' => 'array',
        'signed_at' => 'datetime',
    ];
}
