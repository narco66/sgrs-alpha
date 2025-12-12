<?php

namespace App\Notifications;

use App\Models\ParticipantRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipantRequestSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ParticipantRequest $participantRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $request = $this->participantRequest;

        return (new MailMessage)
            ->subject('Nouvelle demande d\'ajout de participant – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Une nouvelle demande d\'ajout de participant a été soumise pour la réunion "' . ($request->meeting?->title ?? 'N/A') . '".')
            ->line('Nom du participant : ' . $request->participant_name)
            ->when($request->participant_role, fn ($mail) => $mail->line('Rôle : ' . $request->participant_role))
            ->action('Consulter la demande', route('participant-requests.show', $request))
            ->line('Merci d\'utiliser le SGRS-CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'                 => 'participant_request_submitted',
            'participant_request_id' => $this->participantRequest->id,
            'meeting_id'           => $this->participantRequest->meeting_id,
            'participant_name'     => $this->participantRequest->participant_name,
            'status'               => $this->participantRequest->status,
            'message'              => 'Nouvelle demande d\'ajout de participant pour la réunion "' . ($this->participantRequest->meeting?->title ?? 'N/A') . '".',
        ];
    }
}







