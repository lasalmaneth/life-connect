<?php if (!$donor): ?>
    <div class="cp-alert cp-alert--danger">Donor record not found.</div>
<?php else: ?>
    <div class="dr-section">
        <h4 class="dr-section-title">
            <span><i class="fas fa-id-card"></i> Personal Information</span>
        </h4>
        <div class="dr-grid dr-grid--2">
            <div class="dr-label-group" style="grid-column: span 2;">
                <div class="dr-label">Full Name</div>
                <div class="dr-value"><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Date of Birth</div>
                <div class="dr-value dr-value--small"><?= $donor->date_of_birth ? date('Y-m-d', strtotime($donor->date_of_birth)) : 'N/A' ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Age</div>
                <div class="dr-value dr-value--small">
                    <?php 
                        if ($donor->date_of_birth) {
                            $birthDate = new DateTime($donor->date_of_birth);
                            $today = new DateTime();
                            $age = $today->diff($birthDate)->y;
                            echo $age;
                        } else {
                            echo 'N/A';
                        }
                    ?>
                </div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Gender</div>
                <div class="dr-value dr-value--small"><?= strtoupper($donor->gender ?? 'N/A') ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">NIC Number</div>
                <div class="dr-value dr-value--small"><?= htmlspecialchars($donor->nic_number ?? 'N/A') ?></div>
            </div>
            <div class="dr-label-group">
                <div class="dr-label">Nationality</div>
                <div class="dr-value dr-value--small"><?= htmlspecialchars($donor->nationality ?? 'N/A') ?></div>
            </div>
        </div>
    </div>

    <div class="dr-section">
        <h4 class="dr-section-title">
            <span><i class="fas fa-user-times"></i> Withdrawal Details</span>
        </h4>
        <div class="dr-banner dr-banner--info bg-rose-50 border-rose-100">
            <div class="dr-banner__title-sm text-rose-600">Withdrawal Date / Status Update</div>
            <div class="dr-banner__title text-rose-900"><?= date('M d, Y', strtotime($donor->consent_date)) ?></div>
        </div>

        <div class="mb-6">
            <label class="dr-label">Reason for Withdrawal:</label>
            <div class="dr-form-area italic text-gray-500 text-sm leading-relaxed">
                "<?= htmlspecialchars($donor->opt_out_reason ?? 'No detailed reason provided by the donor.') ?>"
            </div>
        </div>

        <div class="dr-card dr-card--dashed">
            <div class="flex items-center justify-between">
                <div>
                    <div class="dr-heading-sm">Withdrawal Notice PDF</div>
                    <div class="dr-doc-meta mt-1">Donor's official signed withdrawal notice.</div>
                </div>
                <button class="cp-btn dr-btn-xs bg-white border-blue-500 text-blue-600">
                    <i class="fas fa-file-pdf mr-1"></i> Download
                </button>
            </div>
        </div>
    </div>

    <div class="dr-empty-state bg-gray-50 border-gray-100 text-gray-400 mt-10 py-5">
        <i class="fas fa-lock text-gray-300"></i>
        <p class="dr-empty-state__title text-gray-500">Legal withdrawal archive. Cannot be edited.</p>
    </div>
<?php endif; ?>
