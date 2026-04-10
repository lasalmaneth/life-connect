<?php
/**
 * Medical School Portal — Custodian Decline Notices (Stage D)
 */

$page_title    = 'Custodian Declines';
$active_page   = 'custodian-declines';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-ban"></i> Custodian Declines</h1>
        <p class="cp-content-header__subtitle">Registry of cases where the custodian decided not to proceed after donor death.</p>
    </div>
</div>

<div class="cp-content-body">
    <div class="cp-table-container">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Donor Name</th>
                    <th>NIC</th>
                    <th>Decline Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($declines)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 3rem;">
                            <div class="cp-empty">
                                <i class="fas fa-file-excel cp-empty__icon"></i>
                                <p>No custodian decline notices recorded.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($declines as $decline): ?>
                        <tr>
                            <td><?= htmlspecialchars($decline->first_name . ' ' . $decline->last_name) ?></td>
                            <td><?= htmlspecialchars($decline->nic_number ?? 'N/A') ?></td>
                            <td><?= date('M d, Y', strtotime($decline->custodian_decline_date)) ?></td>
                            <td style="text-align: right;">
                                <button class="cp-btn cp-btn--secondary cp-btn--sm" onclick="viewDeclineDetails(<?= $decline->cis_id ?>)">
                                    <i class="fas fa-eye"></i> View Notice
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function viewDeclineDetails(id) {
    if (!window.CaseDrawer) return;
    
    document.getElementById('drawerTitle').innerText = 'Custodian Decline Notice';
    const body = document.getElementById('drawerBody');
    body.innerHTML = '<div class="cp-loading"><i class="fas fa-circle-notch fa-spin"></i> Loading...</div>';
    
    window.CaseDrawer.open();
    
    fetch('<?= ROOT ?>/medical-school/custodian-declines/view?id=' + id)
        .then(response => response.text())
        .then(html => {
            body.innerHTML = html;
        });
}
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
