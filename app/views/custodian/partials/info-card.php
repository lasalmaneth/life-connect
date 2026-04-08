<?php
/**
 * Custodian Portal — Info Card Partial
 *
 * Usage:
 *   <?php
 *   $card_title = 'Personal Information';
 *   $card_icon  = 'fa-user';
 *   $card_rows  = [
 *       ['label' => 'Full Name',  'value' => $data['name'] ?? null],
 *       ['label' => 'NIC',        'value' => $data['nic']  ?? null],
 *   ];
 *   $card_action = ['label' => 'View', 'href' => '...']; // optional
 *   include __DIR__ . '/info-card.php';
 *   ?>
 */

$card_title  = $card_title  ?? 'Information';
$card_icon   = $card_icon   ?? 'fa-circle-info';
$card_rows   = $card_rows   ?? [];
$card_action = $card_action ?? null;
?>
<div class="cp-info-card">
    <div class="cp-info-card__header">
        <div class="cp-info-card__title">
            <i class="fas <?= htmlspecialchars($card_icon) ?>"></i>
            <?= htmlspecialchars($card_title) ?>
        </div>
        <?php if ($card_action): ?>
            <a href="<?= htmlspecialchars($card_action['href'] ?? '#') ?>" class="cp-btn cp-btn--outline cp-btn--sm">
                <?= htmlspecialchars($card_action['label'] ?? 'View') ?>
            </a>
        <?php endif; ?>
    </div>
    <div class="cp-info-card__body">
        <?php if (!empty($card_rows)): ?>
            <?php foreach ($card_rows as $row): ?>
                <div class="cp-info-row">
                    <span class="cp-info-label"><?= htmlspecialchars($row['label'] ?? '') ?></span>
                    <span class="cp-info-value <?= empty($row['value']) ? 'cp-info-value--placeholder' : '' ?>">
                        <?= htmlspecialchars($row['value'] ?? '—') ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php
            $empty_icon = 'fa-circle-info';
            $empty_msg  = 'No data available';
            $empty_sub  = 'Backend integration pending';
            include __DIR__ . '/empty-state.php';
            ?>
        <?php endif; ?>
    </div>
</div>
