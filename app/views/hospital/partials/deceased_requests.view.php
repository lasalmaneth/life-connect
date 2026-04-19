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
        <div class="cp-content-header__actions" style="position: relative;">
            <?php 
                $retrievalQueue = array_filter($deceased_requests ?? [], function($r) {
                    return strtoupper(trim((string)$r->request_status)) === 'ACCEPTED';
                });
            ?>
            <button class="cp-badge cp-badge--info cp-badge--lg" onclick="document.getElementById('retrievalQueueDropdown').classList.toggle('cp-d-none')" style="cursor: pointer; border: none; font-family: inherit; position: relative;">
                <i class="fas fa-truck-medical cp-mr-2"></i> Retrieval Queue
                <?php if (count($retrievalQueue) > 0): ?>
                    <span style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border-radius: 20px; padding: 2px 8px; font-size: 0.7rem; font-weight: 800; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        <?= count($retrievalQueue) ?>
                    </span>
                <?php endif; ?>
            </button>

            <!-- Displaced Logic Dropdown for Accepted Cases -->
            <div id="retrievalQueueDropdown" class="cp-d-none" style="position: absolute; right: 0; top: 100%; margin-top: 15px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 340px; z-index: 1050; padding: 12px; text-align: left;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 10px;">
                    <h4 style="margin: 0; font-size: 0.95rem; color: #0f172a; font-weight: 700;">
                        <i class="fas fa-clipboard-check text-accent" style="margin-right: 6px;"></i> Accepted Queue
                    </h4>
                    <span style="font-size: 0.7rem; background: #dbeafe; color: #1e40af; padding: 3px 8px; border-radius: 20px; font-weight: 700;">ACTIVE</span>
                </div>
                
                <?php if (empty($retrievalQueue)): ?>
                    <div style="font-size: 0.85rem; color: #94a3b8; text-align: center; padding: 20px 0; font-style: italic;">
                        <i class="fas fa-box-open" style="font-size: 1.5rem; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                        No accepted cases in the queue yet.
                    </div>
                <?php else: ?>
                    <div style="max-height: 320px; overflow-y: auto; padding-right: 5px;">
                        <?php foreach($retrievalQueue as $acc): ?>
                            <div style="padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#f8fafc';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='white';" onclick="openDeceasedRequestDrawer(<?= $acc->cis_id ?>)">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                                    <div style="font-weight: 800; font-size: 0.85rem; color: #1e293b;">Case <?= htmlspecialchars($acc->case_number) ?></div>
                                    <div style="font-size: 0.7rem; color: #fff; background: #10b981; padding: 2px 6px; border-radius: 4px; font-weight: 700;">ACCEPTED</div>
                                </div>
                                <div style="font-size: 0.8rem; color: #475569; margin-bottom: 6px; font-weight: 600;">
                                    <i class="fas fa-user cp-mr-2" style="color: #94a3b8;"></i> <?= htmlspecialchars($acc->first_name . ' ' . $acc->last_name) ?>
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b; display: flex; justify-content: space-between; align-items: center; background: #f1f5f9; padding: 6px; border-radius: 6px;">
                                    <span>NIC: <?= htmlspecialchars($acc->nic_number) ?></span>
                                    <span style="color: #2563eb; font-weight: 600;"><i class="fas fa-lungs cp-mr-2"></i><?= htmlspecialchars($acc->requested_organs ?: 'Organs') ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
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
                                    <div class="cp-table__filename"><?= date('d/m/Y', strtotime($request->date_of_death)) ?></div>
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
