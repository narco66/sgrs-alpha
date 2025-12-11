<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class MeetingRequest extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    /**
     * Nom de la table en français
     */
    protected $table = 'demandes_reunions';

    protected $fillable = [
        'title',
        'description',
        'meeting_type_id',
        'committee_id',
        'requested_start_at',
        'requested_end_at',
        'requested_room_id',
        'other_location',
        'justification',
        'requested_by',
        'reviewed_by',
        'status',
        'review_comments',
        'reviewed_at',
        'meeting_id',
    ];

    protected $casts = [
        'requested_start_at' => 'datetime',
        'requested_end_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Type de réunion demandé
     */
    public function meetingType()
    {
        return $this->belongsTo(MeetingType::class);
    }

    /**
     * Comité associé
     */
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    /**
     * Salle demandée
     */
    public function requestedRoom()
    {
        return $this->belongsTo(Room::class, 'requested_room_id');
    }

    /**
     * Demandeur
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Examinateur
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Réunion créée (si approuvée)
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Scope pour les demandes en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les demandes approuvées
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
