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
                    <p>Visit <a href="<?= ROOT ?>/custodian/institution-requests">Selection</a> to re-route to another <?= strtolower(str_replace('_', ' ', $currentInstRequest->institution_type)) ?>.</p>
                </div>
            </div>
            <button class="cp-btn cp-btn--primary cp-btn--fw cp-btn--locked" disabled>Process Document Bundle (Locked)</button>
        <?php elseif ($activeCase->overall_status === 'COMPLETED' || $activeCase->overall_status === 'SUCCESSFUL' || ($currentInstRequest->final_exam_status ?? '') === 'ACCEPTED'): ?>
            <div class="cp-notice cp-notice--success mb-4" style="background: #f5f3ff; border: 1px solid #ddd6fe; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.1);">
                <i class="fas fa-award" style="color: #7c3aed; font-size: 1.25rem;"></i>
                <div>
                    <strong style="color: #5b21b6;">Donation Journey Successful</strong>
                    <p style="color: #6d28d9; margin-top: 2px;">The hospital has finalized the certification. All recognition documents are now ready.</p>
                </div>
            </div>
            <div class="mt-auto">
                <a href="<?= ROOT ?>/custodian/certificates" class="cp-btn cp-btn--fw" style="background: #7c3aed; color: white; border: none; font-weight: 700; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2);"><i class="fas fa-certificate mr-1"></i> View Recognition Bundle</a>
            </div>
        <?php elseif ($currentInstRequest->institution_status === 'PENDING'): ?>
            <div class="cp-notice cp-notice--warning mb-4">
                <i class="fas fa-clock"></i>
                <div>
                    <strong>Pending Review</strong>
                    <p>Wait for <?= str_replace('_', ' ', $currentInstRequest->institution_type) ?> approval before submitting documents.</p>
                </div>
            </div>
            <button class="cp-btn cp-btn--primary cp-btn--fw cp-btn--locked" disabled>Process Document Bundle (Locked)</button>
        <?php elseif ($currentInstRequest->institution_status === 'ACCEPTED' && $currentInstRequest->document_status === 'ACCEPTED' && !empty($currentInstRequest->handover_date)): ?>
            <div class="cp-notice cp-notice--success mb-4" style="background: #f0fdf4; border-color: #bbf7d0;">
                <i class="fas fa-truck-medical text-success"></i>
                <div>
                    <strong>Handover Ready</strong>
                    <p>Scheduled for <strong><?= date('M j, Y', strtotime($currentInstRequest->handover_date)) ?></strong> at <strong><?= date('g:i A', strtotime($currentInstRequest->handover_time)) ?></strong>.</p>
                </div>
            </div>
            <?php if (!empty($currentInstRequest->handover_message)): ?>
                <div class="p-3 bg-gray-50 border rounded text-gray-600 mb-4 text-sm italic">
                    "<?= htmlspecialchars($currentInstRequest->handover_message) ?>"
                </div>
            <?php endif; ?>
            <div class="mt-auto">
                <a href="<?= ROOT ?>/custodian/documents" class="cp-btn cp-btn--secondary cp-btn--fw"><i class="fas fa-folder-open"></i> View Documents</a>
            </div>
        <?php else: ?>
            <div class="mt-auto">
                <a href="<?= ROOT ?>/custodian/documents" class="cp-btn cp-btn--primary cp-btn--fw">Process Document Bundle</a>
            </div>
        <?php endif; ?>
    </div>
</div>
