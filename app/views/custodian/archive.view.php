<?php
/**
 * Custodian Portal — History & Archive View
 */
$page_icon     = 'fa-box-archive';
$page_heading  = 'History & Archive';
$page_subtitle = 'Review historical, rejected, and completed workflow records.';

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-section-card">
        <div class="cp-section-card__header">
            <div class="cp-section-card__title"><i class="fas fa-archive"></i> Archived Case Records</div>
        </div>
        <div class="cp-section-card__body p-0">
            <?php if (empty($archived)): ?>
                <div class="p-5 text-center cp-text-g500">No archived cases found.</div>
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
                                <td class="p-4 cp-font-bold">#CASE-<?= str_pad($a->id, 4, '0', STR_PAD_LEFT) ?></td>
                                <td class="p-4"><?= str_replace('_', ' ', $a->donation_type) ?></td>
                                <td class="p-4"><?= date('M j, Y', strtotime($a->date_of_death)) ?></td>
                                <td class="p-4">
                                    <?php 
                                    $statusClass = 'ba'; // completed
                                    if ($a->overall_status === 'CANCELLED') $statusClass = 'bw';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $a->overall_status ?></span>
                                </td>
                                <td class="p-4">
                                    <button class="cp-btn cp-btn--sm cp-btn--outline">View History</button>
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
