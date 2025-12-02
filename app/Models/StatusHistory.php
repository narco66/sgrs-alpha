<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusHistory extends Model
{
    /**
     * Nom de la table en français
     */
    protected $table = 'historiques_statuts';

    protected $fillable = [
        'meeting_id',
        'status',
        'changed_by',
        'notes'
    ];

    /**
     * Relation avec la réunion
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
}
