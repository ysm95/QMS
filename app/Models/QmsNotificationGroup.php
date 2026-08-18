<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class QmsNotificationGroup extends Model
{
    protected $guarded = [];

    public function members(): HasMany
    {
        return $this->hasMany(QmsNotificationGroupMember::class, 'notification_group_id');
    }
}
