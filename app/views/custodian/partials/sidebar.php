<?php
/**
 * Custodian Portal — Sidebar Partial
 * Requires: $active_page (string key matching each nav item's 'key')
 *           $custodian_name, $custodian_id_display (injected by layout)
 *
 * Active page keys:
 *   dashboard | consent | donor-profile | co-custodian | report-death
 *   legal-response | cadaver-data-sheet | documents | coordination
 *   timeline | certificates | authority-limits
 */

$nav_items = [
    'general' => [
        'title' => 'Overview',
        'items' => [
            ['href' => ROOT . '/custodian/dashboard',   'icon' => 'fa-chart-line',    'label' => 'Dashboard',   'key' => 'dashboard'],
        ],
    ],
    'donor' => [
        'title' => 'Donor',
        'items' => [
            ['href' => ROOT . '/custodian/donor-profile',   'icon' => 'fa-id-card',         'label' => 'Donor Profile',      'key' => 'donor-profile'],
            ['href' => ROOT . '/custodian/consent',          'icon' => 'fa-file-signature',  'label' => 'Registered Consent', 'key' => 'consent'],
            ['href' => ROOT . '/custodian/co-custodian',     'icon' => 'fa-user-shield',     'label' => 'Co-Custodian',       'key' => 'co-custodian'],
        ],
    ],
    'case' => [
        'title' => 'Case Actions',
        'items' => [
            ['href' => ROOT . '/custodian/report-death',       'icon' => 'fa-heart-pulse',     'label' => 'Report Death',       'key' => 'report-death'],
            ['href' => ROOT . '/custodian/legal-response',     'icon' => 'fa-gavel',           'label' => 'Legal Response',     'key' => 'legal-response'],
            ['href' => ROOT . '/custodian/cadaver-data-sheet', 'icon' => 'fa-notes-medical',   'label' => 'Cadaver Data Sheet', 'key' => 'cadaver-data-sheet'],
        ],
    ],
    'records' => [
        'title' => 'Records',
        'items' => [
            ['href' => ROOT . '/custodian/documents',    'icon' => 'fa-folder-open',     'label' => 'Documents',         'key' => 'documents'],
            ['href' => ROOT . '/custodian/coordination', 'icon' => 'fa-network-wired',   'label' => 'Coordination',      'key' => 'coordination'],
            ['href' => ROOT . '/custodian/timeline',     'icon' => 'fa-stream',          'label' => 'Timeline',          'key' => 'timeline'],
        ],
    ],
    'official' => [
        'title' => 'Official',
        'items' => [
            ['href' => ROOT . '/custodian/certificates',    'icon' => 'fa-certificate',     'label' => 'Certificates',      'key' => 'certificates'],
            ['href' => ROOT . '/custodian/authority-limits','icon' => 'fa-shield-halved',   'label' => 'Authority Limits',  'key' => 'authority-limits'],
        ],
    ],
];
?>
<aside class="cp-sidebar" id="cp-sidebar">

    <!-- Sidebar Header -->
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
