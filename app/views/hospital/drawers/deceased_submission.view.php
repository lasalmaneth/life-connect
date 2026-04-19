<!-- Deceased Submission Detail View (AJAX Body) -->
<div class="cp-drawer-content">
    <?php if (!$submission): ?>
        <div class="cp-alert cp-alert--danger">Submission details not found.</div>
    <?php else: ?>
        <div class="cp-detail-group">
            <h3 class="cp-detail-group__title">Mandatory Document Bundle</h3>
            <div class="cp-document-list">
                <?php if (empty($documents)): ?>
                    <div class="cp-alert cp-alert--warning">No documents uploaded yet.</div>
                <?php else: ?>
                    <?php foreach ($documents as $doc): ?>
                        <div class="cp-document-item" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8fafc; border-radius: 8px; margin-bottom: 0.5rem; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 1.5rem;"></i>
                                <div>
                                    <div style="font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($doc->document_type) ?></div>
                                    <div style="font-size: 0.75rem; color: #64748b;">Uploaded on <?= date('d/m/Y', strtotime($doc->uploaded_at)) ?></div>
                                </div>
                            </div>
                            <a href="<?= ROOT ?>/<?= htmlspecialchars($doc->file_path) ?>" target="_blank" class="cp-btn cp-btn--secondary cp-btn--sm">
                                <i class="fas fa-external-link-alt cp-mr-2"></i> View
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($submission->document_status === 'PENDING_REVIEW'): ?>
            <div class="cp-detail-group" style="margin-top: 2rem;">
                <h3 class="cp-detail-group__title">Handover Scheduling</h3>
                <form method="POST" action="<?= ROOT ?>/hospital/deceased-submissions/accept">
                    <input type="hidden" name="submission_id" value="<?= $submission->cis_id ?>">
                    <div class="cp-form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label class="cp-label">Intended Handover Date</label>
                            <input type="date" name="handover_date" class="cp-input" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div>
                            <label class="cp-label">Time (Approx)</label>
                            <input type="time" name="handover_time" class="cp-input" required value="<?= date('H:i') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="cp-label">Additional Instructions for Custodians</label>
                        <textarea name="handover_message" class="cp-input" rows="2" placeholder="e.g. Please bring original NICs and death certificate..."></textarea>
                    </div>
                    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button type="submit" class="cp-btn cp-btn--primary" style="flex: 2;">
                            <i class="fas fa-check-circle cp-mr-2"></i> Approve Documents & Schedule
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="cp-alert cp-alert--info">
                Document status: <strong><?= htmlspecialchars($submission->document_status) ?></strong>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
