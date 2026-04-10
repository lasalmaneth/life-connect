<!-- Modal: Formal Consent Withdrawal Form -->
<?php $shouldClear = isset($_SESSION['show_withdrawal']) && $_SESSION['show_withdrawal']; ?>
<div id="withdrawFormalModal" class="d-modal <?= (isset($_SESSION['show_withdrawal']) && $_SESSION['show_withdrawal']) ? 'active' : '' ?>">
    <div class="d-modal__body" style="max-width: 800px; padding: 0; overflow: hidden; border-radius: 20px;">
        
        <!-- Header -->
        <div style="background: #fff; border-top: 6px solid #ef4444; border-bottom: 1px solid #fecaca; color: #991b1b; padding: 25px; text-align: center; position: relative; border-radius: 20px 20px 0 0;">
            <button class="d-modal__close" onclick="closeWithdrawModal()" style="position: absolute; right: 20px; top: 15px; color: #ef4444; font-size: 2.2rem; background: none; border: none; cursor: pointer; line-height: 1;">&times;</button>
            <i class="fas fa-file-contract" style="font-size: 2.2rem; margin-bottom: 10px; color: #ef4444;"></i>
            <h2 style="margin: 0; font-size: 1.4rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Formal Withdrawal Portal</h2>
            <p style="margin: 5px 0 0; color: #6b7280; font-size: 0.85rem; font-weight: 500;">Official Statutory Revocation of Organ Donation Consent</p>
        </div>

        <div class="d-modal__content" style="padding: 30px; max-height: 70vh; overflow-y: auto;">
            
            <!-- Global hidden inputs for JS access -->
            <input type="hidden" id="withdrawOrganId">

            <?php if (!$withdrawal || (isset($_SESSION['force_step1']) && $_SESSION['force_step1'])): ?>
                <!-- STEP 1: FILL DETAILS -->
                <div style="background: #fff5f5; border-left: 4px solid #ef4444; padding: 15px 20px; border-radius: 8px; margin-bottom: 25px;">
                    <h4 style="color: #991b1b; margin: 0 0 5px; font-size: 1rem;"><i class="fas fa-exclamation-triangle"></i> Statutory Requirement</h4>
                    <p style="color: #b91c1c; margin: 0; font-size: 0.85rem; line-height: 1.5;">
                        This form must be completed accurately, printed, signed with two witnesses, and uploaded to satisfy the <strong>Transplantation of Human Tissues Act</strong>.
                    </p>
                </div>

                <form method="POST" action="<?= ROOT ?>/donor/withdraw-consent">
                    <input type="hidden" name="action" value="submit_metadata">
                    <input type="hidden" name="organ_id" id="withdrawOrganIdForm" value="<?= $_SESSION['withdrawal_organ_id'] ?? '' ?>">
                    
                    <div style="margin-bottom: 30px;">
                        <h4 style="border-bottom: 1px solid var(--g200); padding-bottom: 8px; color: var(--blue-800); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-bottom: 20px;">1. Personal Information</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="d-input-group">
                                <label style="font-size: 0.85rem;">Full Name</label>
                                <input type="text" name="full_name" value="<?= htmlspecialchars($donor_data['first_name'] . ' ' . $donor_data['last_name']) ?>" required class="d-input">
                            </div>
                            <div class="d-input-group">
                                <label style="font-size: 0.85rem;">NIC Number</label>
                                <input type="text" name="nic_number" value="<?= htmlspecialchars($donor_data['nic_number'] ?? '') ?>" required class="d-input">
                            </div>
                            <div class="d-input-group">
                                <label style="font-size: 0.85rem;">Date of Birth</label>
                                <input type="date" name="dob" value="<?= htmlspecialchars($donor_data['date_of_birth'] ?? '') ?>" required class="d-input">
                            </div>
                            <div class="d-input-group">
                                <label style="font-size: 0.85rem;">Contact Number</label>
                                <input type="text" name="contact_number" value="<?= htmlspecialchars($donor_data['contact_number'] ?? '') ?>" required class="d-input">
                            </div>
                        </div>
                        <div class="d-input-group" style="margin-top: 15px;">
                            <label style="font-size: 0.85rem;">Residential Address</label>
                            <textarea name="address" rows="2" required class="d-input"><?= htmlspecialchars($donor_data['address'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <h4 style="border-bottom: 1px solid var(--g200); padding-bottom: 8px; color: var(--blue-800); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-bottom: 20px;">2. Legal Witnesses</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div style="background: var(--g50); padding: 15px; border-radius: 10px; border: 1px solid var(--g200);">
                                <strong style="display: block; margin-bottom: 10px; font-size: 0.8rem; color: var(--blue-700);">WITNESS 1</strong>
                                <input type="text" name="w1_name" placeholder="Full Name" required style="width:100%; padding:8px; margin-bottom:8px; border:1px solid var(--g300); border-radius:5px; font-size:0.85rem;">
                                <input type="text" name="w1_nic" placeholder="NIC Number" required style="width:100%; padding:8px; border:1px solid var(--g300); border-radius:5px; font-size:0.85rem;">
                            </div>
                            <div style="background: var(--g50); padding: 15px; border-radius: 10px; border: 1px solid var(--g200);">
                                <strong style="display: block; margin-bottom: 10px; font-size: 0.8rem; color: var(--blue-700);">WITNESS 2</strong>
                                <input type="text" name="w2_name" placeholder="Full Name" required style="width:100%; padding:8px; margin-bottom:8px; border:1px solid var(--g300); border-radius:5px; font-size:0.85rem;">
                                <input type="text" name="w2_nic" placeholder="NIC Number" required style="width:100%; padding:8px; border:1px solid var(--g300); border-radius:5px; font-size:0.85rem;">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="d-btn d-btn--primary" style="width: 100%; padding: 15px; font-size: 1rem; background: var(--blue-600);">
                        Generate Legal Document <i class="fas fa-arrow-right" style="margin-left: 10px;"></i>
                    </button>
                </form>

            <?php elseif ($withdrawal->status === 'PENDING_UPLOAD'): ?>
                <!-- STEP 2: DOWNLOAD & UPLOAD -->
                <div style="text-align: center;">
                    <div style="background: #e8f5e9; color: #2e7d32; width: 60px; height: 60px; line-height: 60px; border-radius: 50%; margin: 0 auto 15px; font-size: 1.5rem;">
                        <i class="fas fa-check"></i>
                    </div>
                    <h3 style="margin: 0; color: #1b5e20;">Metadata Recorded Successfully</h3>
                    <p style="color: var(--g600); font-size: 0.9rem; margin: 10px auto 25px; line-height: 1.5; max-width: 500px;">
                        The legal document has been generated. You must now print it, sign it physically with your witnesses, and upload the signed scan.
                    </p>

                    <button onclick="printFormalWithdrawal()" class="d-btn d-btn--outline" style="width:100%; margin-bottom: 25px; padding: 12px; border-width: 2px;">
                        <i class="fas fa-print"></i> Download & Print Withdrawal Form
                    </button>

                    <div style="background: var(--g50); border: 2px dashed var(--g300); padding: 30px; border-radius: 15px;">
                        <form method="POST" enctype="multipart/form-data" action="<?= ROOT ?>/donor/withdraw-consent">
                            <input type="hidden" name="action" value="upload_withdrawal">
                            <input type="hidden" name="withdrawal_id" value="<?= $withdrawal->id ?>">
                            <div style="margin-bottom: 20px;">
                                <i class="fas fa-file-upload" style="font-size: 2.5rem; color: var(--g400); margin-bottom: 10px;"></i>
                                <h4 style="margin: 0; color: var(--g800);">Upload Signed Document</h4>
                                <p style="font-size: 0.75rem; color: var(--g500);">PDF format (Max 5MB)</p>
                            </div>
                            <input type="file" name="withdrawal_pdf" accept=".pdf" required style="margin-bottom: 20px; font-size: 0.8rem; width: 100%; border: 1px solid var(--g200); padding: 10px; border-radius: 8px;">
                            
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <button type="button" class="d-btn d-btn--outline" onclick="closeWithdrawModal()" style="padding: 12px; font-size: 0.85rem;">
                                        Upload Later
                                    </button>
                                    <button type="submit" class="d-btn d-btn--primary" style="background: #2e7d32; padding: 12px; font-size: 0.85rem;">
                                        Finalize Revocation
                                    </button>
                                </div>
                                <button type="button" class="d-btn" style="background: none; color: #dc2626; border: 1px solid #fecaca; padding: 8px; font-size: 0.75rem; font-weight: 600;" onclick="cancelWithdrawal(<?= $withdrawal->id ?>)">
                                    <i class="fas fa-trash-alt"></i> Cancel Withdrawal & Delete My Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Hidden Printable Form (Ported from withdraw.view.php) -->
                <div id="formalPrintableForm" style="display: none;">
                    <div style="padding: 50px; font-family: 'Times New Roman', serif; line-height: 1.8; color: black; background: white;">
                        <h2 style="text-align: center; text-decoration: underline;">ORGAN DONATION CONSENT WITHDRAWAL FORM</h2>
                        <p style="text-align: center; font-style: italic;">(In accordance with the Transplantation of Human Tissues Act No. 48 of 1987)</p>
                        
                        <h4 style="margin-top: 30px;">1. Personal Information</h4>
                        <p><strong>Full Name:</strong> <?= htmlspecialchars($withdrawal->full_name) ?></p>
                        <p><strong>NIC Number:</strong> <?= htmlspecialchars($withdrawal->nic_number) ?></p>
                        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($withdrawal->dob) ?></p>
                        <p><strong>Address:</strong> <?= htmlspecialchars($withdrawal->address) ?></p>
                        <p><strong>Contact Number:</strong> <?= htmlspecialchars($withdrawal->contact_number) ?></p>
                        
                        <h4 style="margin-top: 30px;">2. Declaration of Withdrawal</h4>
                        <p>I hereby withdraw and revoke my previous consent for organ and/or tissue donation given by me earlier.</p>
                        <p>I understand that after this withdrawal is recorded, my organs or tissues will not be used for transplantation or medical purposes.</p>
                        
                        <div style="margin-top: 50px; width: 100%; display: flex; justify-content: space-between;">
                            <div>______________________________<br>Signature of Applicant</div>
                            <div>______________________________<br>Date</div>
                        </div>

                        <h4 style="margin-top: 50px;">3. Witness Details</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
                            <div style="border: 1px solid black; padding: 15px;">
                                <strong>Witness 1</strong>
                                <p>Name: <?= htmlspecialchars($withdrawal->witness1_name) ?></p>
                                <p>NIC: <?= htmlspecialchars($withdrawal->witness1_nic) ?></p>
                                <p style="margin-top: 25px;">Signature: ____________________</p>
                            </div>
                            <div style="border: 1px solid black; padding: 15px;">
                                <strong>Witness 2</strong>
                                <p>Name: <?= htmlspecialchars($withdrawal->witness2_name) ?></p>
                                <p>NIC: <?= htmlspecialchars($withdrawal->witness2_nic) ?></p>
                                <p style="margin-top: 25px;">Signature: ____________________</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
function closeWithdrawModal() {
    closeModal('withdrawFormalModal');
}

function closeWithdrawModal() {
    closeModal('withdrawFormalModal');
}

function cancelWithdrawal(id) {
    if (confirm("Are you sure you want to cancel this withdrawal? This will permanently delete the witness information you entered for this revocation.")) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= ROOT ?>/donor/withdraw-consent';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'cancel_withdrawal';
        form.appendChild(actionInput);
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'withdrawal_id';
        idInput.value = id;
        form.appendChild(idInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function printFormalWithdrawal() {
    const printContents = document.getElementById('formalPrintableForm').innerHTML;
    const printWindow = window.open('', '_blank', 'height=800,width=1000');
    printWindow.document.write('<html><head><title>Withdrawal Form</title>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>

<?php
// Clear the trigger flags so the modal doesn't re-appear on standard page navigation
if (isset($shouldClear) && $shouldClear) {
    unset($_SESSION['show_withdrawal']);
    unset($_SESSION['force_step1']);
    // We keep 'withdrawal_organ_id' for now as it's used inside the modal forms
}
?>
