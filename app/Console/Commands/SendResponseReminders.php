<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use App\Notifications\ParticipantResponseReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendResponseReminders extends Command
{
    protected $signature = 'sgrs:send-response-reminders';

    protected $description = 'Envoie des relances automatiques aux participants qui n\'ont pas répondu aux invitations (EF43)';

    public function handle(): int
    {
        $now = Carbon::now();
        $upcomingDate = $now->copy()->addDays(7)->endOfDay();

        $this->info('Envoi des relances pour les participants sans réponse...');

        $meetings = Meeting::with(['participants.user', 'participantsUsers'])
            ->where('start_at', '>', $now)
            ->where('start_at', '<=', $upcomingDate)
            ->whereIn('status', ['planifiee', 'en_preparation', 'scheduled'])
            ->whereNull('deleted_at')
            ->get();

        $totalReminders = 0;

        foreach ($meetings as $meeting) {
            // Participants avec statut "invited" et sans réponse depuis plus de 24h
            $pendingParticipants = $meeting->participants()
                ->where('status', 'invited')
                ->where(function ($q) {
                    $q->whereNull('response_at')
                      ->orWhere('response_at', '<', now()->subDay());
                })
                ->with('user')
                ->get();

            if ($pendingParticipants->isEmpty()) {
                continue;
            }

            $this->info("Envoi de relances pour la réunion #{$meeting->id} – {$meeting->title} ({$pendingParticipants->count()} participant(s) sans réponse)");

            foreach ($pendingParticipants as $participant) {
                if ($participant->user) {
                    $participant->user->notify(new ParticipantResponseReminderNotification($meeting));
                    $totalReminders++;
                }
            }
        }

        if ($totalReminders === 0) {
            $this->info('Aucune relance à envoyer.');
        } else {
            $this->info("{$totalReminders} relance(s) envoyée(s) avec succès.");
        }

        return self::SUCCESS;
    }
}

