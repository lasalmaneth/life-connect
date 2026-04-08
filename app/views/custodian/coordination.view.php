<?php
/**
 * Custodian Portal — Coordination View
 * Route: GET /custodian/coordination
 * Active page key: coordination
 */

$page_icon     = 'fa-network-wired';
$page_heading  = 'Coordination';
$page_subtitle = 'Track the status of selected institutions and manage the coordination workflow.';

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <div class="cp-notice cp-notice--info">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Coordination is case-dependent</strong>
            <p>Institution coordination is only available after a donation case has been opened (i.e., after death is declared and legal response is filed).</p>
        </div>
    </div>

    <!-- ── Select Institution ─────────────────────────────────────────── -->
    <?php
    $section_title = 'Select Institution';
    $section_icon  = 'fa-hospital';
    ob_start();
    ?>
    <div class="cp-notice cp-notice--danger" style="margin-bottom:1rem;">
        <i class="fas fa-lock"></i>
        <div>
            <strong>Backend Integration Pending</strong>
            <p>Institution selection requires <code>POST /api/custodian/select-institution</code> and <code>GET /api/custodian/available-institutions</code>.</p>
        </div>
    </div>
    <div class="cp-form-row-2">
        <div class="cp-form-group">
            <label class="cp-form-label" for="inst_track">Donation Track</label>
            <select id="inst_track" name="track" class="cp-form-control" disabled>
                <option value="BODY">Body Donation</option>
                <option value="ORGAN">Organ Donation</option>
                <option value="CORNEA">Cornea Donation</option>
            </select>
        </div>
        <div class="cp-form-group">
            <label class="cp-form-label" for="inst_select">Available Institutions</label>
            <select id="inst_select" name="institution_id" class="cp-form-control" disabled>
                <option value="">No institutions available</option>
            </select>
        </div>
    </div>
    <div class="cp-form-actions">
        <button type="button" class="cp-btn cp-btn--primary" disabled title="Backend integration pending">
            <i class="fas fa-hospital"></i> Contact Institution
        </button>
    </div>
    <?php
    $section_content = ob_get_clean();
    include __DIR__ . '/partials/section-card.php';
    ?>

    <!-- ── Institution Status Table ───────────────────────────────────── -->
    <?php
    $section_title = 'Institution Status Board';
    $section_icon  = 'fa-table-list';
    ob_start();
    ?>
    <div class="cp-table-wrap">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Institution</th>
                    <th>Track</th>
                    <th>Contacted</th>
                    <th>Response</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6">
                        <?php
                        $empty_icon = 'fa-network-wired';
                        $empty_msg  = 'No institutions selected';
                        $empty_sub  = 'Backend integration pending';
                        include __DIR__ . '/partials/empty-state.php';
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
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
