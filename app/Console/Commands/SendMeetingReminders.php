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
        // et qui n’ont pas encore été rappelées.
        $meetings = Meeting::with(['participants.user', 'type', 'room'])
            ->where('reminder_minutes_before', '>', 0)
            ->whereNull('reminder_sent_at')
            ->get()
            ->filter(function (Meeting $meeting) use ($now, $window) {
                if (! $meeting->start_at) {
                    return false;
                }

                $diff = $now->diffInMinutes($meeting->start_at, false); // Minutes jusqu’au début (peut être négatif)

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

            foreach ($meeting->participants as $participant) {
                if ($participant->user) {
                    $participant->user->notify(new MeetingReminderNotification($meeting));
                }
            }

            $meeting->reminder_sent_at = now();
            $meeting->save();
        }

        $this->info('Rappels envoyés avec succès.');
        return self::SUCCESS;
    }
}
