<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationCommitteeMember extends Model
{
    use HasFactory;

    /**
     * Nom de la table en français
     */
    protected $table = 'membres_comites_organisation';

    protected $fillable = [
        'organization_committee_id',
        'user_id',
        'member_type',
        'department',
        'service',
        'role',
        'notes',
        'responsibilities',
        'joined_at',
        'left_at',
    ];

    /**
     * Comité d'organisation
     */
    public function organizationCommittee()
    {
        return $this->belongsTo(OrganizationCommittee::class);
    }

    /**
     * Utilisateur membre
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    /* ============================================================
     |  CONSTANTES MÉTIER
     |============================================================
    */
    public const MEMBER_TYPE_CEEAC = 'ceeac';
    public const MEMBER_TYPE_HOST_COUNTRY = 'host_country';

    public static function memberTypes(): array
    {
        return [
            self::MEMBER_TYPE_CEEAC,
            self::MEMBER_TYPE_HOST_COUNTRY,
        ];
    }

    /* ============================================================
     |  SCOPES
     |============================================================
    */

    public function scopeCeeac($query)
    {
        return $query->where('member_type', self::MEMBER_TYPE_CEEAC);
    }

    public function scopeHostCountry($query)
    {
        return $query->where('member_type', self::MEMBER_TYPE_HOST_COUNTRY);
    }

    /* ============================================================
     |  MÉTHODES MÉTIER
     |============================================================
    */

    /**
     * Vérifie si le membre est un fonctionnaire CEEAC
     */
    public function isCeeac(): bool
    {
        return $this->member_type === self::MEMBER_TYPE_CEEAC;
    }

    /**
     * Vérifie si le membre est un fonctionnaire du pays hôte
     */
    public function isHostCountry(): bool
    {
        return $this->member_type === self::MEMBER_TYPE_HOST_COUNTRY;
    }
}
