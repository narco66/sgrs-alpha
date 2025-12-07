

<?php $__env->startSection('title', 'Feuille de présence - ' . $meeting->title); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .attendance-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #1e3a8a;
    }
    .attendance-title {
        font-size: 18px;
        color: #1e3a8a;
        text-transform: uppercase;
        margin: 10px 0;
    }
    .attendance-meeting {
        font-size: 14px;
        color: #374151;
        margin: 5px 0;
    }
    .attendance-info {
        font-size: 11px;
        color: #6b7280;
    }
    .signature-cell {
        width: 120px;
        height: 40px;
        border: 1px solid #e5e7eb;
    }
    .attendance-table th {
        background: #1e3a8a;
        color: #ffffff;
        font-size: 10px;
        padding: 8px;
    }
    .attendance-table td {
        padding: 6px 8px;
        font-size: 10px;
        vertical-align: middle;
    }
    .empty-row td {
        height: 35px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="attendance-header">
    <div class="attendance-title">FEUILLE DE PRÉSENCE</div>
    <div class="attendance-meeting"><?php echo e($meeting->title); ?></div>
    <div class="attendance-info">
        Date : <?php echo e($meeting->start_at?->format('d/m/Y') ?? 'Non définie'); ?> | 
        Heure : <?php echo e($meeting->start_at?->format('H:i') ?? 'Non définie'); ?> |
        Salle : <?php echo e($meeting->room?->name ?? 'Non définie'); ?>

    </div>
</div>

<?php
    $relations = $meeting->getRelations();
    $meetingTypeName = isset($relations['type']) && is_object($relations['type']) ? $relations['type']->name : null;
    $organizerName = isset($relations['organizer']) && is_object($relations['organizer']) ? $relations['organizer']->name : null;
?>


<div class="info-box info-box-primary mb-3">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none;">
                <strong>Type de réunion :</strong> <?php echo e($meetingTypeName ?? 'Non défini'); ?>

            </td>
            <td style="border: none;">
                <strong>Organisateur :</strong> <?php echo e($organizerName ?? 'Non défini'); ?>

            </td>
        </tr>
    </table>
</div>


<table class="attendance-table">
    <thead>
        <tr>
            <th style="width: 30px;">N°</th>
            <th>Nom et Prénom</th>
            <th style="width: 120px;">Organisation/Pays</th>
            <th style="width: 100px;">Fonction</th>
            <th style="width: 80px;">Téléphone</th>
            <th style="width: 100px;">Email</th>
            <th class="signature-cell">Signature</th>
        </tr>
    </thead>
    <tbody>
        <?php $counter = 1; ?>
        
        
        <?php if($meeting->organizationCommittee && $meeting->organizationCommittee->members->count()): ?>
            <?php $__currentLoopData = $meeting->organizationCommittee->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="text-align: center;"><?php echo e($counter++); ?></td>
                    <td><?php echo e($member->user?->name ?? 'N/A'); ?></td>
                    <td>CEEAC (Comité org.)</td>
                    <td><?php echo e($member->role ?? '—'); ?></td>
                    <td><?php echo e($member->user?->phone ?? '—'); ?></td>
                    <td style="font-size: 8px;"><?php echo e($member->user?->email ?? '—'); ?></td>
                    <td class="signature-cell"></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        
        
        <?php $delegations = $meeting->delegations ?? collect(); ?>
        <?php $__currentLoopData = $delegations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delegation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $delegationMembers = $delegation->members ?? collect(); ?>
            <?php $__currentLoopData = $delegationMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="text-align: center;"><?php echo e($counter++); ?></td>
                    <td>
                        <?php echo e(trim(($member->title ?? '') . ' ' . ($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: $member->full_name ?? '—'); ?>

                    </td>
                    <td><?php echo e($delegation->title); ?> (<?php echo e($delegation->country ?? $delegation->organization_name ?? '—'); ?>)</td>
                    <td><?php echo e($member->position ?? '—'); ?></td>
                    <td><?php echo e($member->phone ?? '—'); ?></td>
                    <td style="font-size: 8px;"><?php echo e($member->email ?? '—'); ?></td>
                    <td class="signature-cell"></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        
        <?php for($i = 0; $i < 10; $i++): ?>
            <tr class="empty-row">
                <td style="text-align: center;"><?php echo e($counter++); ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="signature-cell"></td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>


<div class="section mt-4">
    <table style="width: 50%; margin-left: auto;">
        <tr>
            <td style="background: #f3f4f6; font-weight: bold;">Total présents :</td>
            <td style="width: 100px;"></td>
        </tr>
        <tr>
            <td style="background: #f3f4f6; font-weight: bold;">Total excusés :</td>
            <td></td>
        </tr>
        <tr>
            <td style="background: #f3f4f6; font-weight: bold;">Total absents :</td>
            <td></td>
        </tr>
    </table>
</div>


<div class="section mt-4">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none; vertical-align: top;">
                <p><strong>Établi par :</strong></p>
                <p style="margin-top: 40px;">
                    Nom : _________________________
                    <br><br>
                    Date : _________________________
                    <br><br>
                    Signature :
                </p>
            </td>
            <td style="border: none; vertical-align: top;">
                <p><strong>Validé par :</strong></p>
                <p style="margin-top: 40px;">
                    Nom : _________________________
                    <br><br>
                    Fonction : _________________________
                    <br><br>
                    Signature :
                </p>
            </td>
        </tr>
    </table>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('pdf.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/meetings/pdf-attendance.blade.php ENDPATH**/ ?>