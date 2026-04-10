<?php
/**
 * Medical School Portal — Body Submissions (Stages E & F)
 */

$page_title    = 'Body Submissions';
$active_page   = 'submissions';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-folder-open"></i> Body Submissions</h1>
        <p class="cp-content-header__subtitle">Verification and review of mandatory document bundles submitted by custodians.</p>
    </div>
</div>

<div class="cp-content-body">
    <div class="cp-table-container">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Case Info</th>
                    <th>NIC</th>
                    <th>Last Update</th>
                    <th>Document Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($submissions)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem;">
                            <div class="cp-empty">
                                <i class="fas fa-clipboard-list cp-empty__icon"></i>
                                <p>No document bundles currently pending your review.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($submissions as $sub): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 500;"><?= htmlspecialchars($sub->first_name . ' ' . $sub->last_name) ?></div>
                                <div style="font-size: 0.75rem; color: var(--g500);">Case ID: #<?= $sub->case_id ?></div>
                            </td>
                            <td><?= htmlspecialchars($sub->nic_number ?? 'N/A') ?></td>
                            <td><?= $sub->document_action_at ? date('M d, Y', strtotime($sub->document_action_at)) : 'N/A' ?></td>
                            <td>
                                <span class="cp-status-badge cp-status-badge--<?= strtolower(str_replace('_', '-', $sub->document_status)) ?>">
                                    <?= htmlspecialchars(str_replace('_', ' ', $sub->document_status)) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <button class="cp-btn cp-btn--primary cp-btn--sm" onclick="viewSubmissionDetails(<?= $sub->cis_id ?>)">
                                    <i class="fas fa-folder-open"></i> Review Bundle
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
function viewSubmissionDetails(id) {
    if (!window.CaseDrawer) return;
    
    document.getElementById('drawerTitle').innerText = 'Document Bundle Review';
    const body = document.getElementById('drawerBody');
    body.innerHTML = '<div class="cp-loading"><i class="fas fa-circle-notch fa-spin"></i> Loading bundle...</div>';
    
    window.CaseDrawer.open();
    
    fetch('<?= ROOT ?>/medical-school/submissions/view?id=' + id)
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
