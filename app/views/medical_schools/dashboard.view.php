<?php
/**
 * Medical School Portal — Dashboard View
 * Route: GET /medical-school
 */

$page_title = 'Dashboard';
$active_page = 'dashboard';

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

        <div class="cp-stat cp-stat--success">
            <div class="cp-stat__icon">
                <i class="fas fa-sparkles"></i>
            </div>
            <div class="cp-stat__label">Pristine Bodies</div>
            <div class="cp-stat__value"><?= $stats['pristine_bodies'] ?? 0 ?></div>
        </div>
    </div>

    <!-- ── Dash Grids ───────────────────────────────────────────── -->
    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
        
        <!-- Workflow Quick Actions -->
        <div class="cp-card">
            <div class="cp-card__header">
                <h3 class="cp-card__title"><i class="fas fa-bolt"></i> Operational Workflow</h3>
                <span style="font-size: 0.75rem; color: var(--g500); font-weight: 500;">Core Institution Tasks</span>
            </div>
            <div class="cp-card__body">
                <div class="cp-dashboard-grid">
                    <a href="<?= ROOT ?>/medical-school/submission-requests" class="cp-action-card cp-action-card--intake">
                        <div class="cp-action-card__icon"><i class="fas fa-inbox"></i></div>
                        <div class="cp-action-card__info">
                            <span class="cp-action-card__label">Incoming Requests</span>
                            <span class="cp-action-card__sub">Review Stage C outreach</span>
                        </div>
                        <i class="fas fa-chevron-right cp-action-card__arrow"></i>
                    </a>

                    <a href="<?= ROOT ?>/medical-school/submissions" class="cp-action-card cp-action-card--docs">
                        <div class="cp-action-card__icon"><i class="fas fa-file-shield"></i></div>
                        <div class="cp-action-card__info">
                            <span class="cp-action-card__label">Verify Documents</span>
                            <span class="cp-action-card__sub">Review Stage E/F bundles</span>
                        </div>
                        <i class="fas fa-chevron-right cp-action-card__arrow"></i>
                    </a>

                    <a href="<?= ROOT ?>/medical-school/final-examinations" class="cp-action-card cp-action-card--exam">
                        <div class="cp-action-card__icon"><i class="fas fa-stethoscope"></i></div>
                        <div class="cp-action-card__info">
                            <span class="cp-action-card__label">Physical Exams</span>
                            <span class="cp-action-card__sub">Stage G final inspection</span>
                        </div>
                        <i class="fas fa-chevron-right cp-action-card__arrow"></i>
                    </a>

                    <a href="<?= ROOT ?>/medical-school/usage-logs" class="cp-action-card cp-action-card--inventory">
                        <div class="cp-action-card__icon"><i class="fas fa-box-archive"></i></div>
                        <div class="cp-action-card__info">
                            <span class="cp-action-card__label">Anatomical Inventory</span>
                            <span class="cp-action-card__sub">Manage pristine bodies</span>
                        </div>
                        <i class="fas fa-chevron-right cp-action-card__arrow"></i>
                    </a>

                    <a href="<?= ROOT ?>/medical-school/usage-logs" class="cp-action-card cp-action-card--usage">
                        <div class="cp-action-card__icon"><i class="fas fa-book-medical"></i></div>
                        <div class="cp-action-card__info">
                            <span class="cp-action-card__label">Usage Records</span>
                            <span class="cp-action-card__sub">Teaching & research logs</span>
                        </div>
                        <i class="fas fa-chevron-right cp-action-card__arrow"></i>
                    </a>

                    <a href="<?= ROOT ?>/medical-school/appreciation" class="cp-action-card cp-action-card--comm">
                        <div class="cp-action-card__icon"><i class="fas fa-envelope-circle-check"></i></div>
                        <div class="cp-action-card__info">
                            <span class="cp-action-card__label">Official Correspondence</span>
                            <span class="cp-action-card__sub">Letters & Certificates</span>
                        </div>
                        <i class="fas fa-chevron-right cp-action-card__arrow"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Action Hub / Critical Alerts -->
        <div class="cp-card">
            <div class="cp-card__header">
                <h3 class="cp-card__title"><i class="fas fa-bell"></i> Critical Action Hub</h3>
                <?php if (!empty($urgentAlerts)): ?>
                    <span class="cp-badge cp-badge--danger"><?= count($urgentAlerts) ?> PENDING</span>
                <?php endif; ?>
            </div>
            <div class="cp-card__body">
                <div class="cp-alert-stack">
                    <?php if (empty($urgentAlerts)): ?>
                        <div class="cp-empty-alerts">
                            <i class="fas fa-shield-check"></i>
                            <h4>Systems Nominal</h4>
                            <p>No urgent institutional tasks or impending deadlines detected at this time.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($urgentAlerts as $alert): ?>
                            <div class="cp-alert-item cp-alert-item--<?= strtolower($alert['priority']) ?>">
                                <div class="cp-alert-item__icon">
                                    <i class="fas fa-<?= $alert['type'] === 'DEADLINE' ? 'clock-exclamation' : ($alert['type'] === 'EXAM' ? 'stethoscope' : 'file-circle-exclamation') ?>"></i>
                                </div>
                                <div class="cp-alert-item__content">
                                    <div class="cp-alert-item__title"><?= htmlspecialchars($alert['title']) ?></div>
                                    <div class="cp-alert-item__msg"><?= htmlspecialchars($alert['msg']) ?></div>
                                    <a href="<?= $alert['link'] ?>" class="cp-alert-item__action">
                                        Go to Action <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Secondary Summary Stats -->
                <div class="cp-mini-stats" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding-top: 1.5rem; border-top: 1px solid var(--g100);">
                    <div class="cp-mini-stat" style="background: var(--g50); padding: 10px; border-radius: 10px; text-align: center;">
                        <span class="cp-mini-stat__val" style="display: block; font-weight: 800;"><?= $stats['pending_docs'] ?></span>
                        <span class="cp-mini-stat__lbl" style="font-size: 0.65rem; color: var(--g500); text-transform: uppercase;">Docs Review</span>
                    </div>
                    <div class="cp-mini-stat" style="background: var(--g50); padding: 10px; border-radius: 10px; text-align: center;">
                        <span class="cp-mini-stat__val" style="display: block; font-weight: 800;"><?= $stats['pending_exams'] ?></span>
                        <span class="cp-mini-stat__lbl" style="font-size: 0.65rem; color: var(--g500); text-transform: uppercase;">Pending Exam</span>
                    </div>
                    <div class="cp-mini-stat" style="background: var(--g50); padding: 10px; border-radius: 10px; text-align: center;">
                        <span class="cp-mini-stat__val" style="display: block; font-weight: 800;"><?= $stats['pristine_bodies'] ?></span>
                        <span class="cp-mini-stat__lbl" style="font-size: 0.65rem; color: var(--g500); text-transform: uppercase;">Pristine Inv</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>



<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>