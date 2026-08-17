<?php

namespace App\Support;

use App\Models\QmsNotification;
use App\Models\User;

class QmsNotify
{
    public static function everyone(string $title, string $body, ?string $sourceReference = null): void
    {
        User::where('is_active', true)->get()->each(function (User $user) use ($title, $body, $sourceReference) {
            QmsNotification::create([
                'user_id' => $user->id,
                'title' => $title,
                'body' => $body,
                'source_reference' => $sourceReference,
            ]);
        });
    }
}
