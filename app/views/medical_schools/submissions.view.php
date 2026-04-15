<?php
/**
 * Medical School Portal — Body Submissions (Stages E & F)
 */

$page_title    = 'Document Submissions';
$active_page   = 'submissions';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-folder-open"></i> Document Submissions</h1>
        <p class="cp-content-header__subtitle">Verification and review of mandatory document bundles submitted by custodians.</p>
    </div>
    <div class="cp-content-header__actions">
        <span class="cp-badge cp-badge--info cp-badge--lg">
            <i class="fas fa-file-check cp-mr-2"></i> Document Verification
        </span>
    </div>
</div>

<div class="cp-content-body">
    <!-- Premium Filter Bar -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
        <div class="cp-filter-tabs">
            <a href="<?= ROOT ?>/medical-school/submissions?status=ALL" 
               class="cp-filter-btn <?= $active_status === 'ALL' ? 'active' : '' ?>">All Submissions</a>
            <a href="<?= ROOT ?>/medical-school/submissions?status=PENDING" 
               class="cp-filter-btn <?= $active_status === 'PENDING' ? 'active' : '' ?>">Pending Review</a>
            <a href="<?= ROOT ?>/medical-school/submissions?status=ACCEPTED" 
               class="cp-filter-btn <?= $active_status === 'ACCEPTED' ? 'active' : '' ?>">Accepted</a>
            <a href="<?= ROOT ?>/medical-school/submissions?status=REJECTED" 
               class="cp-filter-btn <?= $active_status === 'REJECTED' ? 'active' : '' ?>">Rejected / Incomplete</a>
        </div>
    </div>
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
                        <td colspan="5">
                            <div class="cp-empty-state">
                                <i class="fas fa-clipboard-list cp-empty-state__icon"></i>
                                <div class="cp-empty-state__msg">No Active Submissions</div>
                                <div class="cp-empty-state__sub">When custodians submit document bundles for review, they will appear here.</div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($submissions as $sub): ?>
                        <tr>
                            <td>
                                <div class="cp-table__icon-cell">
                                    <div class="cp-table__file-icon cp-table__file-icon--primary">
                                        <i class="fas fa-file-medical"></i>
                                    </div>
                                    <div>
                                        <div class="cp-table__filename"><?= htmlspecialchars($sub->first_name . ' ' . $sub->last_name) ?></div>
                                        <div class="cp-table__subtext">Case ID: #<?= $sub->case_id ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="cp-nic-badge"><?= htmlspecialchars($sub->nic_number ?? 'N/A') ?></code></td>
                            <td>
                                <div class="cp-table__filename"><?= $sub->document_action_at ? date('d M Y', strtotime($sub->document_action_at)) : 'N/A' ?></div>
                                <div class="cp-table__subtext"><?= $sub->document_action_at ? date('H:i', strtotime($sub->document_action_at)) : '' ?></div>
                            </td>
                            <td>
                                <span class="cp-badge cp-badge--<?= strtolower(str_replace('_', '-', $sub->document_status)) === 'accepted' ? 'active' : 'pending' ?>">
                                    <?= htmlspecialchars(str_replace('_', ' ', $sub->document_status)) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="cp-table__actions">
                                    <button class="cp-btn cp-btn--secondary cp-btn--sm" onclick="viewSubmissionDetails(<?= $sub->cis_id ?>)">
                                        <i class="fas fa-folder-open"></i> Submission Details
                                    </button>
                                </div>
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
