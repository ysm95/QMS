<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsPublicReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'anonymous' => 'boolean',
        'confidential' => 'boolean',
        'submitted_payload' => 'array',
        'client_context' => 'array',
        'reporter_visible_messages' => 'array',
        'reporter_response_at' => 'datetime',
    ];
}
