<?php $__env->startSection('title', 'Fiche Réunion - ' . $meeting->title); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .meeting-header {
        margin-bottom: 20px;
    }
    .meeting-meta {
        display: table;
        width: 100%;
        margin: 15px 0;
    }
    .meeting-meta-row {
        display: table-row;
    }
    .meeting-meta-cell {
        display: table-cell;
        width: 50%;
        padding: 4px 0;
    }
    .delegation-card {
        margin: 15px 0;
        padding: 10px;
        background: #f9fafb;
        border-left: 3px solid #1e3a8a;
        page-break-inside: avoid;
    }
</style>
<?php $__env->stopSection(); ?>

<?php
    // Préparation des données pour éviter les erreurs de type
    $statusValue = is_object($meeting->status) && property_exists($meeting->status, 'value')
        ? $meeting->status->value
        : ($meeting->status ?? 'brouillon');
    
    // Récupérer les relations chargées (évite le conflit entre la colonne 'type' et la relation 'type')
    $relations = $meeting->getRelations();
    
    // Type de réunion - utiliser la relation chargée
    $meetingTypeModel = $relations['type'] ?? null;
    $meetingTypeName = is_object($meetingTypeModel) ? $meetingTypeModel->name : null;
    
    // Comité
    $committeeModel = $relations['committee'] ?? null;
    $committeeName = is_object($committeeModel) ? $committeeModel->name : null;
    
    // Organisateur
    $organizerModel = $relations['organizer'] ?? null;
    $organizerName = is_object($organizerModel) ? $organizerModel->name : null;
    
    // Salle
    $roomModel = $relations['room'] ?? null;
    $roomName = is_object($roomModel) ? $roomModel->name : null;
?>

<?php $__env->startSection('content'); ?>

<div class="meeting-header">
    <h1><?php echo e($meeting->title); ?></h1>
    
    <p>
        <?php echo $__env->make('pdf.partials.status-badge', ['status' => $statusValue, 'type' => 'meeting'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php if($meetingTypeName): ?>
            <span class="badge badge-info" style="margin-left: 5px;"><?php echo e($meetingTypeName); ?></span>
        <?php endif; ?>
    </p>
</div>


<div class="section">
    <h2>Informations générales</h2>
    
    <?php echo $__env->make('pdf.partials.info-table', ['items' => [
        ['label' => 'Type de réunion', 'value' => $meetingTypeName ?? 'Non renseigné'],
        ['label' => 'Comité', 'value' => $committeeName ?? 'Non renseigné'],
        ['label' => 'Organisateur', 'value' => $organizerName ?? 'Non renseigné'],
        ['label' => 'Salle', 'value' => $roomName ?? 'Non renseignée'],
        ['label' => 'Configuration', 'value' => ucfirst($meeting->configuration ?? 'presentiel')],
        ['label' => 'Date de début', 'value' => $meeting->start_at?->format('d/m/Y à H:i') ?? 'Non définie'],
        ['label' => 'Date de fin', 'value' => $meeting->end_at?->format('d/m/Y à H:i') ?? 'Non définie'],
        ['label' => 'Durée', 'value' => $meeting->duration_minutes ? $meeting->duration_minutes . ' minutes' : 'Non définie'],
        ['label' => 'Rappel', 'value' => $meeting->reminder_minutes_before ? $meeting->reminder_minutes_before . ' minutes avant' : 'Aucun rappel'],
    ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>


<?php if($meeting->description): ?>
<div class="section">
    <h2>Objectif de la réunion</h2>
    <p class="text-justify"><?php echo e($meeting->description); ?></p>
</div>
<?php endif; ?>


<?php if($meeting->agenda): ?>
<div class="section">
    <h2>Ordre du jour</h2>
    <div style="white-space: pre-wrap; background: #f9fafb; padding: 12px; border-radius: 4px;"><?php echo e($meeting->agenda); ?></div>
</div>
<?php endif; ?>


<div class="section avoid-break">
    <h2>Comité d'organisation</h2>
    
    <?php if($meeting->organizationCommittee): ?>
        <div class="subsection">
            <p><strong>Nom :</strong> <?php echo e($meeting->organizationCommittee->name); ?></p>
            <?php if($meeting->organizationCommittee->description): ?>
                <p class="text-muted"><?php echo e($meeting->organizationCommittee->description); ?></p>
            <?php endif; ?>
            <?php if($meeting->organizationCommittee->host_country): ?>
                <p><strong>Pays hôte :</strong> <?php echo e($meeting->organizationCommittee->host_country); ?></p>
            <?php endif; ?>
        </div>
        
        <?php $members = $meeting->organizationCommittee->members ?? collect(); ?>
        <?php if($members->count()): ?>
            <table>
                <thead>
                    <tr>
                        <th>Membre</th>
                        <th>Type</th>
                        <th>Rôle</th>
                        <th>Service/Département</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($member->user?->name ?? 'Non renseigné'); ?></td>
                            <td>
                                <?php
                                    $memberType = $member->member_type ?? 'ceeac';
                                    $typeLabels = ['ceeac' => 'CEEAC', 'host_country' => 'Pays hôte'];
                                ?>
                                <?php echo e($typeLabels[$memberType] ?? $memberType); ?>

                            </td>
                            <td><?php echo e($member->role ?? '—'); ?></td>
                            <td><?php echo e($member->department ?? $member->service ?? '—'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">Aucun membre renseigné dans le comité d'organisation.</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-muted">Aucun comité d'organisation associé à cette réunion.</p>
    <?php endif; ?>
</div>


<?php if($meeting->termsOfReference): ?>
<div class="section avoid-break">
    <h2>Cahier des charges</h2>
    
    <?php echo $__env->make('pdf.partials.info-table', ['items' => [
        ['label' => 'Pays hôte', 'value' => $meeting->termsOfReference->host_country ?? 'Non renseigné'],
        ['label' => 'Date de signature', 'value' => $meeting->termsOfReference->signature_date?->format('d/m/Y') ?? 'Non signé'],
        ['label' => 'Statut', 'value' => ucfirst($meeting->termsOfReference->status ?? 'draft')],
        ['label' => 'Version', 'value' => $meeting->termsOfReference->version ?? '1'],
    ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php endif; ?>


<div class="section">
    <h2>Délégations participantes</h2>
    
    <?php $delegations = $meeting->delegations ?? collect(); ?>
    
    <?php if($delegations->count()): ?>
        <p class="text-muted mb-2"><?php echo e($delegations->count()); ?> délégation(s) enregistrée(s)</p>
        
        <?php $__currentLoopData = $delegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delegation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="delegation-card">
                <h3 style="margin: 0 0 8px 0; color: #1e3a8a;"><?php echo e($delegation->title); ?></h3>
                
                <?php
                    $entityTypes = [
                        'state_member' => 'État membre',
                        'international_organization' => 'Organisation internationale',
                        'technical_partner' => 'Partenaire technique',
                        'financial_partner' => 'Partenaire financier',
                        'other' => 'Autre'
                    ];
                ?>
                
                <table style="margin: 5px 0;">
                    <tr>
                        <td style="width: 30%; background: #f3f4f6;"><strong>Type d'entité</strong></td>
                        <td><?php echo e($entityTypes[$delegation->entity_type] ?? $delegation->entity_type); ?></td>
                    </tr>
                    <?php if($delegation->country): ?>
                    <tr>
                        <td style="background: #f3f4f6;"><strong>Pays</strong></td>
                        <td><?php echo e($delegation->country); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($delegation->organization_name): ?>
                    <tr>
                        <td style="background: #f3f4f6;"><strong>Organisation</strong></td>
                        <td><?php echo e($delegation->organization_name); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($delegation->head_of_delegation_name): ?>
                    <tr>
                        <td style="background: #f3f4f6;"><strong>Chef de délégation</strong></td>
                        <td>
                            <?php echo e($delegation->head_of_delegation_name); ?>

                            <?php if($delegation->head_of_delegation_position): ?>
                                <span class="text-muted">(<?php echo e($delegation->head_of_delegation_position); ?>)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td style="background: #f3f4f6;"><strong>Statut</strong></td>
                        <td><?php echo $__env->make('pdf.partials.status-badge', ['status' => $delegation->participation_status, 'type' => 'delegation'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                    </tr>
                </table>
                
                
                <?php $delegationMembers = $delegation->members ?? collect(); ?>
                <?php if($delegationMembers->count()): ?>
                    <h4 style="margin: 10px 0 5px 0;">Membres (<?php echo e($delegationMembers->count()); ?>)</h4>
                    <table style="font-size: 9px;">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Fonction</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $delegationMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $roles = [
                                        'head' => 'Chef',
                                        'deputy' => 'Adjoint',
                                        'member' => 'Membre',
                                        'advisor' => 'Conseiller',
                                        'expert' => 'Expert',
                                        'interpreter' => 'Interprète'
                                    ];
                                ?>
                                <tr>
                                    <td><?php echo e(trim(($member->title ?? '') . ' ' . ($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: $member->full_name ?? '—'); ?></td>
                                    <td><?php echo e($member->email ?? '—'); ?></td>
                                    <td><?php echo e($member->position ?? '—'); ?></td>
                                    <td><?php echo e($roles[$member->role] ?? $member->role ?? '—'); ?></td>
                                    <td><?php echo $__env->make('pdf.partials.status-badge', ['status' => $member->status ?? 'pending', 'type' => 'participant'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <p class="text-muted">Aucune délégation enregistrée pour cette réunion.</p>
    <?php endif; ?>
</div>


<div class="section">
    <h2>Documents associés</h2>
    
    <?php $documents = $meeting->documents ?? collect(); ?>
    
    <?php if($documents->count()): ?>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Date d'ajout</th>
                    <th>Auteur</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($doc->title); ?></td>
                        <td><?php echo e($doc->type?->name ?? $doc->document_type ?? '—'); ?></td>
                        <td><?php echo e($doc->created_at?->format('d/m/Y') ?? '—'); ?></td>
                        <td><?php echo e($doc->uploader?->name ?? '—'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">Aucun document associé à cette réunion.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/meetings/pdf.blade.php ENDPATH**/ ?>