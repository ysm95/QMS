<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsWorkflowDefinition extends Model
{
    protected $guarded = [];

    protected $casts = [
        'stages' => 'array',
        'rules' => 'array',
    ];
}
