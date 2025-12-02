<?php

namespace App\Http\Controllers;

use App\Enums\MeetingStatus;
use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Models\Committee;
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\Room;
use App\Models\User;
use App\Notifications\MeetingCancellationNotification;
use App\Notifications\MeetingInvitationNotification;
use App\Services\MeetingWorkflowService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Gate;

class MeetingController extends Controller
{
    public function __construct(
        protected MeetingWorkflowService $workflowService
    ) {
        // Liaison automatique des policies : viewAny, view, create, update, delete
        $this->authorizeResource(Meeting::class, 'meeting');
    }

    /**
     * Liste des réunions (vue liste) avec filtres classiques.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Meeting::class);

        $search      = $request->get('q');
        $typeId      = $request->get('meeting_type_id');
        $committeeId = $request->get('committee_id');
        $status      = $request->get('status');
        $dateFrom    = $request->get('date_from');
        $dateTo      = $request->get('date_to');

        $meetingTypes = MeetingType::orderBy('sort_order')->orderBy('name')->get();
        $committees   = Committee::orderBy('sort_order')->orderBy('name')->get();

        $meetings = Meeting::with(['type', 'committee', 'room', 'organizer', 'participants'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('agenda', 'like', "%{$search}%");
                });
            })
            ->when($typeId, fn ($q) => $q->where('meeting_type_id', $typeId))
            ->when($committeeId, fn ($q) => $q->where('committee_id', $committeeId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($dateFrom, fn ($q) => $q->whereDate('start_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('start_at', '<=', $dateTo))
            ->orderBy('created_at', 'desc')
            ->orderBy('start_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        return view('meetings.index', [
            'meetings'     => $meetings,
            'meetingTypes' => $meetingTypes,
            'committees'   => $committees,
            'filters'      => [
                'q'               => $search,
                'meeting_type_id' => $typeId,
                'committee_id'    => $committeeId,
                'status'          => $status,
                'date_from'       => $dateFrom,
                'date_to'         => $dateTo,
            ],
        ]);
    }

    /**
     * Formulaire de création d'une nouvelle réunion.
     */
    public function create()
    {
        $meetingTypes = MeetingType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $committees = Committee::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $rooms = Room::where('is_active', true)
            ->orderBy('name')
            ->get();

        $users = User::orderBy('name')->get();
        
        // EF20 - Comités d'organisation disponibles (non assignés)
        $availableCommittees = \App\Models\OrganizationCommittee::where('is_active', true)
            ->whereNull('meeting_id')
            ->orderBy('name')
            ->get();

        return view('meetings.create', [
            'meeting'      => new Meeting(),
            'meetingTypes' => $meetingTypes,
            'committees'   => $committees,
            'rooms'        => $rooms,
            'users'        => $users,
            'availableCommittees' => $availableCommittees,
        ]);
    }

    /**
     * Enregistrement d'une nouvelle réunion.
     * EF37 - Réservation de salles avec vérification de disponibilité
     */
    public function store(StoreMeetingRequest $request)
    {
        $data = $request->validated();

        // Construire les timestamps à partir de date + time si start_at/end_at ne sont pas fournis
        $durationMinutes = isset($data['duration_minutes'])
            ? (int) $data['duration_minutes']
            : 60;

        $startAt = !empty($data['start_at'])
            ? Carbon::parse($data['start_at'])
            : Carbon::parse(($data['date'] ?? now()->toDateString()) . ' ' . ($data['time'] ?? '00:00'));

        $endAt = !empty($data['end_at'])
            ? Carbon::parse($data['end_at'])
            : $startAt->copy()->addMinutes($durationMinutes);

        // Vérifier la disponibilité de la salle si une salle est sélectionnée
        if (!empty($data['room_id'])) {
            $room = Room::findOrFail($data['room_id']);
            if (!$room->isAvailableFor($startAt, $endAt)) {
                return back()
                    ->withInput()
                    ->withErrors(['room_id' => 'Cette salle n\'est pas disponible pour le créneau sélectionné. Veuillez choisir une autre salle ou un autre créneau.']);
            }
        }

        $meeting = Meeting::create([
            'title'                   => $data['title'],
            'meeting_type_id'         => $data['meeting_type_id'] ?? null,
            'committee_id'            => $data['committee_id'] ?? null,
            'room_id'                 => $data['room_id'] ?? null,
            'start_at'                => $startAt,
            'end_at'                  => $data['end_at'] ?? $endAt,
            'duration_minutes'        => $data['duration_minutes'] ?? null,
            'status'                  => $data['status'] ?? MeetingStatus::DRAFT->value,
            'description'             => $data['description'] ?? null,
            'agenda'                  => $data['agenda'] ?? null,
            'organizer_id'            => Auth::id(),
            'reminder_minutes_before' => $data['reminder_minutes_before'] ?? 0,
        ]);

        // Gestion avancée des participants (si un tableau participants[] est transmis)
        if (! empty($data['participants'] ?? null)) {
            // $data['participants'] est un tableau d'IDs d'utilisateurs
            $meeting->participantsUsers()->sync($data['participants']);
        }

        // EF20 - Assignation d'un comité d'organisation
        if (!empty($data['organization_committee_id'] ?? null)) {
            $committee = \App\Models\OrganizationCommittee::find($data['organization_committee_id']);
            if ($committee) {
                $committee->update(['meeting_id' => $meeting->id]);
            }
        }

        // EF40 - Envoi des invitations par email et notification interne
        $this->sendMeetingInvitations($meeting);

        return redirect()
            ->route('meetings.show', $meeting)
            ->with('success', 'La réunion a été créée avec succès. Les invitations ont été envoyées aux participants.');
    }

    /**
     * Affichage détaillé d'une réunion.
     */
    public function show(Meeting $meeting)
    {
        // Chargement des relations de base
        $meeting->load([
            'type',
            'committee',
            'room',
            'organizer',
            'participants.user',
            'delegations',
            'documents.type',
            'documents.uploader',
            'organizationCommittee.members.user', // EF20 - Comité d'organisation
        ]);

        // Alias pour que la vue puisse utiliser $meeting->creator
        // tout en conservant la relation organizer() dans le modèle.
        $meeting->setRelation('creator', $meeting->organizer);

        // Chargement de l'historique des statuts de manière alignée avec la vue
        $histories = collect();

        // Priorité : historique MeetingStatusHistory (old_status/new_status/comment/user)
        if (method_exists($meeting, 'meetingStatusHistories')) {
            try {
                $meeting->load(['meetingStatusHistories.user']);
                $histories = $meeting->meetingStatusHistories()->orderByDesc('created_at')->get();
            } catch (\Exception $e) {
                $histories = collect();
            }
        }

        // Alternative : ancien modèle StatusHistory (compatibilité)
        if ($histories->isEmpty() && method_exists($meeting, 'statusHistories')) {
            try {
                $meeting->load(['statusHistories.user']);
                $histories = $meeting->statusHistories()->orderByDesc('created_at')->get();
            } catch (\Exception $e) {
                $histories = collect();
            }
        }

        // Pour d'éventuels menus déroulants de statut (enum)
        $availableStatuses = MeetingStatus::cases();

        return view('meetings.show', [
            'meeting'           => $meeting,
            'availableStatuses' => $availableStatuses,
            'histories'         => $histories,
        ]);
    }

    /**
     * Formulaire d'édition d'une réunion.
     */
    public function edit(Meeting $meeting)
    {
        $meetingTypes = MeetingType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $committees = Committee::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $rooms = Room::where('is_active', true)
            ->orderBy('name')
            ->get();

        $meeting->load(['participants.user', 'participantsUsers', 'organizer']);
        // On fournit aussi creator pour rester cohérent avec la vue si nécessaire
        $meeting->setRelation('creator', $meeting->organizer);

        $users = User::orderBy('name')->get();

        return view('meetings.edit', [
            'meeting'      => $meeting,
            'meetingTypes' => $meetingTypes,
            'committees'   => $committees,
            'rooms'        => $rooms,
            'users'        => $users,
        ]);
    }

    /**
     * Mise à jour d'une réunion existante.
     * EF37 - Réservation de salles avec vérification de disponibilité
     */
    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        $data = $request->validated();

        // Vérifier la disponibilité de la salle si une salle est sélectionnée et que la date/heure change
        if (!empty($data['room_id']) && ($data['room_id'] != $meeting->room_id || isset($data['start_at']))) {
            $room = Room::findOrFail($data['room_id']);
            $startAt = \Carbon\Carbon::parse($data['start_at'] ?? $meeting->start_at);
            $endAt = $data['end_at'] 
                ? \Carbon\Carbon::parse($data['end_at'])
                : ($meeting->end_at 
                    ? \Carbon\Carbon::parse($meeting->end_at)
                    : $startAt->copy()->addMinutes((int) ($data['duration_minutes'] ?? $meeting->duration_minutes ?? 60)));

            if (!$room->isAvailableFor($startAt, $endAt, $meeting->id)) {
                return back()
                    ->withInput()
                    ->withErrors(['room_id' => 'Cette salle n\'est pas disponible pour le créneau sélectionné. Veuillez choisir une autre salle ou un autre créneau.']);
            }
        }

        $meeting->update([
            'title'                   => $data['title'],
            'meeting_type_id'         => $data['meeting_type_id'] ?? null,
            'committee_id'            => $data['committee_id'] ?? null,
            'room_id'                 => $data['room_id'] ?? null,
            'start_at'                => $data['start_at'],
            'end_at'                  => $data['end_at'] ?? null,
            'duration_minutes'        => $data['duration_minutes'] ?? null,
            'status'                  => $data['status'] ?? $meeting->status,
            'description'             => $data['description'] ?? null,
            'agenda'                  => $data['agenda'] ?? null,
            'reminder_minutes_before' => $data['reminder_minutes_before'] ?? $meeting->reminder_minutes_before,
        ]);

        if (array_key_exists('participants', $data)) {
            $oldParticipants = $meeting->participantsUsers->pluck('id')->toArray();
            $newParticipants = $data['participants'] ?? [];
            $meeting->participantsUsers()->sync($newParticipants);
            
            // Envoyer des invitations aux nouveaux participants
            $newParticipantIds = array_diff($newParticipants, $oldParticipants);
            if (!empty($newParticipantIds)) {
                $newUsers = User::whereIn('id', $newParticipantIds)->get();
                foreach ($newUsers as $user) {
                    $user->notify(new MeetingInvitationNotification($meeting));
                }
            }
        }

        return redirect()
            ->route('meetings.show', $meeting)
            ->with('success', 'La réunion a été mise à jour avec succès.');
    }

    /**
     * Suppression (soft delete) d'une réunion.
     * EF16 - Annulation d'une réunion avec notification automatique
     */
    public function destroy(Meeting $meeting)
    {
        // EF40 - Notification d'annulation aux participants
        $meeting->load(['participants.user', 'participantsUsers']);
        
        foreach ($meeting->participants as $participant) {
            if ($participant->user) {
                $participant->user->notify(new MeetingCancellationNotification($meeting));
            }
        }
        
        if ($meeting->relationLoaded('participantsUsers')) {
            foreach ($meeting->participantsUsers as $user) {
                $user->notify(new MeetingCancellationNotification($meeting));
            }
        }

        $meeting->delete();

        return redirect()
            ->route('meetings.index')
            ->with('success', 'La réunion a été supprimée. Les participants ont été notifiés.');
    }

    /**
     * Changement de statut de la réunion (workflow).
     * Route typique : POST /meetings/{meeting}/status
     */
    public function changeStatus(Request $request, Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        $request->validate([
            'status'  => ['required', 'string'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $newStatus = MeetingStatus::from($request->input('status'));
        $comment   = $request->input('comment');

        // MeetingWorkflowService gère les règles, l'historique et les notifications
        $this->workflowService->changeStatus(
            meeting: $meeting,
            newStatus: $newStatus,
            user: Auth::user(),
            comment: $comment
        );

        return redirect()
            ->route('meetings.show', $meeting)
            ->with('success', 'Le statut de la réunion a été mis à jour.');
    }

    /**
     * Vue Calendrier avec filtrage rapide : jour / mois / année.
     *
     * Paramètres :
     * - view = 'day' | 'month' | 'year' (défaut : 'month')
     * - date = base au format YYYY-MM-DD (défaut : aujourd'hui)
     */
    public function calendar(Request $request)
    {
        $this->authorize('viewAny', Meeting::class);

        $view      = $request->get('view', 'month'); // day|month|year
        $dateParam = $request->get('date', Carbon::today()->toDateString());

        try {
            $baseDate = Carbon::parse($dateParam);
        } catch (\Exception $e) {
            $baseDate = Carbon::today();
        }

        // Détermination de la plage de dates selon la vue
        switch ($view) {
            case 'day':
                $start = $baseDate->copy()->startOfDay();
                $end   = $baseDate->copy()->endOfDay();
                break;

            case 'year':
                $start = $baseDate->copy()->startOfYear();
                $end   = $baseDate->copy()->endOfYear();
                break;

            case 'month':
            default:
                $view  = 'month';
                $start = $baseDate->copy()->startOfMonth();
                $end   = $baseDate->copy()->endOfMonth();
                break;
        }

        $meetings = Meeting::with(['type', 'committee', 'room'])
            ->whereBetween('start_at', [$start, $end])
            ->orderBy('start_at')
            ->get();

        return view('calendar.index', [
            'view'      => $view,
            'baseDate'  => $baseDate,
            'startDate' => $start,
            'endDate'   => $end,
            'meetings'  => $meetings,
        ]);
    }

    /**
     * Export d'une r��union en PDF (détails, comit��, membres, documents).
     */
    public function exportPdf(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        $meeting->load([
            'type',
            'committee',
            'room',
            'organizer',
            'participants.user',
            'participantsUsers',
            'delegations.users',
            'documents.type',
            'organizationCommittee.members.user',
        ]);

        // Fusionner les participants pour �viter les doublons entre l'ancienne et la nouvelle mod��lisation.
        $participants = collect()
            ->concat($meeting->participants->pluck('user'))
            ->concat($meeting->participantsUsers)
            ->filter()
            ->unique('id');

        $pdf = Pdf::loadView('meetings.pdf', [
            'meeting'       => $meeting,
            'participants'  => $participants,
            'delegations'   => $meeting->delegations,
        ])->setPaper('A4', 'portrait');

        $fileName = 'reunion-' . ($meeting->slug ?? $meeting->id) . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Envoi manuel d'une notification par email aux participants.
     */
    public function notifyParticipants(Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        // Charger les participants (anciennes et nouvelles relations)
        $meeting->loadMissing(['participants.user', 'participantsUsers', 'type', 'room']);

        $pivotUsers = $meeting->relationLoaded('participantsUsers')
            ? $meeting->participantsUsers
            : $meeting->participantsUsers()->get();

        $participantUsers = $meeting->relationLoaded('participants')
            ? $meeting->participants->pluck('user')->filter()
            : $meeting->participants()->with('user')->get()->pluck('user')->filter();

        $recipients = $pivotUsers
            ->concat($participantUsers)
            ->filter()
            ->unique('id');

        if ($recipients->isEmpty()) {
            return back()->with('error', 'Aucun participant à notifier.');
        }

        Notification::send($recipients, new MeetingInvitationNotification($meeting));

        return back()->with('success', 'Les convocations ont été envoyées aux participants par email.');
    }

    /**
     * Envoi des invitations aux participants (ancienne et nouvelle modélisation).
     */
    protected function sendMeetingInvitations(Meeting $meeting): void
    {
        // On charge ce dont les notifications ont besoin (utilisateurs + métadonnées de réunion).
        $meeting->loadMissing(['participants.user', 'participantsUsers', 'type', 'room']);

        // Participants via la table pivot participants_reunions (nouveau schéma).
        $pivotUsers = $meeting->relationLoaded('participantsUsers')
            ? $meeting->participantsUsers
            : $meeting->participantsUsers()->get();

        // Participants via le modèle MeetingParticipant (schéma historique).
        $participantUsers = $meeting->relationLoaded('participants')
            ? $meeting->participants->pluck('user')->filter()
            : $meeting->participants()->with('user')->get()->pluck('user')->filter();

        // Merge + déduplication par id.
        $recipients = $pivotUsers
            ->concat($participantUsers)
            ->filter()
            ->unique('id');

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new MeetingInvitationNotification($meeting));
        }
    }
}
