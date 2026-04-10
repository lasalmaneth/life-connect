<?php
/**
 * Organ Workflow Stepper Partial
 */
$reqStatus = $currentRequest->status ?? null;
$hasDocs = !empty($docs);
?>
<div class="p-track-container mb-4" style="border-color: #fed7aa; background-color: #fffaf0;">
    <div class="p-track-label" style="background-color: transparent; border: 0; border-bottom: 1px solid #ffedd5; color: #ea580c;">Organ Donation Protocol</div>
    
    <div class="progress-steps">
        <div class="progress-line"></div>

        <!-- Step 1: Death -->
        <div class="step done">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <div class="step-lbl">Death Marked</div>
        </div>

        <!-- Step 2: Selection -->
        <div class="step <?= $currentRequest ? 'done' : 'active' ?>" style="<?= !$currentRequest ? '--blue-600: #ea580c; --blue-100: #ffedd5;' : '' ?>">
            <div class="step-circle"><?= $currentRequest ? '<i class="fas fa-check"></i>' : '2' ?></div>
            <div class="step-lbl">Hospital Request</div>
        </div>

        <!-- Step 3: Medical Reports -->
        <?php 
            $step3State = ($currentRequest && !$hasDocs) ? 'active' : ($hasDocs ? 'done' : '');
        ?>
        <div class="step <?= $step3State ?>" style="<?= $step3State === 'active' ? '--blue-600: #ea580c; --blue-100: #ffedd5;' : '' ?>">
            <div class="step-circle"><?= $hasDocs ? '<i class="fas fa-check"></i>' : '3' ?></div>
            <div class="step-lbl">Medical Reports</div>
        </div>

        <!-- Step 4: Decision -->
        <?php 
            $step4State = ($reqStatus === 'APPROVED') ? 'done' : ($reqStatus === 'REJECTED' ? 'danger' : '');
        ?>
        <div class="step <?= $step4State ?>">
            <div class="step-circle"><?= $reqStatus === 'APPROVED' ? '<i class="fas fa-check"></i>' : '4' ?></div>
            <div class="step-lbl">Final Decision</div>
        </div>
    </div>
</div>
