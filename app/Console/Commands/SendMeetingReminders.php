<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Notifications\MeetingReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendMeetingReminders extends Command
{
    protected $signature = 'sgrs:send-meeting-reminders
                            {--window=1 : Fenêtre en minutes pour la détection des rappels}';

    protected $description = 'Envoie les rappels de réunions aux participants selon la configuration de rappel';

    public function handle(): int
    {
        $window = (int) $this->option('window');

        $now = Carbon::now();

        // On récupère les réunions pour lesquelles un rappel est configuré
        // et qui n'ont pas encore été rappelées.
        $meetings = Meeting::with(['delegations.members', 'meetingType', 'room'])
            ->where('reminder_minutes_before', '>', 0)
            ->whereNull('reminder_sent_at')
            ->get()
            ->filter(function (Meeting $meeting) use ($now, $window) {
                if (! $meeting->start_at) {
                    return false;
                }

                $diff = $now->diffInMinutes($meeting->start_at, false); // Minutes jusqu'au début (peut être négatif)

                // On envoie le rappel lorsque diff == reminder_minutes_before,
                // avec une tolérance de [-window, +window] minutes.
                return $diff <= $meeting->reminder_minutes_before
                    && $diff >= ($meeting->reminder_minutes_before - $window);
            });

        if ($meetings->isEmpty()) {
            $this->info('Aucun rappel à envoyer pour cette exécution.');
            return self::SUCCESS;
        }

        foreach ($meetings as $meeting) {
            $this->info("Envoi de rappels pour la réunion #{$meeting->id} – {$meeting->title}");

            // Envoyer aux délégations et leurs membres
            foreach ($meeting->delegations as $delegation) {
                // Envoyer au chef de délégation (champ direct de la délégation)
                if (!empty($delegation->head_of_delegation_email)) {
                    try {
                        \Illuminate\Support\Facades\Notification::route('mail', [
                            $delegation->head_of_delegation_email => $delegation->head_of_delegation_name ?? 'Chef de Délégation'
                        ])->notify(new MeetingReminderNotification($meeting));
                    } catch (\Exception $e) {
                        $this->error("Erreur envoi rappel chef délégation {$delegation->head_of_delegation_email}: " . $e->getMessage());
                    }
                }
                
                // Envoyer à tous les membres de la délégation
                foreach ($delegation->members as $member) {
                    if (!empty($member->email)) {
                        try {
                            \Illuminate\Support\Facades\Notification::route('mail', [
                                $member->email => $member->full_name ?? $member->first_name
                            ])->notify(new MeetingReminderNotification($meeting));
                        } catch (\Exception $e) {
                            $this->error("Erreur envoi rappel membre {$member->email}: " . $e->getMessage());
                        }
                    }
                }
            }

            $meeting->reminder_sent_at = now();
            $meeting->save();
        }

        $this->info('Rappels envoyés avec succès.');
        return self::SUCCESS;
    }
}
