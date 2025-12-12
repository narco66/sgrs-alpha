<?php $__env->startSection('title', 'Invitation - ' . $meeting->title); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .invitation-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .invitation-title {
        font-size: 18px;
        color: #1e3a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 20px 0;
    }
    .invitation-ref {
        font-size: 10px;
        color: #6b7280;
        margin-bottom: 20px;
    }
    .invitation-body {
        text-align: justify;
        line-height: 1.8;
        margin: 20px 0;
    }
    .invitation-details {
        background: #f9fafb;
        padding: 15px;
        border-left: 4px solid #1e3a8a;
        margin: 20px 0;
    }
    .invitation-signature {
        margin-top: 50px;
        text-align: right;
    }
    .invitation-signature-name {
        font-weight: bold;
        margin-top: 40px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="invitation-header">
    <div class="invitation-ref">
        Réf: CEEAC/SGRS/<?php echo e(date('Y')); ?>/<?php echo e(str_pad($meeting->id, 4, '0', STR_PAD_LEFT)); ?>

        <br>
        Libreville, le <?php echo e(now()->format('d/m/Y')); ?>

    </div>
    
    <div class="invitation-title">
        LETTRE D'INVITATION
    </div>
</div>


<div style="margin-bottom: 30px;">
    <?php if(isset($recipient)): ?>
        <p><strong>À l'attention de :</strong></p>
        <p><?php echo e($recipient['title'] ?? ''); ?> <?php echo e($recipient['name'] ?? 'Madame/Monsieur'); ?></p>
        <?php if(isset($recipient['position'])): ?>
            <p><?php echo e($recipient['position']); ?></p>
        <?php endif; ?>
        <?php if(isset($recipient['organization'])): ?>
            <p><?php echo e($recipient['organization']); ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p><strong>À l'attention de :</strong></p>
        <p>Madame/Monsieur le Représentant</p>
    <?php endif; ?>
</div>


<div style="margin-bottom: 20px;">
    <p><strong>Objet :</strong> Invitation à la réunion « <?php echo e($meeting->title); ?> »</p>
</div>


<div class="invitation-body">
    <p>Madame, Monsieur,</p>
    
    <p>
        J'ai l'honneur de vous inviter à participer à la réunion « <strong><?php echo e($meeting->title); ?></strong> » 
        organisée par la Commission de la Communauté Économique des États de l'Afrique Centrale (CEEAC).
    </p>
    
    <p>
        Cette réunion se tiendra conformément aux détails suivants :
    </p>
</div>


<div class="invitation-details">
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 35%; font-weight: bold; border: none; padding: 5px 0;">Date :</td>
            <td style="border: none; padding: 5px 0;"><?php echo e($meeting->start_at?->format('d/m/Y') ?? 'À confirmer'); ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 5px 0;">Heure :</td>
            <td style="border: none; padding: 5px 0;"><?php echo e($meeting->start_at?->format('H:i') ?? 'À confirmer'); ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 5px 0;">Lieu :</td>
            <td style="border: none; padding: 5px 0;">
                <?php if($meeting->room): ?>
                    <?php echo e($meeting->room->name); ?>

                    <?php if($meeting->room->address): ?>
                        <br><?php echo e($meeting->room->address); ?>

                    <?php endif; ?>
                <?php else: ?>
                    Siège de la CEEAC, Libreville, Gabon
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; border: none; padding: 5px 0;">Configuration :</td>
            <td style="border: none; padding: 5px 0;">
                <?php
                    $configs = [
                        'presentiel' => 'En présentiel',
                        'hybride' => 'Hybride (présentiel et visioconférence)',
                        'visioconference' => 'Visioconférence'
                    ];
                ?>
                <?php echo e($configs[$meeting->configuration ?? 'presentiel'] ?? 'En présentiel'); ?>

            </td>
        </tr>
        <?php
            $relations = $meeting->getRelations();
            $meetingTypeName = isset($relations['meetingType']) && is_object($relations['meetingType']) ? $relations['meetingType']->name : null;
        ?>
        <?php if($meetingTypeName): ?>
        <tr>
            <td style="font-weight: bold; border: none; padding: 5px 0;">Type de réunion :</td>
            <td style="border: none; padding: 5px 0;"><?php echo e($meetingTypeName); ?></td>
        </tr>
        <?php endif; ?>
    </table>
</div>


<?php if($meeting->agenda): ?>
<div class="section">
    <h3>Ordre du jour provisoire</h3>
    <div style="background: #f9fafb; padding: 10px; white-space: pre-wrap;"><?php echo e($meeting->agenda); ?></div>
</div>
<?php endif; ?>


<div class="invitation-body">
    <p>
        Nous vous prions de bien vouloir confirmer votre participation en retournant le formulaire 
        de confirmation ci-joint ou en contactant le secrétariat de la réunion avant le 
        <strong><?php echo e($meeting->start_at?->subDays(7)->format('d/m/Y') ?? 'date à confirmer'); ?></strong>.
    </p>
    
    <p>
        Pour toute information complémentaire, veuillez contacter :
        <br>Email : commission@ceeac-eccas.org
        <br>Tél : +(241) 44 47 31 / 44 47 34
    </p>
    
    <p>
        Dans l'attente de votre participation, nous vous prions d'agréer, Madame, Monsieur, 
        l'expression de notre haute considération.
    </p>
</div>


<div class="invitation-signature">
    <p>Le Président de la Commission</p>
    <div class="invitation-signature-name">
        ____________________________
        <br><br>
        <em>Commission de la CEEAC</em>
    </div>
</div>


<div style="margin-top: 40px; font-size: 10px; color: #6b7280;">
    <p><strong>Pièces jointes :</strong></p>
    <ul>
        <li>Formulaire de confirmation de participation</li>
        <li>Note d'information logistique</li>
        <?php if($meeting->agenda): ?>
            <li>Ordre du jour détaillé</li>
        <?php endif; ?>
    </ul>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('pdf.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sgrs-alpha\resources\views/meetings/pdf-invitation.blade.php ENDPATH**/ ?>