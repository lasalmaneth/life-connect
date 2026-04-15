<?php
/**
 * Institution Status Component Partial
 * Reusable card to show current target
 */
if (!$currentInstRequest) return;
?>
<div class="cp-section-card h-full">
    <div class="cp-section-card__header">
        <div class="cp-section-card__title">
            <i class="fas fa-file-contract cp-text-blue-600"></i> Active <?= ($currentInstRequest->institution_type === 'HOSPITAL' ? 'Hospital' : 'Institution') ?> Protocol
        </div>
    </div>
    <div class="cp-section-card__body">
        <h5 class="cp-text-lg cp-font-bold cp-text-slate mb-1">Target: <?= htmlspecialchars($currentInstRequest->institution_name) ?></h5>
        <div class="cp-text-sm cp-text-g500 mb-4">
            <i class="fas fa-building cp-mr-1"></i> Type: <?= ($currentInstRequest->institution_type === 'HOSPITAL' ? 'Transplantation Hospital' : str_replace('_', ' ', $currentInstRequest->institution_type)) ?>
        </div>
        
        <div class="mb-4">
            <span class="cp-text-xs cp-text-g400 text-uppercase fw-bold">Current Status:</span>
            <div class="mt-1">
                <span class="badge bd"><?= str_replace('_', ' ', $currentInstRequest->institution_status) ?></span>
            </div>
        </div>
        
        <?php if ($currentInstRequest->institution_status === 'REJECTED'): ?>
            <div class="cp-notice cp-notice--danger mb-4">
                <i class="fas fa-triangle-exclamation"></i>
                <div>
                    <strong>Request Rejected</strong>
                    <p>Visit <a href="<?= ROOT ?>/custodian/institution-requests">Selection</a> to re-route to another hospital.</p>
                </div>
            </div>
            <button class="cp-btn cp-btn--primary cp-btn--fw cp-btn--locked" disabled>Process Document Bundle (Locked)</button>
        <?php elseif ($currentInstRequest->institution_status === 'PENDING'): ?>
            <div class="cp-notice cp-notice--warning mb-4">
                <i class="fas fa-clock"></i>
                <div>
                    <strong>Pending Review</strong>
                    <p>Wait for Medical School approval before submitting documents.</p>
                </div>
            </div>
            <button class="cp-btn cp-btn--primary cp-btn--fw cp-btn--locked" disabled>Process Document Bundle (Locked)</button>
        <?php else: ?>
            <div class="mt-auto">
                <a href="<?= ROOT ?>/custodian/documents" class="cp-btn cp-btn--primary cp-btn--fw">Process Document Bundle</a>
            </div>
        <?php endif; ?>
    </div>
</div>
