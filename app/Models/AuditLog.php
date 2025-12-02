<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    /**
     * Nom de la table en français
     */
    protected $table = 'journaux_audit';

    protected $fillable = [
        'event',
        'auditable_type',
        'auditable_id',
        'user_id',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
        'meta',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'meta'       => 'array',
    ];

    public function auditable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
