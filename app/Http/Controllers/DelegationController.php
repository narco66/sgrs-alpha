<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDelegationRequest;
use App\Http\Requests\UpdateDelegationRequest;
use App\Models\Delegation;
use App\Models\DelegationMember;
use App\Models\Meeting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        // Vérification des droits d'accès
        $this->authorize('create', Delegation::class);

        $meetingId = $request->get('meeting_id');

        // Si un meeting_id est fourni, vérifier qu'il existe et que l'utilisateur a les droits
        $meeting = null;
        if ($meetingId) {
            $meeting = Meeting::find($meetingId);
            if (!$meeting) {
                return redirect()
                    ->route('delegations.index')
                    ->with('error', '<div class="d-flex align-items-center"><i class="bi bi-exclamation-triangle-fill text-danger me-2 fs-5"></i><div><strong>Erreur</strong><br>La réunion spécifiée n\'existe pas.</div></div>');
            }

            // Vérifier que l'utilisateur peut modifier cette réunion
            if (!$request->user()->can('update', $meeting) && !$request->user()->can('delegations.create')) {
                return redirect()
                    ->route('delegations.index')
                    ->with('error', '<div class="d-flex align-items-center"><i class="bi bi-shield-exclamation text-danger me-2 fs-5"></i><div><strong>Accès refusé</strong><br>Vous n\'avez pas les droits nécessaires pour créer une délégation pour cette réunion.</div></div>');
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
        // Double vérification des droits
        $this->authorize('create', Delegation::class);

        try {
            // Logs détaillés seulement en développement
            if (app()->environment('local', 'testing')) {
                \Log::info('=== DÉBUT CRÉATION DÉLÉGATION ===', [
                    'user_id' => $request->user()->id,
                    'meeting_id' => $request->input('meeting_id'),
                    'entity_type' => $request->input('entity_type'),
                    'members_count' => count($request->input('members', []))
                ]);
            }
            
            $data = $request->validated();
            $membersData = $request->input('members', []);

            // La validation conditionnelle est gérée par StoreDelegationRequest

            // S'assurer que is_active a une valeur par défaut
            if (!isset($data['is_active'])) {
                $data['is_active'] = true;
            }

            // S'assurer que participation_status a une valeur par défaut
            if (!isset($data['participation_status'])) {
                $data['participation_status'] = 'invited';
            }

            $delegation = Delegation::create($data);
            
            if (app()->environment('local', 'testing')) {
                \Log::info('✅ DÉLÉGATION CRÉÉE', [
                    'delegation_id' => $delegation->id,
                    'title' => $delegation->title
                ]);
            }

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

                        // Vérifier que le statut est valide selon l'enum de la migration
                        $validStatuses = ['invited', 'confirmed', 'present', 'absent', 'excused'];
                        $memberStatus = !empty($memberData['status']) ? $memberData['status'] : 'invited';
                        if (!in_array($memberStatus, $validStatuses)) {
                            $memberStatus = 'invited'; // Valeur par défaut valide
                        }
                        
                        // Vérifier que le rôle est valide selon l'enum de la migration
                        $validRoles = ['head', 'member', 'expert', 'observer', 'secretary'];
                        $memberRole = !empty($memberData['role']) ? $memberData['role'] : 'member';
                        if (!in_array($memberRole, $validRoles)) {
                            $memberRole = 'member'; // Valeur par défaut valide
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
                            'role' => $memberRole,
                            'status' => $memberStatus,
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


            return redirect($redirectRoute)
                ->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Erreur de base de données lors de la création de la délégation', [
                'message' => $e->getMessage(),
                'meeting_id' => $request->input('meeting_id')
            ]);

            $errorMessage = '<div class="d-flex align-items-center">';
            $errorMessage .= '<i class="bi bi-exclamation-triangle-fill text-danger me-2 fs-5"></i>';
            $errorMessage .= '<div>';
            $errorMessage .= '<strong>Erreur de base de données</strong><br>';
            $errorMessage .= 'Impossible de créer la délégation. Vérifiez que la réunion existe encore.';
            $errorMessage .= '</div>';
            $errorMessage .= '</div>';

            return back()
                ->withInput()
                ->with('error', $errorMessage);
        } catch (\Exception $e) {
            \Log::error('Erreur inattendue lors de la création de la délégation', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            $errorMessage = '<div class="d-flex align-items-center">';
            $errorMessage .= '<i class="bi bi-exclamation-triangle-fill text-danger me-2 fs-5"></i>';
            $errorMessage .= '<div>';
            $errorMessage .= '<strong>Erreur technique</strong><br>';
            $errorMessage .= 'Une erreur inattendue est survenue. Veuillez réessayer ou contacter l\'administrateur.';
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
        // Double vérification des droits
        $this->authorize('update', $delegation);

        try {
            $data = $request->validated();
            $membersData = $request->input('members', []);

            $delegation->update($data);

            // Récupérer les IDs des membres existants qui doivent être conservés
            $existingMemberIds = [];
            $newMembers = [];
            $updatedMembers = [];
            $deletedMembersCount = 0;

            // Compter les membres existants avant suppression
            $existingMembersCount = $delegation->members()->count();

            foreach ($membersData as $memberData) {
                if (isset($memberData['id'])) {
                    // Membre existant à mettre à jour
                    $existingMemberIds[] = $memberData['id'];
                    $firstName = trim($memberData['first_name'] ?? '');
                    $lastName = trim($memberData['last_name'] ?? '');
                    $email = trim($memberData['email'] ?? '');

                    if (!empty($firstName) && !empty($lastName) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $updatedMembers[] = $memberData;
                    }
                } else {
                    // Nouveau membre à créer
                    $firstName = trim($memberData['first_name'] ?? '');
                    $lastName = trim($memberData['last_name'] ?? '');
                    $email = trim($memberData['email'] ?? '');

                    if (!empty($firstName) && !empty($lastName) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $newMembers[] = $memberData;
                    }
                }
            }

            // Supprimer les membres qui ne sont plus dans la liste
            $deletedMembers = $delegation->members()->whereNotIn('id', $existingMemberIds)->get();
            $deletedMembersCount = $deletedMembers->count();
            $delegation->members()->whereNotIn('id', $existingMemberIds)->delete();

            // Mettre à jour les membres existants
            foreach ($updatedMembers as $memberData) {
                DelegationMember::where('id', $memberData['id'])
                    ->where('delegation_id', $delegation->id)
                    ->update([
                        'first_name' => trim($memberData['first_name']),
                        'last_name' => trim($memberData['last_name']),
                        'email' => trim($memberData['email']),
                        'phone' => !empty($memberData['phone']) ? trim($memberData['phone']) : null,
                        'position' => !empty($memberData['position']) ? trim($memberData['position']) : null,
                        'title' => !empty($memberData['title']) ? trim($memberData['title']) : null,
                        'institution' => !empty($memberData['institution']) ? trim($memberData['institution']) : null,
                        'department' => !empty($memberData['department']) ? trim($memberData['department']) : null,
                        'role' => in_array($memberData['role'] ?? 'member', ['head', 'member', 'expert', 'observer', 'secretary']) 
                            ? ($memberData['role'] ?? 'member') 
                            : 'member',
                        'status' => in_array($memberData['status'] ?? 'invited', ['invited', 'confirmed', 'present', 'absent', 'excused']) 
                            ? ($memberData['status'] ?? 'invited') 
                            : 'invited',
                        'notes' => !empty($memberData['notes']) ? trim($memberData['notes']) : null,
                    ]);
            }

            // Créer les nouveaux membres
            foreach ($newMembers as $memberData) {
                // Vérifier que le statut est valide selon l'enum de la migration
                $validStatuses = ['invited', 'confirmed', 'present', 'absent', 'excused'];
                $memberStatus = !empty($memberData['status']) ? $memberData['status'] : 'invited';
                if (!in_array($memberStatus, $validStatuses)) {
                    $memberStatus = 'invited'; // Valeur par défaut valide
                }
                
                // Vérifier que le rôle est valide selon l'enum de la migration
                $validRoles = ['head', 'member', 'expert', 'observer', 'secretary'];
                $memberRole = !empty($memberData['role']) ? $memberData['role'] : 'member';
                if (!in_array($memberRole, $validRoles)) {
                    $memberRole = 'member'; // Valeur par défaut valide
                }
                
                DelegationMember::create([
                    'delegation_id' => $delegation->id,
                    'first_name' => trim($memberData['first_name']),
                    'last_name' => trim($memberData['last_name']),
                    'email' => trim($memberData['email']),
                    'phone' => !empty($memberData['phone']) ? trim($memberData['phone']) : null,
                    'position' => !empty($memberData['position']) ? trim($memberData['position']) : null,
                    'title' => !empty($memberData['title']) ? trim($memberData['title']) : null,
                    'institution' => !empty($memberData['institution']) ? trim($memberData['institution']) : null,
                    'department' => !empty($memberData['department']) ? trim($memberData['department']) : null,
                    'role' => $memberRole,
                    'status' => $memberStatus,
                    'notes' => !empty($memberData['notes']) ? trim($memberData['notes']) : null,
                ]);
            }

            // Construire le message de succès détaillé
            $successMessages = [];
            $successMessages[] = '<div class="d-flex align-items-center">';
            $successMessages[] = '<i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>';
            $successMessages[] = '<div>';
            $successMessages[] = '<strong>✓ La délégation a été mise à jour avec succès !</strong><br>';

            if (count($updatedMembers) > 0) {
                $successMessages[] = '✓ <strong>' . count($updatedMembers) . ' membre' . (count($updatedMembers) > 1 ? 's' : '') . '</strong> ' . (count($updatedMembers) > 1 ? 'ont été' : 'a été') . ' mis' . (count($updatedMembers) > 1 ? 's' : '') . ' à jour.<br>';
            }

            if (count($newMembers) > 0) {
                $successMessages[] = '✓ <strong>' . count($newMembers) . ' nouveau' . (count($newMembers) > 1 ? 'x' : '') . ' membre' . (count($newMembers) > 1 ? 's' : '') . '</strong> ' . (count($newMembers) > 1 ? 'ont été' : 'a été') . ' ajouté' . (count($newMembers) > 1 ? 's' : '') . '.<br>';
            }

            if ($deletedMembersCount > 0) {
                $successMessages[] = '✓ <strong>' . $deletedMembersCount . ' membre' . ($deletedMembersCount > 1 ? 's' : '') . '</strong> ' . ($deletedMembersCount > 1 ? 'ont été' : 'a été') . ' supprimé' . ($deletedMembersCount > 1 ? 's' : '') . '.<br>';
            }

            $successMessages[] = '</div>';
            $successMessages[] = '</div>';

            $redirectToMeeting = $request->input('redirect_to_meeting');
            $redirectRoute = $redirectToMeeting && $delegation->meeting_id
                ? route('meetings.show', $delegation->meeting_id)
                : route('delegations.show', $delegation);

            return redirect($redirectRoute)
                ->with('success', implode('', $successMessages));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', '<div class="d-flex align-items-center"><i class="bi bi-exclamation-triangle-fill text-danger me-2 fs-5"></i><div><strong>Erreur de validation</strong><br>Veuillez vérifier les informations saisies.</div></div>');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour de la délégation', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'delegation_id' => $delegation->id ?? null,
            ]);

            $errorMessage = '<div class="d-flex align-items-center">';
            $errorMessage .= '<i class="bi bi-exclamation-triangle-fill text-danger me-2 fs-5"></i>';
            $errorMessage .= '<div>';
            $errorMessage .= '<strong>Erreur lors de la mise à jour</strong><br>';
            $errorMessage .= 'Une erreur est survenue. Veuillez vérifier les informations saisies et réessayer.';
            $errorMessage .= '</div>';
            $errorMessage .= '</div>';

            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
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
