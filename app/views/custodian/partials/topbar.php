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
            <button class="cp-mobile-toggle" id="cp-mobile-toggle"
                    onclick="toggleSidebar()"
                    aria-label="Toggle sidebar"
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
