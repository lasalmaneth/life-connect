<?php
/**
 * Institution Status Component Partial
 * Reusable card to show current target
 */
if (!$currentInstRequest) return;
?>
<div class="cp-section-card mb-4 mt-4">
    <div class="cp-section-card__header">
        <div class="cp-section-card__title"><i class="fas fa-file-contract"></i> Active Institution Protocol</div>
    </div>
    <div class="cp-section-card__body p-4">
        <h5 class="cp-text-lg cp-font-bold cp-text-slate mb-1">Target: <?= htmlspecialchars($currentInstRequest->institution_name) ?></h5>
        <div class="cp-text-sm cp-text-g500 mb-3"><i class="fas fa-building cp-mr-1"></i> Type: <?= str_replace('_', ' ', $currentInstRequest->institution_type) ?></div>
        
        <p class="mb-4">Status: <span class="badge bd"><?= str_replace('_', ' ', $currentInstRequest->institution_status) ?></span></p>
        
        <?php if ($currentInstRequest->institution_status === 'REJECTED'): ?>
            <div class="alert alert-danger p-2 mb-3">
                <strong><i class="fas fa-triangle-exclamation"></i> Request Rejected</strong><br>
                <small>Please visit the <a href="<?= ROOT ?>/custodian/institution-requests">Institution Selection</a> page to re-route this donor to an alternate consented medical school if available.</small>
            </div>
            
            <button class="cp-btn cp-btn--primary" style="opacity: 0.5; cursor: not-allowed;" disabled title="You cannot process documents for a rejected request.">Process Document Bundle (Locked)</button>
        <?php elseif ($currentInstRequest->institution_status === 'PENDING'): ?>
            <button class="cp-btn cp-btn--primary" style="opacity: 0.5; cursor: not-allowed;" disabled title="Waiting for Medical School approval.">Process Document Bundle (Locked)</button>
        <?php else: ?>
            <a href="<?= ROOT ?>/custodian/documents" class="cp-btn cp-btn--primary">Process Document Bundle</a>
        <?php endif; ?>
    </div>
</div>
