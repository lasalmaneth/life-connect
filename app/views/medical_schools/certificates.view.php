<?php
/**
 * Medical School Portal — Donation Certificates
 */

$page_title    = 'Donation Certificates';
$active_page   = 'certificates';

ob_start();
?>

<div class="cp-content-header">
    <div class="cp-content-header__content">
        <h1 class="cp-content-header__title"><i class="fas fa-certificate"></i> Donation Certificates</h1>
        <p class="cp-content-header__subtitle">Official recognition records for anatomical donors and their families.</p>
    </div>
</div>

<div class="cp-content-body">
    <div class="cp-card">
        <div class="table-responsive">
            <table class="cp-table">
                <thead>
                    <tr>
                        <th>Donor Name</th>
                        <th>Certificate Number</th>
                        <th>Issuance Date</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($certificates)): ?>
                        <tr>
                            <td colspan="5" style="padding: 4rem 2rem; text-align: center;">
                                <div style="color: #e2e8f0; font-size: 3rem; margin-bottom: 1rem;"><i class="fas fa-certificate"></i></div>
                                <h4 style="color: var(--g600); margin-bottom: 0.5rem;">No Certificates Issued</h4>
                                <p style="color: var(--g400); font-size: 0.875rem;">Certificates are automatically generated after final anatomical pass.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($certificates as $cert): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--slate);"><?= htmlspecialchars($cert->first_name . ' ' . $cert->last_name) ?></div>
                                    <div style="font-size: 0.75rem; color: var(--g500);">Verified on <?= date('M d, Y', strtotime($cert->final_exam_at)) ?></div>
                                </td>
                                <td>
                                    <code style="background: var(--blue-50); color: var(--blue-700); padding: 4px 8px; border-radius: 6px; font-weight: 700;">
                                        <?= htmlspecialchars($cert->certificate_number) ?>
                                    </code>
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem; color: var(--g600); font-weight: 600;">
                                        <?= date('M d, Y', strtotime($cert->issued_at)) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="cp-status-badge cp-status-badge--success">
                                        <i class="fas fa-check-circle mr-1"></i> Issued & Digital
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="<?= ROOT ?>/medical-school/view-certificate?id=<?= $cert->id ?>" class="cp-btn cp-btn--secondary" style="padding: 6px 14px; font-size: 0.75rem; border-radius: 8px;">
                                        <i class="fas fa-external-link-alt mr-2"></i> View Certificate
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/medical_schools.layout.php';
?>
