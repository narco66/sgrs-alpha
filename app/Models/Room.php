<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Traits\LogsActivity;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    /**
     * Nom de la table en français
     */
    protected $table = 'salles';

    protected $fillable = [
        'name',
        'code',
        'location',
        'capacity',
        'description',
        'image',
        'equipments',
        'is_active',
    ];

    protected $casts = [
        'equipments' => 'array',
        'is_active'  => 'boolean',
        'capacity'   => 'integer',
    ];

    /**
     * Labels des équipements pour l'affichage
     */
    public static array $equipmentLabels = [
        'videoprojecteur' => 'Vidéoprojecteur',
        'ecran_projection' => 'Écran de projection',
        'ecran_tv' => 'Écran TV',
        'tableau_blanc' => 'Tableau blanc',
        'visioconference' => 'Visioconférence',
        'systeme_audio' => 'Système audio',
        'microphones' => 'Microphones',
        'wifi' => 'WiFi',
        'climatisation' => 'Climatisation',
        'interpretation' => 'Interprétation simultanée',
        'enregistrement' => 'Enregistrement',
        'ordinateur' => 'Ordinateur',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'room_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSEURS
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie si la salle est actuellement occupée
     */
    public function getIsOccupiedAttribute(): bool
    {
        return $this->currentMeeting() !== null;
    }

    /**
     * Récupère la réunion en cours dans cette salle
     */
    public function getCurrentMeetingAttribute(): ?Meeting
    {
        return $this->currentMeeting();
    }

    /**
     * Récupère la prochaine réunion dans cette salle
     */
    public function getNextMeetingAttribute(): ?Meeting
    {
        return $this->nextMeeting();
    }

    /**
     * URL de l'image de la salle (avec fallback)
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            // Utilise l'URL générée par le disque "public" (gère le préfixe /storage)
            return Storage::url($this->image);
        }

        // Image par défaut : logo institutionnel pour éviter les 404
        return asset('images/logo-ceeac.png');
    }

    /**
     * Retourne les équipements avec leurs labels
     */
    public function getEquipmentsWithLabelsAttribute(): array
    {
        if (!$this->equipments || !is_array($this->equipments)) {
            return [];
        }

        $result = [];
        foreach ($this->equipments as $equip) {
            $result[] = [
                'key' => $equip,
                'label' => self::$equipmentLabels[$equip] ?? ucfirst(str_replace('_', ' ', $equip)),
            ];
        }
        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTHODES MÉTIER
    |--------------------------------------------------------------------------
    */

    /**
     * Récupère la réunion actuellement en cours dans cette salle
     */
    public function currentMeeting(): ?Meeting
    {
        $now = now();

        return $this->meetings()
            ->where('status', '!=', 'annulee')
            ->where('start_at', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->where('end_at', '>=', $now)
                      ->orWhere(function ($q) use ($now) {
                          $q->whereNull('end_at')
                            ->whereRaw('DATE_ADD(start_at, INTERVAL COALESCE(duration_minutes, 60) MINUTE) >= ?', [$now]);
                      });
            })
            ->orderBy('start_at')
            ->first();
    }

    /**
     * Récupère la prochaine réunion programmée dans cette salle
     */
    public function nextMeeting(): ?Meeting
    {
        $now = now();

        return $this->meetings()
            ->where('status', '!=', 'annulee')
            ->where('start_at', '>', $now)
            ->orderBy('start_at')
            ->first();
    }

    /**
     * Vérifier si la salle est disponible pour un créneau donné
     * EF38 - Consultation des disponibilités
     */
    public function isAvailableFor($startAt, $endAt, $excludeMeetingId = null): bool
    {
        $query = $this->meetings()
            ->where(function ($q) use ($startAt, $endAt) {
                // Vérifier les chevauchements
                $q->where(function ($sub) use ($startAt, $endAt) {
                    // Réunion qui commence pendant le créneau demandé
                    $sub->whereBetween('start_at', [$startAt, $endAt])
                        // Ou réunion qui se termine pendant le créneau demandé
                        ->orWhereBetween('end_at', [$startAt, $endAt])
                        // Ou réunion qui englobe complètement le créneau demandé
                        ->orWhere(function ($inner) use ($startAt, $endAt) {
                            $inner->where('start_at', '<=', $startAt)
                                  ->where(function ($endQ) use ($endAt) {
                                      $endQ->where('end_at', '>=', $endAt)
                                           ->orWhereNull('end_at');
                                  });
                        });
                });
            })
            ->where('status', '!=', 'annulee')
            ->whereNull('deleted_at');

        if ($excludeMeetingId) {
            $query->where('id', '!=', $excludeMeetingId);
        }

        return $query->count() === 0;
    }

    /**
     * Obtenir les créneaux disponibles pour une date donnée
     */
    public function getAvailableSlots($date, $durationMinutes = 60): array
    {
        $slots = [];
        $startOfDay = Carbon::parse($date)->startOfDay()->addHours(8); // Début à 8h
        $endOfDay = Carbon::parse($date)->startOfDay()->addHours(18); // Fin à 18h

        // Récupérer toutes les réunions du jour
        $meetings = $this->meetings()
            ->whereDate('start_at', $date)
            ->where('status', '!=', 'annulee')
            ->whereNull('deleted_at')
            ->orderBy('start_at')
            ->get();

        $current = $startOfDay->copy();

        foreach ($meetings as $meeting) {
            $meetingStart = Carbon::parse($meeting->start_at);
            $meetingEnd = $meeting->end_at 
                ? Carbon::parse($meeting->end_at)
                : $meetingStart->copy()->addMinutes($meeting->duration_minutes ?? 60);

            // Si il y a un créneau libre avant la réunion
            if ($current->copy()->addMinutes($durationMinutes)->lte($meetingStart)) {
                $slots[] = [
                    'start' => $current->copy(),
                    'end' => $meetingStart->copy(),
                ];
            }

            $current = $meetingEnd->copy();
        }

        // Vérifier s'il reste un créneau après la dernière réunion
        if ($current->copy()->addMinutes($durationMinutes)->lte($endOfDay)) {
            $slots[] = [
                'start' => $current->copy(),
                'end' => $endOfDay->copy(),
            ];
        }

        return $slots;
    }

    /**
     * Compte les réunions aujourd'hui
     */
    public function getTodayMeetingsCountAttribute(): int
    {
        return $this->meetings()
            ->whereDate('start_at', today())
            ->where('status', '!=', 'annulee')
            ->count();
    }

    /**
     * Scope pour filtrer par disponibilité
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par statut d'occupation actuel
     */
    public function scopeCurrentlyOccupied($query)
    {
        $now = now();
        
        return $query->whereHas('meetings', function ($q) use ($now) {
            $q->where('status', '!=', 'annulee')
              ->where('start_at', '<=', $now)
              ->where(function ($sub) use ($now) {
                  $sub->where('end_at', '>=', $now)
                      ->orWhere(function ($inner) use ($now) {
                          $inner->whereNull('end_at')
                                ->whereRaw('DATE_ADD(start_at, INTERVAL COALESCE(duration_minutes, 60) MINUTE) >= ?', [$now]);
                      });
              });
        });
    }

    /**
     * Scope pour filtrer les salles actuellement libres
     */
    public function scopeCurrentlyFree($query)
    {
        $now = now();
        
        return $query->whereDoesntHave('meetings', function ($q) use ($now) {
            $q->where('status', '!=', 'annulee')
              ->where('start_at', '<=', $now)
              ->where(function ($sub) use ($now) {
                  $sub->where('end_at', '>=', $now)
                      ->orWhere(function ($inner) use ($now) {
                          $inner->whereNull('end_at')
                                ->whereRaw('DATE_ADD(start_at, INTERVAL COALESCE(duration_minutes, 60) MINUTE) >= ?', [$now]);
                      });
              });
        });
    }
}
