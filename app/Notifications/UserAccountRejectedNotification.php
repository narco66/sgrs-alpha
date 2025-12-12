<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification envoyée à l'utilisateur lorsque sa demande
 * de création de compte est rejetée.
 */
class UserAccountRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public ?string $reason = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Votre demande de compte a été rejetée – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Après examen, votre demande de création de compte dans le SGRS-CEEAC n\'a pas été validée.');

        if ($this->reason) {
            $mail->line('Motif indiqué : ' . $this->reason);
        }

        return $mail->line('Pour toute question complémentaire, vous pouvez contacter le support ou votre référent.')
            ->line('Merci pour votre compréhension.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'user_account_rejected',
            'user_id' => $this->user->id,
            'message' => 'Votre demande de compte a été rejetée.',
            'reason'  => $this->reason,
        ];
    }
}




