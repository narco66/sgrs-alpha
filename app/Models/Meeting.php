<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Document;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Nom de la table en français
     */
    protected $table = 'reunions';

    /**
     * Attributs pouvant être remplis en masse.
     */
    protected $fillable = [
        'title',
        'slug',                    // <-- important pour éviter l'erreur sur slug
        'meeting_type_id',
        'committee_id',
        'room_id',
        'start_at',
        'end_at',
        'duration_minutes',
        'status',
        'description',
        'agenda',
        'organizer_id',
        'reminder_minutes_before',
        // ajoute ici d'autres champs si nécessaire (configuration, created_by, updated_by, ...)
    ];

    /**
     * Casts d'attributs.
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    /**
     * Hooks du modèle pour gérer automatiquement le slug.
     */
    protected static function booted(): void
    {
        static::creating(function (Meeting $meeting) {
            if (empty($meeting->slug)) {
                $baseSlug = Str::slug($meeting->title ?? Str::random(8));
                $meeting->slug = $baseSlug.'-'.Str::random(6);
            }
        });

        static::updating(function (Meeting $meeting) {
            // Si le titre change et qu'aucun slug n'est encore défini,
            // on en génère un (tu peux adapter selon ta stratégie métier)
            if ($meeting->isDirty('title') && empty($meeting->slug)) {
                $baseSlug = Str::slug($meeting->title ?? Str::random(8));
                $meeting->slug = $baseSlug.'-'.Str::random(6);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function type()
    {
        return $this->belongsTo(MeetingType::class, 'meeting_type_id');
    }

    public function committee()
    {
        return $this->belongsTo(Committee::class, 'committee_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Délégations participantes à la réunion
     * NOUVELLE RELATION PRINCIPALE : participation par délégations
     */
    public function delegations()
    {
        return $this->hasMany(Delegation::class, 'meeting_id');
    }

    /**
     * Relation legacy pour compatibilité (à déprécier)
     * @deprecated Utiliser delegations() à la place
     */
    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    /**
     * Relation legacy pour compatibilité (à déprécier)
     * @deprecated Utiliser delegations() à la place
     */
    public function participantsUsers()
    {
        return $this->belongsToMany(User::class, 'participants_reunions');
    }

    /**
     * Documents associés à la réunion (ordre du jour, PV, rapports, etc.)
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'meeting_id');
    }


    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class);
    }

    // Ou si vous utilisez un modèle différent, ajustez le nom :
    public function meetingStatusHistories(): HasMany
    {
        return $this->hasMany(MeetingStatusHistory::class);
    }

    /**
     * Comité d'organisation de la réunion
     */
    public function organizationCommittee()
    {
        return $this->hasOne(OrganizationCommittee::class);
    }

    /**
     * Cahier des charges entre la CEEAC et le pays hôte
     */
    public function termsOfReference()
    {
        return $this->hasOne(TermsOfReference::class, 'meeting_id')
            ->whereNull('previous_version_id') // Version actuelle uniquement
            ->latest('version');
    }

    /**
     * Toutes les versions du cahier des charges (avec historique)
     */
    public function termsOfReferences()
    {
        return $this->hasMany(TermsOfReference::class, 'meeting_id')
            ->orderBy('version', 'desc');
    }

    /**
     * Demandes de réunion associées
     */
    public function meetingRequests()
    {
        return $this->hasMany(MeetingRequest::class);
    }

    /**
     * Demandes d'ajout de participants
     */
    public function participantRequests()
    {
        return $this->hasMany(ParticipantRequest::class);
    }
}
