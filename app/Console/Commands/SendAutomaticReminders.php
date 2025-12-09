<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Notifications\MeetingReminderNotification;
use App\Notifications\ParticipantResponseReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAutomaticReminders extends Command
{
    protected $signature = 'sgrs:send-automatic-reminders
                            {--days=7 : Nombre de jours avant la réunion pour envoyer les rappels}';

    protected $description = 'Envoie les rappels automatiques de réunions (J-7, J-1, jour J) et les relances pour les participants sans réponse';

    public function handle(): int
    {
        $daysBefore = (int) $this->option('days');
        $now = Carbon::now();

        $this->info("Envoi des rappels automatiques pour les réunions dans {$daysBefore} jour(s)...");

        // Rappels J-7, J-1, jour J
        $this->sendMeetingReminders($now, $daysBefore);

        // Relances pour les participants sans réponse
        $this->sendResponseReminders($now);

        $this->info('Rappels automatiques envoyés avec succès.');
        return self::SUCCESS;
    }

    /**
     * Envoie les rappels de réunions (J-7, J-1, jour J)
     */
    protected function sendMeetingReminders(Carbon $now, int $daysBefore): void
    {
        $targetDate = $now->copy()->addDays($daysBefore)->startOfDay();
        $endDate = $targetDate->copy()->endOfDay();

        $meetings = Meeting::with(['participants.user', 'meetingType', 'room'])
            ->whereBetween('start_at', [$targetDate, $endDate])
            ->whereIn('status', ['planifiee', 'en_preparation', 'scheduled'])
            ->whereNull('deleted_at')
            ->get();

        foreach ($meetings as $meeting) {
            $this->info("Envoi de rappels pour la réunion #{$meeting->id} – {$meeting->title}");

            // Envoyer aux participants confirmés
            foreach ($meeting->participants as $participant) {
                if ($participant->user && $participant->status === 'confirmed') {
                    $participant->user->notify(new MeetingReminderNotification($meeting));
                }
            }

            // Envoyer aussi aux participantsUsers confirmés
            if ($meeting->relationLoaded('participantsUsers')) {
                foreach ($meeting->participantsUsers as $user) {
                    $participant = $meeting->participants()->where('user_id', $user->id)->first();
                    if (!$participant || $participant->status === 'confirmed') {
                        $user->notify(new MeetingReminderNotification($meeting));
                    }
                }
            }
        }
    }

    /**
     * Envoie les relances pour les participants qui n'ont pas répondu
     */
    protected function sendResponseReminders(Carbon $now): void
    {
        // Réunions dans les 7 prochains jours avec participants sans réponse
        $upcomingDate = $now->copy()->addDays(7)->endOfDay();

        $meetings = Meeting::with(['participants.user'])
            ->where('start_at', '>', $now)
            ->where('start_at', '<=', $upcomingDate)
            ->whereIn('status', ['planifiee', 'en_preparation', 'scheduled'])
            ->whereNull('deleted_at')
            ->get();

        foreach ($meetings as $meeting) {
            $pendingParticipants = $meeting->participants()
                ->where('status', 'invited')
                ->whereNull('response_at')
                ->with('user')
                ->get();

            if ($pendingParticipants->isEmpty()) {
                continue;
            }

            $this->info("Envoi de relances pour la réunion #{$meeting->id} – {$meeting->title} ({$pendingParticipants->count()} participant(s) sans réponse)");

            foreach ($pendingParticipants as $participant) {
                if ($participant->user) {
                    $participant->user->notify(new ParticipantResponseReminderNotification($meeting));
                }
            }
        }
    }
}

