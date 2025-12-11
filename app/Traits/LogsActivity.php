<?php

namespace App\Traits;

use App\Services\AuditLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            $model->writeAuditLog('created', null, $model->getAttributes());
        });

        static::updated(function (Model $model) {
            // On ne logue que les champs modifiés
            $changes = $model->getChanges();
            $original = $model->getOriginal();

            $old = [];
            $new = [];

            foreach ($changes as $key => $value) {
                // On ignore timestamps et champs sensibles
                if (in_array($key, ['updated_at', 'created_at', 'deleted_at', 'password', 'remember_token'])) {
                    continue;
                }
                $old[$key] = $original[$key] ?? null;
                $new[$key] = $value;
            }

            if (! empty($new)) {
                $model->writeAuditLog('updated', $old, $new);
            }
        });

        static::deleted(function (Model $model) {
            $model->writeAuditLog('deleted', $model->getOriginal(), null);
        });
    }

    public function writeAuditLog(string $event, ?array $oldValues, ?array $newValues, ?array $meta = null): void
    {
        AuditLogger::log(
            event: $event,
            target: $this,
            old: $oldValues,
            new: $newValues,
            meta: $meta ?? []
        );
    }
}
