<?php
/**
 * Custodian Portal — Activity History View
 */
$page_icon     = 'fa-clock-rotate-left';
$page_heading  = 'Activity History';
$page_subtitle = 'A comprehensive chronological audit log of all case actions, submissions, and institutional responses.';

$active_page = 'activity-history';

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <!-- 1. FULL AUDIT TIMELINE -->
    <div class="mb-5">
        <?php include __DIR__ . '/partials/activity-timeline.php'; ?>
    </div>

    <!-- 2. AUDIT INTEGRITY NOTICE -->
    <div class="cp-info-banner mt-4">
        <i class="fas fa-shield-halved mr-2"></i>
        <span>This audit log represents all real-time operational events. Historical registration intents and clinical outcomes can be found in the <strong>Consent Registry</strong>.</span>
    </div>

</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
