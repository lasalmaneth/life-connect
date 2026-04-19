<!-- Deceased Final Flow (Stage G for Hospital) -->
<div id="deceased-final-flow" class="content-section" style="<?php echo (isset($initialSection) && $initialSection === 'deceased-final-flow') ? 'display:block' : 'display:none'; ?>">
    <div class="cp-content-header">
        <div class="cp-content-header__content">
            <h1 class="cp-content-header__title">
                <i class="fas fa-check-double"></i> Final Flow & Verification
            </h1>
            <p class="cp-content-header__subtitle">
                Final assessment after the retrieval process and legal documentation is completed.
            </p>
        </div>
        <div class="cp-content-header__actions">
            <span class="cp-badge cp-badge--success cp-badge--lg">
                <i class="fas fa-hand-holding-heart cp-mr-2"></i> Retrieval Verification
            </span>
        </div>
    </div>

    <div class="cp-content-body">
        <!-- Premium Filter Bar -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
            <div class="cp-filter-tabs">
                <button onclick="filterDeceasedFinalFlow('ALL', this)" class="cp-filter-btn active">All Records</button>
                <button onclick="filterDeceasedFinalFlow('AWAITING', this)" class="cp-filter-btn">Awaiting Verification</button>
                <button onclick="filterDeceasedFinalFlow('ACCEPTED', this)" class="cp-filter-btn">Successfully Closed</button>
                <button onclick="filterDeceasedFinalFlow('REJECTED', this)" class="cp-filter-btn">Terminated / Failed</button>
            </div>
        </div>

        <div class="cp-table-container">
            <table class="cp-table" id="deceased-final-flow-table">
                <thead>
                    <tr>
                        <th>Organs</th>
                        <th>NIC</th>
                        <th>Retrieval / Action Date</th>
                        <th style="text-align: center;">Verification Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($deceased_final_flow)): ?>
                        <tr class="empty-row">
                            <td colspan="5">
                                <div class="cp-empty-state">
                                    <i class="fas fa-clipboard-check cp-empty-state__icon"></i>
                                    <div class="cp-empty-state__msg">No Active Verifications</div>
                                    <div class="cp-empty-state__sub">When organ retrieval cases reach the final verification stage, they will appear here.</div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($deceased_final_flow as $flow): ?>
                            <tr class="deceased-final-flow-row" data-status="<?= htmlspecialchars($flow->final_exam_status) ?>">
                                <td>
                                    <div style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($flow->requested_organs ?: 'Organs Pending') ?></div>
                                    <div style="font-size: 0.8rem; color: #64748b;">
                                        <?= htmlspecialchars($flow->first_name . ' ' . $flow->last_name) ?> • Case #<?= htmlspecialchars($flow->case_number) ?>
                                    </div>
                                </td>
                                <td><code class="cp-nic-badge"><?= htmlspecialchars($flow->nic_number) ?></code></td>
                                <td>
                                    <?php if ($flow->final_exam_at): ?>
                                        <div class="cp-table__filename"><?= date('d/m/Y', strtotime($flow->final_exam_at)) ?></div>
                                        <div class="cp-table__subtext"><?= date('H:i', strtotime($flow->final_exam_at)) ?></div>
                                    <?php else: ?>
                                        <span style="color: #94a3b8; font-style: italic;">Awaiting Process</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php 
                                        $fs = strtoupper($flow->final_exam_status);
                                        $fsClass = $fs === 'ACCEPTED' ? 'success' : ($fs === 'AWAITING' ? 'pending' : 'danger');
                                    ?>
                                    <span class="cp-badge cp-badge--<?= $fsClass ?>">
                                        <?= htmlspecialchars($flow->final_exam_status) ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <button class="cp-btn cp-btn--secondary cp-btn--sm" onclick="openDeceasedFinalFlowDrawer(<?= $flow->cis_id ?>)">
                                        <i class="fas fa-stethoscope"></i> Final Assessment
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterDeceasedFinalFlow(status, btn) {
    const rows = document.querySelectorAll('.deceased-final-flow-row');
    const tabs = document.querySelectorAll('#deceased-final-flow .cp-filter-btn');
    
    tabs.forEach(t => t.classList.remove('active'));
    btn.classList.add('active');

    let visibleCount = 0;
    rows.forEach(row => {
        if (status === 'ALL' || row.dataset.status === status) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const emptyRow = document.querySelector('#deceased-final-flow-table .empty-row');
    if (emptyRow) {
        emptyRow.style.display = visibleCount === 0 ? '' : 'none';
    }
}

function openDeceasedFinalFlowDrawer(id) {
    const titleEl = document.getElementById('drawerTitle');
    const bodyEl  = document.getElementById('drawerBody');
    if (!titleEl || !bodyEl) return;

    titleEl.innerText = 'Final Retrieval Assessment & Verification';
    bodyEl.innerHTML  = '<div style="text-align:center; padding:2rem;"><i class="fas fa-circle-notch fa-spin fa-2x"></i></div>';
    
    if (window.CaseDrawer) window.CaseDrawer.open();
    else toggleDrawer('case-details-drawer');

    fetch('<?= ROOT ?>/hospital/deceased-final-flow/view?id=' + id)
        .then(r => r.text())
        .then(html => { bodyEl.innerHTML = html; })
        .catch(() => { bodyEl.innerHTML = '<div class="cp-alert cp-alert--danger">Failed to load assessment details.</div>'; });
}
</script>
