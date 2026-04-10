<?php
/**
 * Custodian Portal — Registered Consents View
 */
$page_icon     = 'fa-file-signature';
$page_heading  = 'Registered Consents';
$page_subtitle = 'Historical and active consent records for the donor.';

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-section-card">
        <div class="cp-section-card__header">
            <div class="cp-section-card__title"><i class="fas fa-history"></i> Consent History Timeline</div>
        </div>
        <div class="cp-section-card__body p-0">
            <?php if (empty($timeline)): ?>
                <div class="p-5 text-center cp-text-g500">No consent records found.</div>
            <?php else: ?>
                <table class="cp-table w-100 text-left">
                    <thead>
                        <tr class="cp-bg-g50 border-bottom">
                            <th class="p-3">Type</th>
                            <th class="p-3">Date Registered</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($timeline as $t): ?>
                            <tr class="border-bottom">
                                <td class="p-4 cp-font-semibold"><?= str_replace('_', ' ', $t->type) ?></td>
                                <td class="p-4"><?= date('M j, Y', strtotime($t->date)) ?></td>
                                <td class="p-4">
                                    <?php 
                                    $statusClass = 'ba'; // active
                                    if ($t->status === 'SUPERSEDED') $statusClass = 'bd';
                                    if ($t->status === 'WITHDRAWN') $statusClass = 'bw';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $t->status ?></span>
                                </td>
                                <td class="p-4">
                                    <button class="cp-btn cp-btn--sm cp-btn--outline">View Details</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <p class="cp-text-xs cp-text-g500 mt-4 px-2">
        <i class="fas fa-info-circle"></i> Note: Only the most recent 'Active' consent is legally binding for the donation process.
    </p>

</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
