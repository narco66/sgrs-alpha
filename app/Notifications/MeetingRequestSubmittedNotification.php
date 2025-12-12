<?php

namespace App\Notifications;

use App\Models\MeetingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingRequestSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public MeetingRequest $meetingRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $request = $this->meetingRequest;

        return (new MailMessage)
            ->subject('Nouvelle demande de réunion – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Une nouvelle demande de création de réunion a été soumise dans le Système de Gestion des Réunions Statutaires de la CEEAC.')
            ->line('Titre : ' . $request->title)
            ->when($request->meetingType, fn ($mail) => $mail->line('Type de réunion : ' . $request->meetingType->name))
            ->when($request->requested_start_at, fn ($mail) => $mail->line('Date demandée : ' . $request->requested_start_at->format('d/m/Y H:i')))
            ->line('Demandeur : ' . ($request->requester?->name ?? 'N/A'))
            ->action('Consulter la demande', route('meeting-requests.show', $request))
            ->line('Merci d\'utiliser le SGRS-CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'meeting_request_submitted',
            'meeting_request_id' => $this->meetingRequest->id,
            'title'         => $this->meetingRequest->title,
            'requested_by'  => $this->meetingRequest->requested_by,
            'status'        => $this->meetingRequest->status,
            'message'       => 'Nouvelle demande de réunion : ' . $this->meetingRequest->title,
        ];
    }
}





