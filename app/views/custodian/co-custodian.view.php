<?php
/**
 * Custodian Portal — Co-Custodian View
 * Route: GET /custodian/co-custodian
 * Active page key: co-custodian
 */

$page_icon     = 'fa-user-shield';
$page_heading  = 'Co-Custodian';
$page_subtitle = 'Details about the appointed co-custodian for this donor\'s case.';

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-notice cp-notice--info">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Dual Custodian Requirement</strong>
            <p>Each donor has a primary and a co-custodian. Both custodians must be in agreement on legal actions. Contact your co-custodian if needed.</p>
        </div>
    </div>

    <div class="cp-grid-2">

        <!-- ── You (Primary Custodian) ────────────────────────────────── -->
        <?php
        $card_title = 'You (Primary Custodian)';
        $card_icon  = 'fa-user-check';
        $card_rows  = [
            ['label' => 'Full Name',    'value' => $custodian_name ?? null],
            ['label' => 'Custodian ID', 'value' => $custodian_id_display ?? null],
            ['label' => 'Relationship', 'value' => null],
            ['label' => 'Phone',        'value' => null],
            ['label' => 'Email',        'value' => null],
        ];
        $card_action = null;
        include __DIR__ . '/partials/info-card.php';
        ?>

        <!-- ── Co-Custodian ───────────────────────────────────────────── -->
        <?php
        $card_title = 'Co-Custodian';
        $card_icon  = 'fa-user-shield';
        $card_rows  = [
            ['label' => 'Full Name',    'value' => null],
            ['label' => 'Custodian ID', 'value' => null],
            ['label' => 'Relationship', 'value' => null],
            ['label' => 'Phone',        'value' => null],
            ['label' => 'Email',        'value' => null],
        ];
        $card_action = null;
        include __DIR__ . '/partials/info-card.php';
        ?>

    </div>

    <!-- ── Agreement Status ───────────────────────────────────────────── -->
    <?php
    $section_title = 'Agreement Status';
    $section_icon  = 'fa-handshake';
    ob_start();
    ?>
    <?php
    $empty_icon = 'fa-handshake';
    $empty_msg  = 'No legal action pending';
    $empty_sub  = 'Agreement status will appear here when a case is active';
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
