<?php if (!$decline): ?>
    <div class="cp-alert cp-alert--danger">Notice record not found.</div>
<?php else: ?>
    <div class="case-detail-section">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <span class="cp-status-badge cp-status-badge--danger" style="text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Custodian Declined</span>
            <div style="font-size: 0.8125rem; color: var(--g500);">Archive ID: #<?= htmlspecialchars($decline->cis_id) ?></div>
        </div>

        <h4 style="margin-bottom: 1rem; color: var(--g800); border-bottom: 1px solid var(--g200); padding-bottom: 0.5rem;">
            Donor Identification
        </h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            <div>
                <div style="font-size: 0.75rem; color: var(--g500);">Full Name</div>
                <div style="font-weight: 500;"><?= htmlspecialchars($decline->first_name . ' ' . $decline->last_name) ?></div>
            </div>
            <div>
                <div style="font-size: 0.75rem; color: var(--g500);">Date of Death</div>
                <div style="font-weight: 500; font-family: monospace; color: var(--danger-600);"><?= $decline->custodian_decline_date ? date('M d, Y', strtotime($decline->custodian_decline_date)) : 'N/A' ?></div>
            </div>
        </div>
    </div>

    <div class="case-detail-section" style="margin-top: 2rem;">
        <h4 style="margin-bottom: 1rem; color: var(--g800); border-bottom: 1px solid var(--g200); padding-bottom: 0.5rem;">
            Decline Information
        </h4>
        <div style="padding: 1.5rem; background: var(--red-50); border: 1px solid var(--red-100); border-radius: 8px; margin-bottom: 1.5rem;">
            <div style="font-size: 0.75rem; color: var(--red-600); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Formal Decision Statement</div>
            <div style="font-weight: 500; color: var(--red-900); font-style: italic; line-height: 1.6;">
                "The legal custodian has formally decided NOT to proceed with the body donation at this time. This case is now closed and moved to the decline registry."
            </div>
        </div>

        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 8px; border: 1px dashed var(--g300);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-weight: 500; font-size: 0.875rem;">Download Decline Confirmation</div>
                    <div style="font-size: 0.75rem; color: var(--g500);">Custodian's official notification to the institution.</div>
                </div>
                <button class="cp-btn cp-btn--secondary cp-btn--sm">
                    <i class="fas fa-file-invoice"></i> Download PDF
                </button>
            </div>
        </div>
    </div>

    <div style="margin-top: 3rem; padding: 1.5rem; background: var(--g50); border-radius: 8px; font-size: 0.8125rem; color: var(--g600); text-align: center; border: 1px solid var(--g200);">
        <i class="fas fa-archive"></i> This case is formally closed.
    </div>
<?php endif; ?>
