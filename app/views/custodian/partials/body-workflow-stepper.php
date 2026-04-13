<?php
/**
 * Body Workflow Stepper Partial
 */
$reqStatus = $currentRequest->request_status ?? null;
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
            $docStatus = $currentRequest->document_status ?? 'PENDING';
            $step3State = ($docStatus === 'ACCEPTED') ? 'done' : (($currentRequest->request_status ?? '') === 'ACCEPTED' ? 'active' : '');
        ?>
        <div class="step <?= $step3State ?>">
            <div class="step-circle"><?= ($docStatus === 'ACCEPTED') ? '<i class="fas fa-check"></i>' : '3' ?></div>
            <div class="step-lbl">Documents</div>
        </div>

        <!-- Step 4: Examination -->
        <?php 
            $examStatus = $currentRequest->final_exam_status ?? 'PENDING';
            $step4State = ($examStatus === 'ACCEPTED') ? 'done' : (($docStatus === 'ACCEPTED') ? 'active' : '');
        ?>
        <div class="step <?= $step4State ?>">
            <div class="step-circle"><?= ($examStatus === 'ACCEPTED') ? '<i class="fas fa-check"></i>' : '4' ?></div>
            <div class="step-lbl">Examination</div>
        </div>

        <!-- Step 5: Appreciation -->
        <?php 
            $hasCorrespondence = !empty($appreciation_letters) || !empty($certificates);
            $step5State = $hasCorrespondence ? 'done' : (($examStatus === 'ACCEPTED') ? 'active' : '');
        ?>
        <div class="step <?= $step5State ?>">
            <div class="step-circle"><?= $hasCorrespondence ? '<i class="fas fa-check"></i>' : '5' ?></div>
            <div class="step-lbl">Appreciation</div>
        </div>
    </div>
</div>
