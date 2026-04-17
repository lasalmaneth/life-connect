<!-- Deceased Final Flow Detail View (AJAX Body) -->
<div class="cp-drawer-content">
    <?php if (!$flow): ?>
        <div class="cp-alert cp-alert--danger">Final flow record not found.</div>
    <?php else: ?>
        <div class="cp-detail-group">
            <h3 class="cp-detail-group__title">Retrieval Completion Status</h3>
            <div class="cp-detail-grid">
                <div class="cp-detail-item">
                    <div class="cp-detail-label">Donor Name</div>
                    <div class="cp-detail-value"><?= htmlspecialchars($flow->first_name . ' ' . $flow->last_name) ?></div>
                </div>
                <div class="cp-detail-item">
                    <div class="cp-detail-label">Status</div>
                    <div class="cp-detail-value">
                        <span class="cp-badge cp-badge--info"><?= htmlspecialchars($flow->final_exam_status) ?></span>
                    </div>
                </div>
                <?php if ($flow->handover_date): ?>
                    <div class="cp-detail-item">
                        <div class="cp-detail-label">Handover Date</div>
                        <div class="cp-detail-value"><?= date('M d, Y', strtotime($flow->handover_date)) ?> at <?= htmlspecialchars($flow->handover_time) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($flow->final_exam_status === 'AWAITING'): ?>
            <div class="cp-detail-group" style="margin-top: 2rem; border-top: 2px solid #f1f5f9; padding-top: 2rem;">
                <h3 class="cp-detail-group__title">Confirm Successful Retrieval</h3>
                <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 2rem;">
                    Marking this case as **Accepted** signifies that the organ retrieval was successful, all protocols were followed, and the institution assumes full responsibility. A formal Donation Certificate will be generated.
                </p>
                
                <form method="POST" action="<?= ROOT ?>/hospital/deceased-final-flow/accept">
                    <input type="hidden" name="flow_id" value="<?= $flow->cis_id ?>">
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="cp-btn cp-btn--primary" style="flex: 1; padding: 1rem;">
                            <i class="fas fa-check-double cp-mr-2"></i> Confirm & Successfully Close Case
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="cp-alert cp-alert--success">
                <i class="fas fa-certificate cp-mr-2"></i> This case was successfully completed and closed on <?= date('M d, Y', strtotime($flow->final_exam_at)) ?>.
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
