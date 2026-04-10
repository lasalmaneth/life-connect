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
            <div>
                <div class="cp-drawer-field__label">Contact</div>
                <div class="cp-drawer-field__value"><?= htmlspecialchars($donor->phone ?? 'N/A') ?></div>
            </div>
            <div style="grid-column: span 2;">
                <div class="cp-drawer-field__label">Email Address</div>
                <div class="cp-drawer-field__value"><?= htmlspecialchars($donor->email ?? 'N/A') ?></div>
            </div>
        </div>
    </div>

    <div class="cp-drawer-section">
        <h4 class="cp-drawer-section__title">
            <i class="fas fa-file-signature"></i> Consent Information
        </h4>
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="flex: 1; padding: 1rem; background: var(--blue-50); border-radius: 8px; text-align: center; border: 1px solid var(--blue-100);">
                <div style="font-size: 0.75rem; color: var(--blue-600); margin-bottom: 4px; font-weight: 700;">Consent Date</div>
                <div style="font-weight: 700; color: var(--blue-900);"><?= date('M d, Y', strtotime($donor->consent_date)) ?></div>
            </div>
            <div style="flex: 1; padding: 1rem; background: var(--g50); border-radius: 8px; text-align: center; border: 1px solid var(--g200);">
                <div style="font-size: 0.75rem; color: var(--g500); margin-bottom: 4px; font-weight: 700;">Registry Status</div>
                <div style="font-weight: 700; color: var(--slate);"><?= htmlspecialchars($donor->consent_status) ?></div>
            </div>
        </div>
        
        <?php if ($donor->flag_reason): ?>
            <div class="cp-alert-box--danger" style="background: var(--orange-50); border-color: var(--orange-200); color: var(--orange-900);">
                <div class="cp-alert-box__title" style="color: var(--orange-800);">
                    <i class="fas fa-flag"></i> Flagged Reason
                </div>
                <div class="cp-alert-box__msg">
                    <?= htmlspecialchars($donor->flag_reason) ?>
                </div>
            </div>
        <?php endif; ?>

        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 8px; border: 1px dashed var(--g300);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--slate);">Consent Document PDF</div>
                    <div style="font-size: 0.75rem; color: var(--g500);">Archive copy of signed willingness form.</div>
                </div>
                <button class="cp-btn cp-btn--secondary cp-btn--sm">
                    <i class="fas fa-download"></i> Download
                </button>
            </div>
        </div>
    </div>

    <div class="cp-drawer-section" style="border-top: 1px solid var(--g100); padding-top: 1.5rem;">
        <h4 class="cp-drawer-section__title" style="color: var(--red-700); border-bottom-color: var(--red-100);">
            <i class="fas fa-shield-halved"></i> Security Action: Flag Record
        </h4>
        <form action="<?= ROOT ?>/medical-school/consents/flag" method="POST">
            <input type="hidden" name="donor_id" value="<?= $donor->id ?>">
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--g600);">Reason for Flagging:</label>
                <textarea name="flag_reason" class="cp-textarea" style="width: 100%; height: 90px;" placeholder="Describe the discrepancy (e.g. missing signature, invalid NIC scan)..." required></textarea>
            </div>
            <button type="submit" class="cp-btn cp-btn--danger" style="width: 100%;">
                <i class="fas fa-flag"></i> Formally Flag for Follow-up
            </button>
        </form>
    </div>

    <style>
    .cp-textarea {
        padding: 0.75rem;
        border: 1px solid var(--g200);
        border-radius: 6px;
        font-size: 0.875rem;
        font-family: inherit;
        resize: vertical;
    }
    .cp-textarea:focus {
        outline: none;
        border-color: var(--blue-400);
        box-shadow: 0 0 0 3px rgba(0, 91, 170, 0.1);
    }
    </style>
<?php endif; ?>
