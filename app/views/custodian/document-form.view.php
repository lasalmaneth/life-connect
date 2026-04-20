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

                    <h5 class="mb-3">
                        <i class="fas <?= $type === 'sworn' ? 'fa-user-check' : 'fa-hand-holding-heart' ?> mr-2" style="color: var(--blue-500);"></i>
                        <?= $type === 'sworn' ? 'A. Declarant Information' : 'A. Person Handing Over the Cadaver' ?>
                    </h5>
                    <p class="cp-text-muted mb-3" style="font-size:0.9rem;">
                        <?= $type === 'sworn' ? 'Verify your details as the legal declarant for this sworn statement.' : 'Fill in the details of the individual physically handing over the donor body.' ?>
                    </p>
                    
                    <div class="cp-profile-grid mb-4">
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Full Name <?= $type === 'sworn' ? '<i class="fas fa-lock ml-1 cp-lock-icon" title="Locked to Custodian"></i>' : '' ?></label>
                            <input type="text" name="custodian_name" class="cp-profile-input <?= $type === 'sworn' ? 'cp-input-locked' : '' ?>"
                                value="<?= htmlspecialchars($formData['custodian_name'] ?? '') ?>"
                                required <?= ($isReadOnly || $type === 'sworn') ? 'readonly' : '' ?>>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">NIC No <?= $type === 'sworn' ? '<i class="fas fa-lock ml-1 cp-lock-icon" title="Locked to Custodian"></i>' : '' ?></label>
                            <input type="text" name="custodian_nic" class="cp-profile-input <?= $type === 'sworn' ? 'cp-input-locked' : '' ?>"
                                value="<?= htmlspecialchars($formData['custodian_nic'] ?? '') ?>"
                                pattern="[0-9]{9}[vVxX]|[0-9]{12}" title="Please enter a valid Sri Lankan NIC (9 digits + V/X or 12 digits)"
                                required <?= ($isReadOnly || $type === 'sworn') ? 'readonly' : '' ?>>
                        </div>
                    </div>

                    <div class="cp-profile-grid mb-4">
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Relationship to Donor <?= $type === 'sworn' ? '<i class="fas fa-lock ml-1 cp-lock-icon" title="Locked to Custodian"></i>' : '' ?></label>
                            <input type="text" name="custodian_relationship" class="cp-profile-input <?= $type === 'sworn' ? 'cp-input-locked' : '' ?>"
                                value="<?= htmlspecialchars($formData['custodian_relationship'] ?? '') ?>"
                                required <?= ($isReadOnly || $type === 'sworn') ? 'readonly' : '' ?>>
                        </div>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Telephone No <?= $type === 'sworn' ? '<i class="fas fa-lock ml-1 cp-lock-icon" title="Locked to Custodian"></i>' : '' ?></label>
                            <input type="text" name="custodian_phone" class="cp-profile-input <?= $type === 'sworn' ? 'cp-input-locked' : '' ?>"
                                value="<?= htmlspecialchars($formData['custodian_phone'] ?? '') ?>"
                                pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number"
                                required <?= ($isReadOnly || $type === 'sworn') ? 'readonly' : '' ?>>
                        </div>
                    </div>

                    <div class="cp-profile-form-group mb-5">
                        <label class="cp-label">Address <?= $type === 'sworn' ? '<i class="fas fa-lock ml-1 cp-lock-icon" title="Locked to Custodian"></i>' : '' ?></label>
                        <input type="text" name="custodian_address" class="cp-profile-input <?= $type === 'sworn' ? 'cp-input-locked' : '' ?>"
                            value="<?= htmlspecialchars($formData['custodian_address'] ?? '') ?>"
                            required <?= ($isReadOnly || $type === 'sworn') ? 'readonly' : '' ?>>
                    </div>

                    <div class="cp-divider mb-5"></div>

                    <h5 class="mb-3">
                        <i class="fas fa-id-card mr-2" style="color: var(--blue-500);"></i>
                        B. Donor Background Info
                    </h5>
                    <div class="cp-profile-grid mb-4">
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Occupation at time of death <i class="fas fa-lock ml-1 cp-lock-icon" title="Official Record"></i></label>
                            <input type="text" name="occupation" class="cp-profile-input cp-input-locked"
                                value="<?= htmlspecialchars($formData['occupation'] ?? '') ?>" required
                                readonly>
                        </div>
                        <?php if ($type === 'datasheet'): ?>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">Race <i class="fas fa-lock ml-1 cp-lock-icon" title="Official Record"></i></label>
                                <input type="text" name="race" class="cp-profile-input cp-input-locked"
                                    value="<?= htmlspecialchars($formData['race'] ?? '') ?>" required readonly>
                            </div>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">Religion <i class="fas fa-lock ml-1 cp-lock-icon" title="Official Record"></i></label>
                                <input type="text" name="donor_religion" class="cp-profile-input cp-input-locked"
                                    value="<?= htmlspecialchars($formData['donor_religion'] ?? '') ?>"
                                    required readonly>
                            </div>
                            <div class="cp-profile-form-group">
                                <label class="cp-label">Place of Birth & District</label>
                                <input type="text" name="birth_place" class="cp-profile-input"
                                    value="<?= htmlspecialchars($formData['birth_place'] ?? '') ?>" required
                                    <?= $isReadOnly ? 'readonly' : '' ?>>
                            </div>
                        <?php endif; ?>
                        <div class="cp-profile-form-group">
                            <label class="cp-label">Place of Death <i class="fas fa-lock ml-1 cp-lock-icon" title="Locked from Death Report"></i></label>
                            <input type="text" name="place_of_death" class="cp-profile-input cp-input-locked"
                                value="<?= htmlspecialchars($formData['place_of_death'] ?? '') ?>" required
                                readonly>
                        </div>
                    </div>

                    <?php if ($type === 'datasheet'): ?>
                        <div class="cp-divider mb-5"></div>
                        <h5 class="mb-3"><i class="fas fa-file-medical mr-2" style="color: var(--blue-500);"></i> C. Medical History</h5>
                        <p class="cp-text-muted mb-3" style="font-size:0.9rem;">Pre-loaded from donor clinical records. Non-editable to ensure research accuracy.</p>
                        
                        <div class="cp-profile-form-group mb-3">
                            <label class="cp-label">Past Medical History <i class="fas fa-lock ml-1 cp-lock-icon"></i></label>
                            <textarea name="past_medical_history" class="cp-profile-input cp-input-locked" rows="3"
                                readonly><?= htmlspecialchars($formData['past_medical_history'] ?? 'No significant medical history reported.') ?></textarea>
                        </div>
                        <div class="cp-profile-form-group mb-3">
                            <label class="cp-label">Past Surgical History <i class="fas fa-lock ml-1 cp-lock-icon"></i></label>
                            <textarea name="past_surgical_history" class="cp-profile-input cp-input-locked" rows="3"
                                readonly><?= htmlspecialchars($formData['past_surgical_history'] ?? 'No significant surgical history reported.') ?></textarea>
                        </div>
                        <div class="cp-profile-form-group mb-4">
                            <label class="cp-label">Other Diseases / Conditions <i class="fas fa-lock ml-1 cp-lock-icon"></i></label>
                            <textarea name="other_diseases" class="cp-profile-input cp-input-locked" rows="3" readonly><?= htmlspecialchars($formData['other_diseases'] ?? 'None identified.') ?></textarea>
                        </div>
                    <?php endif; ?>

                    <?php if ($type === 'sworn'): ?>
                        <div class="cp-divider mb-5"></div>
                        <h5 class="mb-3"><i class="fas fa-users-viewfinder mr-2" style="color: var(--blue-500);"></i> C. Immediate Relations</h5>
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
                                                pattern="[0-9]{9}[vVxX]|[0-9]{12}"
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
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/partials/recognition-viewer.php'; ?>

<style>
    .cp-input-locked {
        background-color: #f8fafc !important;
        color: #64748b !important;
        cursor: not-allowed;
        border-color: #e2e8f0 !important;
    }
    .cp-lock-icon {
        font-size: 0.75rem;
        color: #94a3b8;
    }
</style>

<script>
    document.getElementById('doc-form')?.addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            this.reportValidity();
        }
    });

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
                    <input type="text" name="relations_nic[]" class="cp-profile-input" required pattern="[0-9]{9}[vVxX]|[0-9]{12}">
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