<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour les membres individuels d'une délégation
 * 
 * Conforme au modèle institutionnel CEEAC : Réunion → Délégations → Membres
 */
class DelegationMember extends Model
{
    use HasFactory;

    /**
     * Nom de la table standardisé
     */
    protected $table = 'delegation_members';

    protected $fillable = [
        'delegation_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'title',
        'institution',
        'department',
        'role',
        'status',
        'confirmed_at',
        'checked_in_at',
        'notes',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    /* ============================================================
     |  CONSTANTES MÉTIER
     |============================================================
    */
    public const ROLE_HEAD = 'head';
    public const ROLE_MEMBER = 'member';
    public const ROLE_EXPERT = 'expert';
    public const ROLE_OBSERVER = 'observer';
    public const ROLE_SECRETARY = 'secretary';

    public static function roles(): array
    {
        return [
            self::ROLE_HEAD,
            self::ROLE_MEMBER,
            self::ROLE_EXPERT,
            self::ROLE_OBSERVER,
            self::ROLE_SECRETARY,
        ];
    }

    public const STATUS_INVITED = 'invited';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PRESENT = 'present';
    public const STATUS_ABSENT = 'absent';
    public const STATUS_EXCUSED = 'excused';

    public static function statuses(): array
    {
        return [
            self::STATUS_INVITED,
            self::STATUS_CONFIRMED,
            self::STATUS_PRESENT,
            self::STATUS_ABSENT,
            self::STATUS_EXCUSED,
        ];
    }

    /* ============================================================
     |  ACCESSORS
     |============================================================
    */

    /**
     * Nom complet du membre
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->title} {$this->first_name} {$this->last_name}");
    }

    /* ============================================================
     |  RELATIONS
     |============================================================
    */

    /**
     * Délégation à laquelle appartient le membre
     */
    public function delegation(): BelongsTo
    {
        return $this->belongsTo(Delegation::class);
    }

    /* ============================================================
     |  SCOPES
     |============================================================
    */

    public function scopeHead($query)
    {
        return $query->where('role', self::ROLE_HEAD);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    /* ============================================================
     |  MÉTHODES MÉTIER
     |============================================================
    */

    /**
     * Vérifie si le membre est le chef de délégation
     */
    public function isHead(): bool
    {
        return $this->role === self::ROLE_HEAD;
    }

    /**
     * Vérifie si le membre a confirmé sa participation
     */
    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED && $this->confirmed_at !== null;
    }
}



















