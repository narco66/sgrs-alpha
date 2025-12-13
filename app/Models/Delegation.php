<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle pour les délégations institutionnelles
 * 
 * Conforme au modèle institutionnel CEEAC : participation par délégations
 * et non par participants individuels
 */
class Delegation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'delegations';

    protected $fillable = [
        'title',
        'entity_type',
        'country_code',
        'country',
        'organization_name',
        'organization_type',
        'description',
        'contact_email',
        'contact_phone',
        'head_of_delegation_name',
        'head_of_delegation_position',
        'head_of_delegation_email',
        'meeting_id',
        'is_active',
        'participation_status',
        'confirmed_at',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    /* ============================================================
     |  CONSTANTES MÉTIER
     |============================================================
    */
    public const ENTITY_TYPE_STATE_MEMBER = 'state_member';
    public const ENTITY_TYPE_INTERNATIONAL_ORG = 'international_organization';
    public const ENTITY_TYPE_TECHNICAL_PARTNER = 'technical_partner';
    public const ENTITY_TYPE_FINANCIAL_PARTNER = 'financial_partner';
    public const ENTITY_TYPE_OTHER = 'other';

    public static function entityTypes(): array
    {
        return [
            self::ENTITY_TYPE_STATE_MEMBER,
            self::ENTITY_TYPE_INTERNATIONAL_ORG,
            self::ENTITY_TYPE_TECHNICAL_PARTNER,
            self::ENTITY_TYPE_FINANCIAL_PARTNER,
            self::ENTITY_TYPE_OTHER,
        ];
    }

    public const STATUS_INVITED = 'invited';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REGISTERED = 'registered';
    public const STATUS_PRESENT = 'present';
    public const STATUS_ABSENT = 'absent';
    public const STATUS_EXCUSED = 'excused';

    public static function participationStatuses(): array
    {
        return [
            self::STATUS_INVITED,
            self::STATUS_CONFIRMED,
            self::STATUS_REGISTERED,
            self::STATUS_PRESENT,
            self::STATUS_ABSENT,
            self::STATUS_EXCUSED,
        ];
    }

    /* ============================================================
     |  RELATIONS
     |============================================================
    */

    /**
     * Réunion à laquelle participe la délégation
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Membres de la délégation
     * NOUVELLE RELATION : une délégation a plusieurs membres
     */
    public function members(): HasMany
    {
        return $this->hasMany(DelegationMember::class);
    }

    /**
     * Chef de délégation (membre avec role = 'head')
     */
    public function head()
    {
        return $this->hasOne(DelegationMember::class)
            ->where('role', DelegationMember::ROLE_HEAD);
    }

    /**
     * Relation legacy pour compatibilité (à déprécier)
     * @deprecated Utiliser members() à la place
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation legacy pour compatibilité (à déprécier)
     * @deprecated Utiliser members() à la place
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'delegation_participants')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    /* ============================================================
     |  SCOPES
     |============================================================
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeStateMembers($query)
    {
        return $query->where('entity_type', self::ENTITY_TYPE_STATE_MEMBER);
    }

    public function scopeInternationalOrganizations($query)
    {
        return $query->where('entity_type', self::ENTITY_TYPE_INTERNATIONAL_ORG);
    }

    public function scopePartners($query)
    {
        return $query->whereIn('entity_type', [
            self::ENTITY_TYPE_TECHNICAL_PARTNER,
            self::ENTITY_TYPE_FINANCIAL_PARTNER,
        ]);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('participation_status', self::STATUS_CONFIRMED);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('country', 'like', "%{$search}%")
              ->orWhere('organization_name', 'like', "%{$search}%")
              ->orWhere('head_of_delegation_name', 'like', "%{$search}%");
        });
    }

    /* ============================================================
     |  MÉTHODES MÉTIER
     |============================================================
    */

    /**
     * Vérifie si la délégation est confirmée
     */
    public function isConfirmed(): bool
    {
        return $this->participation_status === self::STATUS_CONFIRMED 
            && $this->confirmed_at !== null;
    }

    /**
     * Obtient le nom de l'entité représentée
     */
    public function getEntityNameAttribute(): string
    {
        return match($this->entity_type) {
            self::ENTITY_TYPE_STATE_MEMBER => $this->country ?? $this->title,
            self::ENTITY_TYPE_INTERNATIONAL_ORG,
            self::ENTITY_TYPE_TECHNICAL_PARTNER,
            self::ENTITY_TYPE_FINANCIAL_PARTNER => $this->organization_name ?? $this->title,
            default => $this->title,
        };
    }

    /**
     * URL de la photo du chef de délégation (si stockée)
     */
    public function getHeadOfDelegationPhotoUrlAttribute(): ?string
    {
        if (empty($this->head_of_delegation_photo_path)) {
            return null;
        }

        // On suppose que le fichier est stocké sur le disque "public"
        return asset('storage/' . ltrim($this->head_of_delegation_photo_path, '/'));
    }
}
