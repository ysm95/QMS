<?php

namespace App\Support;

use App\Models\QmsAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class QmsAuditTrail
{
    public static function record(Request $request, Model $record, string $action, array $oldValues = [], array $newValues = [], ?string $note = null): void
    {
        QmsAuditLog::create([
            'user_id' => $request->user()?->id,
            'actor' => $request->user()?->name,
            'auditable_type' => $record::class,
            'auditable_id' => $record->getKey(),
            'reference' => $record->reference ?? null,
            'action' => $action,
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'note' => $note,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);
    }
}
