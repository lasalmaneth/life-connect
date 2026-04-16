<?php
/**
 * Custodian Portal — Sidebar Partial
 * Cleaned up to explicitly follow the post-death workflow scenario without clutter.
 */

$nav_items = [
    'general' => [
        'title' => 'Overview',
        'items' => array_filter([
            ['href' => ROOT . '/custodian/dashboard',     'icon' => 'fa-chart-line',      'label' => 'Dashboard',   'key' => 'dashboard'],
            ['href' => ROOT . '/custodian/donor-profile', 'icon' => 'fa-id-card',           'label' => 'Donor Profile',      'key' => 'donor-profile'],
            ['href' => ROOT . '/custodian/profile',       'icon' => 'fa-users-line',      'label' => 'Custodians',        'key' => 'profile'],
            ['href' => ROOT . '/custodian/report-death', 'icon' => 'fa-heart-crack', 'label' => (!empty($death_declaration) ? 'Death Details' : 'Report Death'), 'key' => 'report-death'],
        ]),
    ],
    'case' => [
        'title' => 'Case Operations',
        'items' => array_filter([
            ['href' => ROOT . '/custodian/institution-requests', 'icon' => 'fa-network-wired',   'label' => 'Institution Requests', 'key' => 'institution-requests'],
            ['href' => ROOT . '/custodian/documents',       'icon' => 'fa-folder-open',     'label' => 'Documents',          'key' => 'documents'],
            ['href' => ROOT . '/custodian/certificates',    'icon' => 'fa-award',     'label' => 'Appreciation & Recognition',       'key' => 'certificates'],
        ]),
    ],
    'history' => [
        'title' => 'History',
        'items' => [
            ['href' => ROOT . '/custodian/activity-history', 'icon' => 'fa-clock-rotate-left', 'label' => 'Activity History', 'key' => 'activity-history'],
        ],
    ],
];
?>
<aside class="cp-sidebar" id="cp-sidebar">

    <div class="cp-sidebar__header">
        <div class="cp-sidebar__avatar">
            <?= strtoupper(substr($custodian_name ?? 'C', 0, 1)) ?>
        </div>
        <div>
            <div class="cp-sidebar__name"><?= htmlspecialchars($custodian_name ?? 'Custodian') ?></div>
            <div class="cp-sidebar__meta"><?= htmlspecialchars($custodian_id_display ?? 'CID-00000') ?></div>
            <div class="cp-sidebar__pill"><i class="fas fa-shield-halved"></i> Custodian</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav>
        <?php foreach ($nav_items as $group): ?>
            <div class="cp-menu-section">
                <div class="cp-menu-section__title"><?= $group['title'] ?></div>
                <?php foreach ($group['items'] as $item): ?>
                    <a href="<?= $item['href'] ?>"
                       class="cp-menu-item <?= ($active_page ?? '') === $item['key'] ? 'active' : '' ?>"
                       id="nav-<?= htmlspecialchars($item['key']) ?>">
                        <span class="cp-menu-item__icon"><i class="fas <?= $item['icon'] ?>"></i></span>
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <!-- Logout -->
        <div class="cp-menu-section">
            <a href="<?= ROOT ?>/logout" class="cp-menu-item cp-menu-item--danger" id="nav-logout">
                <span class="cp-menu-item__icon"><i class="fas fa-right-from-bracket"></i></span>
                Logout
            </a>
        </div>
    </nav>

</aside>
