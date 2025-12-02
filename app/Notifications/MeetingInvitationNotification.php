<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class MeetingInvitationNotification extends Notification implements ShouldQueue
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
            ->subject('Invitation à une réunion statutaire – ' . $meeting->title)
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Vous êtes invité(e) à participer à une réunion statutaire de la CEEAC.')
            ->line('**Titre :** ' . $meeting->title)
            ->line('**Type :** ' . ($meeting->type?->name ?? 'Non renseigné'))
            ->line('**Date :** ' . $meeting->start_at?->format('d/m/Y'))
            ->line('**Heure :** ' . $meeting->start_at?->format('H:i'))
            ->when($meeting->end_at, function ($mail) use ($meeting) {
                return $mail->line('**Fin prévue :** ' . $meeting->end_at->format('H:i'));
            })
            ->line('**Lieu :** ' . ($meeting->room?->name ?? ($meeting->location ?? 'À déterminer')))
            ->when($meeting->description, function ($mail) use ($meeting) {
                return $mail->line('**Description :** ' . Str::limit($meeting->description, 200));
            })
            ->action('Voir les détails et confirmer ma participation', route('meetings.show', $meeting))
            ->line('Merci de confirmer votre participation dans les plus brefs délais.')
            ->line('Merci d\'utiliser le Système de Gestion des Réunions Statutaires de la CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'meeting_id'   => $this->meeting->id,
            'title'        => $this->meeting->title,
            'start_at'     => $this->meeting->start_at?->toIso8601String(),
            'meeting_type' => $this->meeting->type?->name,
            'room'         => $this->meeting->room?->name,
            'type'         => 'meeting_invitation',
            'message'      => 'Vous avez été invité(e) à la réunion : ' . $this->meeting->title,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}

