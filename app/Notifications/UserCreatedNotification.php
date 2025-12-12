<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification implements ShouldQueue
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
            ->subject('Création d’un compte utilisateur – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Un nouveau compte utilisateur a été créé dans le Système de Gestion des Réunions Statutaires de la CEEAC.')
            ->line('Nom : ' . $user->name)
            ->when($user->email, fn ($mail) => $mail->line('Email : ' . $user->email))
            ->line('Vous recevez cet email car vous êtes impliqué(e) dans la gestion des comptes ou utilisateur concerné.')
            ->line('Merci d’utiliser le SGRS-CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'user_created',
            'user_id' => $this->user->id,
            'message' => 'Un nouveau compte utilisateur a été créé : ' . $this->user->name,
        ];
    }
}








