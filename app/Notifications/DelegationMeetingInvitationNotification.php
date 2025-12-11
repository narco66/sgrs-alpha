<?php

namespace App\Notifications;

use App\Models\Meeting;
use App\Models\Delegation;
use App\Models\DelegationMember;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

/**
 * Notification d'invitation à une réunion pour les membres de délégation
 * Note: Pas de ShouldQueue pour envoi synchrone immédiat
 */
class DelegationMeetingInvitationNotification extends Notification
{
    use Queueable;

    public Meeting $meeting;
    public Delegation $delegation;
    public ?DelegationMember $member;

    public function __construct(Meeting $meeting, Delegation $delegation, ?DelegationMember $member = null)
    {
        $this->meeting = $meeting;
        $this->delegation = $delegation;
        $this->member = $member;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $meeting = $this->meeting;
        $delegation = $this->delegation;
        $member = $this->member;

        // Déterminer le nom du destinataire
        $recipientName = 'Madame, Monsieur';
        if ($member) {
            $recipientName = trim(($member->title ?? '') . ' ' . ($member->first_name ?? '') . ' ' . ($member->last_name ?? ''));
        } elseif (!empty($delegation->head_of_delegation_name)) {
            $recipientName = $delegation->head_of_delegation_name;
        }

        // Déterminer le rôle
        $role = 'Membre de la délégation';
        if ($member && $member->role === 'head') {
            $role = 'Chef de Délégation';
        } elseif (!$member && !empty($delegation->head_of_delegation_name)) {
            $role = 'Chef de Délégation';
        } elseif ($member) {
            $roleLabels = [
                'head' => 'Chef de Délégation',
                'member' => 'Membre',
                'expert' => 'Expert',
                'observer' => 'Observateur',
                'secretary' => 'Secrétaire',
                'advisor' => 'Conseiller',
                'interpreter' => 'Interprète',
            ];
            $role = $roleLabels[$member->role] ?? 'Membre';
        }

        $mail = (new MailMessage)
            ->subject('Convocation - ' . $meeting->title . ' - CEEAC')
            ->greeting('Bonjour ' . $recipientName . ',')
            ->line('Vous êtes cordialement invité(e) à participer à une réunion statutaire de la Communauté Économique des États de l\'Afrique Centrale (CEEAC).')
            ->line('---')
            ->line('**INFORMATIONS SUR LA RÉUNION**')
            ->line('**Titre :** ' . $meeting->title)
            ->line('**Type :** ' . ($meeting->meetingType?->name ?? 'Réunion statutaire'));

        // Date et heure
        if ($meeting->start_at) {
            $mail->line('**Date :** ' . $meeting->start_at->translatedFormat('l d F Y'));
            $mail->line('**Heure :** ' . $meeting->start_at->format('H:i'));
        }

        if ($meeting->end_at) {
            $mail->line('**Fin prévue :** ' . $meeting->end_at->format('H:i'));
        }

        // Lieu
        $lieu = $meeting->room?->name ?? $meeting->host_country ?? 'À confirmer';
        $mail->line('**Lieu :** ' . $lieu);

        $mail->line('---')
            ->line('**VOTRE PARTICIPATION**')
            ->line('**Délégation :** ' . ($delegation->title ?? $delegation->country ?? 'Non spécifiée'))
            ->line('**Votre rôle :** ' . $role);

        // Description si disponible
        if ($meeting->description) {
            $mail->line('---')
                ->line('**DESCRIPTION**')
                ->line(Str::limit($meeting->description, 300));
        }

        // Ordre du jour si disponible
        if ($meeting->agenda) {
            $mail->line('---')
                ->line('**ORDRE DU JOUR**')
                ->line(Str::limit($meeting->agenda, 500));
        }

        $mail->line('---')
            ->action('Voir les détails de la réunion', route('meetings.show', $meeting))
            ->line('Nous vous prions de bien vouloir confirmer votre participation dans les meilleurs délais.')
            ->line('---')
            ->salutation('Cordialement,<br>Le Secrétariat Général de la CEEAC<br>Système de Gestion des Réunions Statutaires');

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'meeting_id'    => $this->meeting->id,
            'delegation_id' => $this->delegation->id,
            'member_id'     => $this->member?->id,
            'title'         => $this->meeting->title,
            'start_at'      => $this->meeting->start_at?->toIso8601String(),
            'type'          => 'delegation_meeting_invitation',
            'message'       => 'Convocation envoyée pour la réunion : ' . $this->meeting->title,
        ];
    }
}







