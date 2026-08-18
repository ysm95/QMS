<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsReportDesign extends Model
{
    protected $guarded = [];

    protected $casts = [
        'layout' => 'array',
        'data_sources' => 'array',
        'output_formats' => 'array',
    ];
}
