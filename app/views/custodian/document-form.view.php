<?php
/**
 * Custodian Portal — Simplified Document Form
 */
$isSaved = !empty($formData);
$isEditMode = isset($_GET['edit']) && $_GET['edit'] == '1';
$isReadOnly = ($isSaved && !$isEditMode) || !$isLeader;
$extra_css  = ['custodian/document_form.css'];

ob_start();
?>

<!-- Page Header -->
<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">
    <!-- Workflow Locking Notice -->
    <?php include __DIR__ . '/partials/lock-notice.php'; ?>

    <div class="cp-card-centered-container">
        <form id="doc-form" action="<?= ROOT ?>/custodian/save-document-form" method="POST">
            <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>" />

            <div class="cp-section-card mb-4" style="<?= !$isLeader ? 'opacity: 0.85;' : '' ?>">
                <div class="cp-section-card__header" style="border-bottom: none; padding-bottom: 0.5rem;">
                    <div class="cp-section-card__title">
                        <i class="fas <?= $isReadOnly ? 'fa-eye' : 'fa-edit' ?>"></i>
                        <?= $isReadOnly ? 'Reviewing: ' . ($type === 'sworn' ? 'Sworn Statement' : 'Cadaver Data Sheet') : 'Editing: ' . ($type === 'sworn' ? 'Sworn Statement' : 'Cadaver Data Sheet') ?>
                    </div>
                </div>
                
                <div class="px-5 pt-4">
                    <div class="cp-form-info-bar">
                        <i class="fas fa-info-circle mt-1" style="color: var(--blue-600); font-size: 1.1rem;"></i>
                        <p style="margin: 0; font-size: 0.875rem; color: var(--blue-800); line-height: 1.5; font-weight: 500;">
                            <?php if ($type === 'sworn'): ?>
                                <strong>Sworn Statement:</strong> Formal legal declaration confirming the next-of-kin's consent and authorization for body donation to the institution.
                            <?php else: ?>
                                <strong>Cadaver Data Sheet:</strong> Institutional record capturing the donor's medical history, parentage, and background for anatomical research suitability.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="cp-section-card__body">

                    <h5 class="mb-3">A. Person Handing Over the Cadaver</h5>
                    <p class="cp-text-muted mb-3" style="font-size:0.9rem;">Fill in the details of the custodian physically handing over the donor body.</p>
                    
                    <div class="cp-profile-grid mb-4">
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Full Name</label>
                            <input type="text" name="custodian_name" class="cp-profile-input"
                                value="<?= htmlspecialchars($formData['custodian_name'] ?? $custodian->name ?? '') ?>"
                                required <?= $isReadOnly ? 'readonly' : '' ?>>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">NIC No</label>
                            <input type="text" name="custodian_nic" class="cp-profile-input"
                                value="<?= htmlspecialchars($formData['custodian_nic'] ?? $custodian->nic_number ?? '') ?>"
                                required <?= $isReadOnly ? 'readonly' : '' ?>>
                        </div>
                    </div>

                    <div class="cp-profile-grid mb-4">
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Relationship to Donor</label>
                            <input type="text" name="custodian_relationship" class="cp-profile-input"
                                value="<?= htmlspecialchars($formData['custodian_relationship'] ?? $custodian->relationship ?? '') ?>"
                                required <?= $isReadOnly ? 'readonly' : '' ?>>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Telephone No</label>
                            <input type="text" name="custodian_phone" class="cp-profile-input"
                                value="<?= htmlspecialchars($formData['custodian_phone'] ?? $custodian->phone ?? '') ?>"
                                required <?= $isReadOnly ? 'readonly' : '' ?>>
                        </div>
                    </div>

                    <div class="cp-profile-form-group mb-5">
                        <label class="cp-label">Address</label>
                        <input type="text" name="custodian_address" class="cp-profile-input"
                            value="<?= htmlspecialchars($formData['custodian_address'] ?? $custodian->address ?? '') ?>"
                            required <?= $isReadOnly ? 'readonly' : '' ?>>
                    </div>

                    <div class="cp-divider mb-5"></div>

                    <h5 class="mb-3">B. Donor Background Info</h5>
                    <div class="cp-profile-grid mb-4">
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Occupation at time of death</label>
                            <input type="text" name="occupation" class="cp-profile-input"
                                value="<?= htmlspecialchars($formData['occupation'] ?? '') ?>" required
                                <?= $isReadOnly ? 'readonly' : '' ?>>
                        </div>
                        <?php if ($type === 'datasheet'): ?>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">Race</label>
                                <input type="text" name="race" class="cp-profile-input"
                                    value="<?= htmlspecialchars($formData['race'] ?? '') ?>" required <?= $isReadOnly ? 'readonly' : '' ?>>
                            </div>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">Religion</label>
                                <input type="text" name="donor_religion" class="cp-profile-input"
                                    value="<?= htmlspecialchars($formData['donor_religion'] ?? $donor->religion ?? '') ?>"
                                    required <?= $isReadOnly ? 'readonly' : '' ?>>
                            </div>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">Place of Birth & District</label>
                                <input type="text" name="birth_place" class="cp-profile-input"
                                    value="<?= htmlspecialchars($formData['birth_place'] ?? '') ?>" required
                                    <?= $isReadOnly ? 'readonly' : '' ?>>
                            </div>
                        <?php endif; ?>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Place of Death</label>
                            <input type="text" name="place_of_death" class="cp-profile-input"
                                value="<?= htmlspecialchars($formData['place_of_death'] ?? '') ?>" required
                                <?= $isReadOnly ? 'readonly' : '' ?>>
                        </div>
                    </div>

                    <?php if ($type === 'datasheet'): ?>
                        <div class="cp-divider mb-5"></div>
                        <h5 class="mb-3">C. Medical History</h5>
                        <div class="cp-profile-form-group mb-3">
                            <label class="cp-label">Past Medical History</label>
                            <textarea name="past_medical_history" class="cp-profile-input" rows="3"
                                <?= $isReadOnly ? 'readonly' : '' ?>><?= htmlspecialchars($formData['past_medical_history'] ?? '') ?></textarea>
                        </div>
                        <div class="cp-profile-form-group mb-3">
                            <label class="cp-label">Past Surgical History</label>
                            <textarea name="past_surgical_history" class="cp-profile-input" rows="3"
                                <?= $isReadOnly ? 'readonly' : '' ?>><?= htmlspecialchars($formData['past_surgical_history'] ?? '') ?></textarea>
                        </div>
                        <div class="cp-profile-form-group mb-4">
                            <label class="cp-label">Other Diseases / Conditions</label>
                            <textarea name="other_diseases" class="cp-profile-input" rows="3" <?= $isReadOnly ? 'readonly' : '' ?>><?= htmlspecialchars($formData['other_diseases'] ?? '') ?></textarea>
                        </div>
                    <?php endif; ?>

                    <?php if ($type === 'sworn'): ?>
                        <div class="cp-divider mb-5"></div>
                        <h5 class="mb-3">C. Immediate Relations</h5>
                        <p class="cp-text-muted mb-4" style="font-size:0.9rem;">Spouse, children, or siblings (Required for Sworn Legal Statement).</p>

                        <div id="relations-container">
                            <?php
                            $relNames = $formData['relations_name'] ?? [''];
                            $relRels = $formData['relations_rel'] ?? [''];
                            $relNics = $formData['relations_nic'] ?? [''];
                            $count = max(count($relNames), 1);

                            for ($i = 0; $i < $count; $i++):
                                ?>
                                <div class="relation-entry mb-4 p-4 rounded-xl border border-slate-200 bg-slate-50/30">
                                    <div class="cp-grid-3">
                                        <div class="cp-profile-form-group">
                                            <label class="cp-label">Full Name</label>
                                            <input type="text" name="relations_name[]"
                                                class="cp-profile-input"
                                                value="<?= htmlspecialchars($relNames[$i] ?? '') ?>" required
                                                <?= $isReadOnly ? 'readonly' : '' ?>>
                                        </div>
                                        <div class="cp-profile-form-group">
                                            <label class="cp-label">Relationship</label>
                                            <input type="text" name="relations_rel[]"
                                                class="cp-profile-input"
                                                value="<?= htmlspecialchars($relRels[$i] ?? '') ?>" required
                                                <?= $isReadOnly ? 'readonly' : '' ?>>
                                        </div>
                                        <div class="cp-profile-form-group">
                                            <label class="cp-label">NIC Number</label>
                                            <input type="text" name="relations_nic[]"
                                                class="cp-profile-input"
                                                value="<?= htmlspecialchars($relNics[$i] ?? '') ?>" required
                                                <?= $isReadOnly ? 'readonly' : '' ?>>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <?php if (!$isReadOnly): ?>
                            <button type="button" class="cp-btn cp-btn--sm cp-btn--outline w-full mb-4 py-3"
                                onclick="addRelationRow()">
                                <i class="fas fa-plus-circle mr-2"></i> Add Another Relation
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>            <!-- Footer Actions -->
            <div class="cp-form-footer-alt mt-8">
                <div style="flex: 1;">
                    <a href="<?= ROOT ?>/custodian/documents" class="cp-btn-back-alt">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Checklist
                    </a>
                </div>
                
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <button type="button" 
                            onclick="openDocumentModal('<?= ROOT ?>/custodian/print-document?type=<?= urlencode($type) ?>', '<?= $type === 'sworn' ? 'Sworn Statement' : 'Cadaver Data Sheet' ?> Preview')" 
                            class="cp-btn-preview-alt">
                        <i class="fas fa-eye mr-2"></i> Preview Official Form
                    </button>

                    <?php if ($isReadOnly): ?>
                        <a href="<?= ROOT ?>/custodian/document-form?type=<?= urlencode($type) ?>&edit=1" class="cp-btn-edit-alt">
                            <i class="fas fa-edit mr-2"></i> Edit Information
                        </a>
                    <?php else: ?>
                        <button type="submit" class="cp-btn-save-alt">
                            <i class="fas fa-save mr-2"></i> Save Information
                        </button>
                    <?php endif; ?>
                </div>
            </div>
>
        </form>
    </div>
</div>

<?php include __DIR__ . '/partials/recognition-viewer.php'; ?>


<script>
    function addRelationRow() {
        const container = document.getElementById('relations-container');
        const html = `
        <div class="relation-entry mb-4 p-4 rounded-xl border border-slate-200 bg-slate-50/30">
            <div class="cp-grid-3">
                <div class="cp-profile-form-group">
                    <label class="cp-label">Full Name</label>
                    <input type="text" name="relations_name[]" class="cp-profile-input" required>
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">Relationship</label>
                    <input type="text" name="relations_rel[]" class="cp-profile-input" required>
                </div>
                <div class="cp-profile-form-group">
                    <label class="cp-label">NIC Number</label>
                    <input type="text" name="relations_nic[]" class="cp-profile-input" required>
                </div>
            </div>
        </div>
    `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>