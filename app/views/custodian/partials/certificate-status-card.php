<?php
/**
 * Custodian Portal — Certificate Status Card Partial
 */
$certs = $certificates ?? [];
?>
<div class="cp-section-card h-100">
    <div class="cp-section-card__header">
        <div class="cp-section-card__title"><i class="fas fa-certificate cp-text-blue-600"></i> Certification Status</div>
    </div>
    <div class="cp-section-card__body">
        <?php if (!empty($certs)): ?>
            <div class="cp-notice cp-notice--success mb-4">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Certificate Issued</strong>
                    <p>Final donation certificate is available for download.</p>
                </div>
            </div>
            <a href="<?= ROOT ?>/custodian/certificates" class="cp-btn cp-btn--primary cp-btn--fw">View Certificates</a>
        <?php else: ?>
            <div class="cp-notice cp-notice--info mb-4">
                <i class="fas fa-clock"></i>
                <div>
                    <strong>Pending Final Approval</strong>
                    <p>Certification will be generated once the host institution completes the case.</p>
                </div>
            </div>
            <button class="cp-btn cp-btn--outline cp-btn--fw" disabled>No Certificates Yet</button>
        <?php endif; ?>
    </div>
</div>
