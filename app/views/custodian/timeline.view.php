<?php
/**
 * Custodian Portal — Timeline View
 * Route: GET /custodian/timeline
 * Active page key: timeline
 */

$page_icon     = 'fa-stream';
$page_heading  = 'Timeline';
$page_subtitle = 'Chronological audit trail of all actions and events in this donation case.';

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <!-- ── Summary Bar ────────────────────────────────────────────────── -->
    <div class="cp-summary-bar">
        <div class="cp-summary-item">
            <div class="cp-summary-item__icon"><i class="fas fa-list-check"></i></div>
            <div>
                <div class="cp-summary-item__label">Total Events</div>
                <div class="cp-summary-item__value">—</div>
            </div>
        </div>
        <div class="cp-summary-item">
            <div class="cp-summary-item__icon" style="background:#dcfce7; color:#059669;"><i class="fas fa-circle-check"></i></div>
            <div>
                <div class="cp-summary-item__label">Completed</div>
                <div class="cp-summary-item__value">—</div>
            </div>
        </div>
        <div class="cp-summary-item">
            <div class="cp-summary-item__icon" style="background:#fef3c7; color:#d97706;"><i class="fas fa-clock"></i></div>
            <div>
                <div class="cp-summary-item__label">Pending</div>
                <div class="cp-summary-item__value">—</div>
            </div>
        </div>
        <div class="cp-summary-item">
            <div class="cp-summary-item__icon" style="background:#fee2e2; color:#dc2626;"><i class="fas fa-triangle-exclamation"></i></div>
            <div>
                <div class="cp-summary-item__label">Alerts</div>
                <div class="cp-summary-item__value">—</div>
            </div>
        </div>
    </div>

    <!-- ── Timeline ───────────────────────────────────────────────────── -->
    <?php
    $section_title = 'Case Event Timeline';
    $section_icon  = 'fa-stream';
    ob_start();
    ?>

    <?php
    $empty_icon = 'fa-stream';
    $empty_msg  = 'No events recorded';
    $empty_sub  = 'Timeline events will appear here once backend is connected';
    include __DIR__ . '/partials/empty-state.php';
    ?>

    <!-- Placeholder timeline to show design: -->
    <div style="opacity:.35; pointer-events:none; margin-top:1.5rem;">
        <ul class="cp-timeline">
            <li class="cp-timeline-item">
                <div class="cp-timeline-dot cp-timeline-dot--done"><i class="fas fa-check"></i></div>
                <div class="cp-timeline-date">— — —</div>
                <div class="cp-timeline-title">Registration Completed</div>
                <div class="cp-timeline-content">Placeholder — backend integration pending</div>
            </li>
            <li class="cp-timeline-item">
                <div class="cp-timeline-dot cp-timeline-dot--current"><i class="fas fa-circle"></i></div>
                <div class="cp-timeline-date">— — —</div>
                <div class="cp-timeline-title">Consent Verified</div>
                <div class="cp-timeline-content">Placeholder — backend integration pending</div>
            </li>
            <li class="cp-timeline-item">
                <div class="cp-timeline-dot cp-timeline-dot--pending"></div>
                <div class="cp-timeline-date">— — —</div>
                <div class="cp-timeline-title">Death Declaration</div>
                <div class="cp-timeline-content">Placeholder — backend integration pending</div>
            </li>
            <li class="cp-timeline-item">
                <div class="cp-timeline-dot cp-timeline-dot--pending"></div>
                <div class="cp-timeline-date">— — —</div>
                <div class="cp-timeline-title">Legal Response Filed</div>
                <div class="cp-timeline-content">Placeholder — backend integration pending</div>
            </li>
        </ul>
    </div>

    <?php
    $section_content = ob_get_clean();
    include __DIR__ . '/partials/section-card.php';
    ?>

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
