<?php
/**
 * Medical School Portal — Empty State Partial
 *
 * Usage:
 *   <?php
 *   $empty_icon = 'fa-folder-open';
 *   $empty_msg  = 'No records found.';
 *   $empty_sub  = 'Records will appear here once data is available.';
 *   include __DIR__ . '/empty-state.php';
 *   ?>
 */

$empty_icon = $empty_icon ?? 'fa-circle-info';
$empty_msg  = $empty_msg  ?? 'No data available';
$empty_sub  = $empty_sub  ?? 'Records will appear here once data is available.';
?>
<div class="cp-empty-state">
    <div class="cp-empty-state__icon">
        <i class="fas <?= htmlspecialchars($empty_icon) ?>"></i>
    </div>
    <div class="cp-empty-state__msg"><?= htmlspecialchars($empty_msg) ?></div>
    <div class="cp-empty-state__sub"><?= htmlspecialchars($empty_sub) ?></div>
</div>
