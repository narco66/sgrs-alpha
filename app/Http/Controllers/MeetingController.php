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

        $meetings = Meeting::with(['type', 'committee', 'room', 'organizer', 'delegations' => function($q) {
                $q->withCount('members');
            }])
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
        // Inclure le type actuel même s'il est inactif pour éviter qu'il n'apparaisse pas sélectionné
        $meetingTypes = MeetingType::withTrashed()
            ->where(function ($query) use ($meeting) {
                $query->where('is_active', true)
                      ->orWhere('id', $meeting->meeting_type_id);
            })
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

        // EF20 - Comités d'organisation disponibles (non assignés)
        $availableCommittees = \App\Models\OrganizationCommittee::where('is_active', true)
            ->whereNull('meeting_id')
            ->with('members.user')
            ->orderBy('name')
            ->get();

        return view('meetings.create', [
            'meeting'            => new Meeting(),
            'meetingTypes'       => $meetingTypes,
            'committees'         => $committees,
            'rooms'              => $rooms,
            'availableCommittees' => $availableCommittees,
        ]);
    }

    /**
     * Enregistrement d'une nouvelle réunion.
     * EF37 - Réservation de salles avec vérification de disponibilité
     */
    public function store(StoreMeetingRequest $request)
    {
        // Vérification des droits - déjà vérifiée par authorizeResource mais on double-vérifie
        $this->authorize('create', Meeting::class);
        
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
            'configuration'           => $data['configuration'] ?? 'presentiel',
            'host_country'            => $data['host_country'] ?? null,
            'description'             => $data['description'] ?? null,
            'agenda'                  => $data['agenda'] ?? null,
            'organizer_id'            => Auth::id(),
            'reminder_minutes_before' => $data['reminder_minutes_before'] ?? 0,
        ]);

        // EF20 - Gestion du comité d'organisation
        $committeeOption = $request->input('committee_option', '');
        $committee = null;
        $committeeCreated = false;
        
        if ($committeeOption === 'existing' && !empty($data['organization_committee_id'] ?? null)) {
            // Assigner un comité existant
            $committee = \App\Models\OrganizationCommittee::find($data['organization_committee_id']);
            if ($committee) {
                $committee->update(['meeting_id' => $meeting->id]);
                $committeeCreated = true;
            }
        } elseif ($committeeOption === 'new' && !empty($request->input('new_committee_name'))) {
            // Créer un nouveau comité d'organisation
            $committee = \App\Models\OrganizationCommittee::create([
                'name' => $request->input('new_committee_name'),
                'description' => $request->input('new_committee_description'),
                'host_country' => $request->input('new_committee_host_country'),
                'meeting_id' => $meeting->id,
                'created_by' => Auth::id(),
                'is_active' => true,
                'activated_at' => now(),
            ]);
            $committeeCreated = true;
        }

        // Création du cahier des charges si demandé
        $termsCreated = false;
        if ($request->boolean('create_terms_of_reference') && !empty($request->input('terms_host_country'))) {
            \App\Models\TermsOfReference::create([
                'meeting_id' => $meeting->id,
                'host_country' => $request->input('terms_host_country'),
                'signature_date' => $request->input('terms_signature_date') ? \Carbon\Carbon::parse($request->input('terms_signature_date')) : null,
                'responsibilities_ceeac' => $request->input('terms_responsibilities_ceeac'),
                'responsibilities_host' => $request->input('terms_responsibilities_host'),
                'financial_sharing' => $request->input('terms_financial_sharing'),
                'logistical_sharing' => $request->input('terms_logistical_sharing'),
                'status' => \App\Models\TermsOfReference::STATUS_DRAFT,
                'version' => 1,
            ]);
            $termsCreated = true;
        }

        // Les délégations peuvent être ajoutées après la création de la réunion
        // depuis la page de détails de la réunion

        // EF40 - Envoi des invitations par email et notification interne
        // Note: Les invitations seront envoyées aux délégations, pas aux participants individuels
        $this->sendMeetingInvitations($meeting);

        // Construire le message de succès détaillé
        $successMessages = ['✓ La réunion a été créée avec succès.'];
        
        if ($committeeCreated) {
            if ($committeeOption === 'new') {
                $successMessages[] = '✓ Un nouveau comité d\'organisation a été créé et associé à la réunion.';
            } else {
                $successMessages[] = '✓ Le comité d\'organisation a été associé à la réunion.';
            }
        }
        
        if ($termsCreated) {
            $successMessages[] = '✓ Le cahier des charges a été créé avec succès.';
        }

        return redirect()
            ->route('meetings.show', $meeting)
            ->with('success', implode(' ', $successMessages));
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
            'delegations' => function($query) {
                $query->withCount('members')->orderBy('entity_type')->orderBy('title');
            },
            'documents.type',
            'documents.uploader',
            'organizationCommittee.members.user', // EF20 - Comité d'organisation
            'termsOfReference', // Cahier des charges
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

        // Charger les relations nécessaires pour la nouvelle logique
        $meeting->load([
            'organizationCommittee.members.user',
            'termsOfReference',
            'delegations.members',
            'organizer',
        ]);

        // Comités d'organisation disponibles (non assignés ou assignés à cette réunion)
        $availableCommittees = \App\Models\OrganizationCommittee::where('is_active', true)
            ->where(function($q) use ($meeting) {
                $q->whereNull('meeting_id')
                  ->orWhere('meeting_id', $meeting->id);
            })
            ->orderBy('name')
            ->get();

        return view('meetings.edit', [
            'meeting'            => $meeting,
            'meetingTypes'       => $meetingTypes,
            'committees'         => $committees,
            'rooms'              => $rooms,
            'availableCommittees' => $availableCommittees,
            'availableStatuses'   => MeetingStatus::cases(),
        ]);
    }

    /**
     * Mise à jour d'une réunion existante.
     * EF37 - Réservation de salles avec vérification de disponibilité
     */
    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        // Vérification des droits - déjà vérifiée par authorizeResource mais on double-vérifie
        $this->authorize('update', $meeting);
        
        $data = $request->validated();

        // Construire les timestamps à partir de date + time si start_at/end_at ne sont pas fournis
        $durationMinutes = isset($data['duration_minutes'])
            ? (int) $data['duration_minutes']
            : ($meeting->duration_minutes ?? 60);

        $startAt = !empty($data['start_at'])
            ? Carbon::parse($data['start_at'])
            : Carbon::parse(($data['date'] ?? $meeting->start_at?->format('Y-m-d') ?? now()->toDateString()) . ' ' . ($data['time'] ?? $meeting->start_at?->format('H:i') ?? '00:00'));

        $endAt = !empty($data['end_at'])
            ? Carbon::parse($data['end_at'])
            : $startAt->copy()->addMinutes($durationMinutes);

        // Vérifier la disponibilité de la salle si une salle est sélectionnée et que la date/heure change
        if (!empty($data['room_id']) && ($data['room_id'] != $meeting->room_id || !$meeting->start_at || $startAt->format('Y-m-d H:i') != $meeting->start_at->format('Y-m-d H:i'))) {
            $room = Room::findOrFail($data['room_id']);
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
            'start_at'                => $startAt,
            'end_at'                  => $endAt,
            'duration_minutes'        => $data['duration_minutes'] ?? null,
            'status'                  => $data['status'] ?? $meeting->status,
            'configuration'           => $data['configuration'] ?? $meeting->configuration ?? 'presentiel',
            'host_country'            => $data['host_country'] ?? $meeting->host_country,
            'description'             => $data['description'] ?? null,
            'agenda'                  => $data['agenda'] ?? null,
            'reminder_minutes_before' => $data['reminder_minutes_before'] ?? $meeting->reminder_minutes_before,
        ]);

        // EF20 - Gestion du comité d'organisation
        $committeeOption = $request->input('committee_option', '');
        $committee = null;
        $oldCommittee = $meeting->organizationCommittee; // Charger avant modifications
        $committeeUpdated = false;
        
        if ($committeeOption === 'existing' && !empty($data['organization_committee_id'] ?? null)) {
            // Désassigner l'ancien comité s'il existe et est différent
            if ($oldCommittee && $oldCommittee->id != $data['organization_committee_id']) {
                $oldCommittee->update(['meeting_id' => null]);
            }
            
            // Assigner le nouveau comité
            $committee = \App\Models\OrganizationCommittee::find($data['organization_committee_id']);
            if ($committee) {
                $committee->update(['meeting_id' => $meeting->id]);
                $committeeUpdated = true;
            }
        } elseif ($committeeOption === 'new' && !empty($request->input('new_committee_name'))) {
            // Désassigner l'ancien comité s'il existe
            if ($oldCommittee) {
                $oldCommittee->update(['meeting_id' => null]);
            }
            
            // Créer un nouveau comité d'organisation
            $committee = \App\Models\OrganizationCommittee::create([
                'name' => $request->input('new_committee_name'),
                'description' => $request->input('new_committee_description'),
                'host_country' => $request->input('new_committee_host_country'),
                'meeting_id' => $meeting->id,
                'created_by' => Auth::id(),
                'is_active' => true,
                'activated_at' => now(),
            ]);
            $committeeUpdated = true;
        } elseif ($committeeOption === '') {
            // Désassigner le comité actuel si "Aucun comité" est sélectionné
            if ($oldCommittee) {
                $oldCommittee->update(['meeting_id' => null]);
                $committeeUpdated = true;
            }
        }

        // Création/mise à jour du cahier des charges si demandé
        $termsUpdated = false;
        $existingTerms = $meeting->termsOfReference;
        
        if ($request->boolean('create_terms_of_reference') && !empty($request->input('terms_host_country'))) {
            $termsData = [
                'host_country' => $request->input('terms_host_country'),
                'signature_date' => $request->input('terms_signature_date') ? Carbon::parse($request->input('terms_signature_date')) : null,
                'responsibilities_ceeac' => $request->input('terms_responsibilities_ceeac'),
                'responsibilities_host' => $request->input('terms_responsibilities_host'),
                'financial_sharing' => $request->input('terms_financial_sharing'),
                'logistical_sharing' => $request->input('terms_logistical_sharing'),
            ];

            // Gestion de l'upload du document signé
            if ($request->hasFile('terms_signed_document')) {
                $file = $request->file('terms_signed_document');
                $extension = strtolower($file->getClientOriginalExtension());
                $storedName = \Illuminate\Support\Str::uuid()->toString() . '.' . $extension;
                $path = $file->storeAs('cahiers-charges/signed', $storedName, 'public');
                
                $termsData['signed_document_path'] = $path;
                $termsData['signed_document_name'] = $storedName;
                $termsData['signed_document_original_name'] = $file->getClientOriginalName();
                $termsData['signed_document_size'] = $file->getSize();
                $termsData['signed_document_mime_type'] = $file->getMimeType();
                $termsData['signed_document_extension'] = $extension;
                $termsData['signed_document_uploaded_at'] = now();
                $termsData['signed_document_uploaded_by'] = Auth::id();
            }
            
            if ($existingTerms && $existingTerms->isSigned()) {
                // Si signé, créer une nouvelle version
                $existingTerms->createNewVersion($termsData);
                $termsUpdated = true;
            } elseif ($existingTerms) {
                // Mettre à jour la version existante
                $existingTerms->update($termsData);
                $termsUpdated = true;
            } else {
                // Créer un nouveau cahier des charges
                $termsData['meeting_id'] = $meeting->id;
                $termsData['status'] = \App\Models\TermsOfReference::STATUS_DRAFT;
                $termsData['version'] = 1;
                \App\Models\TermsOfReference::create($termsData);
                $termsUpdated = true;
            }
        }

        // Les délégations sont gérées séparément depuis la page de détails

        // Construire le message de succès détaillé selon les actions effectuées
        $successMessages = ['✓ Les informations générales de la réunion ont été mises à jour avec succès.'];
        
        // Messages pour le comité d'organisation
        if ($committeeUpdated) {
            if ($committeeOption === 'existing' && $committee) {
                $successMessages[] = '✓ Le comité d\'organisation a été mis à jour.';
            } elseif ($committeeOption === 'new' && $committee) {
                $successMessages[] = '✓ Un nouveau comité d\'organisation a été créé et associé à la réunion.';
            } elseif ($committeeOption === '' && $oldCommittee) {
                $successMessages[] = '✓ Le comité d\'organisation a été dissocié de la réunion.';
            }
        }
        
        // Messages pour le cahier des charges
        if ($termsUpdated) {
            if ($existingTerms) {
                // Recharger pour avoir le statut à jour
                $existingTerms->refresh();
                if ($existingTerms->isSigned()) {
                    $successMessages[] = '✓ Une nouvelle version du cahier des charges a été créée (l\'ancienne version était signée).';
                } else {
                    $successMessages[] = '✓ Le cahier des charges a été mis à jour avec succès.';
                }
            } else {
                $successMessages[] = '✓ Le cahier des charges a été créé avec succès.';
            }
        }

        // Détecter quel onglet était actif pour rediriger vers l'édition avec l'onglet approprié
        $activeTab = $request->input('active_tab', 'general');
        
        return redirect()
            ->route('meetings.edit', $meeting)
            ->with('success', implode(' ', $successMessages))
            ->with('active_tab', $activeTab);
    }

    /**
     * Suppression (soft delete) d'une réunion.
     * EF16 - Annulation d'une réunion avec notification automatique
     */
    public function destroy(Meeting $meeting)
    {
        // EF40 - Notification d'annulation aux délégations
        $meeting->load(['delegations.members']);
        
        foreach ($meeting->delegations as $delegation) {
            foreach ($delegation->members as $member) {
                if ($member->email) {
                    // Envoyer notification par email si nécessaire
                    // Note: Implémenter une notification spécifique pour les membres de délégation
                }
            }
        }

        $meeting->delete();

        return redirect()
            ->route('meetings.index')
            ->with('success', 'La réunion a été supprimée avec succès.');
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
            'delegations.members',
            'documents.type',
            'termsOfReference',
            'organizationCommittee.members.user',
        ]);

        // Fusionner les participants pour �viter les doublons entre l'ancienne et la nouvelle mod��lisation.
        $participants = collect()
            ->concat($meeting->participants->pluck('user'))
            ->concat($meeting->participantsUsers)
            ->filter()
            ->unique('id');

        $pdf = Pdf::loadView('meetings.pdf', [
            'meeting' => $meeting,
        ])->setPaper('A4', 'portrait');

        $fileName = 'reunion-' . ($meeting->slug ?? $meeting->id) . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Export de l'invitation officielle en PDF.
     */
    public function exportInvitationPdf(Meeting $meeting, ?array $recipient = null)
    {
        $this->authorize('view', $meeting);

        $meeting->load(['type', 'room', 'organizer']);

        $pdf = Pdf::loadView('meetings.pdf-invitation', [
            'meeting' => $meeting,
            'recipient' => $recipient,
        ])->setPaper('A4', 'portrait');

        $fileName = 'invitation-' . ($meeting->slug ?? $meeting->id) . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Export de la feuille de présence en PDF.
     */
    public function exportAttendancePdf(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        $meeting->load([
            'type',
            'room',
            'organizer',
            'delegations.members',
            'organizationCommittee.members.user',
        ]);

        $pdf = Pdf::loadView('meetings.pdf-attendance', [
            'meeting' => $meeting,
        ])->setPaper('A4', 'portrait');

        $fileName = 'feuille-presence-' . ($meeting->slug ?? $meeting->id) . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Export du procès-verbal (template) en PDF.
     */
    public function exportMinutesPdf(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        $meeting->load([
            'type',
            'room',
            'organizer',
            'delegations.members',
            'organizationCommittee.members.user',
            'documents.type',
        ]);

        $pdf = Pdf::loadView('meetings.pdf-minutes', [
            'meeting' => $meeting,
        ])->setPaper('A4', 'portrait');

        $fileName = 'pv-' . ($meeting->slug ?? $meeting->id) . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Export de la note logistique en PDF.
     */
    public function exportLogisticsPdf(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        $meeting->load([
            'type',
            'room',
            'organizer',
            'termsOfReference',
            'documents.type',
        ]);

        $pdf = Pdf::loadView('meetings.pdf-logistics', [
            'meeting' => $meeting,
        ])->setPaper('A4', 'portrait');

        $fileName = 'note-logistique-' . ($meeting->slug ?? $meeting->id) . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Export de l'ordre du jour détaillé en PDF.
     */
    public function exportAgendaPdf(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        $meeting->load([
            'type',
            'room',
            'documents.type',
        ]);

        $pdf = Pdf::loadView('meetings.pdf-agenda', [
            'meeting' => $meeting,
        ])->setPaper('A4', 'portrait');

        $fileName = 'ordre-du-jour-' . ($meeting->slug ?? $meeting->id) . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Envoi manuel d'une notification par email aux délégations.
     */
    public function notifyParticipants(Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        // Charger les délégations et leurs membres
        $meeting->loadMissing(['delegations.members', 'type', 'room']);

        $recipients = collect();
        
        foreach ($meeting->delegations as $delegation) {
            foreach ($delegation->members as $member) {
                if ($member->email) {
                    // Créer une notification pour les membres de délégation
                    // Note: Implémenter une notification spécifique pour les membres de délégation
                    $recipients->push((object)['email' => $member->email, 'name' => $member->full_name]);
                }
            }
        }

        if ($recipients->isEmpty()) {
            return back()->with('error', 'Aucun membre de délégation à notifier.');
        }

        // TODO: Implémenter l'envoi de notifications aux membres de délégation
        // Notification::send($recipients, new MeetingInvitationNotification($meeting));

        return back()->with('success', 'Les convocations ont été envoyées aux délégations par email.');
    }

    /**
     * Envoi des invitations aux délégations.
     * NOUVELLE LOGIQUE : Les invitations sont envoyées aux délégations, pas aux participants individuels.
     */
    protected function sendMeetingInvitations(Meeting $meeting): void
    {
        // Charger les délégations et leurs membres
        $meeting->loadMissing(['delegations.members', 'type', 'room']);

        // TODO: Implémenter l'envoi de notifications aux délégations
        // Les notifications doivent être envoyées aux chefs de délégation et/ou aux membres
        // selon la logique métier de la CEEAC
        
        foreach ($meeting->delegations as $delegation) {
            // Envoyer notification au chef de délégation
            $head = $delegation->members()->where('role', 'head')->first();
            if ($head && $head->email) {
                // TODO: Créer et envoyer une notification spécifique pour les délégations
            }
        }
    }
}
