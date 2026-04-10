<?php
/**
 * Custodian Portal Document Checklist Component
 * Simplified for Single Document Bundle Upload
 */
$isBody = (($consent['donation_type'] ?? '') === 'BODY' || ($consent['donation_type'] ?? '') === 'BODY_AND_CORNEA');

$hoursSinceDeath = 0;
$isTimeout = false;
$requiresEmbalming = false;

if ($activeCase && !empty($activeCase->date_of_death) && !empty($activeCase->time_of_death)) {
    $now = new DateTime();
    $deathDateTime = new DateTime($activeCase->date_of_death . ' ' . $activeCase->time_of_death);
    $interval = $now->diff($deathDateTime);
    $hoursSinceDeath = ($interval->days * 24) + $interval->h + ($interval->i / 60);

    if ($isBody) {
        if ($hoursSinceDeath > 48) { $isTimeout = true; }
        if ($hoursSinceDeath > 8) { $requiresEmbalming = true; }
    }
}

$isACCEPTED = $currentInstRequest && $currentInstRequest->institution_status === 'ACCEPTED';
$bundleStatus = $activeCase ? $activeCase->bundle_status : 'PENDING';
?>

<style>
.cp-inline-alert { display: flex; align-items: flex-start; padding: 12px 16px; border-radius: 8px; margin-bottom: 12px; font-size: 0.875rem; line-height: 1.4; border: 1px solid transparent; gap: 12px; }
.cp-inline-alert i { font-size: 1.25rem; margin-top: 2px; }
.cp-inline-alert strong { font-size: 0.9rem; display: block; margin-bottom: 4px; }
.cp-alert-success { background-color: #f0fdf4; border-color: #dcfce7; color: #166534; }
.cp-alert-success i { color: #16a34a; }
.cp-alert-locked { background-color: #fffbeb; border-color: #fef3c7; color: #92400e; }
.cp-alert-locked i { color: #d97706; }
.cp-alert-danger { background-color: #fef2f2; border-color: #fee2e2; color: #991b1b; }
.cp-alert-danger i { color: #dc2626; }

/* Modern Buttons */
.modern-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 500; transition: all 0.2s ease; cursor: pointer; border: 1px solid transparent; text-decoration: none; outline: none; }
.modern-btn-primary { background: #2563eb; color: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.modern-btn-disabled { background: #f1f5f9; color: #94a3b8; border-color: #e2e8f0; cursor: not-allowed; }

/* Modern Cards */
.modern-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 1rem; }
.modern-card-header { background: #f8fafc; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px; font-weight: 600; color: #0f172a; font-size: 1.05rem; }
.modern-card-body { padding: 20px; }

/* Checkbox Styles */
.cp-checklist-item { display: flex; gap: 12px; margin-bottom: 14px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; align-items: center; transition: background 0.2s; }
.cp-checklist-item:hover { background: #f8fafc; }
.cp-checklist-item.conditional { background: #fffbeb; border-color: #fde68a; }
.cp-checkbox { width: 20px; height: 20px; accent-color: #2563eb; cursor: pointer; }
.cp-label { cursor: pointer; font-weight: 500; color: #1e293b; font-size: 0.95rem; margin: 0; display: flex; flex-direction: column; }
.cp-label span { font-size: 0.8rem; color: #64748b; font-weight: 400; margin-top: 2px; }

/* Yes/No Question Styles */
.cp-question-card { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 12px; overflow: hidden; }
.cp-question-header { padding: 16px; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; }
.cp-question-text { font-weight: 500; font-size: 0.95rem; }
</style>

<div class="mb-4">
    <div style="margin-top: 2rem; padding: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 2rem;">
        <h4 style="margin-bottom: 1rem; color: #0f172a; font-size: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
            Physical Body Acceptance Status
        </h4>
        <div style="display: flex; align-items: center; gap: 12px;">
            <?php 
                $examStatus = $currentInstRequest->final_exam_status ?? 'AWAITING';
                $statusClass = $examStatus === 'ACCEPTED' ? 'cp-alert-success' : ($examStatus === 'REJECTED' ? 'cp-alert-danger' : 'cp-alert-locked');
                $statusIcon = $examStatus === 'ACCEPTED' ? 'fa-check-double' : ($examStatus === 'REJECTED' ? 'fa-times-circle' : 'fa-clock');
            ?>
            <div class="cp-inline-alert <?= $statusClass ?>" style="width: 100%; margin-bottom: 0;">
                <i class="fas <?= $statusIcon ?>"></i>
                <div>
                    <strong><?= str_replace('_', ' ', $examStatus) ?></strong>
                    <p>
                        <?php if ($examStatus === 'AWAITING'): ?>
                            The medical school will perform a physical examination once the body is delivered to the institution.
                        <?php elseif ($examStatus === 'ACCEPTED'): ?>
                            The physical body has been formally accepted. Certificate issuance is now authorized.
                        <?php else: ?>
                            The body was not accepted for the following reason: <?= htmlspecialchars($currentInstRequest->final_exam_reason ?? 'No reason provided') ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($bundleStatus === 'SUBMITTED'): ?>
        <div class="cp-inline-alert cp-alert-success" style="margin-bottom: 24px;">
            <i class="fas fa-paper-plane"></i>
            <div>
                <strong>Successfully Submitted</strong>
                <p>Your document bundle has been sent to the medical school and is currently pending review.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($isACCEPTED && !$isTimeout): ?>

        <form action="<?= ROOT ?>/custodian/submit-bundle" method="POST" enctype="multipart/form-data" id="bundleSubmitForm" onchange="checkFormValidity()" oninput="checkFormValidity()">
            
            <div class="modern-card">
                <div class="modern-card-header">
                    <i class="fas fa-list-check" style="color: #2563eb;"></i> 1. Document Checklist
                </div>
                <div class="modern-card-body">
                    <p style="color: #475569; font-size: 0.9rem; margin-bottom: 8px;">
                        First, fill out the required system forms below. This will generate the PDFs you need to print, sign, and include in your final bundle. You must fill out the Sworn Statement first, as it links directly with the Cadaver Data Sheet.
                    </p>

                    <div style="display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: wrap;">
                        <a href="<?= ROOT ?>/custodian/document-form?type=sworn" class="modern-btn" style="background:#e0e7ff; color:#1e40af; border: 1px solid #c7d2fe;">
                            <i class="fas fa-pen-nib"></i> Step 1: <?= empty($hasSworn) ? 'Fill' : 'Edit & Re-print' ?> Sworn Statement
                        </a>
                        <?php if ($isBody): ?>
                            <?php if (!empty($hasSworn)): ?>
                                <a href="<?= ROOT ?>/custodian/document-form?type=datasheet" class="modern-btn" style="background:#fce7f3; color:#3730a3; border: 1px solid #e0e7ff;">
                                    <i class="fas fa-file-medical"></i> Step 2: Fill / Edit Cadaver Data Sheet
                                </a>
                            <?php else: ?>
                                <button type="button" class="modern-btn modern-btn-disabled" onclick="alert('Please complete Step 1: Sworn Statement first!')">
                                    <i class="fas fa-lock"></i> Step 2: Fill Cadaver Data Sheet
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <p style="color: #475569; font-size: 0.9rem; margin-bottom: 16px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                        Once you have generated your PDFs above, combine them with your other required documents (below), and tick each box to confirm they are physically included in your combined ZIP or PDF file bundle.
                    </p>
                    
                    <label class="cp-checklist-item">
                        <input type="checkbox" class="cp-checkbox required-doc" required>
                        <div class="cp-label">Death Certificate <span>Official legal copy required by the government.</span></div>
                    </label>
                    <label class="cp-checklist-item">
                        <input type="checkbox" class="cp-checkbox required-doc" required>
                        <div class="cp-label">Custodian NIC / ID Copy <span>Clear scan of your authoritative ID.</span></div>
                    </label>
                    <?php if (!empty($hasSworn)): ?>
                    <label class="cp-checklist-item">
                        <input type="checkbox" class="cp-checkbox required-doc" required id="cb_sworn">
                        <div class="cp-label">Custodian Declaration / Sworn Statement <span>Consent confirmation logic.</span></div>
                    </label>
                    <?php endif; ?>

                    <label class="cp-checklist-item">
                        <input type="checkbox" class="cp-checkbox required-doc" required id="cb_mcody">
                        <div class="cp-label">Medical Certificate / Cause of Death <span>Provided by the attending physician.</span></div>
                    </label>

                    <?php if ($isBody): ?>
                        <?php if (!empty($hasDatasheet)): ?>
                        <label class="cp-checklist-item">
                            <input type="checkbox" class="cp-checkbox required-doc" required id="cb_cadaver">
                            <div class="cp-label">Cadaver Data Sheet / Body Condition Sheet <span>I confirm I have filled out the Cadaver Sheet data in the system and included it.</span></div>
                        </label>
                        <?php endif; ?>
                        <?php if ($requiresEmbalming): ?>
                            <label class="cp-checklist-item">
                                <input type="checkbox" class="cp-checkbox required-doc" required id="cb_embalm">
                                <div class="cp-label">Embalming Certificate <span>Required because more than 8 hours have passed since death.</span></div>
                            </label>
                        <?php endif; ?>
                    <?php else: ?>
                        <label class="cp-checklist-item">
                            <input type="checkbox" class="cp-checkbox required-doc" required id="cb_organ">
                            <div class="cp-label">Urgent Medical Reports <span>Vital organ status data.</span></div>
                        </label>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Conditional Section -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <i class="fas fa-question-circle" style="color: #d97706;"></i> 2. Special Condition Documents
                </div>
                <div class="modern-card-body">
                    <!-- Q1 -->
                    <div class="cp-question-card">
                        <div class="cp-question-header">
                            <div class="cp-question-text">Was the death due to an accident or unnatural cause?</div>
                            <div>
                                <label style="margin-right: 10px;"><input type="radio" name="q1" value="yes" onchange="toggleCondition('q1_doc', true)"> Yes</label>
                                <label><input type="radio" name="q1" value="no" onchange="toggleCondition('q1_doc', false)" checked> No</label>
                            </div>
                        </div>
                        <div id="q1_doc" style="display: none; padding: 12px; background: #fffbeb; border-top: 1px solid #fde68a;">
                            <label class="cp-checklist-item" style="border: none; background: transparent; padding: 0; margin: 0;">
                                <input type="checkbox" class="cp-checkbox cond-doc">
                                <div class="cp-label">Police Report <span>Included in my bundle file.</span></div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Q2 -->
                    <div class="cp-question-card">
                        <div class="cp-question-header">
                            <div class="cp-question-text">Was a post-mortem conducted?</div>
                            <div>
                                <label style="margin-right: 10px;"><input type="radio" name="q2" value="yes" onchange="toggleCondition('q2_doc', true)"> Yes</label>
                                <label><input type="radio" name="q2" value="no" onchange="toggleCondition('q2_doc', false)" checked> No</label>
                            </div>
                        </div>
                        <div id="q2_doc" style="display: none; padding: 12px; background: #fffbeb; border-top: 1px solid #fde68a;">
                            <label class="cp-checklist-item" style="border: none; background: transparent; padding: 0; margin: 0;">
                                <input type="checkbox" class="cp-checkbox cond-doc">
                                <div class="cp-label">Post-Mortem Report <span>Included in my bundle file.</span></div>
                            </label>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="cp-question-card">
                        <div class="cp-question-header">
                            <div class="cp-question-text">Was the donor infected with COVID-19 or any infectious disease?</div>
                            <div>
                                <label style="margin-right: 10px;"><input type="radio" name="q3" value="yes" onchange="toggleCondition('q3_doc', true)"> Yes</label>
                                <label><input type="radio" name="q3" value="no" onchange="toggleCondition('q3_doc', false)" checked> No</label>
                            </div>
                        </div>
                        <div id="q3_doc" style="display: none; padding: 12px; background: #fffbeb; border-top: 1px solid #fde68a;">
                            <label class="cp-checklist-item" style="border: none; background: transparent; padding: 0; margin: 0;">
                                <input type="checkbox" class="cp-checkbox cond-doc">
                                <div class="cp-label">Negative COVID-19 (RT-PCR) Report <span>Included in my bundle file.</span></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single File Upload & Submit -->
            <div class="modern-card">
                <div class="modern-card-body text-center p-4">
                    <h4 class="mb-3" style="font-weight: 600; color: #0f172a;"><i class="fas fa-file-zipper" style="color: #2563eb;"></i> 3. Upload Full Document Bundle</h4>
                    <p style="color: #475569; font-size: 0.95rem; margin-bottom: 20px;">
                        Merge all ticked documents into a single PDF, or compress them into a ZIP file.
                    </p>
                    
                    <input type="file" name="bundle_file" accept=".zip,.pdf,.rar" class="cp-form-control" style="max-width: 400px; margin: 0 auto 20px auto; padding: 10px;" required>

                    <?php if ($bundleStatus !== 'SUBMITTED'): ?>
                        <div id="validationSummary" class="mb-4" style="text-align: left; background: #fff5f5; border: 1px solid #feb2b2; padding: 15px; border-radius: 8px; display: none;">
                            <h5 style="color: #c53030; font-size: 0.9rem; margin-bottom: 8px;"><i class="fas fa-exclamation-triangle"></i> Missing Requirements:</h5>
                            <ul id="missingItemsList" style="font-size: 0.85rem; color: #742a2a; padding-left: 20px;"></ul>
                        </div>

                        <button type="submit" id="finalSubmitBtn" class="modern-btn modern-btn-primary" style="font-size: 1.1rem; padding: 12px 30px;" disabled title="All items must be checked and file uploaded to submit">
                            <i class="fas fa-paper-plane"></i> Final Submit
                        </button>
                    <?php else: ?>

                        <button type="button" class="modern-btn modern-btn-disabled" disabled style="font-size: 1.1rem; padding: 12px 30px;">
                            <i class="fas fa-check-double"></i> Bundle Already Submitted
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
function toggleCondition(id, show) {
    const el = document.getElementById(id);
    if (!el) return;
    const cb = el.querySelector('.cond-doc');
    if (show) {
        el.style.display = 'block';
        if (cb) cb.required = true;
    } else {
        el.style.display = 'none';
        if (cb) {
            cb.required = false;
            cb.checked = false;
        }
    }
    checkFormValidity();
}

function checkFormValidity() {
    const form = document.getElementById('bundleSubmitForm');
    const submitBtn = document.getElementById('finalSubmitBtn');
    const summary = document.getElementById('validationSummary');
    const list = document.getElementById('missingItemsList');
    if (!form || !submitBtn) return;

    let missing = [];

    // 1. Check System forms first if missing from view
    <?php if (empty($hasSworn)): ?>
        missing.push("Complete the Sworn Statement (Step 1)");
    <?php endif; ?>
    <?php if ($isBody && empty($hasDatasheet)): ?>
        missing.push("Complete the Cadaver Data Sheet (Step 2)");
    <?php endif; ?>

    // 2. Check checkboxes
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(function(cb) {
        if (cb.required && !cb.checked) {
            const label = cb.closest('.cp-checklist-item').querySelector('.cp-label').firstChild.textContent.trim();
            missing.push("Tick: " + label);
        }
    });

    // 3. Check File
    const fileInput = form.querySelector('input[type="file"]');
    if (!fileInput || !fileInput.value) {
        missing.push("Upload the complete ZIP/PDF file bundle");
    }

    if (missing.length === 0) {
        submitBtn.removeAttribute('disabled');
        submitBtn.classList.remove('modern-btn-disabled');
        if (summary) summary.style.display = 'none';
    } else {
        submitBtn.setAttribute('disabled', 'disabled');
        submitBtn.classList.add('modern-btn-disabled');
        if (summary) {
            summary.style.display = 'block';
            list.innerHTML = '';
            missing.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item;
                list.appendChild(li);
            });
        }
    }
}

// Call on load to ensure proper initial state
document.addEventListener('DOMContentLoaded', function() {
    // Sync initial state of conditions
    ['q1', 'q2', 'q3'].forEach(name => {
        const radios = document.getElementsByName(name);
        radios.forEach(r => {
            if (r.checked) toggleCondition(name + '_doc', r.value === 'yes');
        });
    });
    
    checkFormValidity();
});

</script>
