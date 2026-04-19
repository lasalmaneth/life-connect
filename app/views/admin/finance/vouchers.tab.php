<div id="vouchers" class="content-section" style="display: none;">
    <div class="content-header">
        <h2>Healthcare Voucher Management</h2>
        <p>Generate and manage healthcare vouchers to facilitate patient access to authorized medical services.</p>
    </div>

    <!-- Voucher Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div class="stat-card glass-card" style="padding: 1.5rem; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Active Vouchers</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #10b981;"><?= $voucher_stats['active'] ?? 0 ?></div>
        </div>
        <div class="stat-card glass-card" style="padding: 1.5rem; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Redemption Rate</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #6366f1;"><?= $voucher_stats['redemption_rate'] ?? 0 ?>%</div>
        </div>
        <div class="stat-card glass-card" style="padding: 1.5rem; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Total Disbursed</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">LKR <?= number_format($voucher_stats['total_disbursed'] ?? 0, 2) ?></div>
        </div>
        <div class="stat-card glass-card" style="padding: 1.5rem; border-radius: 20px; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.025em;">Expired</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #f43f5e;"><?= $voucher_stats['expired'] ?? 0 ?></div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem;">
        <div style="position: relative; flex: 1; max-width: 400px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            <input type="text" id="voucher-search" placeholder="Search by code or patient NIC..." 
                style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border-radius: 14px; border: 1px solid #e2e8f0; outline: none; font-size: 0.95rem;"
                onkeyup="filterVouchers()">
        </div>
    </div>

    <!-- Vouchers Table -->
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;" id="vouchers-table">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Voucher Details</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Beneficiary</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Amount</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Validity</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vouchers)): ?>
                    <tr>
                        <td colspan="5" style="padding: 3rem; text-align: center; color: #94a3b8;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;"><i class="fa-solid fa-ticket"></i></div>
                            <div>No vouchers have been issued yet.</div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($vouchers as $v): ?>
                        <tr class="voucher-row" data-search="<?= strtolower($v->voucher_code . ' ' . $v->patient_nic . ' ' . $v->patient_name) ?>" style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-family: monospace; font-weight: 700; color: #0f172a; font-size: 1rem;"><?= htmlspecialchars($v->voucher_code) ?></div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Case ID: #<?= $v->request_id ?></div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($v->patient_name) ?></div>
                                <div style="font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars($v->patient_nic) ?></div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 700; color: #1e293b;">LKR <?= number_format($v->amount, 2) ?></div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-size: 0.875rem; color: #475569;">Issued: <?= date('M d', strtotime($v->issued_date)) ?></div>
                                <div style="font-size: 0.875rem; color: #ef4444; font-weight: 600;">Exp: <?= date('M d, Y', strtotime($v->expiry_date)) ?></div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <?php 
                                    $vStatusColor = '#94a3b8'; $vStatusBg = '#f1f5f9';
                                    if ($v->status === 'ACTIVE') { $vStatusColor = '#10b981'; $vStatusBg = '#ecfdf5'; }
                                    elseif ($v->status === 'USED') { $vStatusColor = '#6366f1'; $vStatusBg = '#eef2ff'; }
                                    elseif ($v->status === 'EXPIRED') { $vStatusColor = '#f43f5e'; $vStatusBg = '#fff1f2'; }
                                ?>
                                <span style="padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; background: <?= $vStatusBg ?>; color: <?= $vStatusColor ?>; text-transform: uppercase;">
                                    <?= $v->status ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterVouchers() {
    const query = document.getElementById('voucher-search').value.toLowerCase();
    const rows = document.querySelectorAll('.voucher-row');
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(query) ? 'table-row' : 'none';
    });
}
</script>
