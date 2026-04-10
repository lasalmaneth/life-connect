<?php
/**
 * Body Workflow Stepper Partial
 */
$reqStatus = $currentRequest->status ?? null;
$hasDocs = !empty($docs);
?>
<div class="p-track-container mb-4">
    <div class="p-track-label">Body Donation Protocol</div>
    
    <div class="progress-steps">
        <div class="progress-line"></div>
        
        <!-- Step 1: Death -->
        <div class="step done">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <div class="step-lbl">Death Marked</div>
        </div>

        <!-- Step 2: Selection -->
        <div class="step <?= $currentRequest ? 'done' : 'active' ?>">
            <div class="step-circle"><?= $currentRequest ? '<i class="fas fa-check"></i>' : '2' ?></div>
            <div class="step-lbl">School Request</div>
        </div>

        <!-- Step 3: Documents -->
        <?php 
            $step3State = ($currentRequest && !$hasDocs) ? 'active' : ($hasDocs ? 'done' : '');
        ?>
        <div class="step <?= $step3State ?>">
            <div class="step-circle"><?= $hasDocs ? '<i class="fas fa-check"></i>' : '3' ?></div>
            <div class="step-lbl">Documents</div>
        </div>

        <!-- Step 4: Examination -->
        <?php 
            $step4State = ($reqStatus === 'EXAMINATION') ? 'active' : '';
        ?>
        <div class="step <?= $step4State ?>">
            <div class="step-circle">4</div>
            <div class="step-lbl">Examination</div>
        </div>

        <!-- Step 5: Decision -->
        <?php 
            $step5State = ($reqStatus === 'APPROVED') ? 'done' : ($reqStatus === 'REJECTED' ? 'danger' : '');
        ?>
        <div class="step <?= $step5State ?>">
            <div class="step-circle"><?= $reqStatus === 'APPROVED' ? '<i class="fas fa-check"></i>' : '5' ?></div>
            <div class="step-lbl">Final Decision</div>
        </div>
    </div>
</div>
