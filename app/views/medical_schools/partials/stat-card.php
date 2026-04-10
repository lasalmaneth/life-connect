<?php
/**
 * Medical School Portal — Stat Card Partial
 *
 * Usage:
 *   <?php
 *   $stat_icon    = 'fa-file-signature';
 *   $stat_label   = 'Pre-Death Consents';
 *   $stat_value   = 12;
 *   $stat_variant = '';           // '' | 'success' | 'warning' | 'danger'
 *   include __DIR__ . '/stat-card.php';
 *   ?>
 */

$stat_icon    = $stat_icon    ?? 'fa-circle-info';
$stat_label   = $stat_label   ?? 'Stat';
$stat_value   = $stat_value   ?? '—';
$stat_sub     = $stat_sub     ?? '';
$stat_variant = $stat_variant ?? '';
$stat_class   = $stat_variant ? "cp-stat cp-stat--{$stat_variant}" : 'cp-stat';
?>
<div class="<?= $stat_class ?>">
    <div class="cp-stat__icon">
        <i class="fas <?= htmlspecialchars($stat_icon) ?>"></i>
    </div>
    <div class="cp-stat__label"><?= htmlspecialchars($stat_label) ?></div>
    <div class="cp-stat__value"><?= htmlspecialchars((string)$stat_value) ?></div>
    <?php if ($stat_sub): ?>
        <div class="cp-stat__sub"><?= htmlspecialchars($stat_sub) ?></div>
    <?php endif; ?>
</div>
