<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification envoyée à l'utilisateur lorsque son compte
 * a été validé par un administrateur.
 */
class UserAccountApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Validation de votre compte – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Votre compte a été validé par un administrateur du SGRS-CEEAC.')
            ->line('Vous pouvez désormais vous connecter à la plateforme et accéder aux fonctionnalités qui vous sont autorisées.')
            ->action('Se connecter', route('login'))
            ->line('Merci d\'utiliser le SGRS-CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'user_account_approved',
            'user_id' => $this->user->id,
            'message' => 'Votre compte a été validé. Vous pouvez maintenant vous connecter.',
        ];
    }
}


