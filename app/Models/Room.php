<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Nom de la table en français
     */
    protected $table = 'salles';

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'equipments',
        'is_active',
    ];

    protected $casts = [
        'equipments' => 'array',
        'is_active'  => 'boolean',
    ];

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function currentMeeting(): ?Meeting
    {
        $now = now();

        return $this->meetings()
            ->where('start_at', '<=', $now)
            ->whereRaw('DATE_ADD(start_at, INTERVAL duration_minutes MINUTE) >= ?', [$now])
            ->orderBy('start_at')
            ->first();
    }

    public function nextMeeting(): ?Meeting
    {
        $now = now();

        return $this->meetings()
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
        $startOfDay = \Carbon\Carbon::parse($date)->startOfDay();
        $endOfDay = $startOfDay->copy()->endOfDay();

        // Récupérer toutes les réunions du jour
        $meetings = $this->meetings()
            ->whereDate('start_at', $date)
            ->where('status', '!=', 'annulee')
            ->whereNull('deleted_at')
            ->orderBy('start_at')
            ->get();

        $current = $startOfDay->copy();

        foreach ($meetings as $meeting) {
            $meetingStart = \Carbon\Carbon::parse($meeting->start_at);
            $meetingEnd = $meeting->end_at 
                ? \Carbon\Carbon::parse($meeting->end_at)
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
}
