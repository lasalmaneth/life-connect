<div class="dr-content">
    <?php if (!$flow): ?>
        <div class="dr-alert-box dr-alert-box--danger">
            <div class="dr-alert-box__title"><i class="fas fa-exclamation-circle"></i> Error</div>
            <div class="dr-alert-box__main">Final flow record not found or inaccessible.</div>
        </div>
    <?php else: ?>
        <!-- Premium Header -->
        <div class="dr-header">
            <div class="dr-header__inner">
                <div class="dr-header__top">
                    <span class="dr-tag">Recovery Request: <?= htmlspecialchars($flow->requested_organs ?: 'Organs Pending') ?></span>
                    <span class="dr-badge dr-badge--success">
                        <?= htmlspecialchars($flow->final_exam_status) ?>
                    </span>
                </div>
                <h3>Case #<?= htmlspecialchars($flow->case_number) ?></h3>
                <p><?= htmlspecialchars($flow->first_name . ' ' . $flow->last_name) ?></p>
            </div>
        </div>

        <!-- Section: Status Header -->
        <div class="dr-section">
            <div class="dr-section-title">
                <span><i class="fas fa-check-double"></i> Retrieval Completion Status</span>
            </div>
            
            <div class="dr-card">
                <div class="dr-grid dr-grid--2">
                    <div class="dr-label-group">
                        <span class="dr-label">Donor Name</span>
                        <div class="dr-value--sub"><?= htmlspecialchars($flow->first_name . ' ' . $flow->last_name) ?></div>
                    </div>
                    <div class="dr-label-group">
                        <span class="dr-label">Examination Status</span>
                        <div>
                            <span class="dr-badge dr-badge--pending"><?= htmlspecialchars($flow->final_exam_status) ?></span>
                        </div>
                    </div>
                    <?php if ($flow->handover_date): ?>
                        <div class="dr-label-group">
                            <span class="dr-label">Handover Date</span>
                            <div class="dr-value--small"><?= date('M d, Y', strtotime($flow->handover_date)) ?></div>
                        </div>
                        <div class="dr-label-group">
                            <span class="dr-label">Handover Time</span>
                            <div class="dr-value--small"><?= htmlspecialchars($flow->handover_time) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($flow->handover_date): ?>
                    <div class="cp-detail-item">
                        <div class="cp-detail-label">Handover Date</div>
                        <div class="cp-detail-value"><?= date('d/m/Y', strtotime($flow->handover_date)) ?> at <?= htmlspecialchars($flow->handover_time) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($flow->final_exam_status === 'AWAITING'): ?>
            <div class="dr-banner dr-banner--info">
                <div class="dr-banner__content">
                    <div class="dr-banner__main">
                        <div class="dr-banner__title-sm">Final Institutional Confirmation</div>
                        <div class="dr-banner__msg">
                            Marking this case as <strong>Accepted</strong> signifies that the organ retrieval was successfully concluded and all institutional protocols were satisfied. 
                            A formal <strong>Donation Certificate</strong> will be issued to the family.
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <form method="POST" action="<?= ROOT ?>/hospital/deceased-final-flow/accept">
                    <input type="hidden" name="flow_id" value="<?= $flow->cis_id ?>">
                    <button type="submit" class="cp-btn cp-btn--primary dr-btn-full">
                        <i class="fas fa-check-double mr-2"></i> Confirm Successfully Retrieval & Close Case
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="dr-alert-box dr-alert-box--success">
                <div class="dr-alert-box__title">
                    <i class="fas fa-certificate"></i> Case Successfully Closed
                </div>
                <div class="dr-alert-box__main">
                    This retrieval case was successfully completed and formally closed by the institution.
                </div>
                <div class="dr-alert-box__meta">
                    Finalized on: <?= date('M d, Y', strtotime($flow->final_exam_at)) ?>
                </div>
            <div class="cp-alert cp-alert--success">
                <i class="fas fa-certificate cp-mr-2"></i> This case was successfully completed and closed on <?= date('d/m/Y', strtotime($flow->final_exam_at)) ?>.
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
