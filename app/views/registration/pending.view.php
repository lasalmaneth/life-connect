<?php
$pageTitle = 'LifeConnect — Application Status';
$pageKey = 'status';
$hideProgress = true;
require __DIR__ . '/partials/head.view.php';
require __DIR__ . '/partials/header.view.php';

$status = $status ?? 'PENDING';
$statusUpper = strtoupper($status);
$reviewMessage = $review_message ?? '';

// UI Mapping
$ui = [
    'PENDING' => [
        'bg_style' => '', // Uses default blue gradient from .pend-banner
        'icon' => 'fa-clock-rotate-left',
        'badge' => 'Under Admin Review',
        'title' => 'Application Pending',
        'subtitle' => 'Your registration is currently being reviewed by our medical board team. We will notify you via email once a decision has been made. This process typically takes 1–3 business days.',
        'status_color' => 'var(--warning)',
    ],
    'APPROVED' => [
        'bg_style' => 'background:linear-gradient(135deg,#059669 0%,#10b981 100%);',
        'icon' => 'fa-circle-check',
        'badge' => 'Approved',
        'title' => 'Welcome to LifeConnect',
        'subtitle' => 'Your registration has been approved! You are now part of our saving community. You can now log in to access your donor dashboard and preferences.',
        'status_color' => 'var(--success)',
    ],
    'ACTIVE' => [
        'bg_style' => 'background:linear-gradient(135deg,#059669 0%,#10b981 100%);',
        'icon' => 'fa-circle-check',
        'badge' => 'Approved',
        'title' => 'Welcome to LifeConnect',
        'subtitle' => 'Your registration has been approved! You are now part of our saving community. You can now log in to access your donor dashboard and preferences.',
        'status_color' => 'var(--success)',
    ],
    'REJECTED' => [
        'bg_style' => 'background:linear-gradient(135deg,#b91c1c 0%,#ef4444 100%);',
        'icon' => 'fa-circle-exclamation',
        'badge' => 'Application Not Approved',
        'title' => 'Review Completed',
        'subtitle' => 'Unfortunately, your registration could not be approved at this time. Please review the reason below or contact support for more information.',
        'status_color' => 'var(--danger)',
    ]
];

$cfg = $ui[$statusUpper] ?? $ui['PENDING'];

// Role display mapping
$roleDisplay = [
    'DONOR' => 'Individual Donor',
    'FINANCIAL_DONOR' => 'Financial Donor',
    'HOSPITAL' => 'Medical Institution (Hospital)',
    'MEDICAL_SCHOOL' => 'Medical Institution (Medical School)'
];
$displayRole = $roleDisplay[strtoupper($role ?? '')] ?? ($role ?? 'Donor');
?>

<div class="pending-wrap">
    <div class="pend-banner" style="<?= $cfg['bg_style'] ?>">
        <div class="pend-icon-ring">
            <i class="fas <?= $cfg['icon'] ?>"></i>
        </div>
        <h2><?= $cfg['title'] ?></h2>
        <p style="color: rgba(255,255,255,0.85); font-size: 13px; line-height: 1.5; padding: 0 10px; margin-bottom: 0;">
            <?= $cfg['subtitle'] ?>
        </p>
    </div>

    <div style="padding: 34px 38px;">
        <div class="sum-block">
            <div class="sum-head">
                <i class="fas fa-list-check"></i> Application Summary
            </div>
            <div class="sum-row">
                <div class="sum-key">Current Status</div>
                <div class="sum-val">
                    <span style="display:inline-flex; align-items:center; gap:5px; color: <?= $cfg['status_color'] ?>;">
                        <i class="fas <?= $cfg['icon'] ?>"></i> <?= $cfg['badge'] ?>
                    </span>
                </div>
            </div>
            <div class="sum-row">
                <div class="sum-key">Username</div>
                <div class="sum-val">@<?= htmlspecialchars($username ?? '') ?></div>
            </div>
            <div class="sum-row">
                <div class="sum-key">Account Type</div>
                <div class="sum-val"><?= htmlspecialchars($displayRole) ?></div>
            </div>
            <div class="sum-row">
                <div class="sum-key">Official Email</div>
                <div class="sum-val"><?= htmlspecialchars($email ?? '') ?></div>
            </div>
        </div>

        <?php if ($reviewMessage): ?>
            <div class="info-box" <?= ($statusUpper === 'REJECTED') ? 'style="background:#fff7f7; border-left-color:var(--danger); color:#b91c1c;"' : '' ?>>
                <strong><i class="fas <?= ($statusUpper === 'REJECTED') ? 'fa-triangle-exclamation' : 'fa-info-circle' ?>"></i> <?= ($statusUpper === 'REJECTED') ? 'Decision Details:' : 'Admin Update:' ?></strong>
                <div style="margin-top: 6px; color: <?= ($statusUpper === 'REJECTED') ? '#991b1b' : '#374151' ?>; line-height: 1.5;">
                    <?= nl2br(htmlspecialchars($reviewMessage)) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="btn-group" style="padding-top: 0; border-top: none; margin-top: 24px; justify-content: flex-end;">
            <button onclick="window.location.reload()" class="btn btn-outline" style="flex: none; padding: 10px 20px;">
                <i class="fas fa-rotate"></i> Refresh Status
            </button>
            <a href="<?= ROOT ?>/login" class="btn btn-primary" style="flex: none; padding: 10px 20px;">
                <i class="fas fa-right-to-bracket"></i> Go to Login
            </a>
        </div>

        <div class="login-link" style="margin-top:20px; text-align: center;">
            Need more information? Contact <a href="mailto:support@lifeconnect.lk">support@lifeconnect.lk</a>
        </div>
    </div>
</div>

<script>
    // Prevent going back to registration forms
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>

<?php require __DIR__ . '/partials/footer.view.php'; ?>
