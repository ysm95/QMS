<?php

namespace App\Support;

use App\Models\QmsNumberingRule;

class QmsNumbering
{
    public static function next(string $code, string $module, string $prefix): string
    {
        $rule = QmsNumberingRule::query()
            ->where('code', $code)
            ->lockForUpdate()
            ->first();

        if (! $rule) {
            $rule = QmsNumberingRule::create([
                'code' => $code,
                'module' => $module,
                'prefix' => $prefix,
                'pattern' => '{PREFIX}-{YYYY}-{SEQ:6}',
                'next_sequence' => 1,
                'reset_annually' => true,
                'status' => 'Active',
            ]);
        }

        $sequence = $rule->next_sequence;
        $rule->update(['next_sequence' => $sequence + 1]);

        return str_replace(
            ['{PREFIX}', '{YYYY}', '{SEQ:6}', '{SEQ:5}'],
            [$rule->prefix, now()->format('Y'), str_pad((string) $sequence, 6, '0', STR_PAD_LEFT), str_pad((string) $sequence, 5, '0', STR_PAD_LEFT)],
            $rule->pattern
        );
    }
}
