<?php
/**
 * Custodian Portal — Status Badge Partial
 *
 * Usage:
 *   <?php
 *   $badge_type = 'active';   // active | pending | danger | info | neutral
 *   $badge_text = 'Active';
 *   $badge_icon = 'fa-circle-check'; // optional FA icon class
 *   include __DIR__ . '/status-badge.php';
 *   ?>
 */

$badge_type = $badge_type ?? 'neutral';
$badge_text = $badge_text ?? '—';
$badge_icon = $badge_icon ?? null;
?>
<span class="cp-badge cp-badge--<?= htmlspecialchars($badge_type) ?>">
    <?php if ($badge_icon): ?>
        <i class="fas <?= htmlspecialchars($badge_icon) ?>"></i>
    <?php endif; ?>
    <?= htmlspecialchars($badge_text) ?>
</span>
