<?php

namespace App\Models;

use App\Models\Delegation;
use App\Models\Document;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\Notification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use LogsActivity;

    /**
     * Nom de la table en français
     */
    protected $table = 'utilisateurs';

    /**
     * Attributs assignables en masse.
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'delegation_id',
        'service',
        'is_active',
    ];

    /**
     * Attributs à cacher lors de la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts automatiques (Laravel 11).
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'        => 'boolean',
        'password' => 'hashed',
    ];

    /* ============================================================
     |  RELATIONS
     |============================================================
    */

    /**
     * Réunions organisées par l'utilisateur.
     */
    public function organizedMeetings()
    {
        return $this->hasMany(Meeting::class, 'organizer_id');
    }

    /**
     * Participations aux réunions (pivot).
     */
    public function meetingParticipations()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'participants_reunions')
            ->withPivot(['role', 'status', 'validated_at', 'checked_in_at']);
    }

    /**
     * Notifications personnalisées de l'utilisateur.
     */
    public function userNotifications() // Renamed to avoid conflict with Notifiable trait
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Documents uploadés par l'utilisateur.
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    /**
     * Délégation à laquelle appartient l'utilisateur (si invité externe)
     */
    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }

    /* ============================================================
     |  SCOPES
     |============================================================
    */

    /**
     * Filtrer les utilisateurs actifs (si futur champ is_active).
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ordre alphabétique par défaut.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('name');
    }

    /* ============================================================
     |  ACCESSORS / MUTATORS
     |============================================================
    */

    /**
     * Formater le nom complet (première lettre majuscule).
     */
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }


    /* ============================================================
     |  NOTIFICATIONS
     |============================================================
    */

    /**
     * Raccourci pour récupérer les 5 dernières notifications.
     */
    public function latestNotifications(int $limit = 5)
    {
        return $this->userNotifications() // Use the renamed relationship
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Compteur de notifications non lues (pour la cloche).
     */
    public function unreadCount()
    {
        return $this->unreadNotifications()->count();
    }

    /* ============================================================
     |  AUTRES FONCTIONS UTILES POUR LE SGRS-CEEAC
     |============================================================
    */

    /**
     * Vérifie si l'utilisateur peut organiser une réunion.
     */
    public function canOrganizeMeetings(): bool
    {
        return $this->hasAnyPermission([
            'meetings.create',
            'meetings.update',
        ]);
    }

    /**
     * Vérifie si l'utilisateur est Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Vérifie si l'utilisateur a un rôle à responsabilités (SG, DSI, Admin).
     */
    public function isStaffDirection(): bool
    {
        return $this->hasAnyRole(['super-admin', 'admin', 'sg', 'dsi']);
    }
}
