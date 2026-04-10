<?php
/**
 * Medical School Portal — Consent Registry (Stage A)
 * Route: GET /medical-school/consents
 */

$page_title  = 'Consent Registry';
$active_page = 'consents';

ob_start();

// Map consent_status to badge variant
function consentBadgeClass($status) {
    return match($status) {
        'GIVEN'     => 'active',
        'PENDING'   => 'pending',
        'WITHDRAWN' => 'danger',
        default     => 'neutral',
    };
}
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title">
            <i class="fas fa-file-signature"></i> Consent Registry
        </h1>
        <p class="cp-content-header__subtitle">
            Pre-death body donation intent records for your institution. You may verify consent documents and flag any records with issues.
        </p>
    </div>
    <div class="cp-content-header__actions">
        <span class="cp-badge cp-badge--info cp-badge--lg">
            <i class="fas fa-list"></i><?= count($donors) ?> Records
        </span>
    </div>
</div>

<div class="cp-content-body">
    <div class="cp-table-container">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Donor</th>
                    <th>NIC Number</th>
                    <th>Consent Date</th>
                    <th>Witnesses</th>
                    <th>Verification</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($donors)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="cp-empty-state">
                                <i class="fas fa-inbox cp-empty-state__icon"></i>
                                <div class="cp-empty-state__msg">No Consent Records Found</div>
                                <div class="cp-empty-state__sub">Once donors register body donation intent with your institution, they will appear here.</div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($donors as $donor): ?>
                        <tr>
                            <td>
                                <div class="cp-table__icon-cell">
                                    <div class="cp-table__file-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="cp-table__filename">
                                            <?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?>
                                        </div>
                                        <div class="cp-table__subtext">ID #<?= $donor->id ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($donor->nic_number) ?></td>
                            <td>
                                <div class="cp-table__filename"><?= date('d M Y', strtotime($donor->consent_date)) ?></div>
                                <div class="cp-table__subtext"><?= date('H:i', strtotime($donor->consent_date)) ?></div>
                            </td>
                            <td>
                                <div class="cp-table__filename"><?= htmlspecialchars($donor->witness1_name) ?></div>
                                <div class="cp-table__subtext"><?= htmlspecialchars($donor->witness2_name) ?></div>
                            </td>
                            <td>
                                <span class="cp-badge cp-badge--<?= $donor->verification_status === 'APPROVED' ? 'success' : 'warning' ?>">
                                    <?= htmlspecialchars($donor->verification_status) ?>
                                </span>
                            </td>
                            <td>
                                <span class="cp-badge cp-badge--<?= consentBadgeClass($donor->consent_status) ?>">
                                    <?= htmlspecialchars($donor->consent_status) ?>
                                </span>
                                <?php if (!empty($donor->flag_reason)): ?>
                                    <span class="cp-badge cp-badge--danger" title="<?= htmlspecialchars($donor->flag_reason) ?>">
                                        <i class="fas fa-flag"></i> FLAGGED
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="cp-table__actions">
                                    <button class="cp-btn cp-btn--secondary cp-btn--sm"
                                            onclick="openConsentDrawer(<?= $donor->id ?>)">
                                        <i class="fas fa-eye"></i> Details
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
function openConsentDrawer(id) {
    const titleEl = document.getElementById('drawerTitle');
    const bodyEl  = document.getElementById('drawerBody');
    if (!titleEl || !bodyEl) { alert('Drawer not initialised.'); return; }

    titleEl.innerText = 'Consent Record Details';
    bodyEl.innerHTML  = '<div style="padding:2rem; text-align:center;"><i class="fas fa-circle-notch fa-spin" style="font-size:1.5rem; color:var(--blue-400);"></i></div>';

    if (window.CaseDrawer) window.CaseDrawer.open();

    fetch('<?= ROOT ?>/medical-school/consents/view?id=' + id)
        .then(r => r.text())
        .then(html => { bodyEl.innerHTML = html; })
        .catch(() => { bodyEl.innerHTML = '<div class="cp-alert cp-alert--danger">Failed to load. Please try again.</div>'; });
}
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
