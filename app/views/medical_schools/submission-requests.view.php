<?php
/**
 * Medical School Portal — Submission Requests (Stage C)
 * Route: GET /medical-school/submission-requests
 */

$page_title  = 'Submission Requests';
$active_page = 'submission-requests';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title">
            <i class="fas fa-inbox"></i> Submission Requests
        </h1>
        <p class="cp-content-header__subtitle">
            Initial custodian outreach requests after donor death. Review and decide whether to initiate body intake.
        </p>
    </div>
    <div class="cp-content-header__actions">
        <span class="cp-badge cp-badge--info cp-badge--lg">
            <i class="fas fa-inbox cp-mr-2"></i> Case Intake Queue
        </span>
    </div>
</div>

<div class="cp-content-body">
    <!-- Premium Filter Bar -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
        <div class="cp-filter-tabs">
            <a href="<?= ROOT ?>/medical-school/submission-requests?status=ALL" 
               class="cp-filter-btn <?= $active_status === 'ALL' ? 'active' : '' ?>">All Requests</a>
            <a href="<?= ROOT ?>/medical-school/submission-requests?status=PENDING" 
               class="cp-filter-btn <?= $active_status === 'PENDING' ? 'active' : '' ?>">Pending Review</a>
            <a href="<?= ROOT ?>/medical-school/submission-requests?status=ACCEPTED" 
               class="cp-filter-btn <?= $active_status === 'ACCEPTED' ? 'active' : '' ?>">Accepted</a>
            <a href="<?= ROOT ?>/medical-school/submission-requests?status=REJECTED" 
               class="cp-filter-btn <?= $active_status === 'REJECTED' ? 'active' : '' ?>">Rejected</a>
        </div>
    </div>

    <div class="cp-table-container">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Donor & Case</th>
                    <th>NIC Number</th>
                    <th>Date of Death</th>
                    <th>Submitted Date</th>
                    <th>Clinical Deadline</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="cp-empty-state">
                                <i class="fas fa-envelope-open-text cp-empty-state__icon"></i>
                                <div class="cp-empty-state__msg">No Active Submission Requests</div>
                                <div class="cp-empty-state__sub">When custodians initiate body donation outreach for a deceased donor, they will appear here.</div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td>
                                <div class="cp-table__icon-cell">
                                    <div class="cp-table__file-icon cp-table__file-icon--warning">
                                        <i class="fas fa-user-clock"></i>
                                    </div>
                                    <div>
                                        <div class="cp-table__filename">
                                            <?= htmlspecialchars($request->first_name . ' ' . $request->last_name) ?>
                                        </div>
                                        <div class="cp-table__subtext"><?= htmlspecialchars($request->case_number) ?></div>
                                        <?php if ($request->resolved_operational_track === 'BODY_CORNEA_SPLIT'): ?>
                                            <span class="cp-badge-mini cp-bg-amber-100 cp-text-amber-700 mt-1" style="font-size: 0.65rem;">
                                                <i class="fas fa-eye"></i> Cornea Retrieval Priority
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><code class="cp-nic-badge"><?= htmlspecialchars($request->nic_number) ?></code></td>
                            <td>
                                <div class="cp-table__filename"><?= date('d M Y', strtotime($request->date_of_death)) ?></div>
                                <div class="cp-table__subtext"><?= date('H:i', strtotime($request->date_of_death)) ?></div>
                            </td>
                            <td>
                                <div class="cp-table__filename"><?= date('d M Y', strtotime($request->request_at)) ?></div>
                                <div class="cp-table__subtext"><?= date('H:i', strtotime($request->request_at)) ?></div>
                            </td>
                            <td>
                                <?php if (isset($request->clinical_deadline)): ?>
                                    <div class="countdown" 
                                         data-expire="<?= htmlspecialchars($request->clinical_deadline['deadline']) ?>"
                                         style="font-weight: 700; font-family: monospace; font-size: 0.9rem;">
                                        Calculating...
                                    </div>
                                    <div class="cp-table__subtext">
                                        Limit: <?= date('d M, H:i', strtotime($request->clinical_deadline['deadline'])) ?>
                                    </div>
                                <?php else: ?>
                                    <span class="cp-text-gray-400">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="cp-badge cp-badge--<?= strtolower($request->request_status) === 'pending' ? 'pending' : 'active' ?>">
                                    <?= htmlspecialchars($request->request_status) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="cp-table__actions">
                                    <button class="cp-btn cp-btn--secondary cp-btn--sm"
                                             onclick="openRequestDrawer(<?= $request->cis_id ?>)">
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

<script>
function updateCountdowns() {
    const elements = document.querySelectorAll('.countdown');
    elements.forEach(el => {
        const expireTs = new Date(el.dataset.expire).getTime();
        const now = new Date().getTime();
        const dist = expireTs - now;
        
        if (dist < 0) {
            el.innerText = "WINDOW CLOSED";
            el.style.color = "var(--red-600)";
            return;
        }
        
        const h = Math.floor(dist / (1000 * 60 * 60));
        const m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
        el.innerText = h + "h " + m + "m left";
        if (h < 12) el.style.color = "var(--amber-600)";
        if (h < 4) el.style.color = "var(--red-600)";
    });
}
setInterval(updateCountdowns, 60000);
updateCountdowns();

function openRequestDrawer(id) {
    const titleEl = document.getElementById('drawerTitle');
    const bodyEl  = document.getElementById('drawerBody');
    if (!titleEl || !bodyEl) { alert('Drawer components not found.'); return; }

    titleEl.innerText = 'Body Submission Request Review';
    bodyEl.innerHTML  = '<div class="cp-loading-container"><i class="fas fa-circle-notch fa-spin"></i> Loading request details...</div>';

    if (window.CaseDrawer) window.CaseDrawer.open();

    fetch('<?= ROOT ?>/medical-school/submission-requests/view?id=' + id)
        .then(r => r.text())
        .then(html => { bodyEl.innerHTML = html; })
        .catch(() => { bodyEl.innerHTML = '<div class="cp-alert cp-alert--danger">Failed to load request. Please try again.</div>'; });
}
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>

