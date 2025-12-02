<?php

namespace App\Services;

use App\Enums\MeetingStatus;
use App\Models\Meeting;
use App\Models\MeetingStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MeetingWorkflowService
{
    /**
     * Change le statut d'une réunion avec journalisation et historique.
     *
     * @param  \App\Models\Meeting                 $meeting
     * @param  \App\Enums\MeetingStatus|string     $newStatus
     * @param  \App\Models\User|null              $user
     * @param  string|null                        $comment
     * @return void
     */
    public function changeStatus(Meeting $meeting, MeetingStatus|string $newStatus, ?User $user = null, ?string $comment = null): void
    {
        DB::transaction(function () use ($meeting, $newStatus, $user, $comment) {

            // --- 1. Normalisation de l'ancien statut ----------------------

            $oldStatusRaw = $meeting->status; // peut être string OU enum selon les casts

            // On transforme systématiquement en string (ou null)
            $oldStatusValue = $oldStatusRaw instanceof MeetingStatus
                ? $oldStatusRaw->value
                : ($oldStatusRaw !== null ? (string) $oldStatusRaw : null);

            // --- 2. Normalisation du nouveau statut -----------------------

            $newStatusValue = $newStatus instanceof MeetingStatus
                ? $newStatus->value
                : (string) $newStatus;

            // --- 3. Mise à jour du statut sur la réunion ------------------

            $meeting->update([
                'status' => $newStatusValue,
            ]);

            // --- 4. Historique de statut (si le modèle existe) -----------

            if (class_exists(MeetingStatusHistory::class)) {
                MeetingStatusHistory::create([
                    'meeting_id' => $meeting->id,
                    'old_status' => $oldStatusValue,   // plus de ->value ici
                    'new_status' => $newStatusValue,   // plus de ->value ici
                    'changed_by' => $user?->id,
                    'comment'    => $comment,
                ]);
            }

            // --- 5. Audit log spécifique (optionnel) ---------------------

            if (method_exists($meeting, 'writeAuditLog')) {
                $meeting->writeAuditLog(
                    'status_changed',
                    ['status' => $oldStatusValue],
                    ['status' => $newStatusValue],
                    [
                        'user_id' => $user?->id,
                        'comment' => $comment,
                    ]
                );
            }

            // --- 6. Notifications éventuelles (à implémenter si besoin) --
            // Exemple :
            // if (method_exists($meeting, 'notifyStatusChanged')) {
            //     $meeting->notifyStatusChanged($oldStatusValue, $newStatusValue, $user, $comment);
            // }
        });
    }
}
