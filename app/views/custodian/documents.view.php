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

    <!-- Workflow Locking Notice -->
    <?php include __DIR__ . '/partials/lock-notice.php'; ?>

    <?php if ($activeCase && !$isLeader): ?>
        <div class="cp-notice cp-notice--info mb-4 shadow-sm" style="border-radius: 12px; border-left: 4px solid var(--blue-500); background: #f0f9ff;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--blue-600); box-shadow: 0 2px 4px rgba(0,0,0,0.05); flex-shrink: 0;">
                    <i class="fas fa-crown"></i>
                </div>
                <div>
                    <div style="font-weight: 700; color: var(--blue-900); font-size: 0.95rem;">Process Leader Assigned</div>
                    <div style="color: var(--blue-700); font-size: 0.85rem; font-weight: 500;">
                        Management of this case is restricted to the primary custodian. You have view-only access.
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

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
