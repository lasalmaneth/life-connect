<?php
/**
 * Custodian Portal — Certificates View
 */
$page_icon     = 'fa-certificate';
$page_heading  = 'Donation Certificates';
$page_subtitle = 'Official recognition and completion certificates.';

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <?php if (empty($certificates)): ?>
        <div class="cp-empty-state">
            <i class="fas fa-file-contract cp-empty-state__icon"></i>
            <div class="cp-empty-state__msg">No Certificates Available Yet</div>
            <div class="cp-empty-state__sub">
                Official certificates are issued only after the final approval of the donation case by the recipient institution. 
                Keep track of your active case status in the dashboard.
            </div>
            <a href="<?= ROOT ?>/custodian/dashboard" class="cp-btn cp-btn--primary">Return to Dashboard</a>
        </div>
    <?php else: ?>
        <div class="cp-grid-3">
            <?php foreach ($certificates as $cert): ?>
                <div class="cp-info-card cp-border-success">
                    <div class="cp-info-card__header cp-bg-success-light">
                        <div class="cp-info-card__title text-success"><i class="fas fa-award"></i> <?= htmlspecialchars($cert->certificate_type ?? 'Donation Certificate') ?></div>
                    </div>
                    <div class="cp-info-card__body p-4 text-center">
                        <h4 class="mb-2 cp-font-bold text-slate"><?= htmlspecialchars($cert->institution_name ?? 'Institution') ?></h4>
                        <div class="cp-text-sm cp-text-g500 mb-4">Issued: <?= date('F j, Y', strtotime($cert->issued_at)) ?></div>
                        <button class="cp-btn cp-btn--outline cp-w-100"><i class="fas fa-download cp-mr-2"></i> Download PDF</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
