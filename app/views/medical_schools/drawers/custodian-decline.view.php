<?php if (!$decline): ?>
    <div class="cp-alert cp-alert--danger">Notice record not found.</div>
<?php else: ?>
    <div class="dr-section">
        <div class="flex items-center justify-between mb-6">
            <span class="dr-badge dr-badge--success bg-red-50 text-red-600 border-red-100 font-bold uppercase">Custodian Declined</span>
            <div class="dr-doc-meta">Archive ID: #<?= htmlspecialchars($decline->cis_id) ?></div>
        </div>

        <h4 class="dr-section-title">
            <span>Donor Identification</span>
        </h4>
        <div class="dr-grid dr-grid--2">
            <div class="dr-label-group">
                <div class="dr-label">Full Name</div>
                <div class="dr-value dr-value--sub"><?= htmlspecialchars($decline->first_name . ' ' . $decline->last_name) ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Date of Death</div>
                <div class="dr-value dr-value--accent text-red-600 font-mono"><?= $decline->custodian_decline_date ? date('M d, Y', strtotime($decline->custodian_decline_date)) : 'N/A' ?></div>
            </div>
        </div>
    </div>

    <div class="dr-section">
        <h4 class="dr-section-title">
            <span>Decline Information</span>
        </h4>
        <div class="dr-alert-box dr-alert-box--danger">
            <div class="dr-alert-box__title">Formal Decision Statement</div>
            <div class="dr-alert-box__notes text-red-900 leading-relaxed font-medium">
                "The legal custodian has formally decided NOT to proceed with the body donation at this time. This case is now closed and moved to the decline registry."
            </div>
        </div>

        <div class="dr-card dr-card--dashed">
            <div class="flex items-center justify-between">
                <div>
                    <div class="dr-heading-sm">Download Decline Confirmation</div>
                    <div class="dr-doc-meta mt-1">Custodian's official notification to the institution.</div>
                </div>
                <button class="cp-btn dr-btn-xs bg-white border-blue-500 text-blue-600">
                    <i class="fas fa-file-invoice mr-1"></i> Download PDF
                </button>
            </div>
        </div>
    </div>

    <div class="dr-empty-state bg-gray-50 border-gray-100 text-gray-400 mt-12 py-6">
        <i class="fas fa-archive text-gray-300"></i>
        <p class="dr-empty-state__title text-gray-500">This case is formally closed.</p>
    </div>
<?php endif; ?>
