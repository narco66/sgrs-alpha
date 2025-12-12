<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification envoyée à l'administrateur qui a validé un compte
 * pour récapituler son action (audit fonctionnel).
 */
class UserAccountApprovedAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $approvedUser,
        public ?User $actor = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $user = $this->approvedUser;

        return (new MailMessage)
            ->subject('Validation de compte confirmée – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Vous avez validé le compte utilisateur suivant dans le SGRS-CEEAC :')
            ->line('Nom : ' . $user->name)
            ->when($user->email, fn($mail) => $mail->line('Email : ' . $user->email))
            ->line('Date et heure de validation : ' . now()->format('d/m/Y H:i'))
            ->line('Cette notification sert de confirmation et de trace fonctionnelle de votre action.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'user_account_approved_admin',
            'user_id'      => $this->approvedUser->id,
            'approved_by'  => $this->actor?->id,
            'message'      => 'Vous avez validé le compte de ' . $this->approvedUser->email,
        ];
    }
}




