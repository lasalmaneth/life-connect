<?php
/**
 * Custodian Portal — Page Header Partial
 *
 * Usage:
 *   <?php
 *   $page_icon     = 'fa-chart-line';
 *   $page_heading  = 'Dashboard';
 *   $page_subtitle = 'Overview of the donation case.';
 *   $page_badge    = ['type' => 'active', 'text' => 'Active'];  // optional
 *   include __DIR__ . '/page-header.php';
 *   ?>
 */

$page_icon     = $page_icon     ?? 'fa-circle-info';
$page_heading  = $page_heading  ?? $page_title ?? 'Page';
$page_subtitle = $page_subtitle ?? '';
$page_badge    = $page_badge    ?? null;
?>
<div class="cp-content__header">
    <div class="cp-content__header-inner">
        <div>
            <h2>
                <i class="fas <?= htmlspecialchars($page_icon) ?>"></i>
                <?= htmlspecialchars($page_heading) ?>
            </h2>
            <?php if ($page_subtitle): ?>
                <p><?= htmlspecialchars($page_subtitle) ?></p>
            <?php endif; ?>
        </div>
        <?php if ($page_badge): ?>
            <span class="cp-badge cp-badge--<?= htmlspecialchars($page_badge['type'] ?? 'neutral') ?> cp-badge--lg">
                <?= htmlspecialchars($page_badge['text'] ?? '') ?>
            </span>
        <?php endif; ?>
    </div>
</div>
