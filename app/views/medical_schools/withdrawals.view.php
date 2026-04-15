<?php
/**
 * Medical School Portal — Withdrawal Notices (Stage B)
 * Route: GET /medical-school/withdrawals
 * Read-only. No actions permitted — withdrawal is a legal record.
 */

$page_title  = 'Withdrawal Notices';
$active_page = 'withdrawals';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title">
            <i class="fas fa-user-times"></i> Withdrawal Notices
        </h1>
        <p class="cp-content-header__subtitle">
            Legal archive of donors who formally rescinded their body donation intent.
        </p>
    </div>
    <div class="cp-content-header__actions">
        <span class="cp-badge cp-badge--danger cp-badge--lg">
            <i class="fas fa-lock cp-mr-2"></i> Read-Only Legal Archive
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
                    <th>Original Consent</th>
                    <th>Withdrawal Date</th>
                    <th>Reason Summary</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($donors)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="cp-empty-state">
                                <i class="fas fa-history cp-empty-state__icon"></i>
                                <div class="cp-empty-state__msg">No Withdrawal Notices</div>
                                <div class="cp-empty-state__sub">No donors have rescinded their donation intent with your institution.</div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($donors as $donor): ?>
                        <tr>
                            <td>
                                <div class="cp-table__icon-cell">
                                    <div class="cp-table__file-icon">
                                        <i class="fas fa-user-slash"></i>
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
                                <div class="cp-table__subtext">Original Consent</div>
                            </td>
                            <td>
                                <?php if (!empty($donor->withdrawal_date)): ?>
                                    <div class="cp-table__filename"><?= date('d M Y', strtotime($donor->withdrawal_date)) ?></div>
                                    <div class="cp-table__subtext"><?= date('H:i', strtotime($donor->withdrawal_date)) ?></div>
                                <?php else: ?>
                                    <span class="cp-badge cp-badge--neutral">Date not recorded</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $reason = $donor->withdrawal_reason ?? $donor->opt_out_reason ?? null;
                                if ($reason):
                                    $short = mb_strlen($reason) > 55 ? mb_substr($reason, 0, 55) . '…' : $reason;
                                ?>
                                    <div class="cp-table__filename"><?= htmlspecialchars($short) ?></div>
                                    <div class="cp-table__subtext">View full notice for details</div>
                                <?php else: ?>
                                    <span class="cp-badge cp-badge--neutral">No reason recorded</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="cp-table__actions">
                                    <button class="cp-btn cp-btn--secondary cp-btn--sm"
                                            onclick="openWithdrawalDrawer(<?= $donor->id ?>)">
                                        <i class="fas fa-file-alt"></i> View Notice
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
function openWithdrawalDrawer(id) {
    const titleEl = document.getElementById('drawerTitle');
    const bodyEl  = document.getElementById('drawerBody');
    if (!titleEl || !bodyEl) { alert('Drawer not initialised.'); return; }

    titleEl.innerText = 'Withdrawal Notice — Legal Archive';
    bodyEl.innerHTML  = '<div style="padding:2rem; text-align:center;"><i class="fas fa-circle-notch fa-spin" style="font-size:1.5rem; color:var(--blue-400);"></i></div>';

    if (window.CaseDrawer) window.CaseDrawer.open();

    fetch('<?= ROOT ?>/medical-school/withdrawals/view?id=' + id)
        .then(r => r.text())
        .then(html => { bodyEl.innerHTML = html; })
        .catch(() => { bodyEl.innerHTML = '<div class="cp-alert cp-alert--danger">Failed to load. Please try again.</div>'; });
}
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
