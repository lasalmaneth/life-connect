<?php
/**
 * Medical School Portal — Final Body Examination (Stage G)
 */

$page_title    = 'Final Examinations';
$active_page   = 'final-examinations';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-clipboard-check"></i> Final Body Examination</h1>
        <p class="cp-content-header__subtitle">Anatomical assessment after the body reaches the institution.</p>
    </div>
    <div class="cp-content-header__actions">
        <span class="cp-badge cp-badge--info cp-badge--lg">
            <i class="fas fa-clipboard-check cp-mr-2"></i> Physical Verification
        </span>
    </div>
</div>

<div class="cp-content-body">
    <!-- Premium Filter Bar -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
        <div class="cp-filter-tabs">
            <a href="<?= ROOT ?>/medical-school/final-examinations?status=ALL" 
               class="cp-filter-btn <?= $active_status === 'ALL' ? 'active' : '' ?>">All Records</a>
            <a href="<?= ROOT ?>/medical-school/final-examinations?status=AWAITING" 
               class="cp-filter-btn <?= $active_status === 'AWAITING' ? 'active' : '' ?>">Awaiting Exam</a>
            <a href="<?= ROOT ?>/medical-school/final-examinations?status=ACCEPTED" 
               class="cp-filter-btn <?= $active_status === 'ACCEPTED' ? 'active' : '' ?>">Accepted</a>
            <a href="<?= ROOT ?>/medical-school/final-examinations?status=REJECTED" 
               class="cp-filter-btn <?= $active_status === 'REJECTED' ? 'active' : '' ?>">Rejected</a>
        </div>
    </div>

    <div class="cp-table-container">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Case Info</th>
                    <th>NIC</th>
                    <th>Arrival/Exam Date</th>
                    <th>Medical Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($exams)): ?>
                    <tr>
                        <td colspan="5">
                            <div class="cp-empty-state">
                                <i class="fas fa-stethoscope cp-empty-state__icon"></i>
                                <div class="cp-empty-state__msg">No Active Examinations</div>
                                <div class="cp-empty-state__sub">When bodies arrive at the institution, they will appear here for anatomical assessment.</div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($exams as $exam): ?>
                        <tr>
                            <td>
                                <div class="cp-table__icon-cell">
                                    <div class="cp-table__file-icon cp-table__file-icon--success">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div>
                                        <div class="cp-table__filename"><?= htmlspecialchars($exam->first_name . ' ' . $exam->last_name) ?></div>
                                        <div class="cp-table__subtext">Case ID: #<?= $exam->case_id ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="cp-nic-badge"><?= htmlspecialchars($exam->nic_number ?? 'N/A') ?></code></td>
                            <td>
                                <div class="cp-table__filename"><?= $exam->final_exam_at ? date('d M Y', strtotime($exam->final_exam_at)) : 'Pending Arrival' ?></div>
                                <div class="cp-table__subtext"><?= $exam->final_exam_at ? date('H:i', strtotime($exam->final_exam_at)) : 'N/A' ?></div>
                            </td>
                            <td>
                                <span class="cp-badge cp-badge--<?= strtolower($exam->final_exam_status) === 'accepted' ? 'active' : (strtolower($exam->final_exam_status) === 'awaiting' ? 'pending' : 'danger') ?>">
                                    <?= htmlspecialchars($exam->final_exam_status) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="cp-table__actions">
                                    <button class="cp-btn cp-btn--secondary cp-btn--sm" onclick="viewExamDetails(<?= $exam->cis_id ?>)">
                                        <i class="fas fa-file-medical"></i> Examination Details
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
function viewExamDetails(id) {
    if (!window.CaseDrawer) return;
    
    document.getElementById('drawerTitle').innerText = 'Final Physical Examination';
    const body = document.getElementById('drawerBody');
    body.innerHTML = '<div class="cp-loading"><i class="fas fa-circle-notch fa-spin"></i> Loading exam history...</div>';
    
    window.CaseDrawer.open();
    
    fetch('<?= ROOT ?>/medical-school/final-examinations/view?id=' + id)
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
