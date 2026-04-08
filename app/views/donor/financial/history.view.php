<?php
/**
 * Donor Portal — Financial Donation History
 */
include __DIR__ . '/../inc/header.view.php';
include __DIR__ . '/../inc/sidebar.view.php';
?>

<main class="d-content">
    <div class="d-content__header">
        <h2><i class="fas fa-hand-holding-dollar text-accent"></i> Financial Donation History</h2>
        <p>A complete record of your financial contributions to LifeConnect.</p>
    </div>

    <div class="d-content__body">

        <!-- Summary Banner -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="d-widget" style="border-left: 4px solid var(--blue-500);">
                <div class="d-widget__body" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem;">
                    <div style="width: 48px; height: 48px; background: var(--blue-50); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: var(--blue-600); flex-shrink: 0;">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--g500); margin-bottom: 0.2rem;">Total Contributed</div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: var(--blue-700);">LKR <?= number_format($total_donated ?? 0, 2) ?></div>
                    </div>
                </div>
            </div>
            <div class="d-widget" style="border-left: 4px solid #10b981;">
                <div class="d-widget__body" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem;">
                    <div style="width: 48px; height: 48px; background: #ecfdf5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #10b981; flex-shrink: 0;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--g500); margin-bottom: 0.2rem;">Total Donations</div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: #059669;"><?= count($history) ?></div>
                    </div>
                </div>
            </div>
            <div class="d-widget" style="border-left: 4px solid #f59e0b;">
                <div class="d-widget__body" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem 1.5rem;">
                    <div style="width: 48px; height: 48px; background: #fffbeb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #f59e0b; flex-shrink: 0;">
                        <i class="fas fa-award"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--g500); margin-bottom: 0.2rem;">Donor Status</div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: #b45309;">Active</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="d-widget" style="overflow: hidden;">
            <div class="d-widget__header" style="display: flex; align-items: center; justify-content: space-between;">
                <div class="d-widget__title"><i class="fas fa-history"></i> Transaction History</div>
                <a href="<?= ROOT ?>/donor/financial-donate" class="d-btn d-btn--primary" style="font-size: 0.8rem; padding: 0.5rem 1rem;">
                    <i class="fas fa-plus"></i> Donate Again
                </a>
            </div>
            <div class="d-widget__body" style="padding: 0;">

                <?php if (!empty($history)): ?>
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background: var(--blue-50); border-bottom: 2px solid var(--blue-100);">
                        <tr>
                            <th style="padding: 1rem 1.5rem; font-size: 0.72rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase; letter-spacing: 0.06em;">Date</th>
                            <th style="padding: 1rem 1.5rem; font-size: 0.72rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase; letter-spacing: 0.06em;">Reference</th>
                            <th style="padding: 1rem 1.5rem; font-size: 0.72rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase; letter-spacing: 0.06em;">Amount</th>
                            <th style="padding: 1rem 1.5rem; font-size: 0.72rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase; letter-spacing: 0.06em;">Note</th>
                            <th style="padding: 1rem 1.5rem; font-size: 0.72rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase; letter-spacing: 0.06em;">Status</th>
                            <th style="padding: 1rem 1.5rem; font-size: 0.72rem; font-weight: 700; color: var(--blue-800); text-transform: uppercase; letter-spacing: 0.06em; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $row): ?>
                        <tr style="border-bottom: 1px solid var(--g200); transition: background 0.15s;" onmouseover="this.style.background='var(--g50)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1rem 1.5rem; font-size: 0.875rem; font-weight: 600; color: var(--slate);">
                                <?= date('M d, Y', strtotime($row->created_at)) ?>
                            </td>
                            <td style="padding: 1rem 1.5rem; font-size: 0.8rem; color: var(--g500); font-family: monospace;">
                                #<?= str_pad($row->id, 8, '0', STR_PAD_LEFT) ?>
                            </td>
                            <td style="padding: 1rem 1.5rem; font-weight: 700; color: var(--blue-700);">
                                LKR <?= number_format($row->amount, 2) ?>
                            </td>
                            <td style="padding: 1rem 1.5rem; font-size: 0.85rem; color: var(--g500); max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($row->note ?: '—') ?>
                            </td>
                            <td style="padding: 1rem 1.5rem;">
                                <?php if (($row->status ?? '') === 'SUCCESS'): ?>
                                    <span class="d-status d-status--success" style="font-size: 0.72rem; padding: 0.3rem 0.7rem; border-radius: 99px;">
                                        <i class="fas fa-check-circle"></i> Successful
                                    </span>
                                <?php else: ?>
                                    <span class="d-status d-status--neutral" style="font-size: 0.72rem; padding: 0.3rem 0.7rem; border-radius: 99px;">
                                        <?= htmlspecialchars($row->status ?? 'Pending') ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right;">
                                <button onclick='viewCertificate(<?= json_encode($row) ?>)' class="d-btn d-btn--outline" style="font-size: 0.75rem; padding: 0.35rem 0.8rem;">
                                    <i class="fas fa-certificate"></i> Certificate
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div style="padding: 4rem; text-align: center; color: var(--g400);">
                    <i class="fas fa-inbox" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.4;"></i>
                    <p style="font-size: 1rem; font-weight: 600; color: var(--g500); margin-bottom: 0.5rem;">No donations yet</p>
                    <p style="font-size: 0.85rem; margin-bottom: 1.5rem;">Make your first financial donation to support LifeConnect's mission.</p>
                    <a href="<?= ROOT ?>/donor/financial-donate" class="d-btn d-btn--primary">
                        <i class="fas fa-hand-holding-heart"></i> Donate Now
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>

<!-- Appreciation Certificate Modal -->
<div id="certificateModal" class="d-modal">
    <div class="d-modal__body" style="max-width: 820px; padding: 0; overflow: hidden;">
        <div id="certificateContent" style="padding: 4rem; text-align: center; background: #fffaf0;">
            <div style="border: 2px solid #d4a017; padding: 3rem; position: relative; background: #fff;">
                <div style="position: absolute; top: -10px; left: -10px; width: 20px; height: 20px; border: 2px solid #d4a017; background: #fff;"></div>
                <div style="position: absolute; top: -10px; right: -10px; width: 20px; height: 20px; border: 2px solid #d4a017; background: #fff;"></div>
                <div style="position: absolute; bottom: -10px; left: -10px; width: 20px; height: 20px; border: 2px solid #d4a017; background: #fff;"></div>
                <div style="position: absolute; bottom: -10px; right: -10px; width: 20px; height: 20px; border: 2px solid #d4a017; background: #fff;"></div>

                <div style="font-size: 2.8rem; color: #d4a017; margin-bottom: 0.5rem;"><i class="fas fa-award"></i></div>
                <h1 style="font-size: 2.2rem; color: var(--navy); margin-bottom: 0.5rem; letter-spacing: 0.02em;">Certificate of Appreciation</h1>
                <p style="font-size: 0.9rem; color: var(--g500); margin-bottom: 2rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 600;">This certificate is proudly presented to</p>

                <h2 style="font-size: 2rem; color: var(--blue-800); margin-bottom: 2rem; padding: 0 2rem 0.5rem; border-bottom: 2px solid #d4a017; font-style: italic; display: inline-block;">
                    <?= htmlspecialchars($donor_data['first_name'] ?? '') . ' ' . htmlspecialchars($donor_data['last_name'] ?? '') ?>
                </h2>

                <p style="max-width: 550px; margin: 0 auto 2.5rem; line-height: 1.9; color: var(--g700); font-size: 1rem;">
                    In recognition of your generous contribution of
                    <strong style="color: var(--blue-600);" id="certAmount">LKR 0.00</strong>
                    received on <strong id="certDate">—</strong>.<br>
                    Your monumental support directly empowers our mission of saving lives.
                </p>

                <div style="display: flex; justify-content: space-around; align-items: flex-end; margin-top: 2rem;">
                    <div style="text-align: center;">
                        <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height: 45px; opacity: 0.9;">
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.4rem; margin-bottom: 0.25rem; padding-bottom: 0.25rem; border-bottom: 1px solid var(--slate); color: var(--navy); font-style: italic;">Dr. Sarah Perera</div>
                        <div style="font-size: 0.7rem; color: var(--g500); text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700;">Director, LifeConnect</div>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding: 1.25rem 1.5rem; background: var(--g50); display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid var(--g200);">
            <button onclick="document.getElementById('certificateModal').classList.remove('active')" class="d-btn d-btn--outline">Close</button>
            <button onclick="printCertificate()" class="d-btn d-btn--primary"><i class="fas fa-print"></i> Print Certificate</button>
        </div>
    </div>
</div>

<script>
function viewCertificate(donation) {
    document.getElementById('certAmount').textContent = 'LKR ' + parseFloat(donation.amount).toLocaleString(undefined, {minimumFractionDigits: 2});
    document.getElementById('certDate').textContent = new Date(donation.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    document.getElementById('certificateModal').classList.add('active');
}
function printCertificate() {
    const c = document.getElementById('certificateContent').innerHTML;
    const w = window.open('', '_blank');
    w.document.write('<html><head><title>Certificate</title><style>@media print{@page{size:landscape;margin:0}body{padding:40px;background:white;-webkit-print-color-adjust:exact}}</style></head><body>' + c + '</body></html>');
    w.document.close();
    w.focus();
    w.print();
    w.close();
}
</script>

<?php include __DIR__ . '/../inc/footer.view.php'; ?>
