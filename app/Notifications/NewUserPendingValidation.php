<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification interne (base de données + éventuellement mail) envoyée
 * aux administrateurs lorsque qu'un nouvel utilisateur crée un compte
 * et que celui-ci est en attente de validation.
 *
 * Utilisée pour alimenter la cloche de notifications et la liste
 * des notifications non lues dans le back-office.
 */
class NewUserPendingValidation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user)
    {
    }

    /**
     * Canal : on force au moins "database" pour la cloche interne.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Optionnel : canal mail si on veut un doublon email aux admins.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $user = $this->user;

        return (new MailMessage)
            ->subject('Nouveau compte en attente de validation – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Un nouvel utilisateur a créé un compte et est en attente de validation.')
            ->line('Nom : ' . $user->name)
            ->when($user->email, fn ($mail) => $mail->line('Email : ' . $user->email))
            ->line('Date de création : ' . optional($user->created_at)->format('d/m/Y H:i'))
            ->action('Valider le compte', route('users.show', $user));
    }

    /**
     * Représentation en base de données (table notifications).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'new_user_pending_validation',
            'user_id'    => $this->user->id,
            'user_name'  => $this->user->name,
            'user_email' => $this->user->email,
            'message'    => 'Nouveau compte en attente de validation : ' . $this->user->name,
            'url'        => route('users.show', $this->user),
        ];
    }
}


