

<?php $__env->startSection('title', 'Procès-verbal - ' . $meeting->title); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .minutes-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #1e3a8a;
    }
    .minutes-title {
        font-size: 20px;
        color: #1e3a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 10px 0;
    }
    .minutes-subtitle {
        font-size: 14px;
        color: #374151;
    }
    .minutes-section {
        margin: 20px 0;
        page-break-inside: avoid;
    }
    .minutes-section-title {
        background: #1e3a8a;
        color: #ffffff;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .minutes-content {
        padding: 10px;
        background: #f9fafb;
        border-left: 3px solid #1e3a8a;
        min-height: 60px;
    }
    .editable-field {
        border-bottom: 1px dotted #9ca3af;
        min-height: 20px;
        display: block;
        margin: 5px 0;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="minutes-header">
    <div class="minutes-title">PROCÈS-VERBAL DE RÉUNION</div>
    <div class="minutes-subtitle"><?php echo e($meeting->title); ?></div>
    <p class="text-muted" style="margin-top: 10px;">
        Réf: PV/CEEAC/<?php echo e(date('Y')); ?>/<?php echo e(str_pad($meeting->id, 4, '0', STR_PAD_LEFT)); ?>

    </p>
</div>


<div class="minutes-section">
    <div class="minutes-section-title">1. INFORMATIONS GÉNÉRALES</div>
    <div class="minutes-content">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 30%; font-weight: bold; border: none; padding: 4px 0;">Date de la réunion :</td>
                <td style="border: none; padding: 4px 0;"><?php echo e($meeting->start_at?->format('d/m/Y') ?? '___________________'); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; border: none; padding: 4px 0;">Heure de début :</td>
                <td style="border: none; padding: 4px 0;"><?php echo e($meeting->start_at?->format('H:i') ?? '___________________'); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; border: none; padding: 4px 0;">Heure de fin :</td>
                <td style="border: none; padding: 4px 0;"><?php echo e($meeting->end_at?->format('H:i') ?? '___________________'); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; border: none; padding: 4px 0;">Lieu :</td>
                <td style="border: none; padding: 4px 0;"><?php echo e($meeting->room?->name ?? '___________________'); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; border: none; padding: 4px 0;">Type de réunion :</td>
                <?php
                    $relations = $meeting->getRelations();
                    $meetingTypeName = isset($relations['type']) && is_object($relations['type']) ? $relations['type']->name : null;
                ?>
                <td style="border: none; padding: 4px 0;"><?php echo e($meetingTypeName ?? '___________________'); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold; border: none; padding: 4px 0;">Présidé par :</td>
                <td style="border: none; padding: 4px 0;"><span class="editable-field">___________________</span></td>
            </tr>
        </table>
    </div>
</div>


<div class="minutes-section">
    <div class="minutes-section-title">2. LISTE DES PARTICIPANTS</div>
    <div class="minutes-content">
        <?php 
            $delegations = $meeting->delegations ?? collect();
            $totalParticipants = 0;
        ?>
        
        <?php if($delegations->count()): ?>
            <p><strong>Délégations présentes :</strong></p>
            <ul>
                <?php $__currentLoopData = $delegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delegation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $memberCount = $delegation->members?->count() ?? 0; $totalParticipants += $memberCount; ?>
                    <li><?php echo e($delegation->title); ?> (<?php echo e($delegation->country ?? $delegation->organization_name ?? ''); ?>) - <?php echo e($memberCount); ?> membre(s)</li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
        
        <?php if($meeting->organizationCommittee): ?>
            <?php $orgCount = $meeting->organizationCommittee->members?->count() ?? 0; $totalParticipants += $orgCount; ?>
            <p><strong>Comité d'organisation :</strong> <?php echo e($orgCount); ?> membre(s)</p>
        <?php endif; ?>
        
        <p style="margin-top: 10px;"><strong>Total participants :</strong> <?php echo e($totalParticipants); ?> personnes</p>
        <p><em>(Voir feuille de présence annexée)</em></p>
    </div>
</div>


<div class="minutes-section">
    <div class="minutes-section-title">3. ORDRE DU JOUR</div>
    <div class="minutes-content">
        <?php if($meeting->agenda): ?>
            <div style="white-space: pre-wrap;"><?php echo e($meeting->agenda); ?></div>
        <?php else: ?>
            <span class="editable-field" style="min-height: 80px; display: block;"></span>
        <?php endif; ?>
    </div>
</div>


<div class="minutes-section">
    <div class="minutes-section-title">4. DÉROULEMENT DES TRAVAUX</div>
    <div class="minutes-content" style="min-height: 150px;">
        <p><strong>4.1. Ouverture de la séance</strong></p>
        <span class="editable-field" style="min-height: 60px; display: block;"></span>
        
        <p style="margin-top: 15px;"><strong>4.2. Adoption de l'ordre du jour</strong></p>
        <span class="editable-field" style="min-height: 40px; display: block;"></span>
        
        <p style="margin-top: 15px;"><strong>4.3. Examen des points à l'ordre du jour</strong></p>
        <span class="editable-field" style="min-height: 100px; display: block;"></span>
    </div>
</div>


<div class="minutes-section">
    <div class="minutes-section-title">5. DÉCISIONS ET RECOMMANDATIONS</div>
    <div class="minutes-content" style="min-height: 100px;">
        <span class="editable-field" style="min-height: 80px; display: block;"></span>
    </div>
</div>


<div class="minutes-section">
    <div class="minutes-section-title">6. POINTS D'ACTION</div>
    <div class="minutes-content">
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">N°</th>
                    <th>Action</th>
                    <th style="width: 120px;">Responsable</th>
                    <th style="width: 80px;">Échéance</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i = 1; $i <= 5; $i++): ?>
                <tr>
                    <td style="text-align: center;"><?php echo e($i); ?></td>
                    <td style="height: 30px;"></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="minutes-section">
    <div class="minutes-section-title">7. CLÔTURE</div>
    <div class="minutes-content">
        <p>L'ordre du jour étant épuisé, la séance a été levée à <span class="editable-field" style="width: 60px; display: inline-block;">_____</span> heures.</p>
        <p>La prochaine réunion est prévue le <span class="editable-field" style="width: 100px; display: inline-block;">_______________</span>.</p>
    </div>
</div>


<div class="section mt-4">
    <h3>Signatures</h3>
    <table style="width: 100%; border: none; margin-top: 20px;">
        <tr>
            <td style="width: 50%; border: none; text-align: center; vertical-align: top;">
                <p><strong>Le Président de séance</strong></p>
                <div style="margin-top: 50px; border-top: 1px solid #374151; width: 80%; margin-left: 10%;">
                    <p style="margin-top: 5px;">Nom et signature</p>
                </div>
            </td>
            <td style="border: none; text-align: center; vertical-align: top;">
                <p><strong>Le Secrétaire de séance</strong></p>
                <div style="margin-top: 50px; border-top: 1px solid #374151; width: 80%; margin-left: 10%;">
                    <p style="margin-top: 5px;">Nom et signature</p>
                </div>
            </td>
        </tr>
    </table>
</div>


<div class="section mt-4">
    <p class="text-muted text-small">
        <strong>Annexes :</strong>
        <br>- Feuille de présence
        <br>- Documents de travail
        <?php if($meeting->documents && $meeting->documents->count()): ?>
            <?php $__currentLoopData = $meeting->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <br>- <?php echo e($doc->title); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </p>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('pdf.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/meetings/pdf-minutes.blade.php ENDPATH**/ ?>