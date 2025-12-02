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
        'role',
        'notes',
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
}
