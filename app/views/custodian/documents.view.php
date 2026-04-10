<?php
/**
 * Custodian Portal — Documents View
 */
$page_icon     = 'fa-folder-open';
$page_heading  = 'Documents';
$page_subtitle = 'Upload and manage documentation bundle for the active donor case.';

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <?php if (!$activeCase): ?>
        <div class="cp-notice cp-notice--info mb-4">
            <i class="fas fa-info-circle fa-2x"></i>
            <div>
                <strong>Preparation Mode</strong>
                <p>No active case has been opened yet. You may review the required documentation checklist below, but uploads are enabled only after institutional request has been initiated.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Reusable Dynamic Checklist -->
    <?php require __DIR__ . '/partials/document-checklist.php'; ?>

</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
