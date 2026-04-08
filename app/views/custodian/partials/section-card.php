<?php
/**
 * Custodian Portal — Section Card Partial
 *
 * Usage:
 *   <?php
 *   $section_title  = 'Case Status';
 *   $section_icon   = 'fa-briefcase-medical';
 *   $section_action = ['label' => 'View All', 'href' => '...']; // optional
 *   ob_start();
 *   ?>
 *     <!-- section inner content -->
 *   <?php
 *   $section_content = ob_get_clean();
 *   include __DIR__ . '/section-card.php';
 *   ?>
 */

$section_title   = $section_title   ?? 'Section';
$section_icon    = $section_icon    ?? 'fa-circle-info';
$section_action  = $section_action  ?? null;
$section_content = $section_content ?? '';
?>
<div class="cp-section-card">
    <div class="cp-section-card__header">
        <div class="cp-section-card__title">
            <i class="fas <?= htmlspecialchars($section_icon) ?>"></i>
            <?= htmlspecialchars($section_title) ?>
        </div>
        <?php if ($section_action): ?>
            <a href="<?= htmlspecialchars($section_action['href'] ?? '#') ?>"
               class="cp-btn cp-btn--outline cp-btn--sm">
                <?= htmlspecialchars($section_action['label'] ?? 'View') ?>
            </a>
        <?php endif; ?>
    </div>
    <div class="cp-section-card__body">
        <?= $section_content ?>
    </div>
</div>
