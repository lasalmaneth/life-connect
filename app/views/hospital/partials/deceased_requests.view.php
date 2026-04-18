<!-- Deceased Requests Registry (Stage C for Hospital) -->
<div id="deceased-requests" class="content-section" style="<?php echo (isset($initialSection) && $initialSection === 'deceased-requests') ? 'display:block' : 'display:none'; ?>">
    <div class="cp-content-header">
        <div class="cp-content-header__content">
            <h1 class="cp-content-header__title">
                <i class="fas fa-inbox"></i> Deceased Organ Requests
            </h1>
            <p class="cp-content-header__subtitle">
                Incoming organ procurement requests from the registry. Review and decide whether to initiate the procurement process.
            </p>
        </div>
        <div class="cp-content-header__actions">
            <span class="cp-badge cp-badge--info cp-badge--lg">
                <i class="fas fa-inbox cp-mr-2"></i> Retrieval Queue
            </span>
        </div>
    </div>

    <div class="cp-content-body">
        <!-- Premium Filter Bar -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
            <div class="cp-filter-tabs">
                <button onclick="filterDeceasedRequests('ALL', this)" class="cp-filter-btn active">All Requests</button>
                <button onclick="filterDeceasedRequests('PENDING', this)" class="cp-filter-btn">Pending Review</button>
                <button onclick="filterDeceasedRequests('ACCEPTED', this)" class="cp-filter-btn">Accepted</button>
                <button onclick="filterDeceasedRequests('REJECTED', this)" class="cp-filter-btn">Rejected</button>
            </div>
        </div>

        <div class="cp-table-container">
            <table class="cp-table" id="deceased-requests-table">
                <thead>
                    <tr>
                        <th>Organs</th>
                        <th>NIC Number</th>
                        <th>Date of Death</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($deceased_requests)): ?>
                        <tr class="empty-row">
                            <td colspan="5">
                                <div class="cp-empty-state">
                                    <i class="fas fa-envelope-open-text cp-empty-state__icon"></i>
                                    <div class="cp-empty-state__msg">No Active Retrieval Requests</div>
                                    <div class="cp-empty-state__sub">When custodians initiate organ donation outreach, they will appear here.</div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($deceased_requests as $request): ?>
                            <tr class="deceased-request-row" data-status="<?= htmlspecialchars($request->request_status) ?>">
                                <td>
                                    <div class="cp-table__icon-cell">
                                        <div class="cp-table__file-icon cp-table__file-icon--warning">
                                            <i class="fas fa-hand-holding-medical"></i>
                                        </div>
                                        <div>
                                            <div class="cp-table__filename">
                                                <?= htmlspecialchars($request->requested_organs ?: 'Organs Pending') ?>
                                            </div>
                                            <div class="cp-table__subtext">
                                                Case: <?= htmlspecialchars($request->case_number) ?> • <?= htmlspecialchars($request->first_name . ' ' . $request->last_name) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><code class="cp-nic-badge"><?= htmlspecialchars($request->nic_number) ?></code></td>
                                <td>
                                    <div class="cp-table__filename"><?= date('d M Y', strtotime($request->date_of_death)) ?></div>
                                    <div class="cp-table__subtext"><?= date('H:i', strtotime($request->date_of_death)) ?></div>
                                </td>
                                <td style="text-align: center;">
                                    <?php 
                                        $s = strtoupper($request->request_status);
                                        $statusClass = $s === 'PENDING' ? 'pending' : ($s === 'ACCEPTED' ? 'active' : 'danger');
                                    ?>
                                    <span class="cp-badge cp-badge--<?= $statusClass ?>">
                                        <?= htmlspecialchars($request->request_status) ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="cp-table__actions">
                                        <button class="cp-btn cp-btn--secondary cp-btn--sm"
                                                onclick="openDeceasedRequestDrawer(<?= $request->cis_id ?>)">
                                            <i class="fas fa-search"></i> Request Details
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
</div>

<script>
function filterDeceasedRequests(status, btn) {
    const rows = document.querySelectorAll('.deceased-request-row');
    const tabs = document.querySelectorAll('#deceased-requests .cp-filter-btn');
    
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

    const emptyRow = document.querySelector('#deceased-requests-table .empty-row');
    if (emptyRow) {
        emptyRow.style.display = visibleCount === 0 ? '' : 'none';
    }
}

function openDeceasedRequestDrawer(id) {
    const titleEl = document.getElementById('drawerTitle');
    const bodyEl  = document.getElementById('drawerBody');
    if (!titleEl || !bodyEl) { alert('Drawer components not found.'); return; }

    titleEl.innerText = 'Organ Submission Request Review';
    bodyEl.innerHTML  = '<div class="cp-loading-container" style="text-align:center; padding:2rem;"><i class="fas fa-circle-notch fa-spin" style="font-size:2rem; color:#3b82f6;"></i><p>Loading request details...</p></div>';

    if (window.CaseDrawer) window.CaseDrawer.open();
    else toggleDrawer('case-details-drawer'); // Fallback if custom helper not available

    fetch('<?= ROOT ?>/hospital/deceased-requests/view?id=' + id)
        .then(r => r.text())
        .then(html => { bodyEl.innerHTML = html; })
        .catch(() => { bodyEl.innerHTML = '<div class="cp-alert cp-alert--danger">Failed to load request. Please try again.</div>'; });
}
</script>
