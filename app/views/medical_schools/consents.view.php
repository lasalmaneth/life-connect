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
            <i class="fas fa-list cp-mr-2"></i> Institutional Registry
        </span>
    </div>
</div>

<div class="cp-content-body">
    <!-- Premium Filter Bar -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
        <div class="cp-filter-tabs">
            <?php 
            $statuses = [
                'ALL' => 'All Records',
                'GIVEN' => 'Active',
                'WITHDRAWN' => 'Withdrawn'
            ];
            foreach ($statuses as $val => $lbl): 
                $active = ($active_status === $val) ? 'active' : '';
            ?>
                <a href="?status=<?= $val ?>" class="cp-filter-btn <?= $active ?>"><?= $lbl ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="cp-table-container">
        <table class="cp-table">
            <thead>
                <tr>
                    <th>Donor</th>
                    <th>NIC Number</th>
                    <th>Consent Date</th>
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
                                <div class="cp-empty-state__msg">No records match this filter</div>
                                <div class="cp-empty-state__sub">Try switching to "All Records" to see the full list of donors assigned to your institution.</div>
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
                            <td><code class="cp-nic-badge"><?= htmlspecialchars($donor->nic_number) ?></code></td>
                            <td>
                                <?php if ($donor->consent_date): ?>
                                    <div class="cp-table__filename"><?= date('d M Y', strtotime($donor->consent_date)) ?></div>
                                    <div class="cp-table__subtext"><?= date('H:i', strtotime($donor->consent_date)) ?></div>
                                <?php else: ?>
                                    <span class="cp-text-g400">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="cp-badge cp-badge--<?= consentBadgeClass($donor->consent_status) ?>">
                                    <?= htmlspecialchars($donor->consent_status) ?>
                                </span>
                                <?php if ($donor->consent_status === 'WITHDRAWN'): ?>
                                    <div class="cp-table__subtext text-danger mt-1">
                                        <i class="fas fa-calendar-xmark"></i> <?= date('d M Y', strtotime($donor->withdrawal_date)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="cp-table__actions">
                                    <button class="cp-btn cp-btn--secondary cp-btn--sm"
                                            onclick="openConsentDrawer(<?= $donor->id ?>)">
                                        <i class="fas fa-eye"></i> Consent Details
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
