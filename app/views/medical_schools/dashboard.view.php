<?php
/**
 * Medical School Portal — Dashboard View
 * Route: GET /medical-school
 */

$page_title    = 'Dashboard';
$active_page   = 'dashboard';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title">Welcome, <?= htmlspecialchars($school->school_name ?? 'Professor') ?></h1>
        <p class="cp-content-header__subtitle">Institution Overview — Deceased Body Donation Case Management System</p>
    </div>
    <div class="cp-content-header__actions">
        <div class="cp-date-badge">
            <i class="fas fa-calendar-alt"></i> <?= date('l, F d, Y') ?>
        </div>
    </div>
</div>

<div class="cp-content-body">

    <!-- ── Stats Overview ────────────────────────────────────────── -->
    <div class="cp-stats-grid">
        <div class="cp-stat">
            <div class="cp-stat__icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <div class="cp-stat__label">Total Consents</div>
            <div class="cp-stat__value"><?= $stats['total_consents'] ?? 0 ?></div>
        </div>

        <div class="cp-stat cp-stat--warning">
            <div class="cp-stat__icon">
                <i class="fas fa-inbox"></i>
            </div>
            <div class="cp-stat__label">Pending Requests</div>
            <div class="cp-stat__value"><?= $stats['pending_requests'] ?? 0 ?></div>
        </div>

        <div class="cp-stat cp-stat--success">
            <div class="cp-stat__icon">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="cp-stat__label">Active Submissions</div>
            <div class="cp-stat__value"><?= $stats['active_submissions'] ?? 0 ?></div>
        </div>

        <div class="cp-stat">
            <div class="cp-stat__icon">
                <i class="fas fa-microscope"></i>
            </div>
            <div class="cp-stat__label">Exam Pending</div>
            <div class="cp-stat__value"><?= $stats['pending_exams'] ?? 0 ?></div>
        </div>
    </div>

    <!-- ── Dash Grids ───────────────────────────────────────────── -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
        
        <!-- Quick Actions -->
        <div class="cp-card">
            <div class="cp-card__header">
                <h3 class="cp-card__title"><i class="fas fa-bolt"></i> Operational Quick Actions</h3>
            </div>
            <div class="cp-card__body">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                    <a href="<?= ROOT ?>/medical-school/consents" class="cp-action-box">
                        <i class="fas fa-file-medical"></i>
                        <span>Verify Consents</span>
                    </a>
                    <a href="<?= ROOT ?>/medical-school/submission-requests" class="cp-action-box">
                        <i class="fas fa-clipboard-question"></i>
                        <span>Review Requests</span>
                    </a>
                    <a href="<?= ROOT ?>/medical-school/final-examinations" class="cp-action-box">
                        <i class="fas fa-stethoscope"></i>
                        <span>Physical Exams</span>
                    </a>
                    <a href="<?= ROOT ?>/medical-school/appreciation" class="cp-action-box">
                        <i class="fas fa-award"></i>
                        <span>Send Letters</span>
                    </a>
                    <a href="<?= ROOT ?>/medical-school/reports" class="cp-action-box">
                        <i class="fas fa-file-chart-line"></i>
                        <span>Gen Analytics</span>
                    </a>
                    <a href="<?= ROOT ?>/medical-school/usage-logs" class="cp-action-box">
                        <i class="fas fa-book-medical"></i>
                        <span>Usage History</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Alerts -->
        <div class="cp-card">
            <div class="cp-card__header">
                <h3 class="cp-card__title"><i class="fas fa-bell"></i> Critical Alerts</h3>
            </div>
            <div class="cp-card__body">
                <div class="cp-alert cp-alert--warning" style="margin-bottom: 1rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>2 Bundle Submissions require immediate review.</div>
                </div>
                <div class="cp-alert cp-alert--info">
                    <i class="fas fa-info-circle"></i>
                    <div>Institutional intake quota is at 85%.</div>
                </div>
            </div>
        </div>

    </div>

</div>



<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
