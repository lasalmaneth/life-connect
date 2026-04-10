<?php
/**
 * Co-Custodian Approvals Tracker Partial
 */
if (empty($custodianApprovals)) return;
?>
<div class="cp-section-card mb-4 mt-5">
    <div class="cp-section-card__header">
        <div class="cp-section-card__title"><i class="fas fa-users"></i> Other Custodian Approvals</div>
    </div>
    <div class="cp-section-card__body p-0">
        <table class="cp-table w-100 text-left">
            <thead>
                <tr class="cp-bg-g50 cp-border-bottom">
                    <th class="p-3">Custodian</th>
                    <th class="p-3">Approval Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($custodianApprovals as $ca): ?>
                <tr class="cp-border-bottom">
                    <td class="p-4 cp-font-semibold"><?= htmlspecialchars($ca->custodian_name) ?></td>
                    <td class="p-4"><span class="badge <?= $ca->status === 'APPROVED' ? 'ba' : 'bw' ?>"><?= htmlspecialchars($ca->status) ?></span></td>
                    <td class="p-4">
                        <?php if ($ca->approval_document_path): ?>
                            <button class="cp-btn cp-btn--sm cp-btn--primary">View</button>
                        <?php else: ?>
                            <span class="cp-text-danger cp-text-sm"><i class="fas fa-upload"></i> Upload Needed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
