<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipantResponseReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Meeting $meeting)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $meeting = $this->meeting;

        return (new MailMessage)
            ->subject('Rappel : Réponse à l\'invitation – ' . $meeting->title)
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Vous n\'avez pas encore répondu à l\'invitation pour la réunion suivante :')
            ->line('**Titre :** ' . $meeting->title)
            ->line('**Date :** ' . $meeting->start_at?->format('d/m/Y à H:i'))
            ->line('**Lieu :** ' . ($meeting->room?->name ?? ($meeting->location ?? 'À déterminer')))
            ->line('Merci de confirmer ou de refuser votre participation dans les plus brefs délais.')
            ->action('Répondre à l\'invitation', route('meetings.show', $meeting))
            ->line('Merci d\'utiliser le Système de Gestion des Réunions Statutaires de la CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'meeting_id' => $this->meeting->id,
            'title'      => $this->meeting->title,
            'start_at'   => $this->meeting->start_at?->toIso8601String(),
            'type'       => 'response_reminder',
            'message'    => 'Rappel : Vous n\'avez pas encore répondu à l\'invitation pour : ' . $this->meeting->title,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}

