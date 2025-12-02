<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingStatusHistory extends Model
{
    use HasFactory;

    /**
     * Nom de la table en français
     */
    protected $table = 'historiques_statuts_reunions';

    protected $fillable = [
        'meeting_id',
        'old_status',
        'new_status',
        'changed_by',
        'comment',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
