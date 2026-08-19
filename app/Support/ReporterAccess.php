<?php

namespace App\Support;

use App\Models\QmsReportTypeRule;
use App\Models\User;
use Illuminate\Support\Collection;

class ReporterAccess
{
    public function allowedReportTypes(?User $user): Collection
    {
        $role = $user?->qms_role ?: 'Public';
        $department = $user?->department_id ? (string) $user->department_id : null;

        return QmsReportTypeRule::query()
            ->where('published', true)
            ->where('status', 'Active')
            ->where(function ($query) {
                $query->whereNull('effective_from')->orWhereDate('effective_from', '<=', now()->toDateString());
            })
            ->where(function ($query) {
                $query->whereNull('effective_until')->orWhereDate('effective_until', '>=', now()->toDateString());
            })
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get()
            ->filter(fn (QmsReportTypeRule $rule) => $this->canSubmit($rule, $role, $department, $user !== null))
            ->values();
    }

    public function findAllowed(string $reportTypeKey, ?User $user): ?QmsReportTypeRule
    {
        return $this->allowedReportTypes($user)
            ->firstWhere('report_type_key', $reportTypeKey);
    }

    public function apiPayload(QmsReportTypeRule $rule): array
    {
        return [
            'key' => $rule->report_type_key,
            'title' => $rule->title,
            'type' => $rule->type,
            'module' => $rule->module,
            'priority' => $rule->priority,
            'description' => $rule->description,
            'form_version' => $rule->form_version,
            'supports_anonymous' => $rule->supports_anonymous,
        ];
    }

    private function canSubmit(QmsReportTypeRule $rule, string $role, ?string $department, bool $authenticated): bool
    {
        if ($rule->requires_auth && ! $authenticated) {
            return false;
        }

        $roles = collect($rule->allowed_roles ?: ['Public']);
        $departments = collect($rule->allowed_departments ?: []);

        $roleAllowed = $roles->contains('All')
            || $roles->contains($role)
            || (! $authenticated && $roles->contains('Public'));

        $departmentAllowed = $departments->isEmpty()
            || ($department !== null && $departments->contains($department));

        return $roleAllowed && $departmentAllowed;
    }
}
