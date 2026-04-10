<?php if (!$donor): ?>
    <div class="cp-alert cp-alert--danger">Donor record not found.</div>
<?php else: ?>
    <div class="cp-drawer-section">
        <h4 class="cp-drawer-section__title">
            <i class="fas fa-id-card"></i> Personal Information
        </h4>
        <div class="cp-drawer-grid">
            <div style="grid-column: span 2;">
                <div class="cp-drawer-field__label">Full Name</div>
                <div class="cp-drawer-field__value"><?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">Date of Birth</div>
                <div class="cp-drawer-field__value"><?= $donor->date_of_birth ? date('Y-m-d', strtotime($donor->date_of_birth)) : 'N/A' ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">Age</div>
                <div class="cp-drawer-field__value">
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
            <div>
                <div class="cp-drawer-field__label">Gender</div>
                <div class="cp-drawer-field__value"><?= strtoupper($donor->gender ?? 'N/A') ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">NIC Number</div>
                <div class="cp-drawer-field__value"><?= htmlspecialchars($donor->nic_number ?? 'N/A') ?></div>
            </div>
            <div>
                <div class="cp-drawer-field__label">Nationality</div>
                <div class="cp-drawer-field__value"><?= htmlspecialchars($donor->nationality ?? 'N/A') ?></div>
            </div>
        </div>
    </div>

    <div class="cp-drawer-section">
        <h4 class="cp-drawer-section__title">
            <i class="fas fa-user-times"></i> Withdrawal Details
        </h4>
        <div class="cp-callout" style="background: var(--red-50); border-left-color: var(--red-500);">
            <div class="cp-callout__title" style="color: var(--red-600);">Withdrawal Date / Status Update</div>
            <div class="cp-callout__text" style="color: var(--red-900);"><?= date('M d, Y', strtotime($donor->consent_date)) ?></div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.8125rem; font-weight: 700; color: var(--g600); margin-bottom: 0.5rem;">Reason for Withdrawal:</label>
            <div style="padding: 1.25rem; background: #fff; border: 1px solid var(--g200); border-radius: 8px; color: var(--slate); font-style: italic; line-height: 1.5; font-size: 0.875rem;">
                "<?= htmlspecialchars($donor->opt_out_reason ?? 'No detailed reason provided by the donor.') ?>"
            </div>
        </div>

        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 8px; border: 1px dashed var(--g300);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--slate);">Withdrawal Notice PDF</div>
                    <div style="font-size: 0.75rem; color: var(--g500);">Donor's official signed withdrawal notice.</div>
                </div>
                <button class="cp-btn cp-btn--secondary cp-btn--sm">
                    <i class="fas fa-file-pdf"></i> Download
                </button>
            </div>
        </div>
    </div>

    <div style="margin-top: 2.5rem; padding: 1.25rem; background: var(--g50); border-radius: 8px; font-size: 0.8125rem; color: var(--g500); text-align: center; border: 1px solid var(--g200);">
        <i class="fas fa-lock"></i> This record is archived as a legal withdrawal and cannot be edited.
    </div>
<?php endif; ?>
