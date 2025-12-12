<?php $__env->startSection('content'); ?>
<?php
    // Statut courant sous forme de chaîne
    // On gère le cas où status est un enum OU une simple chaîne.
    if (is_object($meeting->status) && property_exists($meeting->status, 'value')) {
        $currentStatus = $meeting->status->value ?? 'brouillon';
    } else {
        $currentStatus = $meeting->status ?? 'brouillon';
    }

    // Libellés lisibles
    $statusLabels = [
        'brouillon'      => 'Brouillon',
        'planifiee'      => 'Planifiée',
        'en_preparation' => 'En préparation',
        'en_cours'       => 'En cours',
        'terminee'       => 'Terminée',
        'annulee'        => 'Annulée',
    ];

    // Style Bootstrap des badges
    $statusBadges = [
        'brouillon'      => 'bg-secondary',
        'planifiee'      => 'bg-info text-dark',
        'en_preparation' => 'bg-warning text-dark',
        'en_cours'       => 'bg-primary',
        'terminee'       => 'bg-success',
        'annulee'        => 'bg-danger',
    ];

    // Transitions autorisées (simplifiées – à aligner avec MeetingWorkflowService)
    $transitions = [
        'brouillon'      => ['planifiee', 'annulee'],
        'planifiee'      => ['en_preparation', 'annulee'],
        'en_preparation' => ['en_cours', 'annulee'],
        'en_cours'       => ['terminee', 'annulee'],
        'terminee'       => [],
        'annulee'        => [],
    ];

    $availableTargets = $transitions[$currentStatus] ?? [];

    // Style des boutons de workflow
    $statusButtons = [
        'planifiee'      => ['label' => 'Marquer comme planifiée',     'class' => 'btn-outline-info'],
        'en_preparation' => ['label' => 'Passer en préparation',       'class' => 'btn-outline-warning'],
        'en_cours'       => ['label' => 'Démarrer la réunion',         'class' => 'btn-outline-primary'],
        'terminee'       => ['label' => 'Clôturer la réunion',         'class' => 'btn-outline-success'],
        'annulee'        => ['label' => 'Annuler la réunion',          'class' => 'btn-outline-danger'],
    ];

    // Historique des statuts (fourni par le contrôleur, sinon fallback)
    if (!isset($histories)) {
        try {
            $histories = $meeting->meetingStatusHistories()->with('user')->orderByDesc('created_at')->get();
        } catch (Exception $e) {
            $histories = collect();
        }
    }

    // Préparation des références fréquentes
    $typeName     = optional($meeting->meetingType)->name;
    $typeCode     = optional($meeting->meetingType)->code;
    $creatorName  = optional($meeting->creator)->name;
    $roomName     = optional($meeting->room)->name;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?php echo e($meeting->title); ?></h4>
        <p class="text-muted mb-0">
            <?php if($typeName): ?>
                <?php echo e($typeName); ?> •
            <?php endif; ?>
            Créée par <?php echo e($creatorName ?? 'Non renseigné'); ?>

        </p>
    </div>
    <div class="text-end">
        <div class="mb-2">
            <span class="badge rounded-pill <?php echo e($statusBadges[$currentStatus] ?? 'bg-secondary'); ?>">
                <?php echo e($statusLabels[$currentStatus] ?? ucfirst($currentStatus)); ?>

            </span>
        </div>

        <div class="d-flex flex-wrap justify-content-end gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $meeting)): ?>
                <a href="<?php echo e(route('meetings.edit', $meeting)); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil me-1"></i> Modifier
                </a>
            <?php endif; ?>
            
            
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Exports PDF
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Documents de réunion</h6></li>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('meetings.pdf', $meeting)); ?>">
                            <i class="bi bi-file-text me-2"></i> Fiche complète
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('meetings.pdf.agenda', $meeting)); ?>">
                            <i class="bi bi-list-check me-2"></i> Ordre du jour
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('meetings.pdf.logistics', $meeting)); ?>">
                            <i class="bi bi-geo-alt me-2"></i> Note logistique
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Documents officiels</h6></li>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('meetings.pdf.invitation', $meeting)); ?>">
                            <i class="bi bi-envelope me-2"></i> Lettre d'invitation
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('meetings.pdf.attendance', $meeting)); ?>">
                            <i class="bi bi-person-check me-2"></i> Feuille de présence
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('meetings.pdf.minutes', $meeting)); ?>">
                            <i class="bi bi-journal-text me-2"></i> Procès-verbal (template)
                        </a>
                    </li>
                    <?php if($meeting->termsOfReference): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('terms-of-reference.pdf', [$meeting, $meeting->termsOfReference])); ?>">
                                <i class="bi bi-file-earmark-ruled me-2"></i> Cahier des charges
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $meeting)): ?>
                <form action="<?php echo e(route('meetings.notify', $meeting)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-envelope me-1"></i> Notifier les délégations
                    </button>
                </form>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $meeting)): ?>
                <form action="<?php echo e(route('meetings.destroy', $meeting)); ?>" method="POST"
                      onsubmit="return confirm('Confirmez-vous la suppression de cette réunion ?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        Supprimer
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $meeting)): ?>
    <?php if(count($availableTargets)): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="mb-3">Workflow de la réunion</h6>
                <p class="text-muted small mb-2">
                    Statut actuel :
                    <span class="badge rounded-pill <?php echo e($statusBadges[$currentStatus] ?? 'bg-secondary'); ?>">
                        <?php echo e($statusLabels[$currentStatus] ?? ucfirst($currentStatus)); ?>

                    </span>
                </p>

                <div class="mb-2 small text-muted">
                    Choisissez la prochaine étape dans le cycle de vie de la réunion.
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <?php $__currentLoopData = $availableTargets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <form method="POST"
                              action="<?php echo e(route('meetings.change-status', $meeting)); ?>"
                              class="d-inline">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="status" value="<?php echo e($target); ?>">
                            <button type="submit"
                                    class="btn btn-sm <?php echo e($statusButtons[$target]['class'] ?? 'btn-outline-secondary'); ?>">
                                <?php echo e($statusButtons[$target]['label'] ?? $statusLabels[$target] ?? ucfirst($target)); ?>

                            </button>
                        </form>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <form method="POST"
                      action="<?php echo e(route('meetings.change-status', $meeting)); ?>"
                      class="mt-3">
                    <?php echo csrf_field(); ?>

                    <div class="row g-2 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label small mb-1">Changer le statut avec un commentaire</label>
                            <select name="status" class="form-select form-select-sm">
                                
                                <option value="<?php echo e($currentStatus); ?>" selected>
                                    <?php echo e($statusLabels[$currentStatus] ?? ucfirst($currentStatus)); ?>

                                </option>
                                <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($value !== $currentStatus): ?>
                                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Commentaire (optionnel)</label>
                            <input type="text" name="comment" class="form-control form-control-sm"
                                   placeholder="Motif du changement de statut">
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                Valider
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="row g-3">
    
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="mb-3">Informations générales</h6>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="text-muted small">Type de réunion</div>
                        <div class="fw-semibold">
                            <?php echo e($typeName ?? 'Non renseigné'); ?>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Configuration</div>
                        <div>
                            <?php if($meeting->configuration === 'presentiel'): ?>
                                <span class="badge bg-primary">Présentiel</span>
                            <?php elseif($meeting->configuration === 'hybride'): ?>
                                <span class="badge bg-info text-dark">Hybride</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Visioconférence</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <div class="text-muted small">Date de début</div>
                        <div class="fw-semibold">
                            <?php echo e($meeting->start_at?->format('d/m/Y') ?? '—'); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Heure</div>
                        <div class="fw-semibold">
                            <?php if($meeting->start_at): ?>
                                <?php echo e($meeting->start_at->format('H:i')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Date / heure de fin</div>
                        <div class="fw-semibold">
                            <?php if($meeting->end_at): ?>
                                <?php echo e($meeting->end_at->format('d/m/Y H:i')); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <div class="text-muted small">Salle</div>
                        <div class="fw-semibold">
                            <?php echo e($roomName ?? 'Non attribuée'); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Pays hôte</div>
                        <div class="fw-semibold">
                            <?php echo e($meeting->host_country ?? 'Non renseigné'); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Rappel</div>
                        <div class="fw-semibold">
                            <?php
                                $r = $meeting->reminder_minutes_before;
                            ?>
                            <?php if($r === null): ?>
                                —
                            <?php elseif($r === 0): ?>
                                Aucun rappel
                            <?php elseif($r < 60): ?>
                                <?php echo e($r); ?> minutes avant
                            <?php elseif($r === 60): ?>
                                1 heure avant
                            <?php elseif($r % 60 === 0): ?>
                                <?php echo e($r / 60); ?> heures avant
                            <?php else: ?>
                                <?php echo e($r); ?> minutes avant
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if($meeting->description): ?>
                    <hr>
                    <div class="text-muted small mb-1">Description</div>
                    <p class="mb-0"><?php echo e($meeting->description); ?></p>
                <?php endif; ?>
        </div>
    </div>

        
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="mb-3">Documents liés</h6>
                <?php $documents = $meeting->documents ?? collect(); ?>
                <?php if($documents->isNotEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Ajouté par</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="fw-semibold"><?php echo e($doc->title); ?></td>
                                        <td class="small text-muted"><?php echo e($doc->type?->name ?? $doc->type_label); ?></td>
                                        <td class="small"><?php echo e($doc->uploader?->name ?? 'N/A'); ?></td>
                                        <td class="small text-muted"><?php echo e($doc->created_at?->format('d/m/Y') ?? '—'); ?></td>
                                        <td class="text-end">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('download', $doc)): ?>
                                                <a href="<?php echo e(route('documents.download', $doc)); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small"><i class="bi bi-lock"></i></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted small mb-0">Aucun document lié à cette réunion.</p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Délégations participantes</h6>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Delegation::class)): ?>
                        <a href="<?php echo e(route('delegations.create', ['meeting_id' => $meeting->id])); ?>" 
                           class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Ajouter une délégation
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if($meeting->delegations->isNotEmpty()): ?>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <?php $__currentLoopData = $meeting->delegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delegation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('delegations.show', $delegation)); ?>"
                               class="badge bg-primary text-decoration-none p-2">
                                <i class="bi bi-building"></i> <?php echo e($delegation->title); ?>

                                <?php if($delegation->members_count > 0): ?>
                                    <span class="badge bg-light text-dark ms-1"><?php echo e($delegation->members_count); ?> membre<?php echo e($delegation->members_count > 1 ? 's' : ''); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> 
                        Aucune délégation n'a encore été ajoutée à cette réunion. 
                        Cliquez sur "Ajouter une délégation" pour commencer.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($histories->isNotEmpty()): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="mb-3">Historique des statuts</h6>

                    <ul class="list-unstyled mb-0">
                        <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $old = $history->old_status;
                                $new = $history->new_status;
                                $userName = optional($history->user)->name ?? 'Système';
                            ?>
                            <li class="mb-3 d-flex">
                                <div class="me-3">
                                    <span class="badge rounded-pill <?php echo e($statusBadges[$new] ?? 'bg-secondary'); ?>">
                                        <?php echo e($statusLabels[$new] ?? ucfirst($new)); ?>

                                    </span>
                                </div>
                                <div>
                                    <div class="small">
                                        <strong><?php echo e($userName); ?></strong>
                                        a changé le statut
                                        <?php if($old): ?>
                                            de
                                            <span class="text-muted">
                                                <?php echo e($statusLabels[$old] ?? $old); ?>

                                            </span>
                                        <?php endif; ?>
                                        à
                                        <span class="fw-semibold">
                                            <?php echo e($statusLabels[$new] ?? $new); ?>

                                        </span>
                                    </div>
                                    <div class="text-muted small">
                                        <?php echo e($history->created_at?->format('d/m/Y H:i')); ?>

                                    </div>
                                    <?php if($history->comment): ?>
                                        <div class="small mt-1">
                                            <span class="text-muted">Commentaire :</span>
                                            <?php echo e($history->comment); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="mb-3">Historique des statuts</h6>
                    <p class="text-muted small mb-0">
                        Aucun changement de statut enregistré pour le moment.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="mb-3">Notes & actions rapides</h6>
                <ul class="list-unstyled small text-muted mb-3">
                    <li class="mb-2"><i class="bi bi-check2-circle me-1 text-success"></i>Vérifier les présences clés et envoyer les relances si besoin.</li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-1 text-primary"></i>Confirmer la salle et les équipements (projection, audio).</li>
                    <li><i class="bi bi-clipboard-check me-1 text-warning"></i>Finaliser l’ordre du jour et partager les documents associés.</li>
                </ul>
                <textarea class="form-control" rows="3" placeholder="Ajoutez vos notes internes ou points à suivre..."></textarea>
                <small class="text-muted d-block mt-2">Ces notes ne sont pas enregistrées : elles servent de pense-bête local.</small>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        
        <?php if($meeting->organizationCommittee): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i> Comité d'Organisation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><?php echo e($meeting->organizationCommittee->name); ?></strong>
                        <?php if($meeting->organizationCommittee->description): ?>
                            <p class="text-muted small mb-0 mt-1"><?php echo e($meeting->organizationCommittee->description); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if($meeting->organizationCommittee->members->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Membre</th>
                                        <th>Rôle</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $meeting->organizationCommittee->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($member->user->name ?? 'N/A'); ?></td>
                                            <td><span class="badge bg-info"><?php echo e($member->role); ?></span></td>
                                            <td class="text-muted small"><?php echo e($member->notes ?? '—'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    <div class="mt-3">
                        <a href="<?php echo e(route('organization-committees.show', $meeting->organizationCommittee)); ?>" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Voir les détails du comité
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $meeting)): ?>
                <div class="card shadow-sm border-0 mb-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                    Aucun comité d'organisation assigné
                                </h6>
                                <p class="text-muted small mb-0">EF20 - Assignez un comité d'organisation à cette réunion</p>
                            </div>
                            <a href="<?php echo e(route('organization-committees.create', ['meeting_id' => $meeting->id])); ?>" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-plus-circle"></i> Créer/Assigner un comité
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        
        

        
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="mb-3">Informations système</h6>
                <div class="small mb-1">
                    <span class="text-muted">Créée le :</span>
                    <?php echo e($meeting->created_at?->format('d/m/Y H:i') ?? '—'); ?>

                </div>
                <div class="small mb-1">
                    <span class="text-muted">Dernière mise à jour :</span>
                    <?php echo e($meeting->updated_at?->format('d/m/Y H:i') ?? '—'); ?>

                </div>
                <div class="small">
                    <span class="text-muted">Organisateur :</span>
                    <?php echo e($creatorName ?? 'Non renseigné'); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Si un message de succès pour une délégation est affiché, faire défiler vers la section des délégations
    <?php
        $successMessage = session('success');
        $isDelegationMessage = $successMessage && (strpos($successMessage, 'Délégation') !== false || strpos($successMessage, 'délégation') !== false);
    ?>
    
    <?php if($isDelegationMessage): ?>
        setTimeout(function() {
            const delegationsSection = document.getElementById('delegations-section');
            if (delegationsSection) {
                delegationsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Ajouter un effet de surbrillance
                delegationsSection.style.transition = 'box-shadow 0.3s ease';
                delegationsSection.style.boxShadow = '0 0 20px rgba(13, 110, 253, 0.5)';
                setTimeout(function() {
                    delegationsSection.style.boxShadow = '';
                }, 2000);
            }
        }, 500);
    <?php endif; ?>
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/meetings/show.blade.php ENDPATH**/ ?>