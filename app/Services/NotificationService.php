<?php

namespace App\Services;

use App\Events\DocumentSubmitted;
use App\Events\DocumentValidated;
use App\Events\MeetingCancelled;
use App\Events\MeetingCreated;
use App\Events\MeetingInvitationsRequested;
use App\Events\MeetingStatusChanged;
use App\Events\MeetingUpdated;
use App\Events\ParticipantRsvpUpdated;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Events\UserSelfRegistered;
use App\Services\AuditLogger;
use App\Models\Document;
use App\Models\DocumentValidation;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\User;
use App\Notifications\DelegationMeetingInvitationNotification;
use App\Notifications\DocumentAddedNotification;
use App\Notifications\DocumentValidationNotification;
use App\Notifications\MeetingCancellationNotification;
use App\Notifications\MeetingInvitationNotification;
use App\Notifications\MeetingReminderNotification;
use App\Notifications\ParticipantResponseReminderNotification;
use App\Notifications\UserCreatedNotification;
use App\Notifications\UserUpdatedNotification;
use App\Notifications\UserPendingApprovalNotification;
use App\Notifications\UserRegistrationReceivedNotification;
use App\Notifications\UserAccountApprovedNotification;
use App\Notifications\UserAccountRejectedNotification;
use App\Events\UserApproved;
use App\Events\UserRejected;
use App\Notifications\UserAccountApprovedAdminNotification;
use App\Notifications\NewUserPendingValidation;
use Illuminate\Support\Facades\Log;

/**
 * Service centralisé pour la gestion des notifications (emails + notifications internes).
 *
 * Objectifs :
 * - Point d'entrée unique pour toutes les notifications métier (EF40–EF43, UC19–UC24).
 * - Basé sur le pattern events → listeners → jobs (Notifications Laravel en file d'attente).
 * - Journalisation dans AuditLog pour les actions critiques.
 */
class NotificationService
{
    /**
     * Point d'entrée générique appelé par le listener principal.
     */
    public function handleEvent(object $event): void
    {
        try {
            match (true) {
                $event instanceof UserCreated              => $this->onUserCreated($event),
                $event instanceof UserUpdated              => $this->onUserUpdated($event),
                $event instanceof UserSelfRegistered       => $this->onUserSelfRegistered($event),
                $event instanceof UserApproved             => $this->onUserApproved($event),
                $event instanceof UserRejected             => $this->onUserRejected($event),
                $event instanceof MeetingCreated           => $this->onMeetingCreated($event),
                $event instanceof MeetingUpdated           => $this->onMeetingUpdated($event),
                $event instanceof MeetingCancelled         => $this->onMeetingCancelled($event),
                $event instanceof MeetingInvitationsRequested => $this->onMeetingInvitationsRequested($event),
                $event instanceof MeetingStatusChanged     => $this->onMeetingStatusChanged($event),
                $event instanceof ParticipantRsvpUpdated   => $this->onParticipantRsvpUpdated($event),
                $event instanceof DocumentSubmitted        => $this->onDocumentSubmitted($event),
                $event instanceof DocumentValidated        => $this->onDocumentValidated($event),
                default => null,
            };
        } catch (\Throwable $e) {
            Log::error('Erreur NotificationService::handleEvent', [
                'event' => get_class($event),
                'message' => $e->getMessage(),
            ]);
        }
    }

    /* =======================================================================
     |  UTILISATEURS
     |=======================================================================*/

    protected function onUserCreated(UserCreated $event): void
    {
        $user  = $event->user;
        $actor = $event->actor;

        // 1) Notifier le nouvel utilisateur (email + notification interne)
        if ($user instanceof User) {
            $user->notify(new UserCreatedNotification($user));
        }

        // 2) Informer les rôles DSI / admin
        $adminRecipients = User::role(['super-admin', 'admin', 'dsi'])->get();
        foreach ($adminRecipients as $recipient) {
            $recipient->notify(new UserCreatedNotification($user));
        }

        $this->logNotification('user_created', [
            'user_id'  => $user->id,
            'actor_id' => $actor?->id,
        ]);
    }

    /**
     * Inscription front-office d'un utilisateur (auto-inscription).
     *
     * - Le compte est créé en statut « en attente » (is_active = false)
     * - Tous les administrateurs sont notifiés pour valider / rejeter la demande
     * - L'utilisateur reçoit un accusé de réception
     * - Un log d'audit spécifique est écrit
     */
    protected function onUserSelfRegistered(UserSelfRegistered $event): void
    {
        $user = $event->user;

        // Notifier tous les rôles d'administration
        $adminRecipients = User::role(['super-admin', 'admin', 'dsi'])->get();
        foreach ($adminRecipients as $recipient) {
            // Notification interne pour la cloche + notification email existante si souhaitée
            $recipient->notify(new NewUserPendingValidation($user));
            $recipient->notify(new UserPendingApprovalNotification($user));
        }

        // Accusé de réception à l'utilisateur
        $user->notify(new UserRegistrationReceivedNotification($user));

        $this->logNotification('user_registration_requested', [
            'user_id' => $user->id,
        ]);
    }

    protected function onUserUpdated(UserUpdated $event): void
    {
        $user  = $event->user;
        $actor = $event->actor;

        // Notification à l'utilisateur pour l’informer d’une mise à jour de son profil.
        if ($user instanceof User) {
            $user->notify(new UserUpdatedNotification($user));
        }

        $this->logNotification('user_updated', [
            'user_id'  => $user->id,
            'actor_id' => $actor?->id,
        ]);
    }

    /**
     * Validation d'un compte utilisateur par un administrateur.
     */
    protected function onUserApproved(UserApproved $event): void
    {
        $user  = $event->user;
        $actor = $event->actor;

        // Notification à l'utilisateur validé
        $user->notify(new UserAccountApprovedNotification($user));

        // Récapitulatif pour l'administrateur ayant validé
        if ($actor instanceof User) {
            $actor->notify(new UserAccountApprovedAdminNotification($user, $actor));
        }

        $this->logNotification('user_account_approved', [
            'user_id'  => $user->id,
            'actor_id' => $actor?->id,
        ]);
    }

    /**
     * Rejet d'un compte utilisateur par un administrateur.
     */
    protected function onUserRejected(UserRejected $event): void
    {
        $user   = $event->user;
        $actor  = $event->actor;
        $reason = $event->reason;

        // Notification à l'utilisateur rejeté
        $user->notify(new UserAccountRejectedNotification($user, $reason));

        $this->logNotification('user_account_rejected', [
            'user_id'  => $user->id,
            'actor_id' => $actor?->id,
            'reason'   => $reason,
        ]);
    }

    /* =======================================================================
     |  REUNIONS
     |=======================================================================*/

    protected function onMeetingCreated(MeetingCreated $event): void
    {
        $meeting = $event->meeting;
        $actor   = $event->actor;

        // Notification interne à l'organisateur (rappel de création).
        if ($meeting->organizer) {
            $meeting->organizer->notify(new MeetingInvitationNotification($meeting));
        }

        $this->logNotification('meeting_created', [
            'meeting_id' => $meeting->id,
            'actor_id'   => $actor?->id,
        ]);
    }

    protected function onMeetingUpdated(MeetingUpdated $event): void
    {
        $meeting = $event->meeting;
        $actor   = $event->actor;

        // Notification interne simple à l'organisateur pour confirmer la modification.
        if ($meeting->organizer) {
            $meeting->organizer->notify(new MeetingReminderNotification($meeting));
        }

        $this->logNotification('meeting_updated', [
            'meeting_id' => $meeting->id,
            'actor_id'   => $actor?->id,
        ]);
    }

    protected function onMeetingCancelled(MeetingCancelled $event): void
    {
        $meeting = $event->meeting;
        $actor   = $event->actor;

        // Notifier les membres de délégations (si informations disponibles).
        $meeting->loadMissing(['delegations.members']);

        foreach ($meeting->delegations as $delegation) {
            foreach ($delegation->members as $member) {
                if (! empty($member->email)) {
                    // Cas "externe" : uniquement email
                    \Illuminate\Support\Facades\Notification::route('mail', [
                        $member->email => $member->full_name ?? $member->first_name ?? 'Participant',
                    ])->notify(new MeetingCancellationNotification($meeting));
                }
            }
        }

        // Notifier aussi l'organisateur en interne
        if ($meeting->organizer) {
            $meeting->organizer->notify(new MeetingCancellationNotification($meeting));
        }

        $this->logNotification('meeting_cancelled', [
            'meeting_id' => $meeting->id,
            'actor_id'   => $actor?->id,
        ]);
    }

    protected function onMeetingInvitationsRequested(MeetingInvitationsRequested $event): void
    {
        $meeting = $event->meeting;
        $actor   = $event->actor;

        $meeting->loadMissing(['delegations.members', 'meetingType', 'room']);

        $sentCount = 0;

        foreach ($meeting->delegations as $delegation) {
            // Chef de délégation
            if (! empty($delegation->head_of_delegation_email)) {
                \Illuminate\Support\Facades\Notification::route('mail', [
                    $delegation->head_of_delegation_email => $delegation->head_of_delegation_name ?? 'Chef de Délégation',
                ])->notify(new DelegationMeetingInvitationNotification($meeting, $delegation));
                $sentCount++;
            }

            // Membres
            foreach ($delegation->members as $member) {
                if (! empty($member->email)) {
                    \Illuminate\Support\Facades\Notification::route('mail', [
                        $member->email => $member->full_name ?? $member->first_name ?? 'Membre',
                    ])->notify(new DelegationMeetingInvitationNotification($meeting, $delegation, $member));
                    $sentCount++;
                }
            }
        }

        $this->logNotification('meeting_invitations_requested', [
            'meeting_id'      => $meeting->id,
            'actor_id'        => $actor?->id,
            'recipients_count'=> $sentCount,
            'source'          => $event->source,
        ]);
    }

    protected function onMeetingStatusChanged(MeetingStatusChanged $event): void
    {
        $meeting = $event->meeting;

        // Exemple : si passage à un statut d'archivage, notifier l'organisateur.
        if (in_array($event->newStatus, ['archived', 'archivee', 'archivée'], true) && $meeting->organizer) {
            $meeting->organizer->notify(new MeetingReminderNotification(
                new Meeting([
                    'title'       => 'Archivage de la réunion',
                    'description' => 'La réunion "' . $meeting->title . '" a été archivée.',
                ])
            ));
        }

        $this->logNotification('meeting_status_changed', [
            'meeting_id' => $meeting->id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
            'actor_id'   => $event->actor?->id,
        ]);
    }

    protected function onParticipantRsvpUpdated(ParticipantRsvpUpdated $event): void
    {
        $meeting     = $event->meeting;
        $participant = $event->participant;

        // Informer l'organisateur de la réponse d'un participant interne.
        if ($meeting->organizer && $participant->user) {
            $meeting->organizer->notify(
                new ParticipantResponseReminderNotification($meeting)
            );
        }

        $this->logNotification('participant_rsvp_updated', [
            'meeting_id'      => $meeting->id,
            'participant_id'  => $participant->id,
            'participant_user'=> $participant->user_id,
            'status'          => $participant->status,
        ]);
    }

    /* =======================================================================
     |  DOCUMENTS
     |=======================================================================*/

    protected function onDocumentSubmitted(DocumentSubmitted $event): void
    {
        $document = $event->document;
        $actor    = $event->actor;

        // Notifier l'organisateur de la réunion liée (si existante) qu'un document a été déposé.
        if ($document->meeting && $document->meeting->organizer) {
            $document->meeting->organizer->notify(new DocumentAddedNotification($document));
        }

        $this->logNotification('document_submitted', [
            'document_id' => $document->id,
            'actor_id'    => $actor?->id,
        ]);
    }

    protected function onDocumentValidated(DocumentValidated $event): void
    {
        $document   = $event->document;
        $validation = $event->validation;

        // 1) Toujours notifier l'uploader (auteur) du document (chaîne complète).
        if ($document->uploader) {
            $document->uploader->notify(new DocumentValidationNotification($document, $validation));
        }

        // 2) Notifier le niveau suivant de validation en respectant la chaîne
        //    Chef de Département (protocole) → SG → Président.
        $nextLevel = $this->nextValidationLevel($validation->validation_level, $validation->status);

        if ($nextLevel !== null) {
            $recipients = $this->recipientsForValidationLevel($nextLevel);

            foreach ($recipients as $user) {
                $user->notify(new DocumentValidationNotification($document, $validation));
            }
        }

        $this->logNotification('document_validated', [
            'document_id'      => $document->id,
            'validation_id'    => $validation->id,
            'validation_level' => $validation->validation_level,
            'status'           => $validation->status,
        ]);
    }

    /**
     * Détermine le prochain niveau dans la chaîne de validation en fonction
     * du niveau actuel et du statut.
     */
    protected function nextValidationLevel(string $currentLevel, string $status): ?string
    {
        if ($status !== 'approved') {
            // En cas de rejet, on ne passe pas au niveau suivant.
            return null;
        }

        return match ($currentLevel) {
            'protocole' => 'sg',
            'sg'        => 'president',
            default     => null,
        };
    }

    /**
     * Retourne les utilisateurs cibles pour un niveau de validation donné.
     *
     * Mapping approximatif (à ajuster si des rôles spécifiques sont ajoutés) :
     * - protocole  → utilisateurs avec permission documents.validate (hors SG/Président)
     * - sg         → rôle "sg"
     * - president  → rôle "super-admin" (représente le Président dans ce contexte)
     */
    protected function recipientsForValidationLevel(string $level)
    {
        return match ($level) {
            'protocole' => User::permission('documents.validate')
                ->whereDoesntHave('roles', function ($q) {
                    $q->whereIn('name', ['sg', 'super-admin']);
                })->get(),
            'sg'        => User::role('sg')->get(),
            'president' => User::role('super-admin')->get(),
            default     => collect(),
        };
    }

    /* =======================================================================
     |  LOGGING
     |=======================================================================*/

    protected function logNotification(string $event, array $meta = []): void
    {
        AuditLogger::log(
            event: $event,
            target: null,
            old: null,
            new: null,
            meta: $meta
        );
    }
}


