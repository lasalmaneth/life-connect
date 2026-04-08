<?php
/**
 * Custodian Portal — Report Death View
 * Route: GET /custodian/report-death
 * Active page key: report-death
 */

$page_icon     = 'fa-heart-pulse';
$page_heading  = 'Report Death';
$page_subtitle = 'Formally declare the donor\'s passing to initiate the donation process.';
$page_badge    = ['type' => 'danger', 'text' => 'Critical Action'];

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-notice cp-notice--warning">
        <i class="fas fa-triangle-exclamation"></i>
        <div>
            <strong>This action is irreversible</strong>
            <p>Once death is declared, the donation workflow is activated. A 72-hour window opens for legal and logistical coordination. Ensure all information is accurate before submitting.</p>
        </div>
    </div>

    <!-- ── Declaration Check ──────────────────────────────────────────── -->
    <?php
    $section_title = 'Current Declaration Status';
    $section_icon  = 'fa-circle-dot';
    ob_start();
    ?>
    <?php
    $empty_icon = 'fa-heart-pulse';
    $empty_msg  = 'No death declaration on record';
    $empty_sub  = 'Submit the form below to initiate the declaration';
    include __DIR__ . '/partials/empty-state.php';
    ?>
    <?php
    $section_content = ob_get_clean();
    $section_action  = null;
    include __DIR__ . '/partials/section-card.php';
    ?>

    <!-- ── Declaration Form ───────────────────────────────────────────── -->
    <?php
    $section_title = 'Death Declaration Form';
    $section_icon  = 'fa-pen-to-square';
    ob_start();
    ?>
    <div class="cp-notice cp-notice--danger" style="margin-bottom:1.25rem;">
        <i class="fas fa-lock"></i>
        <div>
            <strong>Backend Integration Pending</strong>
            <p>Form submission is disabled. Connect the backend endpoint <code>POST /api/custodian/declare-death</code> to enable this form.</p>
        </div>
    </div>

    <form action="#" method="POST" id="report-death-form">

        <div class="cp-form-row-2">
            <div class="cp-form-group">
                <label class="cp-form-label" for="date_of_death">Date of Death <span class="cp-required">*</span></label>
                <input type="date" id="date_of_death" name="date_of_death" class="cp-form-control" disabled>
            </div>
            <div class="cp-form-group">
                <label class="cp-form-label" for="time_of_death">Time of Death <span class="cp-required">*</span></label>
                <input type="time" id="time_of_death" name="time_of_death" class="cp-form-control" disabled>
            </div>
        </div>

        <div class="cp-form-group">
            <label class="cp-form-label" for="place_of_death">Place of Death <span class="cp-required">*</span></label>
            <input type="text" id="place_of_death" name="place_of_death" class="cp-form-control"
                   placeholder="Hospital name, city…" disabled>
        </div>

        <div class="cp-form-group">
            <label class="cp-form-label" for="cause_of_death">Cause of Death <span class="cp-required">*</span></label>
            <input type="text" id="cause_of_death" name="cause_of_death" class="cp-form-control"
                   placeholder="e.g. Cardiac arrest, stroke…" disabled>
        </div>

        <div class="cp-form-group">
            <label class="cp-form-label" for="additional_notes">Additional Notes</label>
            <textarea id="additional_notes" name="additional_notes" class="cp-form-control"
                      placeholder="Any relevant additional information…" disabled></textarea>
        </div>

        <div class="cp-form-actions">
            <button type="submit" class="cp-btn cp-btn--danger" disabled title="Backend integration pending">
                <i class="fas fa-heart-pulse"></i> Submit Death Declaration
            </button>
            <span style="font-size:.78rem; color:var(--g500); font-style:italic;">
                Backend integration pending — form is locked
            </span>
        </div>

    </form>
    <?php
    $section_content = ob_get_clean();
    include __DIR__ . '/partials/section-card.php';
    ?>

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
