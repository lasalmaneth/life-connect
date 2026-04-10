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
        <p class="cp-content-header__subtitle">Final physical verification and anatomical assessment after the body reaches the institution.</p>
    </div>
</div>

<div class="cp-content-body">
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
                        <td colspan="5" style="text-align: center; padding: 3rem;">
                            <div class="cp-empty">
                                <i class="fas fa-stethoscope cp-empty__icon"></i>
                                <p>No bodies currently awaiting final examination.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($exams as $exam): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 500;"><?= htmlspecialchars($exam->first_name . ' ' . $exam->last_name) ?></div>
                                <div style="font-size: 0.75rem; color: var(--g500);">Case ID: #<?= $exam->case_id ?></div>
                            </td>
                            <td><?= htmlspecialchars($exam->nic_number ?? 'N/A') ?></td>
                            <td><?= $exam->final_exam_at ? date('M d, Y', strtotime($exam->final_exam_at)) : 'Pending Arrival' ?></td>
                            <td>
                                <span class="cp-status-badge cp-status-badge--<?= strtolower($exam->final_exam_status) ?>">
                                    <?= htmlspecialchars($exam->final_exam_status) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <button class="cp-btn cp-btn--primary cp-btn--sm" onclick="viewExamDetails(<?= $exam->cis_id ?>)">
                                    <i class="fas fa-file-medical"></i> Physical Exam
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
