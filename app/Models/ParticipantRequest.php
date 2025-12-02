<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParticipantRequest extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nom de la table en français
     */
    protected $table = 'demandes_participants';

    protected $fillable = [
        'meeting_id',
        'participant_name',
        'participant_email',
        'participant_role',
        'justification',
        'requested_by',
        'reviewed_by',
        'status',
        'review_comments',
        'reviewed_at',
        'participant_id',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Réunion concernée
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
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
     * Participant créé (si approuvé)
     */
    public function participant()
    {
        return $this->belongsTo(MeetingParticipant::class, 'participant_id');
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
