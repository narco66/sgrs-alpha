<?php

namespace App\Http\Controllers;

use App\Models\MeetingRequest;
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\Committee;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MeetingRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste des demandes de réunion
     * UC35 - Envoyer une demande de création de réunion
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('q');

        $query = MeetingRequest::with(['meetingType', 'committee', 'requestedRoom', 'requester', 'reviewer', 'meeting'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });

        // Les utilisateurs normaux voient seulement leurs demandes
        if (!Auth::user()->hasAnyRole(['super-admin', 'admin', 'dsi', 'chef-departement'])) {
            $query->where('requested_by', Auth::id());
        }

        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('meeting-requests.index', compact('requests', 'status', 'search'));
    }

    /**
     * Formulaire de création d'une demande
     */
    public function create()
    {
        $meetingTypes = MeetingType::orderBy('sort_order')->orderBy('name')->get();
        $committees = Committee::active()->orderBy('name')->get();
        $rooms = Room::active()->orderBy('name')->get();

        return view('meeting-requests.create', compact('meetingTypes', 'committees', 'rooms'));
    }

    /**
     * Enregistrement d'une demande
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'meeting_type_id' => ['nullable', 'exists:types_reunions,id'],
            'committee_id' => ['nullable', 'exists:comites,id'],
            'requested_start_at' => ['required', 'date', 'after:now'],
            'requested_end_at' => ['nullable', 'date', 'after:requested_start_at'],
            'requested_room_id' => ['nullable', 'exists:salles,id'],
            'other_location' => ['nullable', 'string', 'max:255'],
            'justification' => ['nullable', 'string'],
        ]);

        $meetingRequest = MeetingRequest::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'meeting_type_id' => $validated['meeting_type_id'] ?? null,
            'committee_id' => $validated['committee_id'] ?? null,
            'requested_start_at' => $validated['requested_start_at'],
            'requested_end_at' => $validated['requested_end_at'] ?? null,
            'requested_room_id' => $validated['requested_room_id'] ?? null,
            'other_location' => $validated['other_location'] ?? null,
            'justification' => $validated['justification'] ?? null,
            'requested_by' => Auth::id(),
            'status' => 'pending',
        ]);

        // Notification au chef de département (à implémenter)

        return redirect()
            ->route('meeting-requests.show', $meetingRequest)
            ->with('success', 'Votre demande de réunion a été soumise avec succès.');
    }

    /**
     * Affichage d'une demande
     */
    public function show(MeetingRequest $meetingRequest)
    {
        $this->authorize('view', $meetingRequest);

        $meetingRequest->load(['meetingType', 'committee', 'requestedRoom', 'requester', 'reviewer', 'meeting']);

        return view('meeting-requests.show', compact('meetingRequest'));
    }

    /**
     * Approbation d'une demande (UC36)
     */
    public function approve(Request $request, MeetingRequest $meetingRequest)
    {
        $this->authorize('update', $meetingRequest);

        $validated = $request->validate([
            'review_comments' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $meetingRequest) {
            // Créer la réunion
            $meeting = Meeting::create([
                'title' => $meetingRequest->title,
                'description' => $meetingRequest->description,
                'meeting_type_id' => $meetingRequest->meeting_type_id,
                'committee_id' => $meetingRequest->committee_id,
                'room_id' => $meetingRequest->requested_room_id,
                'start_at' => $meetingRequest->requested_start_at,
                'end_at' => $meetingRequest->requested_end_at,
                'organizer_id' => $meetingRequest->requested_by,
                'status' => 'planifiee',
            ]);

            // Mettre à jour la demande
            $meetingRequest->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_comments' => $validated['review_comments'] ?? null,
                'meeting_id' => $meeting->id,
            ]);

            // Notification au demandeur (à implémenter)
        });

        return redirect()
            ->route('meeting-requests.show', $meetingRequest)
            ->with('success', 'La demande a été approuvée et la réunion a été créée.');
    }

    /**
     * Rejet d'une demande
     */
    public function reject(Request $request, MeetingRequest $meetingRequest)
    {
        $this->authorize('update', $meetingRequest);

        $validated = $request->validate([
            'review_comments' => ['required', 'string', 'min:10'],
        ]);

        $meetingRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_comments' => $validated['review_comments'],
        ]);

        // Notification au demandeur (à implémenter)

        return redirect()
            ->route('meeting-requests.show', $meetingRequest)
            ->with('success', 'La demande a été rejetée.');
    }

    /**
     * Suppression d'une demande
     */
    public function destroy(MeetingRequest $meetingRequest)
    {
        $this->authorize('delete', $meetingRequest);

        if ($meetingRequest->status !== 'pending') {
            return back()->with('error', 'Seules les demandes en attente peuvent être supprimées.');
        }

        $meetingRequest->delete();

        return redirect()
            ->route('meeting-requests.index')
            ->with('success', 'La demande a été supprimée.');
    }
}
