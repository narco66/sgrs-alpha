<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\DocumentValidation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentValidationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Document $document,
        public DocumentValidation $validation
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $document = $this->document;
        $validation = $this->validation;

        $statusLabel = match($validation->status) {
            'approved' => 'approuvé',
            'rejected' => 'rejeté',
            default => 'en attente',
        };

        return (new MailMessage)
            ->subject('Validation de document – ' . $document->title)
            ->greeting('Bonjour ' . ($notifiable->name ?? ''))
            ->line('Le document "' . $document->title . '" a été ' . $statusLabel . ' au niveau ' . $validation->level_label . '.')
            ->when($validation->comments, function ($mail) use ($validation) {
                return $mail->line('Commentaires : ' . $validation->comments);
            })
            ->action('Voir le document', route('documents.show', $document))
            ->line('Merci d\'utiliser le Système de Gestion des Réunions Statutaires de la CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title' => $this->document->title,
            'validation_level' => $this->validation->validation_level,
            'status' => $this->validation->status,
            'type' => 'document_validation',
            'message' => 'Validation du document "' . $this->document->title . '" au niveau ' . $this->validation->level_label . ' : ' . $this->validation->status_label,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}

