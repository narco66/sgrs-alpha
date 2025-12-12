<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification envoyée aux administrateurs lorsqu'un utilisateur
 * crée un compte en auto-inscription (compte en attente de validation).
 */
class UserPendingApprovalNotification extends Notification implements ShouldQueue
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
        $user = $this->user;

        return (new MailMessage)
            ->subject('Nouveau compte en attente de validation – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Un nouvel utilisateur a demandé la création d\'un compte dans le SGRS-CEEAC.')
            ->line('Nom : ' . $user->name)
            ->when($user->email, fn($mail) => $mail->line('Email : ' . $user->email))
            ->line('Date de création : ' . optional($user->created_at)->format('d/m/Y H:i'))
            ->line('Ce compte est actuellement en attente de validation.')
            ->action('Gérer ce compte', route('users.show', $user))
            ->line('Vous recevez cet email car vous disposez de droits d\'administration dans le système.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'user_pending_approval',
            'user_id' => $this->user->id,
            'message' => 'Nouvelle demande de création de compte pour : ' . $this->user->email,
        ];
    }
}


