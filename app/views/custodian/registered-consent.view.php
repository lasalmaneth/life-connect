<?php
/**
 * Custodian Portal — Registered Consent View
 * Route: GET /custodian/consent
 * Active page key: consent
 */

$page_icon     = 'fa-file-signature';
$page_heading  = 'Registered Consent';
$page_subtitle = 'The donor\'s legally registered consent record. This is read-only.';

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-notice cp-notice--info">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Consent is legally binding</strong>
            <p>This record reflects the donor's last registered and verified consent. Any changes must be made through the Administration team.</p>
        </div>
    </div>

    <!-- ── Consent Summary ────────────────────────────────────────────── -->
    <?php
    $card_title = 'Consent Summary';
    $card_icon  = 'fa-file-contract';
    $card_rows  = [
        ['label' => 'Consent Type',     'value' => null],
        ['label' => 'Donation Track',   'value' => null],
        ['label' => 'Consent Date',     'value' => null],
        ['label' => 'Last Updated',     'value' => null],
        ['label' => 'Verification',     'value' => null],
        ['label' => 'Consent Status',   'value' => null],
    ];
    $card_action = null;
    include __DIR__ . '/partials/info-card.php';
    ?>

    <!-- ── Pledged Body / Organs ──────────────────────────────────────── -->
    <?php
    $section_title = 'Pledged Items';
    $section_icon  = 'fa-hand-holding-heart';
    ob_start();
    ?>
    <?php
    $empty_icon = 'fa-hand-holding-heart';
    $empty_msg  = 'No pledges on record';
    $empty_sub  = 'Backend integration pending';
    include __DIR__ . '/partials/empty-state.php';
    ?>
    <?php
    $section_content = ob_get_clean();
    include __DIR__ . '/partials/section-card.php';
    ?>

    <!-- ── Consent Specific Organ Exclusions ─────────────────────────── -->
    <?php
    $section_title = 'Exclusions / Restrictions';
    $section_icon  = 'fa-ban';
    ob_start();
    ?>
    <?php
    $empty_icon = 'fa-ban';
    $empty_msg  = 'No exclusions recorded';
    $empty_sub  = 'Backend integration pending';
    include __DIR__ . '/partials/empty-state.php';
    ?>
    <?php
    $section_content = ob_get_clean();
    $section_action  = null;
    include __DIR__ . '/partials/section-card.php';
    ?>

    <!-- ── Consent History ────────────────────────────────────────────── -->
    <?php
    $section_title = 'Consent History';
    $section_icon  = 'fa-clock-rotate-left';
    ob_start();
    ?>
    <?php
    $empty_icon = 'fa-clock-rotate-left';
    $empty_msg  = 'No history available';
    $empty_sub  = 'Consent version history will appear here';
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
