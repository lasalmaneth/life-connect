<div class="dr-content">
    <?php if (!$submission): ?>
        <div class="dr-alert-box dr-alert-box--danger">
            <div class="dr-alert-box__title"><i class="fas fa-exclamation-circle"></i> Error</div>
            <div class="dr-alert-box__main">Submission details not found or inaccessible.</div>
        </div>
    <?php else: ?>
        <!-- Premium Header -->
        <div class="dr-header">
            <div class="dr-header__inner">
                <div class="dr-header__top">
                    <span class="dr-tag">Recovery Request: <?= htmlspecialchars($submission->requested_organs ?: 'Organs Pending') ?></span>
                    <span class="dr-badge dr-badge--warning">
                        <?= htmlspecialchars($submission->document_status) ?>
                    </span>
                </div>
                <h3>Case #<?= htmlspecialchars($submission->case_number) ?></h3>
                <p><?= htmlspecialchars($submission->first_name . ' ' . $submission->last_name) ?></p>
            </div>
        </div>

        <!-- Section 1: Documents -->
        <div class="dr-section">
            <div class="dr-section-title">
                <span><i class="fas fa-file-invoice"></i> Mandatory Document Bundle</span>
                <span class="dr-count-badge"><?= count($documents) ?> Files</span>
            </div>
            
            <div class="dr-item-list">
                <?php if (empty($documents)): ?>
                    <div class="dr-empty-state">
                        <i class="fas fa-file-slash"></i>
                        <p class="dr-empty-state__title">No Documents Uploaded</p>
                        <p class="dr-empty-state__sub">Waiting for custodian submission.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($documents as $doc): ?>
                        <div class="dr-doc-card-slim">
                            <div class="dr-icon-box dr-icon-box--sm">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-1">
                                <div class="dr-doc-type"><?= htmlspecialchars($doc->document_type) ?></div>
                                <div class="dr-doc-meta">Uploaded: <?= date('M d, Y', strtotime($doc->uploaded_at)) ?></div>
                            </div>
                            <a href="<?= ROOT ?>/<?= htmlspecialchars($doc->file_path) ?>" target="_blank" class="cp-btn cp-btn--secondary dr-btn-xs" style="text-decoration: none;">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section 2: Handover Scheduling -->
        <?php if ($submission->document_status === 'PENDING_REVIEW'): ?>
            <div class="dr-section">
                <div class="dr-section-title">
                    <span><i class="fas fa-calendar-check"></i> Handover Scheduling</span>
                </div>
                
                <div class="dr-form-area">
                    <form method="POST" action="<?= ROOT ?>/hospital/deceased-submissions/accept">
                        <input type="hidden" name="submission_id" value="<?= $submission->cis_id ?>">
                        
                        <div class="dr-grid dr-grid--2 mb-4">
                            <div class="dr-label-group">
                                <label class="dr-slim-label">Intended Handover Date</label>
                                <input type="date" name="handover_date" class="dr-slim-input" required value="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="dr-label-group">
                                <label class="dr-slim-label">Arrival Time (Approx)</label>
                                <input type="time" name="handover_time" class="dr-slim-input" required value="<?= date('H:i') ?>">
                            </div>
                        </div>

                        <div class="dr-label-group mb-6">
                            <label class="dr-slim-label">Additional Instructions for Custodians</label>
                            <textarea name="handover_message" class="dr-slim-textarea" placeholder="e.g. Please bring original NICs and death certificate. Ensure transport vehicle is refrigeration-equipped..."></textarea>
                        </div>

                        <button type="submit" class="cp-btn cp-btn--primary dr-btn-full">
                            <i class="fas fa-check-circle mr-2"></i> Approve Documents & Finalize Schedule
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="dr-alert-box dr-alert-box--info">
                <div class="dr-alert-box__title"><i class="fas fa-info-circle"></i> Submission Status</div>
                <div class="dr-alert-box__main">
                    This document bundle is <strong><?= htmlspecialchars($submission->document_status) ?></strong>.
                </div>
                <div class="dr-alert-box__meta">
                    Once approved, the handover timeline will be displayed in the custodian portal.
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
