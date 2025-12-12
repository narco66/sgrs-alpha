<?php

namespace App\Notifications;

use App\Models\ParticipantRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipantRequestStatusUpdatedNotification extends Notification implements ShouldQueue
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
        $statusLabel = match ($request->status) {
            'approved' => 'approuvée',
            'rejected' => 'rejetée',
            default    => $request->status,
        };

        $mail = (new MailMessage)
            ->subject('Mise à jour de votre demande de participant – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Votre demande d\'ajout de participant "' . $request->participant_name . '" pour la réunion "' . ($request->meeting?->title ?? '') . '" a été ' . $statusLabel . '.');

        if ($request->review_comments) {
            $mail->line('Commentaires : ' . $request->review_comments);
        }

        $mail->action('Voir la demande', route('participant-requests.show', $request));

        return $mail->line('Merci d\'utiliser le SGRS-CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'                  => 'participant_request_status_updated',
            'participant_request_id'=> $this->participantRequest->id,
            'meeting_id'            => $this->participantRequest->meeting_id,
            'participant_name'      => $this->participantRequest->participant_name,
            'status'                => $this->participantRequest->status,
            'message'               => 'Votre demande d\'ajout de participant "' . $this->participantRequest->participant_name . '" a été ' . $this->participantRequest->status . '.',
        ];
    }
}





