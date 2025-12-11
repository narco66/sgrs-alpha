<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Document $document)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $document = $this->document;

        return (new MailMessage)
            ->subject('Nouveau document disponible – ' . $document->title)
            ->greeting('Bonjour ' . ($notifiable->first_name ?? $notifiable->name ?? ''))
            ->line('Un nouveau document a été ajouté au système SGRS-CEEAC.')
            ->line('**Titre :** ' . $document->title)
            ->when($document->type, function ($mail) use ($document) {
                return $mail->line('**Type :** ' . $document->type->name);
            })
            ->when($document->meeting, function ($mail) use ($document) {
                return $mail->line('**Réunion associée :** ' . $document->meeting->title);
            })
            ->line('**Ajouté par :** ' . ($document->uploader?->name ?? 'N/A'))
            ->line('**Date d\'ajout :** ' . $document->created_at?->format('d/m/Y à H:i'))
            ->action('Voir le document', route('documents.show', $document))
            ->line('Merci d\'utiliser le Système de Gestion des Réunions Statutaires de la CEEAC.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title'       => $this->document->title,
            'document_type' => $this->document->type?->name,
            'meeting_id'  => $this->document->meeting_id,
            'type_notif'  => 'document_added', // compatibilité ascendante
            'type'        => 'document_added',
            'message'     => 'Nouveau document : ' . $this->document->title,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}

