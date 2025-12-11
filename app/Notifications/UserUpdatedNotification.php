<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserUpdatedNotification extends Notification implements ShouldQueue
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
            ->subject('Mise à jour de votre profil – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Votre profil utilisateur a été mis à jour dans le Système de Gestion des Réunions Statutaires de la CEEAC.')
            ->line('Nom : ' . $user->name)
            ->when($user->email, fn ($mail) => $mail->line('Email : ' . $user->email))
            ->line('Si vous n’êtes pas à l’origine de cette modification, veuillez contacter l’administrateur du système.')
            ->line('Merci d’utiliser le SGRS-CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'user_updated',
            'user_id' => $this->user->id,
            'message' => 'Votre profil utilisateur a été mis à jour.',
        ];
    }
}




