<?php
/**
 * Custodian Portal — Donor Profile View
 * Route: GET /custodian/donor-profile
 * Active page key: donor-profile
 */

$page_icon     = 'fa-id-card';
$page_heading  = 'Donor Profile';
$page_subtitle = 'View the registered donor\'s personal and medical information.';

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-notice cp-notice--info">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Read-Only View</strong>
            <p>Donor profile data is managed by the system administrator. This page is view-only.</p>
        </div>
    </div>

    <!-- ── Personal Information ───────────────────────────────────────── -->
    <?php
    $card_title = 'Personal Information';
    $card_icon  = 'fa-user';
    $card_rows  = [
        ['label' => 'Full Name',       'value' => null],
        ['label' => 'Date of Birth',   'value' => null],
        ['label' => 'Age',             'value' => null],
        ['label' => 'Gender',          'value' => null],
        ['label' => 'NIC Number',      'value' => null],
        ['label' => 'Nationality',     'value' => null],
    ];
    include __DIR__ . '/partials/info-card.php';
    ?>

    <div class="cp-grid-2">

        <!-- ── Medical Information ────────────────────────────────────── -->
        <?php
        $card_title = 'Medical Information';
        $card_icon  = 'fa-heart-pulse';
        $card_rows  = [
            ['label' => 'Blood Group',     'value' => null],
            ['label' => 'Donation Type',   'value' => null],
            ['label' => 'Health Status',   'value' => null],
            ['label' => 'Registered Date', 'value' => null],
        ];
        $card_action = null;
        include __DIR__ . '/partials/info-card.php';
        ?>

        <!-- ── Contact Details ────────────────────────────────────────── -->
        <?php
        $card_title = 'Contact Details';
        $card_icon  = 'fa-phone';
        $card_rows  = [
            ['label' => 'Phone',   'value' => null],
            ['label' => 'Email',   'value' => null],
            ['label' => 'Address', 'value' => null],
            ['label' => 'City',    'value' => null],
        ];
        $card_action = null;
        include __DIR__ . '/partials/info-card.php';
        ?>

    </div>

    <!-- ── Next of Kin ────────────────────────────────────────────────── -->
    <?php
    $section_title = 'Next of Kin';
    $section_icon  = 'fa-people-group';
    ob_start();
    ?>
    <div class="cp-notice cp-notice--warning" style="margin-bottom:0;">
        <i class="fas fa-triangle-exclamation"></i>
        <div>
            <strong>No Data Available</strong>
            <p>Next of kin information will be displayed here once backend is connected.</p>
        </div>
    </div>
    <?php
    $section_content = ob_get_clean();
    include __DIR__ . '/partials/section-card.php';
    ?>

    <!-- ── Pledged Organs / Body ──────────────────────────────────────── -->
    <?php
    $section_title = 'Pledged Donations';
    $section_icon  = 'fa-hand-holding-heart';
    $section_action = ['label' => 'View Consent', 'href' => ROOT . '/custodian/consent'];
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

</div><!-- /.cp-content__body -->

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
