<?php

namespace App\Traits;

use App\Models\AuditLog;
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
                // On ignore timestamps
                if (in_array($key, ['updated_at', 'created_at', 'deleted_at'])) {
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
        AuditLog::create([
            'event'          => $event,
            'auditable_type' => static::class,
            'auditable_id'   => $this->getKey(),
            'user_id'        => Auth::id(),
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::header('User-Agent'),
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'meta'           => $meta,
        ]);
    }
}
