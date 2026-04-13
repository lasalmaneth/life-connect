<?php
/**
 * Custodian Portal � Document Form Data Entry
 */
ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">
    <!-- Workflow Locking Notice -->
    <?php include __DIR__ . '/partials/lock-notice.php'; ?>

    <form action="<?= ROOT ?>/custodian/save-document-form" method="POST">
        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>" />

        <div class="cp-section-card mb-4" style="<?= !$isLeader ? 'opacity: 0.85;' : '' ?>">
            <div class="cp-section-card__header">
                <div class="cp-section-card__title">
                    <i class="fas <?= $isLeader ? 'fa-edit' : 'fa-eye' ?>"></i> 
                    <?= $isLeader ? 'Complete Pending Information' : 'Review Information' ?>
                </div>
            </div>
            <div class="cp-section-card__body">
                
                <h5 class="mb-3">Details of the Person Handing Over the Cadaver</h5>
                <p class="cp-text-muted mb-3" style="font-size:0.9rem;">This will default to your details. If someone else is physically handing over the body, you can update it here.</p>
                <div class="cp-profile-grid mb-4">
                    <div class="cp-profile-form-group">
                        <label class="cp-label">Full Name</label>
                        <input type="text" name="custodian_name" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_name'] ?? $custodian->name ?? trim(($custodian->first_name ?? '') . ' ' . ($custodian->last_name ?? ''))) ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                    </div>
                    <div class="cp-profile-form-group">
                        <label class="cp-label">NIC No</label>
                        <input type="text" name="custodian_nic" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_nic'] ?? $custodian->nic_number ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                    </div>
                </div>

                <div class="cp-profile-grid mb-4">
                    <div class="cp-profile-form-group">
                        <label class="cp-label">Relationship to Donor</label>
                        <input type="text" name="custodian_relationship" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_relationship'] ?? $custodian->relationship ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                    </div>
                    <div class="cp-profile-form-group">
                        <label class="cp-label">Telephone No</label>
                        <input type="text" name="custodian_phone" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_phone'] ?? $custodian->phone ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                    </div>
                </div>

                <div class="cp-profile-form-group mb-4">
                    <label class="cp-label">Address</label>
                    <input type="text" name="custodian_address" class="cp-profile-input" value="<?= htmlspecialchars($formData['custodian_address'] ?? $custodian->address ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                </div>

                <h5 class="mb-3">Donor Details & Medical Info</h5>
                <div class="cp-profile-grid mb-4">
                    <div class="cp-profile-form-group">
                        <label class="cp-label">Occupation at the time of death</label>
                        <input type="text" name="occupation" class="cp-profile-input" value="<?= htmlspecialchars($formData['occupation'] ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                    </div>
                    <?php if ($type === 'datasheet'): ?>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Race</label>
                            <input type="text" name="race" class="cp-profile-input" value="<?= htmlspecialchars($formData['race'] ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Religion</label>
                            <input type="text" name="donor_religion" class="cp-profile-input" value="<?= htmlspecialchars($formData['donor_religion'] ?? $donor->religion ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Place of Birth & District</label>
                            <input type="text" name="birth_place" class="cp-profile-input" value="<?= htmlspecialchars($formData['birth_place'] ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Place of Death</label>
                            <input type="text" name="place_of_death" class="cp-profile-input" value="<?= htmlspecialchars($formData['place_of_death'] ?? $death_declaration->place_of_death ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($type === 'datasheet'): ?>
                    <h5 class="mb-3">Medical History Details</h5>
                    <div class="cp-profile-form-group mb-3">
                        <label class="cp-label">Past Medical History</label>
                        <textarea name="past_medical_history" class="cp-profile-input" rows="2" <?= !$isLeader ? 'readonly' : '' ?>><?= htmlspecialchars($formData['past_medical_history'] ?? '') ?></textarea>
                    </div>
                    <div class="cp-profile-form-group mb-3">
                        <label class="cp-label">Past Surgical History</label>
                        <textarea name="past_surgical_history" class="cp-profile-input" rows="2" <?= !$isLeader ? 'readonly' : '' ?>><?= htmlspecialchars($formData['past_surgical_history'] ?? '') ?></textarea>
                    </div>
                    <div class="cp-profile-form-group mb-4">
                        <label class="cp-label">Other Diseases</label>
                        <textarea name="other_diseases" class="cp-profile-input" rows="2" <?= !$isLeader ? 'readonly' : '' ?>><?= htmlspecialchars($formData['other_diseases'] ?? '') ?></textarea>
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
                                <input type="text" name="relations_name[]" class="cp-profile-input" value="<?= htmlspecialchars($relNames[$i] ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                            </div>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">Relationship</label>
                                <input type="text" name="relations_rel[]" class="cp-profile-input" value="<?= htmlspecialchars($relRels[$i] ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                            </div>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">NIC Number</label>
                                <input type="text" name="relations_nic[]" class="cp-profile-input" value="<?= htmlspecialchars($relNics[$i] ?? '') ?>" required <?= !$isLeader ? 'readonly' : '' ?>>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <?php if ($isLeader): ?>
                        <button type="button" class="cp-btn cp-btn--sm cp-btn--outline mt-2" onclick="addRelationRow()">+ Add Another Relation</button>
                    <?php endif; ?>
                    
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
            <?php if ($isLeader): ?>
                <button type="submit" class="cp-btn cp-btn--primary"><i class="fas fa-save"></i> Save & View Document</button>
            <?php else: ?>
                <button type="button" class="cp-btn cp-btn--primary" onclick="window.history.back()"><i class="fas fa-check"></i> Finished Review</button>
            <?php endif; ?>
        </div>

    </form>

    </form>
</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>

