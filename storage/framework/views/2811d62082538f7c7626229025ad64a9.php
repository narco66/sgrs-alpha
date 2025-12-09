<?php $__env->startSection('title', 'Délégation - ' . $delegation->title); ?>

<?php $__env->startSection('content'); ?>

<div class="section">
    <h1><?php echo e($delegation->title); ?></h1>
    
    <p>
        <?php if($delegation->is_active): ?>
            <span class="badge badge-success">Actif</span>
        <?php else: ?>
            <span class="badge badge-secondary">Inactif</span>
        <?php endif; ?>
        
        <?php if($delegation->participation_status): ?>
            <?php echo $__env->make('pdf.partials.status-badge', ['status' => $delegation->participation_status, 'type' => 'delegation'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>
    </p>
    
    <?php if($delegation->code || $delegation->country): ?>
        <p class="text-muted">
            <?php if($delegation->code): ?> Code : <?php echo e($delegation->code); ?> <?php endif; ?>
            <?php if($delegation->code && $delegation->country): ?> — <?php endif; ?>
            <?php if($delegation->country): ?> Pays : <?php echo e($delegation->country); ?> <?php endif; ?>
        </p>
    <?php endif; ?>
</div>


<div class="section">
    <h2>Informations générales</h2>
    
    <?php
        $entityTypes = [
            'state_member' => 'État membre',
            'international_organization' => 'Organisation internationale',
            'technical_partner' => 'Partenaire technique',
            'financial_partner' => 'Partenaire financier',
            'other' => 'Autre'
        ];
    ?>
    
    <?php echo $__env->make('pdf.partials.info-table', ['items' => [
        ['label' => 'Type d\'entité', 'value' => $entityTypes[$delegation->entity_type] ?? $delegation->entity_type ?? 'Non renseigné'],
        ['label' => 'Pays', 'value' => $delegation->country],
        ['label' => 'Organisation', 'value' => $delegation->organization_name],
        ['label' => 'Email de contact', 'value' => $delegation->contact_email],
        ['label' => 'Téléphone', 'value' => $delegation->contact_phone],
        ['label' => 'Adresse', 'value' => $delegation->address],
    ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>


<?php if($delegation->head_of_delegation_name): ?>
<div class="section">
    <h2>Chef de délégation</h2>
    
    <?php echo $__env->make('pdf.partials.info-table', ['items' => [
        ['label' => 'Nom', 'value' => $delegation->head_of_delegation_name],
        ['label' => 'Fonction', 'value' => $delegation->head_of_delegation_position],
        ['label' => 'Email', 'value' => $delegation->head_of_delegation_email],
    ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php endif; ?>


<?php if($delegation->description): ?>
<div class="section">
    <h2>Présentation</h2>
    <p class="text-justify"><?php echo e($delegation->description); ?></p>
</div>
<?php endif; ?>


<div class="section">
    <h2>Réunion associée</h2>
    
    <?php if($delegation->meeting): ?>
        <div class="info-box info-box-primary">
            <p><strong><?php echo e($delegation->meeting->title); ?></strong></p>
            <p class="text-muted">
                <?php echo e($delegation->meeting->start_at?->format('d/m/Y à H:i') ?? 'Date non définie'); ?>

                <?php if($delegation->meeting->room): ?>
                    — Salle : <?php echo e($delegation->meeting->room->name); ?>

                <?php endif; ?>
            </p>
            <?php if($delegation->meeting->meetingType): ?>
                <p class="text-small">Type : <?php echo e($delegation->meeting->meetingType->name); ?></p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">Aucune réunion associée à cette délégation.</p>
    <?php endif; ?>
</div>


<div class="section">
    <h2>Membres de la délégation</h2>
    
    <?php $members = $delegation->members ?? collect(); ?>
    
    <?php if($members->count()): ?>
        <p class="text-muted mb-2"><?php echo e($members->count()); ?> membre(s) enregistré(s)</p>
        
        <?php
            $roles = [
                'head' => 'Chef de délégation',
                'deputy' => 'Chef adjoint',
                'member' => 'Membre',
                'advisor' => 'Conseiller',
                'expert' => 'Expert',
                'interpreter' => 'Interprète'
            ];
        ?>
        
        <table>
            <thead>
                <tr>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Fonction</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <?php echo e(trim(($member->title ?? '') . ' ' . ($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: $member->full_name ?? '—'); ?>

                        </td>
                        <td><?php echo e($member->email ?? '—'); ?></td>
                        <td><?php echo e($member->phone ?? '—'); ?></td>
                        <td><?php echo e($member->position ?? '—'); ?></td>
                        <td><?php echo e($roles[$member->role] ?? $member->role ?? '—'); ?></td>
                        <td><?php echo $__env->make('pdf.partials.status-badge', ['status' => $member->status ?? 'pending', 'type' => 'participant'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">Aucun membre enregistré pour cette délégation.</p>
    <?php endif; ?>
</div>


<?php if(isset($delegation->participants) && $delegation->participants->count()): ?>
<div class="section">
    <h2>Participants utilisateurs</h2>
    
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Service</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $delegation->participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($user->name); ?></td>
                    <td><?php echo e($user->email ?? '—'); ?></td>
                    <td><?php echo e($user->service ?? '—'); ?></td>
                    <td><?php echo e($user->pivot->role ?? 'Participant'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php endif; ?>


<?php if($delegation->notes): ?>
<div class="section">
    <h2>Notes</h2>
    <div class="info-box info-box-warning">
        <p><?php echo e($delegation->notes); ?></p>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/delegations/pdf.blade.php ENDPATH**/ ?>