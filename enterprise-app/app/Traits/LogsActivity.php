<?php

namespace App\Traits;

use App\Models\AuditLog;

trait LogsActivity
{
    protected static function getAuditModule(): string
    {
        return property_exists(static::class, 'auditModule') ? static::$auditModule : 'Core';
    }

    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            static::writeAuditLog('create', $model, [], $model->toArray());
        });

        static::updated(function ($model) {
            static::writeAuditLog('update', $model, $model->getOriginal(), $model->getDirty());
        });

        static::deleted(function ($model) {
            static::writeAuditLog('delete', $model, $model->toArray(), []);
        });
    }

    private static function writeAuditLog(string $action, $model, array $old, array $new): void
    {
        try {
            AuditLog::create([
                'user_id'    => auth()->id(),
                'action'     => $action,
                'module'     => static::getAuditModule(),
                'model_type' => get_class($model),
                'model_id'   => (string) $model->getKey(),
                'old_values' => $old ?: null,
                'new_values' => $new ?: null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable) {
            // Never let audit logging crash the main operation
        }
    }
}
