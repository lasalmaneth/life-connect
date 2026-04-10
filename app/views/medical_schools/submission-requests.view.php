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
            Initial custodian outreach requests after donor death. Review and decide whether to initiate the body intake process.
        </p>
    </div>
    <div class="cp-content-header__actions">
        <span class="cp-badge cp-badge--info cp-badge--lg">
            <i class="fas fa-list"></i> <?= count($requests) ?> <?= ($current_filter ?? 'PENDING') === 'PENDING' ? 'Pending Review' : 'Records Found' ?>
        </span>
    </div>
</div>

<div class="cp-content-body">
    <div style="margin-bottom: 1.5rem; display: flex; justify-content: flex-end; align-items: center; gap: 0.75rem; background: var(--white); padding: 10px 15px; border-radius: 8px; border: 1px solid var(--g200); box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
        <i class="fas fa-filter" style="color: var(--g400);"></i>
        <label for="statusFilter" style="font-weight: 600; color: var(--g800); font-size: 0.875rem; margin: 0;">Show Requests:</label>
        <select id="statusFilter" class="cp-form-control" style="width: auto; min-width: 180px; padding-top: 6px; padding-bottom: 6px; border-color: var(--g300);" onchange="window.location.href='<?= ROOT ?>/medical-school/submission-requests?status=' + this.value;">
            <option value="PENDING" <?= ($current_filter ?? 'PENDING') === 'PENDING' ? 'selected' : '' ?>>Pending / Under Review</option>
            <option value="ACCEPTED" <?= ($current_filter ?? 'PENDING') === 'ACCEPTED' ? 'selected' : '' ?>>Accepted</option>
            <option value="REJECTED" <?= ($current_filter ?? 'PENDING') === 'REJECTED' ? 'selected' : '' ?>>Rejected</option>
            <option value="ALL" <?= ($current_filter ?? 'PENDING') === 'ALL' ? 'selected' : '' ?>>All Requests</option>
        </select>
    </div>

    <div class="cp-table-container">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Donor & Case</th>
                    <th>NIC Number</th>
                    <th>Date of Death</th>
                    <th>Submission Age</th>
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
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($request->nic_number) ?></td>
                            <td>
                                <div class="cp-table__filename"><?= date('d M Y', strtotime($request->date_of_death)) ?></div>
                                <div class="cp-table__subtext"><?= date('H:i', strtotime($request->date_of_death)) ?></div>
                            </td>
                            <td>
                                <?php 
                                    $submitted = strtotime($request->request_at);
                                    $diff = time() - $submitted;
                                    $hours = floor($diff / 3600);
                                    $mins = floor(($diff % 3600) / 60);
                                ?>
                                <div class="cp-table__filename"><?= $hours ?>h <?= $mins ?>m ago</div>
                                <div class="cp-table__subtext">Submitted on <?= date('M d', $submitted) ?></div>
                            </td>
                            <td style="text-align: center;">
                                <span class="cp-badge cp-badge--<?= strtolower($request->request_status) === 'pending' ? 'pending' : 'info' ?>">
                                    <?= htmlspecialchars($request->request_status) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="cp-table__actions">
                                    <button class="cp-btn cp-btn--primary cp-btn--sm"
                                            onclick="openRequestDrawer(<?= $request->cis_id ?>)">
                                        <i class="fas fa-search"></i> Review Request
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

