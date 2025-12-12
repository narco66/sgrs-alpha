<?php

namespace App\Notifications;

use App\Models\MeetingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingRequestStatusUpdatedNotification extends Notification implements ShouldQueue
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
        $statusLabel = match ($request->status) {
            'approved' => 'approuvée',
            'rejected' => 'rejetée',
            default    => $request->status,
        };

        $mail = (new MailMessage)
            ->subject('Mise à jour de votre demande de réunion – SGRS-CEEAC')
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Votre demande de réunion "' . $request->title . '" a été ' . $statusLabel . '.');

        if ($request->review_comments) {
            $mail->line('Commentaires : ' . $request->review_comments);
        }

        if ($request->meeting) {
            $mail->action('Voir la réunion créée', route('meetings.show', $request->meeting));
        } else {
            $mail->action('Voir la demande', route('meeting-requests.show', $request));
        }

        return $mail->line('Merci d\'utiliser le SGRS-CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'               => 'meeting_request_status_updated',
            'meeting_request_id' => $this->meetingRequest->id,
            'title'              => $this->meetingRequest->title,
            'status'             => $this->meetingRequest->status,
            'message'            => 'Votre demande de réunion "' . $this->meetingRequest->title . '" a été ' . $this->meetingRequest->status . '.',
        ];
    }
}





