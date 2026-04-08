<?php
/**
 * Custodian Portal — Legal Response View
 * Route: GET /custodian/legal-response
 * Active page key: legal-response
 */

$page_icon     = 'fa-gavel';
$page_heading  = 'Legal Response';
$page_subtitle = 'Confirm or formally object to the donation on behalf of the estate.';
$page_badge    = ['type' => 'pending', 'text' => 'Awaiting'];

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-notice cp-notice--warning">
        <i class="fas fa-gavel"></i>
        <div>
            <strong>Legal Action Window</strong>
            <p>After death is declared, a legal response must be filed within the designated time window. Please act promptly. Confirm the donor's wish or formally register an objection.</p>
        </div>
    </div>

    <!-- ── Current Legal Status ───────────────────────────────────────── -->
    <?php
    $card_title = 'Current Legal Status';
    $card_icon  = 'fa-scale-balanced';
    $card_rows  = [
        ['label' => 'Case Number',      'value' => null],
        ['label' => 'Legal Status',     'value' => null],
        ['label' => 'Window Opens',     'value' => null],
        ['label' => 'Window Closes',    'value' => null],
        ['label' => 'Time Remaining',   'value' => null],
    ];
    $card_action = null;
    include __DIR__ . '/partials/info-card.php';
    ?>

    <!-- ── Action Cards ───────────────────────────────────────────────── -->
    <div class="cp-action-grid">
        <?php
        $action_type  = 'confirm';
        $action_icon  = 'fa-circle-check';
        $action_title = 'Confirm Donation';
        $action_desc  = 'Legally confirm that you support the donor\'s registered consent and wish for their body or organs to be donated.';
        $action_btn   = 'Confirm Donation';
        include __DIR__ . '/partials/action-card.php';

        $action_type  = 'object';
        $action_icon  = 'fa-ban';
        $action_title = 'Object to Donation';
        $action_desc  = 'Formally register a legal objection to the donation. This will trigger a review process. A reason is required.';
        $action_btn   = 'File Objection';
        include __DIR__ . '/partials/action-card.php';
        ?>
    </div>

    <!-- ── Previous Actions ───────────────────────────────────────────── -->
    <?php
    $section_title = 'Legal Action History';
    $section_icon  = 'fa-clock-rotate-left';
    ob_start();
    ?>
    <?php
    $empty_icon = 'fa-scale-balanced';
    $empty_msg  = 'No legal actions recorded';
    $empty_sub  = 'Actions taken here will appear in this log';
    include __DIR__ . '/partials/empty-state.php';
    ?>
    <?php
    $section_content = ob_get_clean();
    $section_action  = null;
    include __DIR__ . '/partials/section-card.php';
    ?>

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
