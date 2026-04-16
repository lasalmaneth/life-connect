<?php
/**
 * Medical School Portal — Topbar Partial
 * Requires: $school_name (injected by layout)
 */
?>
<header class="cp-header" id="cp-header">
    <div class="cp-header__inner">

        <!-- Left: Mobile toggle + Logo -->
        <div class="cp-header__left">
            <button class="cp-mobile-toggle" id="cp-mobile-toggle" onclick="toggleSidebar()"
                aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <a href="<?= ROOT ?>" class="cp-logo">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect Logo">
                <div class="cp-logo__text">
                    <strong>LifeConnect</strong>
                    <span>Medical School Portal</span>
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
                <?php if (isset($stats) && ($stats['pending_requests'] > 0 || $stats['active_submissions'] > 0)): ?>
                    <span class="cp-bell__dot"></span>
                <?php endif; ?>
            </div>

            <div class="cp-user-chip" id="cp-user-chip">
                <div class="cp-user-avatar">
                    <?= strtoupper(substr($school_name ?? 'M', 0, 1)) ?>
                </div>
                <div>
                    <div class="cp-user-chip__name"><?= htmlspecialchars($school_name ?? 'Medical School') ?></div>
                    <div class="cp-user-chip__id">
                        <i class="fas fa-microscope"></i> Anatomy Dept
                    </div>
                </div>
                <i class="fas fa-chevron-down cp-user-chip__chevron"></i>
            </div>

        </div>
    </div>
</header>