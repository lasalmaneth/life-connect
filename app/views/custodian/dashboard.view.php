<?php
/**
 * Custodian Portal — Dashboard View
 * Route: GET /custodian/dashboard
 * Active page key: dashboard
 */

// ── Page-specific variables ────────────────────────────────────────────────
$page_icon     = 'fa-chart-line';
$page_heading  = 'Dashboard';
$page_subtitle = 'Welcome back. Here is an overview of your custodian responsibilities.';
$page_badge    = ['type' => 'info', 'text' => 'Custodian Portal'];

// ── Stat values (placeholder — backend integration pending) ────────────────
$stat_donor_status  = '—';
$stat_case_status   = '—';
$stat_documents     = '—';
$stat_days          = '—';

// ── Output buffering: capture page content ─────────────────────────────────
ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <!-- ── Stats Grid ─────────────────────────────────────────────────── -->
    <div class="cp-stats-grid">

        <?php
        $stat_icon = 'fa-heart-pulse'; $stat_label = 'Donor Status';
        $stat_value = $stat_donor_status; $stat_variant = '';
        include __DIR__ . '/partials/stat-card.php';

        $stat_icon = 'fa-briefcase-medical'; $stat_label = 'Case Status';
        $stat_value = $stat_case_status; $stat_variant = '';
        include __DIR__ . '/partials/stat-card.php';

        $stat_icon = 'fa-folder-open'; $stat_label = 'Documents';
        $stat_value = $stat_documents; $stat_variant = '';
        include __DIR__ . '/partials/stat-card.php';

        $stat_icon = 'fa-calendar-days'; $stat_label = 'Days Since Registration';
        $stat_value = $stat_days; $stat_variant = '';
        include __DIR__ . '/partials/stat-card.php';
        ?>

    </div>

    <!-- ── Dashboard Widget Grid ──────────────────────────────────────── -->
    <div class="cp-dashboard-grid">

        <!-- Widget: Donor Summary -->
        <div class="cp-widget">
            <div class="cp-widget__header">
                <div class="cp-widget__title">
                    <i class="fas fa-id-card"></i> Donor Summary
                </div>
                <a href="<?= ROOT ?>/custodian/donor-profile" class="cp-btn cp-btn--outline cp-btn--sm">
                    View Profile
                </a>
            </div>
            <div class="cp-widget__body">
                <div class="cp-info-row">
                    <span class="cp-info-label">Full Name</span>
                    <span class="cp-info-value cp-info-value--placeholder">—</span>
                </div>
                <div class="cp-info-row">
                    <span class="cp-info-label">Donor ID</span>
                    <span class="cp-info-value cp-info-value--placeholder">—</span>
                </div>
                <div class="cp-info-row">
                    <span class="cp-info-label">Blood Group</span>
                    <span class="cp-info-value cp-info-value--placeholder">—</span>
                </div>
                <div class="cp-info-row">
                    <span class="cp-info-label">Donation Type</span>
                    <span class="cp-info-value cp-info-value--placeholder">—</span>
                </div>
                <div class="cp-info-row">
                    <span class="cp-info-label">Consent Status</span>
                    <span class="cp-info-value cp-info-value--placeholder">—</span>
                </div>
            </div>
        </div>

        <!-- Widget: Case Status -->
        <div class="cp-widget">
            <div class="cp-widget__header">
                <div class="cp-widget__title">
                    <i class="fas fa-briefcase-medical"></i> Case Status
                </div>
            </div>
            <div class="cp-widget__body">
                <?php
                $empty_icon = 'fa-briefcase-medical';
                $empty_msg  = 'No active case';
                $empty_sub  = 'A case opens when death is declared';
                include __DIR__ . '/partials/empty-state.php';
                ?>
            </div>
        </div>

        <!-- Widget: Quick Links (full width) -->
        <div class="cp-widget cp-widget--full">
            <div class="cp-widget__header">
                <div class="cp-widget__title">
                    <i class="fas fa-bolt"></i> Quick Actions
                </div>
            </div>
            <div class="cp-widget__body" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem;">

                <a href="<?= ROOT ?>/custodian/consent" class="cp-section-card" style="text-decoration:none; padding:1.25rem; text-align:center; transition:all .25s; cursor:pointer;" onmouseover="this.style.borderColor='var(--blue-300)'" onmouseout="this.style.borderColor='var(--g200)'">
                    <i class="fas fa-file-signature" style="font-size:1.5rem; color:var(--blue-500); display:block; margin-bottom:.6rem;"></i>
                    <div style="font-size:.85rem; font-weight:700; color:var(--slate);">View Consent</div>
                    <div style="font-size:.75rem; color:var(--g500); margin-top:.2rem;">Registered consent record</div>
                </a>

                <a href="<?= ROOT ?>/custodian/report-death" class="cp-section-card" style="text-decoration:none; padding:1.25rem; text-align:center; transition:all .25s; cursor:pointer;" onmouseover="this.style.borderColor='var(--blue-300)'" onmouseout="this.style.borderColor='var(--g200)'">
                    <i class="fas fa-heart-pulse" style="font-size:1.5rem; color:var(--danger); display:block; margin-bottom:.6rem;"></i>
                    <div style="font-size:.85rem; font-weight:700; color:var(--slate);">Report Death</div>
                    <div style="font-size:.75rem; color:var(--g500); margin-top:.2rem;">Declare donor's passing</div>
                </a>

                <a href="<?= ROOT ?>/custodian/documents" class="cp-section-card" style="text-decoration:none; padding:1.25rem; text-align:center; transition:all .25s; cursor:pointer;" onmouseover="this.style.borderColor='var(--blue-300)'" onmouseout="this.style.borderColor='var(--g200)'">
                    <i class="fas fa-folder-open" style="font-size:1.5rem; color:var(--blue-500); display:block; margin-bottom:.6rem;"></i>
                    <div style="font-size:.85rem; font-weight:700; color:var(--slate);">Documents</div>
                    <div style="font-size:.75rem; color:var(--g500); margin-top:.2rem;">Manage case documents</div>
                </a>

                <a href="<?= ROOT ?>/custodian/timeline" class="cp-section-card" style="text-decoration:none; padding:1.25rem; text-align:center; transition:all .25s; cursor:pointer;" onmouseover="this.style.borderColor='var(--blue-300)'" onmouseout="this.style.borderColor='var(--g200)'">
                    <i class="fas fa-stream" style="font-size:1.5rem; color:var(--blue-500); display:block; margin-bottom:.6rem;"></i>
                    <div style="font-size:.85rem; font-weight:700; color:var(--slate);">Timeline</div>
                    <div style="font-size:.75rem; color:var(--g500); margin-top:.2rem;">Full audit trail</div>
                </a>

            </div>
        </div>

        <!-- Widget: Recent Activity (full width) -->
        <div class="cp-widget cp-widget--full">
            <div class="cp-widget__header">
                <div class="cp-widget__title">
                    <i class="fas fa-history"></i> Recent Activity
                </div>
                <a href="<?= ROOT ?>/custodian/timeline" class="cp-btn cp-btn--outline cp-btn--sm">
                    Full Timeline
                </a>
            </div>
            <div class="cp-widget__body">
                <?php
                $empty_icon = 'fa-clock-rotate-left';
                $empty_msg  = 'No recent activity';
                $empty_sub  = 'Backend integration pending';
                include __DIR__ . '/partials/empty-state.php';
                ?>
            </div>
        </div>

    </div><!-- /.cp-dashboard-grid -->

    <!-- ── Notice Banner ──────────────────────────────────────────────── -->
    <div class="cp-notice cp-notice--info">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Backend Integration Pending</strong>
            <p>Dashboard data will be loaded from the server once the backend is connected. All sections currently show placeholder content.</p>
        </div>
    </div>

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
