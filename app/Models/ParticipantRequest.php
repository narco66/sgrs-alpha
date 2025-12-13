<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class ParticipantRequest extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

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
        'participant_id',        // legacy (MeetingParticipant) - conservé pour compatibilité historique
        'delegation_member_id',  // nouveau lien vers DelegationMember
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
     * Participant legacy créé (si approuvé) - MeetingParticipant
     * @deprecated Utiliser delegationMember() à la place
     */
    public function participant()
    {
        return $this->belongsTo(MeetingParticipant::class, 'participant_id');
    }

    /**
     * Membre de délégation créé (nouvelle logique) si la demande est approuvée.
     */
    public function delegationMember()
    {
        return $this->belongsTo(DelegationMember::class, 'delegation_member_id');
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
