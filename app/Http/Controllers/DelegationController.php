<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDelegationRequest;
use App\Http\Requests\UpdateDelegationRequest;
use App\Models\Delegation;
use App\Models\DelegationMember;
use App\Models\Meeting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/**
 * Contrôleur pour la gestion des délégations institutionnelles
 * 
 * Conforme au modèle CEEAC : participation par délégations
 */
class DelegationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Delegation::class, 'delegation');
    }

    /**
     * Liste des délégations
     */
    public function index(Request $request)
    {
        $search = $request->get('q');
        $meetingId = $request->get('meeting_id');
        $entityType = $request->get('entity_type');
        $status = $request->get('status');

        $delegations = Delegation::withCount('members')
            ->with('meeting')
            ->when($search, fn($q) => $q->search($search))
            ->when($meetingId, fn($q) => $q->where('meeting_id', $meetingId))
            ->when($entityType, fn($q) => $q->where('entity_type', $entityType))
            ->when($status, fn($q) => $q->where('participation_status', $status))
            ->orderBy('created_at', 'desc')
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        $meetings = Meeting::orderByDesc('start_at')->take(50)->get();

        return view('delegations.index', compact('delegations', 'search', 'meetings', 'meetingId', 'entityType', 'status'));
    }

    /**
     * Formulaire de création d'une délégation
     */
    public function create(Request $request)
    {
        $meetingId = $request->get('meeting_id');
        
        // Si un meeting_id est fourni, vérifier qu'il existe
        $meeting = null;
        if ($meetingId) {
            $meeting = Meeting::find($meetingId);
            if (!$meeting) {
                return redirect()
                    ->route('delegations.index')
                    ->with('error', 'La réunion spécifiée n\'existe pas.');
            }
        }
        
        return view('delegations.create', [
            'delegation' => new Delegation(),
            'meetings'   => Meeting::orderByDesc('start_at')->take(50)->get(),
            'meetingId'  => $meetingId,
            'meeting'    => $meeting,
        ]);
    }

    /**
     * Enregistrement d'une nouvelle délégation
     */
    public function store(StoreDelegationRequest $request)
    {
        try {
            \Log::info('Début création délégation', ['request_data' => $request->except(['_token', 'members'])]);
            
            $data = $request->validated();
            $membersData = $request->input('members', []);
            
            \Log::info('Données validées', ['data' => $data]);
            
            // Validation manuelle des champs conditionnels
            $entityType = $data['entity_type'] ?? null;
            if (in_array($entityType, ['state_member', 'other']) && empty($data['country'])) {
                return back()
                    ->withInput()
                    ->withErrors(['country' => 'Le champ pays est requis pour ce type d\'entité.']);
            }
            
            if (in_array($entityType, ['international_organization', 'technical_partner', 'financial_partner']) && empty($data['organization_name'])) {
                return back()
                    ->withInput()
                    ->withErrors(['organization_name' => 'Le nom de l\'organisation est requis pour ce type d\'entité.']);
            }
            
            // S'assurer que is_active a une valeur par défaut
            if (!isset($data['is_active'])) {
                $data['is_active'] = true;
            }
            
            // S'assurer que participation_status a une valeur par défaut
            if (!isset($data['participation_status'])) {
                $data['participation_status'] = 'invited';
            }
            
            \Log::info('Création de la délégation', ['data' => $data]);
            $delegation = Delegation::create($data);
            \Log::info('Délégation créée', ['delegation_id' => $delegation->id]);

            // Créer les membres de la délégation
            if (!empty($membersData) && is_array($membersData)) {
                foreach ($membersData as $index => $memberData) {
                    // Filtrer les membres vides (tous les champs requis vides)
                    $firstName = trim($memberData['first_name'] ?? '');
                    $lastName = trim($memberData['last_name'] ?? '');
                    $email = trim($memberData['email'] ?? '');
                    
                    if (!empty($firstName) && !empty($lastName) && !empty($email)) {
                        // Valider l'email
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            continue; // Skip invalid emails
                        }
                        
                        DelegationMember::create([
                            'delegation_id' => $delegation->id,
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'email' => $email,
                            'phone' => !empty($memberData['phone']) ? trim($memberData['phone']) : null,
                            'position' => !empty($memberData['position']) ? trim($memberData['position']) : null,
                            'title' => !empty($memberData['title']) ? trim($memberData['title']) : null,
                            'institution' => !empty($memberData['institution']) ? trim($memberData['institution']) : null,
                            'department' => !empty($memberData['department']) ? trim($memberData['department']) : null,
                            'role' => !empty($memberData['role']) ? $memberData['role'] : 'member',
                            'status' => !empty($memberData['status']) ? $memberData['status'] : 'pending',
                            'notes' => !empty($memberData['notes']) ? trim($memberData['notes']) : null,
                        ]);
                    }
                }
            }

            // Compter les membres créés
            $membersCount = 0;
            if (!empty($membersData) && is_array($membersData)) {
                $membersCount = count(array_filter($membersData, function($m) {
                    return !empty($m['first_name']) && !empty($m['last_name']) && !empty($m['email']);
                }));
            }
            
            // Rediriger vers la réunion si créée depuis une réunion, sinon vers la délégation
            $redirectToMeeting = $request->input('redirect_to_meeting');
            $redirectRoute = $redirectToMeeting 
                ? route('meetings.show', $delegation->meeting_id)
                : route('delegations.show', $delegation);
            
            // Message de succès détaillé
            if ($redirectToMeeting) {
                // Message pour création depuis une réunion
                $message = '<div class="d-flex align-items-center">';
                $message .= '<i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>';
                $message .= '<div>';
                $message .= '<strong>Délégation créée avec succès !</strong><br>';
                $message .= 'La délégation <strong>"' . e($delegation->title) . '"</strong> a été ajoutée à cette réunion.';
                if ($membersCount > 0) {
                    $message .= ' <strong>' . $membersCount . ' membre' . ($membersCount > 1 ? 's' : '') . '</strong> ' . ($membersCount > 1 ? 'ont été' : 'a été') . ' ajouté' . ($membersCount > 1 ? 's' : '') . '.';
                } else {
                    $message .= ' Vous pouvez maintenant <a href="' . route('delegations.show', $delegation) . '" class="alert-link">ajouter des membres</a> à cette délégation.';
                }
                $message .= '</div>';
                $message .= '</div>';
            } else {
                // Message pour création normale
                $message = '<div class="d-flex align-items-center">';
                $message .= '<i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>';
                $message .= '<div>';
                $message .= '<strong>Délégation créée avec succès !</strong><br>';
                $message .= 'La délégation <strong>"' . e($delegation->title) . '"</strong> a été créée.';
                if ($membersCount > 0) {
                    $message .= ' <strong>' . $membersCount . ' membre' . ($membersCount > 1 ? 's' : '') . '</strong> ' . ($membersCount > 1 ? 'ont été' : 'a été') . ' ajouté' . ($membersCount > 1 ? 's' : '') . '.';
                } else {
                    $message .= ' Vous pouvez maintenant ajouter des membres à cette délégation.';
                }
                $message .= '</div>';
                $message .= '</div>';
            }
            
            \Log::info('Redirection', ['route' => $redirectRoute, 'meeting_id' => $delegation->meeting_id, 'members_count' => $membersCount]);
            
            return redirect($redirectRoute)
                ->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erreur de validation lors de la création de la délégation', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['_token', 'members'])
            ]);
            
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la délégation', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'members'])
            ]);
            
            $errorMessage = '<div class="d-flex align-items-center">';
            $errorMessage .= '<i class="bi bi-exclamation-triangle-fill text-danger me-2 fs-5"></i>';
            $errorMessage .= '<div>';
            $errorMessage .= '<strong>Erreur lors de la création de la délégation</strong><br>';
            $errorMessage .= 'Une erreur est survenue. Veuillez vérifier les informations saisies et réessayer.';
            $errorMessage .= '</div>';
            $errorMessage .= '</div>';
            
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Affichage d'une délégation
     */
    public function show(Delegation $delegation)
    {
        $delegation->load([
            'meeting',
            'members' => function($query) {
                $query->orderByRaw("CASE WHEN role = 'head' THEN 0 ELSE 1 END")
                      ->orderBy('last_name');
            },
        ]);

        return view('delegations.show', compact('delegation'));
    }

    /**
     * Formulaire d'édition d'une délégation
     */
    public function edit(Delegation $delegation)
    {
        $delegation->load(['meeting', 'members']);

        return view('delegations.edit', [
            'delegation' => $delegation,
            'meetings'   => Meeting::orderByDesc('start_at')->take(50)->get(),
        ]);
    }

    /**
     * Mise à jour d'une délégation
     */
    public function update(UpdateDelegationRequest $request, Delegation $delegation)
    {
        $data = $request->validated();
        $membersData = $request->input('members', []);
        
        $delegation->update($data);

        // Récupérer les IDs des membres existants qui doivent être conservés
        $existingMemberIds = [];
        $newMembers = [];
        $updatedMembers = [];

        foreach ($membersData as $memberData) {
            if (isset($memberData['id'])) {
                // Membre existant à mettre à jour
                $existingMemberIds[] = $memberData['id'];
                if (!empty($memberData['first_name']) && !empty($memberData['last_name']) && !empty($memberData['email'])) {
                    $updatedMembers[] = $memberData;
                }
            } else {
                // Nouveau membre à créer
                if (!empty($memberData['first_name']) && !empty($memberData['last_name']) && !empty($memberData['email'])) {
                    $newMembers[] = $memberData;
                }
            }
        }

        // Supprimer les membres qui ne sont plus dans la liste
        $delegation->members()->whereNotIn('id', $existingMemberIds)->delete();

        // Mettre à jour les membres existants
        foreach ($updatedMembers as $memberData) {
            DelegationMember::where('id', $memberData['id'])
                ->where('delegation_id', $delegation->id)
                ->update([
                    'first_name' => $memberData['first_name'],
                    'last_name' => $memberData['last_name'],
                    'email' => $memberData['email'],
                    'phone' => $memberData['phone'] ?? null,
                    'position' => $memberData['position'] ?? null,
                    'title' => $memberData['title'] ?? null,
                    'institution' => $memberData['institution'] ?? null,
                    'department' => $memberData['department'] ?? null,
                    'role' => $memberData['role'] ?? 'member',
                    'status' => $memberData['status'] ?? 'pending',
                    'notes' => $memberData['notes'] ?? null,
                ]);
        }

        // Créer les nouveaux membres
        foreach ($newMembers as $memberData) {
            DelegationMember::create([
                'delegation_id' => $delegation->id,
                'first_name' => $memberData['first_name'],
                'last_name' => $memberData['last_name'],
                'email' => $memberData['email'],
                'phone' => $memberData['phone'] ?? null,
                'position' => $memberData['position'] ?? null,
                'title' => $memberData['title'] ?? null,
                'institution' => $memberData['institution'] ?? null,
                'department' => $memberData['department'] ?? null,
                'role' => $memberData['role'] ?? 'member',
                'status' => $memberData['status'] ?? 'pending',
                'notes' => $memberData['notes'] ?? null,
            ]);
        }

        return redirect()
            ->route('delegations.show', $delegation)
            ->with('success', 'La délégation a été mise à jour avec succès.');
    }

    /**
     * Suppression d'une délégation
     */
    public function destroy(Delegation $delegation)
    {
        if ($delegation->members()->count() > 0) {
            return redirect()
                ->route('delegations.index')
                ->with('error', 'Impossible de supprimer cette délégation car elle contient des membres.');
        }

        $delegation->delete();

        return redirect()
            ->route('delegations.index')
            ->with('success', 'La délégation a été supprimée avec succès.');
    }

    /**
     * Confirmation de participation d'une délégation
     */
    public function confirm(Request $request, Delegation $delegation)
    {
        $this->authorize('update', $delegation);

        $delegation->update([
            'participation_status' => Delegation::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);

        return back()->with('success', 'La participation de la délégation a été confirmée.');
    }

    /**
     * Export PDF des détails d'une délégation
     */
    public function exportPdf(Delegation $delegation)
    {
        $this->authorize('view', $delegation);

        $delegation->load([
            'meeting',
            'members' => function($query) {
                $query->orderByRaw("CASE WHEN role = 'head' THEN 0 ELSE 1 END")
                      ->orderBy('last_name');
            },
        ]);

        $pdf = Pdf::loadView('delegations.pdf', [
            'delegation' => $delegation,
        ])->setPaper('A4', 'portrait');

        $fileName = 'delegation-' . ($delegation->id) . '.pdf';

        return $pdf->download($fileName);
    }
}
