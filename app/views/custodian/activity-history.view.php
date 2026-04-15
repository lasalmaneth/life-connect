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

    <!-- 2. HISTORICAL RECORD (ARCHIVE) -->
    <div class="cp-section-card">
        <div class="cp-section-card__header">
            <div class="cp-section-card__title">
                <i class="fas fa-box-archive cp-text-g400"></i> Historical Case Records
            </div>
        </div>
        <div class="cp-section-card__body p-0">
            <?php if (empty($archived)): ?>
                <div class="p-5 text-center cp-text-g500">No alternate or historical case records found.</div>
            <?php else: ?>
                <table class="cp-table w-100 text-left">
                    <thead>
                        <tr class="cp-bg-g50 border-bottom">
                            <th class="p-3">Case ID</th>
                            <th class="p-3">Consent Type</th>
                            <th class="p-3">Date of Death</th>
                            <th class="p-3">Final Outcome</th>
                            <th class="p-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archived as $a): ?>
                            <tr class="border-bottom">
                                <td class="p-3 cp-font-bold">#CASE-<?= str_pad($a->id, 4, '0', STR_PAD_LEFT) ?></td>
                                <td class="p-3" style="font-size: 0.85rem;"><?= str_replace('_', ' ', $a->donation_type) ?></td>
                                <td class="p-3" style="font-size: 0.85rem;"><?= date('M j, Y', strtotime($a->date_of_death)) ?></td>
                                <td class="p-3">
                                    <span class="badge bd"><?= $a->overall_status ?></span>
                                </td>
                                <td class="p-3">
                                    <button class="cp-btn cp-btn--sm cp-btn--outline" style="padding: 4px 12px; font-size: 0.75rem;">View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
