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
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <?php if (!empty($history)): ?>
                        <?php 
                            // Find the first successful donation for the header certificate button
                            $lastSuccessful = null;
                            foreach($history as $h) { if($h->status === 'SUCCESS') { $lastSuccessful = $h; break; } }
                        ?>
                        <?php if ($lastSuccessful): ?>
                            <button type="button" onclick="viewCertificate('<?= ROOT ?>/donor/download-pdf?type=total_financial_certificate')" class="d-btn d-btn--outline" style="font-size: 0.8rem; padding: 0.5rem 1rem;">
                                <i class="fas fa-certificate"></i> Download Certificate
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="<?= ROOT ?>/donor/financial-donate" class="d-btn d-btn--primary" style="font-size: 0.8rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-plus"></i> Donate Again
                    </a>
                </div>
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
                                <?= date('d/m/Y', strtotime($row->created_at)) ?>
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
                                <?php if (($row->status ?? '') === 'SUCCESS'): ?>
                                    <button onclick="viewCertificate('<?= ROOT ?>/donor/download-pdf?type=financial_certificate&id=<?= $row->id ?>')" class="d-btn d-btn--outline d-btn--sm">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                <?php else: ?>
                                    <span style="color: var(--g400); font-size: 0.75rem;">N/A</span>
                                <?php endif; ?>
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

<!-- Certificate Viewer Modal -->
<div id="certificateModal" class="d-modal">
    <div class="d-modal__body" style="max-width: 95%; width: 1150px; height: 95vh; padding: 0; display: flex; flex-direction: column; overflow: hidden; border: none;">
        <div class="d-modal__header" style="background: var(--blue-900); color: white; padding: 1.25rem 2rem; margin-bottom: 0; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #cbd5e0;">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h3 style="color: white; margin: 0; font-size: 1.1rem; font-weight: 700;">Certificate of Appreciation</h3>
                    <div style="font-size: 0.75rem; color: var(--blue-200);">Financial Donation Recognition</div>
                </div>
            </div>
            <button class="d-modal__close" onclick="closeModal('certificateModal')" style="color: white; opacity: 0.8; font-size: 1.5rem; background: none; border: none; cursor: pointer;">&times;</button>
        </div>
        <div style="flex: 1; overflow: hidden; background: #525659; position: relative;">
            <iframe id="certificateFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
        <div style="padding: 1rem 2rem; background: var(--g50); border-top: 1px solid var(--g200); display: flex; justify-content: flex-end; gap: 12px;">
            <button class="d-btn d-btn--outline d-btn--sm" onclick="closeModal('certificateModal')">Close Preview</button>
            <button class="d-btn d-btn--primary d-btn--sm" onclick="document.getElementById('certificateFrame').contentWindow.print()">
                <i class="fas fa-print"></i> Print Certificate
            </button>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = 'auto';
        document.getElementById('certificateFrame').src = '';
    }
    function viewCertificate(url) {
        document.getElementById('certificateFrame').src = url;
        openModal('certificateModal');
    }
    window.onclick = function(event) {
        if (event.target.classList.contains('d-modal')) {
            closeModal(event.target.id);
        }
    }
</script>

<?php include __DIR__ . '/../inc/footer.view.php'; ?>
