<?php
/**
 * Custodian Portal — Topbar Partial
 * Requires: $custodian_name, $custodian_id_display (injected by layout)
 */
?>
<header class="cp-header" id="cp-header">
    <div class="cp-header__inner">

        <!-- Left: Mobile toggle + Logo -->
        <div style="display:flex; align-items:center; gap:.75rem;">
            <button class="cp-mobile-toggle" id="cp-mobile-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar"
                style="display:none;">
                <i class="fas fa-bars"></i>
            </button>

            <a href="<?= ROOT ?>" class="cp-logo">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect Logo">
                <div class="cp-logo__text">
                    <strong>LifeConnect</strong>
                    <span>Custodian Portal</span>
                </div>
            </a>
        </div>

        <!-- Right: Nav + Bell + User chip -->
        <div class="cp-header__right">

            <?php
            // Advanced Dashboard Banner Logic for Active Body Cases
            if (isset($death_declaration) && isset($activeCase) && !empty($activeCase->date_of_death) && (($activeCase->donation_type ?? '') === 'BODY' || ($activeCase->donation_type ?? '') === 'BODY_AND_CORNEA')) {
                $now = new DateTime();
                $deathDateTime = new DateTime($activeCase->date_of_death . ' ' . $activeCase->time_of_death);
                $interval = $now->diff($deathDateTime);
                $hoursSinceDeathStr = ($interval->days * 24) + $interval->h + ($interval->i / 60);

                if ($hoursSinceDeathStr > 48) {
                    echo '<div style="background: var(--r100); border: 1px solid var(--r300); color: var(--r700); padding: 6px 16px; border-radius: 6px; font-weight: 600; font-size: 0.9rem; margin-right: 15px; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 5px rgba(220,38,38,0.1);"><i class="fas fa-ban"></i> 48-Hour Hard Limit Exceeded</div>';
                } else {
                    $hoursLeft = floor(48 - $hoursSinceDeathStr);
                    $minsLeft = floor(((48 - $hoursSinceDeathStr) - $hoursLeft) * 60);
                    echo '<div style="background: var(--blue-50); border: 1px solid var(--blue-200); color: var(--blue-700); padding: 6px 16px; border-radius: 6px; font-weight: 600; font-size: 0.9rem; margin-right: 15px; display: flex; align-items: center; gap: 8px; animation: pulse 2s infinite;"><i class="fas fa-clock"></i> Submission Window: ' . sprintf('%02d', $hoursLeft) . 'h ' . sprintf('%02d', $minsLeft) . 'm left</div>';
                    echo '<style>@keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(59,130,246,0.4); } 70% { box-shadow: 0 0 0 6px rgba(59,130,246,0); } 100% { box-shadow: 0 0 0 0 rgba(59,130,246,0); } }</style>';
                }
            }
            ?>

            <nav class="cp-header__nav">
                <a href="<?= ROOT ?>">
                    <i class="fas fa-house"></i>
                    <span>Home</span>
                </a>
            </nav>

            <div class="cp-bell" title="Notifications" id="cp-bell-btn">
                <i class="fas fa-bell"></i>
            </div>

            <div class="cp-user-chip" id="cp-user-chip">
                <div class="cp-user-avatar">
                    <?= strtoupper(substr($custodian_name ?? 'C', 0, 1)) ?>
                </div>
                <div>
                    <div class="cp-user-chip__name"><?= htmlspecialchars($custodian_name ?? 'Custodian') ?></div>
                    <div class="cp-user-chip__id"><?= htmlspecialchars($custodian_id_display ?? 'CID-00000') ?></div>
                </div>
                <i class="fas fa-chevron-down" style="font-size:.65rem; color:var(--g400); margin-left:.25rem;"></i>
            </div>

        </div>
    </div>
</header>

</div>
</div>
</header>