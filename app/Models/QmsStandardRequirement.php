<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsStandardRequirement extends Model
{
    protected $guarded = [];

    protected $casts = [
        'controls' => 'array',
        'evidence' => 'array',
        'mapped_documents' => 'array',
        'mapped_forms' => 'array',
        'mapped_risks' => 'array',
        'mapped_audits' => 'array',
        'mapped_actions' => 'array',
    ];
}
