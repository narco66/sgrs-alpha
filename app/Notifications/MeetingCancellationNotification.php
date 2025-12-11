<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingCancellationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Meeting $meeting, public ?string $reason = null)
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
            ->subject('Annulation de réunion – ' . $meeting->title)
            ->greeting('Bonjour ' . ($notifiable->name ?? ''))
            ->line('La réunion suivante a été annulée :')
            ->line('Titre : ' . $meeting->title)
            ->line('Date prévue : ' . $meeting->start_at?->format('d/m/Y à H:i'))
            ->when($this->reason, function ($mail) {
                return $mail->line('Raison : ' . $this->reason);
            })
            ->line('Merci de votre compréhension.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'meeting_id' => $this->meeting->id,
            'title' => $this->meeting->title,
            'start_at' => $this->meeting->start_at?->toIso8601String(),
            'reason' => $this->reason,
            'type' => 'meeting_cancelled',
            'message' => 'La réunion "' . $this->meeting->title . '" a été annulée.',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}

