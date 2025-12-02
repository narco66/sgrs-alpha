<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationCommittee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nom de la table en français
     */
    protected $table = 'comites_organisation';

    protected $fillable = [
        'name',
        'description',
        'host_country',
        'meeting_id',
        'created_by',
        'is_active',
        'activated_at',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
    ];

    /**
     * Réunion associée
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Créateur du comité
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Membres du comité
     */
    public function members()
    {
        return $this->hasMany(OrganizationCommitteeMember::class);
    }

    /**
     * Utilisateurs membres (via pivot)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_committee_members')
            ->withPivot(['role', 'notes'])
            ->withTimestamps();
    }

    /**
     * Scope pour les comités actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
