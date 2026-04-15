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
            <div class="dr-label-group">
                <div class="dr-label">Contact</div>
                <div class="dr-value dr-value--small"><?= htmlspecialchars($donor->phone ?? 'N/A') ?></div>
            </div>
            <div class="dr-label-group" style="grid-column: span 2;">
                <div class="dr-label">Email Address</div>
                <div class="dr-value dr-value--small break-all"><?= htmlspecialchars($donor->email ?? 'N/A') ?></div>
            </div>
        </div>
    </div>

    <!-- Family Custodians section removed for Consent Registry (Pre-Death) -->

    <div class="dr-section">
        <h4 class="dr-section-title">
            <span><i class="fas fa-file-signature"></i> Consent Information</span>
        </h4>
        <div class="dr-grid dr-grid--2 mb-6">
            <div class="dr-item bg-blue-50 border-blue-100 text-center">
                <div class="dr-label text-blue-600 mb-1">Consent Date</div>
                <div class="dr-value text-blue-900"><?= date('M d, Y', strtotime($donor->consent_date)) ?></div>
            </div>
            <div class="dr-item bg-gray-50 text-center">
                <div class="dr-label mb-1">Registry Status</div>
                <div class="dr-value"><?= htmlspecialchars($donor->consent_status) ?></div>
            </div>
        </div>
        
        <?php if ($donor->flag_reason): ?>
            <div class="dr-alert-box dr-alert-box--warning mb-6">
                <div class="dr-alert-box__title">
                    <i class="fas fa-flag"></i> Flagged Reason
                </div>
                <div class="dr-alert-box__main">
                    <?= htmlspecialchars($donor->flag_reason) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="dr-card dr-card--dashed">
            <div class="flex items-center justify-between">
                <div>
                    <div class="dr-heading-sm">Consent Document PDF</div>
                    <div class="dr-doc-meta mt-1">Archive copy of signed willingness form.</div>
                </div>
                <?php if ($donor->signed_form_path): ?>
                    <a href="<?= ROOT ?>/<?= htmlspecialchars($donor->signed_form_path) ?>" target="_blank" class="cp-btn dr-btn-xs bg-white border-blue-500 text-blue-600">
                        <i class="fas fa-eye mr-1"></i> View Signed form
                    </a>
                <?php else: ?>
                    <button class="cp-btn dr-btn-xs bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed" disabled title="Donor has not uploaded the signed form yet.">
                        <i class="fas fa-clock mr-1"></i> Pending Upload
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="dr-section pt-6 border-t border-gray-100 mt-6">
        <h4 class="dr-heading-sm text-red-700 mb-4 flex items-center gap-2">
            <i class="fas fa-shield-halved"></i> Security Action: Flag Record
        </h4>
        <form action="<?= ROOT ?>/medical-school/consents/flag" method="POST">
            <input type="hidden" name="donor_id" value="<?= $donor->id ?>">
            <div class="mb-4">
                <label class="dr-slim-label text-gray-600">Reason for Flagging:</label>
                <textarea name="flag_reason" class="dr-slim-textarea border-red-100 focus:border-red-400" placeholder="Describe the discrepancy (e.g. missing signature, invalid NIC scan)..." required></textarea>
            </div>
            <button type="submit" class="cp-btn dr-btn-full bg-red-600 text-white shadow-red-200">
                <i class="fas fa-flag mr-1"></i> Formally Flag for Follow-up
            </button>
        </form>
    </div>
<?php endif; ?>
