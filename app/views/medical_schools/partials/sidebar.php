<?php
/**
 * Medical School Portal — Sidebar Partial
 * Requires: $active_page (string key matching each nav item's 'key')
 *           $school_name (injected by layout)
 *
 * Active page keys:
 *   dashboard | consents | withdrawals | submissions | body-acceptance
 *   usage-logs | certificates | archived | reports
 */

$ms_nav_items = [
    'overview' => [
        'title' => 'Dashboard',
        'items' => [
            ['href' => ROOT . '/medical-school', 'icon' => 'fa-chart-line', 'label' => 'Dashboard', 'key' => 'dashboard'],
        ],
    ],
    'donor_registry' => [
        'title' => 'Donor Registry',
        'items' => [
            ['href' => ROOT . '/medical-school/consents', 'icon' => 'fa-file-signature', 'label' => 'Consent Registry', 'key' => 'consents', 'badge' => true],
        ],
    ],
    'post_death' => [
        'title' => 'Post-Death Review',
        'items' => [
            ['href' => ROOT . '/medical-school/submission-requests', 'icon' => 'fa-inbox',          'label' => 'Submission Requests', 'key' => 'submission-requests', 'badge' => true],
            ['href' => ROOT . '/medical-school/submissions',         'icon' => 'fa-folder-open',    'label' => 'Body Submissions',    'key' => 'submissions', 'badge' => true],
            ['href' => ROOT . '/medical-school/final-examinations',  'icon' => 'fa-clipboard-check','label' => 'Final Examination',   'key' => 'final-examinations'],
        ],
    ],
    'recognition_records' => [
        'title' => 'Recognition & Usage',
        'items' => [
            ['href' => ROOT . '/medical-school/usage-logs',   'icon' => 'fa-clipboard-list',     'label' => 'Cadaver Usage Logs',   'key' => 'usage-logs'],
            ['href' => ROOT . '/medical-school/stories',      'icon' => 'fa-star',               'label' => 'Success Stories',      'key' => 'stories'],
            ['href' => ROOT . '/medical-school/reports',      'icon' => 'fa-chart-bar',          'label' => 'Reports',              'key' => 'reports'],
        ],
    ],
];
?>
<aside class="cp-sidebar" id="cp-sidebar">

    <!-- Sidebar Header -->
    <div class="cp-sidebar__header">
        <div class="cp-sidebar__avatar">
            <?= strtoupper(substr($school_name ?? 'M', 0, 1)) ?>
        </div>
        <div>
            <div class="cp-sidebar__name"><?= htmlspecialchars($school_name ?? 'Medical School') ?></div>
            <div class="cp-sidebar__meta">Body Donation Management</div>
            <div class="cp-sidebar__pill">
                <i class="fas fa-microscope"></i> Medical School
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav>
        <!-- Back to Home -->
        <div class="cp-menu-section">
            <a href="<?= ROOT ?>/home" class="cp-menu-item" id="nav-back-home">
                <span class="cp-menu-item__icon"><i class="fas fa-arrow-left"></i></span>
                Back to Home
            </a>
        </div>

        <?php foreach ($ms_nav_items as $group): ?>
            <div class="cp-menu-section">
                <div class="cp-menu-section__title"><?= $group['title'] ?></div>
                <?php foreach ($group['items'] as $item): ?>
                    <a href="<?= $item['href'] ?>"
                        class="cp-menu-item <?= ($active_page ?? '') === $item['key'] ? 'active' : '' ?>"
                        id="nav-<?= htmlspecialchars($item['key']) ?>">
                        <span class="cp-menu-item__icon"><i class="fas <?= $item['icon'] ?>"></i></span>
                        <span class="cp-menu-item__label"><?= htmlspecialchars($item['label']) ?></span>
                        
                        <?php if (!empty($item['badge']) && isset($stats)): ?>
                            <?php 
                                $count = 0;
                                if ($item['key'] === 'submission-requests') $count = $stats['pending_requests']   ?? 0;
                                if ($item['key'] === 'submissions')         $count = $stats['active_submissions'] ?? 0;
                            ?>
                            <?php if ($count > 0): ?>
                                <span class="cp-menu-item__badge"><?= $count ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
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