<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification envoyée à l'utilisateur après auto-inscription
 * pour confirmer la prise en compte de sa demande.
 */
class UserRegistrationReceivedNotification extends Notification implements ShouldQueue
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
            ->subject('Votre demande de création de compte – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Votre demande de création de compte dans le Système de Gestion des Réunions Statutaires de la CEEAC a bien été enregistrée.')
            ->line('Votre compte est actuellement en attente de validation par un administrateur.')
            ->line('Vous serez informé(e) par email dès que votre compte sera validé ou rejeté.')
            ->line('Merci pour votre intérêt pour le SGRS-CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'user_registration_received',
            'user_id' => $this->user->id,
            'message' => 'Votre demande de création de compte a été enregistrée.',
        ];
    }
}


