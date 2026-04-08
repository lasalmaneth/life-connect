<?php
/**
 * Custodian Portal — Action Card Partial
 *
 * Usage:
 *   <?php
 *   $action_type  = 'confirm';     // 'confirm' | 'object'
 *   $action_icon  = 'fa-check-circle';
 *   $action_title = 'Confirm Donation';
 *   $action_desc  = 'Legally confirm the donor\'s wish to donate.';
 *   $action_btn   = 'Confirm';
 *   // All buttons are disabled — backend integration pending
 *   include __DIR__ . '/action-card.php';
 *   ?>
 */

$action_type  = $action_type  ?? 'confirm';
$action_icon  = $action_icon  ?? ($action_type === 'confirm' ? 'fa-circle-check' : 'fa-ban');
$action_title = $action_title ?? 'Action';
$action_desc  = $action_desc  ?? '';
$action_btn   = $action_btn   ?? 'Submit';
?>
<div class="cp-action-card cp-action-card--<?= htmlspecialchars($action_type) ?>" aria-disabled="true">
    <div class="cp-action-card__icon">
        <i class="fas <?= htmlspecialchars($action_icon) ?>"></i>
    </div>
    <h3><?= htmlspecialchars($action_title) ?></h3>
    <?php if ($action_desc): ?>
        <p><?= htmlspecialchars($action_desc) ?></p>
    <?php endif; ?>
    <button class="cp-btn cp-btn--<?= $action_type === 'confirm' ? 'success' : 'danger' ?>" disabled
            title="Backend integration pending">
        <?= htmlspecialchars($action_btn) ?>
    </button>
</div>
