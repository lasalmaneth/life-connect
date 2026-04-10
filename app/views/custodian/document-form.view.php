<?php
/**
 * Custodian Portal � Document Form Data Entry
 */
ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">
    <div class="cp-notice cp-notice--info mb-4">
        <i class="fas fa-circle-info"></i>
        <div>
            <strong>Autofilled and Secure</strong>
            <p>Some data is automatically fetched from the database based on your details and the donor's profile. Please fill the remaining empty fields carefully.</p>
        </div>
    </div>

    <!-- PREVIEW BOX -->
    <div style="background-color: #fff; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
        <h4 style="margin-bottom: 15px; color: var(--cp-primary-300);">Document Preview</h4>
        <div style="background-color: #f8fafc; padding: 15px; border-radius: 5px; font-size: 0.95rem; color: #475569; overflow-y: auto; max-height: 250px;">
            <?php if ($type === 'sworn'): ?>
                <p><b>Addressing:</b> Head, <?= htmlspecialchars($instName ?? 'Department of Anatomy, Faculty of Medicine, University of Colombo') ?>, <?= nl2br(htmlspecialchars($instAddress ?? 'Kynsey Road, Colombo 08')) ?></p>
                <hr style="margin: 10px 0; border: 0; border-top: 1px solid #cbd5e1;">
                <p><b>Statement:</b> I/We hereby sign and declare my/our willingness without objection, to donate the cadaver of <b><?= htmlspecialchars($donor->name ?? trim(($donor->first_name ?? '') . ' ' . ($donor->last_name ?? ''))) ?></b> of N.I.C No <b><?= htmlspecialchars($donor->nic_number) ?></b> previously residing at address <b><?= htmlspecialchars($donor->address ?? 'N/A') ?></b> who expired on <b><?= htmlspecialchars($activeCase->date_of_death ?? '[Date of Death]') ?></b>, to <?= htmlspecialchars($instName ?? 'the Department of Anatomy at the Faculty of Medicine, University of Colombo') ?>. Furthermore I/we wish to handover the legal ownership of the above mentioned cadaver to <b><?= htmlspecialchars($custodian->name ?? trim(($custodian->first_name ?? '') . ' ' . ($custodian->last_name ?? ''))) ?></b> of NIC No <b><?= htmlspecialchars($custodian->nic_number) ?></b>.</p>
                <p>In addition, I/We hereby state that there will be no further inquiry or request regarding the above mentioned cadaver after donation has taken place.</p>
                <p><i>- Please add immediate family relations below who will affirm this statement and sign the final printout.</i></p>
            <?php else: ?>
                <p><b>Data Sheet for Cadaver Donation</b></p>
                <hr style="margin: 10px 0; border: 0; border-top: 1px solid #cbd5e1;">
                <p><b>Addressing:</b> <?= htmlspecialchars($instName ?? 'Department of Anatomy, Faculty of Medicine, University of Colombo') ?></p>
                <p><b>Statement:</b> Herewith I Give Consent to Use the Cadaver of <b><?= htmlspecialchars($donor->name ?? trim(($donor->first_name ?? '') . ' ' . ($donor->last_name ?? ''))) ?></b> (N.I.C No: <b><?= htmlspecialchars($donor->nic_number) ?></b>) for Medical Research and Educational Purposes.</p>
                <p><i>- Please fill in the remaining medical facts below carefully so they can be captured correctly on the final printed Data Sheet.</i></p>
            <?php endif; ?>
        </div>
    </div>

    <form action="<?= ROOT ?>/custodian/save-document-form" method="POST">
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>" />

        <div class="cp-section-card mb-4">
            <div class="cp-section-card__header">
                <div class="cp-section-card__title"><i class="fas fa-edit"></i> Complete Pending Information</div>
            </div>
            <div class="cp-section-card__body">
                
                <h5 class="mb-3">Details of the Person Handing Over the Cadaver</h5>
                <p class="cp-text-muted mb-3" style="font-size:0.9rem;">This will default to your details. If someone else is physically handing over the body, you can update it here.</p>
                <div class="cp-profile-grid mb-4">
                    <div class="cp-profile-form-group">
                        <label class="cp-label">Full Name</label>
                        <input type="text" name="custodian_name" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_name'] ?? $custodian->name ?? trim(($custodian->first_name ?? '') . ' ' . ($custodian->last_name ?? ''))) ?>" required>
                    </div>
                    <div class="cp-profile-form-group">
                        <label class="cp-label">NIC No</label>
                        <input type="text" name="custodian_nic" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_nic'] ?? $custodian->nic_number ?? '') ?>" required>
                    </div>
                </div>

                <div class="cp-profile-grid mb-4">
                    <div class="cp-profile-form-group">
                        <label class="cp-label">Relationship to Donor</label>
                        <input type="text" name="custodian_relationship" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_relationship'] ?? $custodian->relationship ?? '') ?>" required>
                    </div>
                    <div class="cp-profile-form-group">
                        <label class="cp-label">Telephone No</label>
                        <input type="text" name="custodian_phone" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_phone'] ?? $custodian->phone ?? '') ?>" required>
                    </div>
                </div>

                <div class="cp-profile-form-group mb-4">
                    <label class="cp-label">Address</label>
                    <input type="text" name="custodian_address" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_address'] ?? $custodian->address ?? '') ?>" required>
                </div>

                <h5 class="mb-3">Donor Details & Medical Info</h5>
                <div class="cp-profile-grid mb-4">
                    <div class="cp-profile-form-group">
                        <label class="cp-label">Occupation at the time of death</label>
                        <input type="text" name="occupation" class="cp-profile-input" value="<?= htmlspecialchars($formData['occupation'] ?? '') ?>" required>
                    </div>
                    <?php if ($type === 'datasheet'): ?>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Race</label>
                            <input type="text" name="race" class="cp-profile-input" value="<?= htmlspecialchars($formData['race'] ?? '') ?>" required>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Religion</label>
                            <input type="text" name="donor_religion" class="cp-profile-input" value="<?= htmlspecialchars($formData['donor_religion'] ?? $donor->religion ?? '') ?>" required>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Place of Birth & District</label>
                            <input type="text" name="birth_place" class="cp-profile-input" value="<?= htmlspecialchars($formData['birth_place'] ?? '') ?>" required>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Place of Death</label>
                            <input type="text" name="place_of_death" class="cp-profile-input" value="<?= htmlspecialchars($formData['place_of_death'] ?? $death_declaration->place_of_death ?? '') ?>" required>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($type === 'datasheet'): ?>
                    <h5 class="mb-3">Medical History Details</h5>
                    <div class="cp-profile-form-group mb-3">
                        <label class="cp-label">Past Medical History</label>
                        <textarea name="past_medical_history" class="cp-profile-input" rows="2"><?= htmlspecialchars($formData['past_medical_history'] ?? '') ?></textarea>
                    </div>
                    <div class="cp-profile-form-group mb-3">
                        <label class="cp-label">Past Surgical History</label>
                        <textarea name="past_surgical_history" class="cp-profile-input" rows="2"><?= htmlspecialchars($formData['past_surgical_history'] ?? '') ?></textarea>
                    </div>
                    <div class="cp-profile-form-group mb-4">
                        <label class="cp-label">Other Diseases</label>
                        <textarea name="other_diseases" class="cp-profile-input" rows="2"><?= htmlspecialchars($formData['other_diseases'] ?? '') ?></textarea>
                    </div>
                <?php endif; ?>

                <?php if ($type === 'sworn'): ?>
                    <h5 class="mb-3">Immediate Relations Details (For Sworn Statement Table)</h5>
                    <p class="cp-text-muted mb-3" style="font-size:0.9rem;">List the spouse, children, or siblings (Required for Sworn Legal doc).</p>

                    <div id="relations-container">
                        <?php 
                            $relNames = $formData['relations_name'] ?? [''];
                            $relRels = $formData['relations_rel'] ?? [''];
                            $relNics = $formData['relations_nic'] ?? [''];
                            $count = count($relNames);
                            if ($count === 0) $count = 1;

                            for($i=0; $i<$count; $i++): 
                        ?>
                        <div class="cp-grid-3 mb-2 relation-row">
                            <div class="cp-profile-form-group">
                                <label class="cp-label">Full Name</label>
                                <input type="text" name="relations_name[]" class="cp-profile-input" value="<?= htmlspecialchars($relNames[$i] ?? '') ?>" required>
                            </div>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">Relationship</label>
                                <input type="text" name="relations_rel[]" class="cp-profile-input" value="<?= htmlspecialchars($relRels[$i] ?? '') ?>" required>
                            </div>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">NIC Number</label>
                                <input type="text" name="relations_nic[]" class="cp-profile-input" value="<?= htmlspecialchars($relNics[$i] ?? '') ?>" required>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <button type="button" class="cp-btn cp-btn--sm cp-btn--outline mt-2" onclick="addRelationRow()">+ Add Another Relation</button>
                    
                    <script>
                        function addRelationRow() {
                            const container = document.getElementById('relations-container');
                            const html = `
                                <div class="cp-grid-3 mb-2 relation-row">
                                    <div class="cp-profile-form-group"><input type="text" name="relations_name[]" class="cp-profile-input" placeholder="Full Name" required></div>
                                    <div class="cp-profile-form-group"><input type="text" name="relations_rel[]" class="cp-profile-input" placeholder="Relationship" required></div>
                                    <div class="cp-profile-form-group"><input type="text" name="relations_nic[]" class="cp-profile-input" placeholder="NIC Number" required></div>
                                </div>
                            `;
                            container.insertAdjacentHTML('beforeend', html);
                        }
                    </script>
                <?php endif; ?>

            </div>
        </div>

        <div class="d-flex justify-end gap-2">
            <a href="<?= ROOT ?>/custodian/documents" class="cp-btn cp-btn--outline"><i class="fas fa-arrow-left"></i> Back to Checklist</a>
            <button type="submit" class="cp-btn cp-btn--primary"><i class="fas fa-save"></i> Save & View Document</button>
        </div>

    </form>
</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>

