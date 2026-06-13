<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Expense;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->record($model, 'created', null, $this->cleanValues($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $changes = $model->getChanges();
        unset($changes['updated_at']);

        if ($changes === []) {
            return;
        }

        $oldValues = [];

        foreach (array_keys($changes) as $key) {
            $oldValues[$key] = $model->getOriginal($key);
        }

        $this->record($model, 'updated', $this->cleanValues($oldValues), $this->cleanValues($changes));
    }

    public function deleted(Model $model): void
    {
        $this->record($model, 'deleted', $this->cleanValues($model->getOriginal()), null);
    }

    private function record(Model $model, string $action, ?array $oldValues, ?array $newValues): void
    {
        try {
            if (! Schema::hasTable('audit_logs')) {
                return;
            }

            $user = Auth::user();

            AuditLog::query()->create([
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'user_email' => $user?->email,
                'module' => $this->moduleName($model),
                'action' => $action,
                'auditable_type' => $model::class,
                'auditable_id' => $model->getKey(),
                'description' => $this->description($model, $action),
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } catch (Throwable) {
            // Audit logging should never block the main user action.
        }
    }

    private function moduleName(Model $model): string
    {
        return match (true) {
            $model instanceof Expense => 'Expense',
            $model instanceof SiteSetting => 'Website Settings',
            $model instanceof User => 'Staff/User',
            default => class_basename($model),
        };
    }

    private function description(Model $model, string $action): string
    {
        $name = match (true) {
            $model instanceof Expense => $model->sector ?: 'Expense',
            $model instanceof SiteSetting => $model->company_name ?: 'Website Settings',
            $model instanceof User => $model->name ?: $model->email,
            default => class_basename($model),
        };

        return ucfirst($action).' '.$this->moduleName($model).': '.$name;
    }

    private function cleanValues(array $values): array
    {
        unset($values['password'], $values['remember_token']);

        return $values;
    }
}
