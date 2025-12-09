<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Meeting $meeting)
    {
    }

    public function via(object $notifiable): array
    {
        // mail + enregistrement en base + broadcast temps réel
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $meeting = $this->meeting;

        return (new MailMessage)
            ->subject('Rappel de réunion – ' . $meeting->title)
            ->greeting('Bonjour ' . ($notifiable->name ?? ''))
            ->line('Ceci est un rappel de réunion statutaire de la CEEAC.')
            ->line('Titre : ' . $meeting->title)
            ->line('Type : ' . ($meeting->meetingType?->name ?? 'Non renseigné'))
            ->line('Date : ' . $meeting->start_at?->format('d/m/Y'))
            ->line('Heure : ' . $meeting->start_at?->format('H:i'))
            ->line('Salle : ' . ($meeting->room?->name ?? 'Non attribuée'))
            ->action('Voir la réunion dans le SGRS-CEEAC', route('meetings.show', $meeting))
            ->line('Merci d’utiliser le Système de Gestion des Réunions Statutaires de la CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'meeting_id'   => $this->meeting->id,
            'title'        => $this->meeting->title,
            'start_at'     => $this->meeting->start_at,
            'meeting_type' => $this->meeting->meetingType?->name,
            'room'         => $this->meeting->room?->name,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
