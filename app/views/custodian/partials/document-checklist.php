<?php
/**
 * Custodian Portal Document Checklist Component
 * Premium Overhaul: Step-by-Step Mandatory Workflow
 */

// [CONSOLIDATED LOGIC]
$hoursSinceDeath = 0;
$requiresEmbalming = false;
$donationType = 'BODY'; // Default

if ($activeCase) {
    // 1. Time Calculation
    if (!empty($death_declaration->time_of_death)) {
        $now = time();
        $deathTs = strtotime($death_declaration->time_of_death);
        $hoursSinceDeath = ($now - $deathTs) / 3600;
    }

    // 2. Donation Type Normalization (Bridge Legacy Flags)
    $mode = $activeCase->resolved_deceased_mode;
    $track = $activeCase->resolved_operational_track;
    
    if (str_contains($mode, 'BODY')) {
        $donationType = str_contains($mode, 'CORNEA') ? 'BODY_AND_CORNEA' : 'BODY';
    } elseif ($track === 'HOSPITAL_TISSUE' || str_contains($mode, 'ORGAN')) {
        $donationType = 'ORGAN';
    }

    // 3. Embalming Rule
    if ($donationType !== 'ORGAN' && $hoursSinceDeath > 8) {
        $requiresEmbalming = true;
    }
}

$anyAccepted = false;
if (isset($allInstitutionStatuses)) {
    foreach ($allInstitutionStatuses as $st) {
        if (($st->institution_status ?? '') === 'ACCEPTED' || ($st->request_status ?? '') === 'ACCEPTED') {
            $anyAccepted = true;
            break;
        }
    }
}
$isACCEPTED = $anyAccepted || ($currentInstRequest && (($currentInstRequest->institution_status ?? '') === 'ACCEPTED' || ($currentInstRequest->request_status ?? '') === 'ACCEPTED'));
$bundleStatus = $activeCase ? ($activeCase->bundle_status ?? 'PENDING') : 'PENDING';
$isBody = ($donationType === 'BODY' || $donationType === 'BODY_AND_CORNEA');

// Determine if the checklist should be interactive or hidden based on review status
$docStatus = $currentInstRequest->document_status ?? 'NOT_STARTED';
$isUnderReview = ($docStatus === 'PENDING_REVIEW');
$isReviewCompleted = ($docStatus === 'ACCEPTED');
$showChecklist = !$isUnderReview && !$isReviewCompleted;
?>

<style>
/* Base Portal Variables (Aligned with Donor Profile) */
:root {
    --cp-blue-50: #f0f9ff;
    --cp-blue-100: #e0f2fe;
    --cp-blue-600: #0284c7;
    --cp-blue-700: #0369a1;
    --cp-gray-50: #f8fafc;
    --cp-gray-200: #e2e8f0;
    --cp-gray-500: #64748b;
    --cp-gray-800: #1e293b;
    --cp-accent: #2563eb;
    --cp-success: #16a34a;
    --cp-warning: #d97706;
}

/* Step Mechanism */
.cp-step { 
    opacity: 0.4; 
    pointer-events: none; 
    transition: all 0.4s ease;
    filter: grayscale(80%);
}
.cp-step--active { 
    opacity: 1; 
    pointer-events: auto; 
    filter: none;
}
.cp-step-num {
    width: 28px;
    height: 28px;
    background: var(--cp-gray-200);
    color: var(--cp-gray-500);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
    flex-shrink: 0;
}
.cp-step--active .cp-step-num {
    background: var(--cp-accent);
    color: white;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
}

/* Modern Checklist Cards */
.cp-checklist-card {
    background: white;
    border: 1px solid var(--cp-gray-200);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.cp-checklist-header {
    padding: 14px 20px;
    background: var(--cp-gray-50);
    border-bottom: 1px solid var(--cp-gray-200);
    display: flex;
    align-items: center;
    gap: 12px;
}
.cp-checklist-header h4 {
    margin: 0;
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--cp-gray-800);
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.cp-checklist-body { padding: 20px; }

/* Interactive List Items */
.cp-item-row {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 16px;
    border-radius: 10px;
    border: 1px solid transparent;
    transition: all 0.2s;
    margin-bottom: 8px;
}
.cp-item-row:hover {
    background: var(--cp-blue-50);
    border-color: var(--cp-blue-100);
}
.cp-item-row.locked { opacity: 0.6; cursor: not-allowed; }

.cp-custom-checkbox {
    width: 22px;
    height: 22px;
    border: 2px solid var(--cp-gray-200);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}
input[type="checkbox"]:checked + .cp-custom-checkbox {
    background: var(--cp-success);
    border-color: var(--cp-success);
}
.cp-custom-checkbox i { color: white; display: none; font-size: 0.75rem; }
input[type="checkbox"]:checked + .cp-custom-checkbox i { display: block; }

.cp-item-info { flex: 1; }
.cp-item-title { font-weight: 600; font-size: 0.9rem; color: var(--cp-gray-800); display: block; }
.cp-item-desc { font-size: 0.75rem; color: var(--cp-gray-500); display: block; }

/* Buttons & Links */
.compact-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.2s;
    border: 1px solid transparent;
}
.compact-action-btn--primary {
    background: var(--cp-accent);
    color: white !important;
}
.compact-action-btn--primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
.compact-action-btn--outline {
    background: white;
    color: var(--cp-accent) !important;
    border-color: var(--cp-accent);
}
.compact-action-btn--disabled {
    background: #f1f5f9;
    color: #94a3b8 !important;
    border-color: #e2e8f0;
    pointer-events: none;
}

@keyframes cp-pulse-red {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.02); box-shadow: 0 0 15px rgba(239, 68, 68, 0.3); }
    100% { opacity: 1; transform: scale(1); }
}

.cp-banner--high-alert {
    animation: cp-pulse-red 2s infinite ease-in-out;
    border-color: #f87171 !important;
    background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%) !important;
}
</style>

<div class="cp-workflow-container">

    <!-- CLINICAL DEADLINE TIMER -->
    <?php if ($activeCase && !$isReviewCompleted && isset($clinical_deadline)): ?>
        <div class="cp-banner <?= $is_expired ? 'cp-banner--expired' : 'cp-banner--timer' ?>" 
             style="background: <?= $is_expired ? 'linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%)' : 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)' ?>; 
                    border: 1px solid <?= $is_expired ? '#fecaca' : '#fde68a' ?>; 
                    border-left: 5px solid <?= $is_expired ? '#ef4444' : '#d97706' ?>; 
                    padding: 18px 24px; border-radius: 12px; margin-bottom: 24px;
                    display: flex; align-items: center; justify-content: space-between;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; background: <?= $is_expired ? '#ef4444' : '#d97706' ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem;">
                    <i class="fas <?= $is_expired ? 'fa-calendar-times' : 'fa-hourglass-half' ?>"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 800; font-size: 0.95rem; color: <?= $is_expired ? '#991b1b' : '#92400e' ?>; text-transform: uppercase; letter-spacing: 0.02em;">
                        <?= $is_expired ? 'Clinical Window Expired' : 'Clinical Deadline Approaching' ?>
                    </h4>
                    <p style="margin: 4px 0 0 0; font-size: 0.85rem; font-weight: 500; color: <?= $is_expired ? '#b91c1c' : '#b45309' ?>;">
                        <?= $is_expired ? 'The clinical window for recovery has closed. Documentation can no longer be accepted.' : 'Documentation must be accepted by the institution within the clinical window.' ?>
                    </p>
                </div>
            </div>
            <?php if (!$is_expired): ?>
                <div class="timer-display" style="text-align: right;">
                    <div id="clinicalTimer" style="font-family: 'Courier New', monospace; font-size: 1.8rem; font-weight: 900; color: #92400e;">
                        --:--:--
                    </div>
                    <span style="font-size: 0.65rem; font-weight: 800; color: #b45309; text-transform: uppercase;">Time Remaining</span>
                </div>
                <script>
                    (function() {
                        let seconds = <?= (int)($seconds_remaining ?? 0) ?>;
                        const display = document.getElementById('clinicalTimer');
                        const banner = display.closest('.cp-banner');
                        
                        function update() {
                            if (seconds <= 0) {
                                location.reload();
                                return;
                            }
                            
                            if (seconds < 3600) { // < 1 hour
                                banner.classList.add('cp-banner--high-alert');
                                display.style.color = '#ef4444';
                            }

                            const h = Math.floor(seconds / 3600);
                            const m = Math.floor((seconds % 3600) / 60);
                            const s = seconds % 60;
                            display.textContent = 
                                h.toString().padStart(2, '0') + ':' + 
                                m.toString().padStart(2, '0') + ':' + 
                                s.toString().padStart(2, '0');
                            seconds--;
                        }
                        update();
                        setInterval(update, 1000);
                    })();
                </script>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- STATUS BANNERS (KEEP EXISTING LOGIC) -->
    <div id="statusBanners">
        <?php if ($activeCase && $currentInstRequest && $currentInstRequest->document_status === 'REJECTED'): ?>
            <div class="cp-banner cp-banner--danger" style="background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%); border: 1px solid #fecaca; border-left: 5px solid #e11d48; padding: 20px; border-radius: 12px; margin-bottom: 24px;">
                <div style="display: flex; gap: 16px;">
                    <div style="width: 44px; height: 44px; background: #e11d48; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="color: #9f1239; margin-bottom: 4px; font-weight: 700;">Corrections Required</h4>
                        
                        <?php if ($currentInstRequest->rejection_reason_code === 'DOCS_MISSING'): ?>
                            <p style="color: #9f1239; font-size: 0.9rem; font-weight: 600; margin-bottom: 8px;">Missing or Invalid Documents:</p>
                            <?php $missingItems = json_decode($currentInstRequest->missing_documents_json, true) ?? []; ?>
                            <?php if (!empty($missingItems)): ?>
                                <ul style="margin: 0; padding-left: 20px; color: #e11d48; font-size: 0.85rem; margin-bottom: 12px;">
                                    <?php foreach ($missingItems as $item): ?>
                                        <li style="margin-bottom: 4px;"><strong><?= htmlspecialchars($item) ?></strong></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (!empty($currentInstRequest->rejection_reason_text)): ?>
                             <p style="color: #4b5563; font-size: 0.9rem; background: rgba(255,255,255,0.7); padding: 10px; border-radius: 6px; border: 1px dashed #fca5a5; margin-bottom: 12px;">
                                  <strong>Faculty Notes & Instructions:</strong><br/>
                                  <?= nl2br(htmlspecialchars($currentInstRequest->rejection_reason_text)) ?>
                             </p>
                        <?php else: ?>
                             <p style="color: #4b5563; font-size: 0.9rem; margin-bottom: 12px;">The institution has requested updates to your document bundle.</p>
                        <?php endif; ?>

                        <p style="margin: 0; font-weight: 600; color: #e11d48; font-size: 0.8rem;"><i class="fas fa-redo-alt"></i> Please review Step 2 & 3 below, re-upload the corrected files, and submit.</p>
                    </div>
                </div>
            </div>
        <?php elseif ($currentInstRequest && $currentInstRequest->document_status === 'ACCEPTED' && $currentInstRequest->final_exam_status !== 'ACCEPTED'): ?>
             <!-- Accepted banner logic remains similar but in premium style -->
             <div class="cp-banner" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #bae6fd; border-left: 5px solid #0284c7; padding: 20px; border-radius: 12px; margin-bottom: 24px;">
                <div style="display: flex; gap: 16px;">
                     <div style="width: 44px; height: 44px; background: #0284c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-truck-loading"></i>
                    </div>
                    <div>
                        <h4 style="color: #075985; margin: 0; font-weight: 700;">Documents Approved</h4>
                        <p style="color: #0c4a6e; font-size: 0.9rem; margin-top: 4px;">Proceed with handover at <strong><?= date('g:i A', strtotime($currentInstRequest->handover_time)) ?></strong> on <strong><?= date('M j, Y', strtotime($currentInstRequest->handover_date)) ?></strong>.</p>
                    </div>
                </div>
             </div>
        <?php elseif ($currentInstRequest && $currentInstRequest->document_status === 'PENDING_REVIEW'): ?>
            <div class="cp-banner" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0; border-left: 5px solid #16a34a; padding: 20px; border-radius: 12px; margin-bottom: 24px;">
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div style="width: 44px; height: 44px; background: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-file-export"></i>
                    </div>
                    <div>
                        <h4 style="color: #166534; margin: 0 0 4px 0; font-weight: 700;">Documents Under Review</h4>
                        <p style="color: #15803d; margin: 0; font-size: 0.9rem;">Your document bundle has been successfully submitted. The medical school will now verify the files.</p>
                    </div>
                </div>
            </div>
        <?php elseif ($currentInstRequest && $currentInstRequest->institution_status === 'PENDING'): ?>
            <div class="cp-banner" style="background: #fffbeb; border: 1px solid #fef3c7; border-left: 5px solid #d97706; padding: 20px; border-radius: 12px; margin-bottom: 24px;">
                <div style="display: flex; gap: 16px; align-items: center;">
                    <div style="width: 44px; height: 44px; background: #d97706; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h4 style="color: #92400e; margin: 0 0 4px 0; font-weight: 700;">Awaiting Institution Response</h4>
                        <p style="color: #b45309; margin: 0; font-size: 0.9rem;">You can prepare your documents below, but submission will only be enabled after the medical school accepts the case.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- MAIN WORKFLOW STEPS -->
    <div id="workflowSteps" class="<?= (!$isACCEPTED || $is_expired) ? 'cp-workflow--prep' : '' ?>">

        <!-- STEP 1: LEGAL AFFIDAVITS (Always visible for printing, but simplified if submitted) -->
        <div class="cp-step cp-step--active" id="step1">
            <div class="cp-checklist-card">
                <div class="cp-checklist-header">
                    <div class="cp-step-num">1</div>
                    <h4>Legal Affidavits & System Forms</h4>
                </div>
                <div class="cp-checklist-body">
                    <?php if ($showChecklist): ?>
                        <p style="font-size: 0.85rem; color: var(--cp-gray-500); margin-bottom: 1.5rem;">Fill out these digital forms first. They generate the core legal documents required by the institution.</p>
                    <?php else: ?>
                        <p style="font-size: 0.85rem; color: var(--cp-gray-500); margin-bottom: 1.5rem;">Your digital forms are completed. You can re-print them here if needed for physical handover.</p>
                    <?php endif; ?>
                    
                    <div class="cp-item-row" style="background: var(--cp-blue-50); border: 1px solid var(--cp-blue-100);">
                        <div class="cp-item-info">
                            <span class="cp-item-title">Sworn Statement & Declaration</span>
                            <span class="cp-item-desc">Confirm your identity and the donor's consent legally.</span>
                        </div>
                        <?php if (!$isACCEPTED || $is_expired): ?>
                            <button class="compact-action-btn compact-action-btn--disabled" style="background: #e2e8f0; color: #64748b;" title="<?= $is_expired ? 'Clinical window expired' : 'Waiting for institutional acceptance' ?>">
                                <i class="fas <?= $is_expired ? 'fa-calendar-times' : 'fa-lock' ?>"></i> <?= $is_expired ? 'Expired' : 'Locked' ?>
                            </button>
                        <?php else: ?>
                            <a href="<?= ROOT ?>/custodian/document-form?type=sworn" class="compact-action-btn <?= empty($hasSworn) ? 'compact-action-btn--primary' : 'compact-action-btn--outline' ?>">
                                <i class="fas <?= empty($hasSworn) ? ($isLeader ? 'fa-pen-nib' : 'fa-eye') : 'fa-check-circle' ?>"></i> <?= empty($hasSworn) ? ($isLeader ? 'Fill Form' : 'View') : 'View & Print' ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if ($isBody): ?>
                    <div class="cp-item-row <?= (empty($hasSworn) && $isACCEPTED) ? 'locked' : '' ?>" style="background: var(--cp-blue-50); border: 1px solid var(--cp-blue-100);">
                        <div class="cp-item-info">
                            <span class="cp-item-title">Cadaver Data Sheet</span>
                            <span class="cp-item-desc">Vital medical facts about the deceased.</span>
                        </div>
                        <?php if (!$isACCEPTED || $is_expired): ?>
                            <button class="compact-action-btn compact-action-btn--disabled" style="background: #e2e8f0; color: #64748b;" title="<?= $is_expired ? 'Clinical window expired' : 'Waiting for institutional acceptance' ?>">
                                <i class="fas <?= $is_expired ? 'fa-calendar-times' : 'fa-lock' ?>"></i> <?= $is_expired ? 'Expired' : 'Locked' ?>
                            </button>
                        <?php elseif (empty($hasSworn)): ?>
                            <button class="compact-action-btn compact-action-btn--disabled"><i class="fas fa-lock"></i> Locked</button>
                        <?php else: ?>
                            <a href="<?= ROOT ?>/custodian/document-form?type=datasheet" class="compact-action-btn <?= empty($hasDatasheet) ? 'compact-action-btn--primary' : 'compact-action-btn--outline' ?>">
                                <i class="fas <?= empty($hasDatasheet) ? ($isLeader ? 'fa-file-medical' : 'fa-eye') : 'fa-check-circle' ?>"></i> <?= empty($hasDatasheet) ? ($isLeader ? 'Fill Form' : 'View') : 'View & Print' ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php if ($showChecklist): ?>
        <!-- STEP 2: PHYSICAL DOCUMENTS CHECKLIST -->
        <?php 
            $canProceedToStep2 = $hasSworn && ($isBody ? $hasDatasheet : true);
        ?>
        <div class="cp-step <?= ($canProceedToStep2 || !$isACCEPTED) ? 'cp-step--active' : '' ?>" id="step2">
            <div class="cp-checklist-card">
                <div class="cp-checklist-header">
                    <div class="cp-step-num">2</div>
                    <h4>Physical Document Bundle Checklist</h4>
                </div>
                <div class="cp-checklist-body">
                    <p style="font-size: 0.85rem; color: var(--cp-gray-500); margin-bottom: 1.5rem;">Please gather and tick all items below. These MUST be included in your final bundle folder.</p>
                    
                    <form id="checklistForm">
                        <?php 
                        if ($donationType === 'ORGAN') {
                            $checklistItems = [
                                ['name' => 'donor_id', 'title' => 'Donor Identification Document', 'desc' => 'NIC, Passport or Birth Certificate of the donor.', 'show' => true],
                                ['name' => 'organ_consent', 'title' => 'Organ Donation Consent Record', 'desc' => 'The signed pledge or mandate from the central registry.', 'show' => true],
                                ['name' => 'death_confirmation', 'title' => 'Death Confirmation Record', 'desc' => 'Official medical confirmation of death (MCCOD).', 'show' => true],
                                ['name' => 'custodian_id', 'title' => 'Custodian Identification Document', 'desc' => 'Your NIC or Passport for identity verification.', 'show' => true]
                            ];
                        } else {
                            $checklistItems = [
                                ['name' => 'printout_sworn', 'title' => 'Sworn Statement (Physical Printout)', 'desc' => 'Hand-signed and stamped by JP.', 'show' => $hasSworn, 'bg' => 'var(--cp-blue-50)', 'border' => 'var(--cp-blue-100)'],
                                ['name' => 'printout_datasheet', 'title' => 'Cadaver Data Sheet (Physical Printout)', 'desc' => 'Medical facts sheet completed in Step 1.', 'show' => $hasDatasheet, 'bg' => 'var(--cp-blue-50)', 'border' => 'var(--cp-blue-100)'],
                                ['name' => 'death_cert', 'title' => 'Original Death Certificate', 'desc' => 'Official legal copy issued by the Registrar.', 'show' => true],
                                ['name' => 'custodian_nic', 'title' => 'Custodian NIC / ID Copy', 'desc' => 'Front and back scan of your identification.', 'show' => true],
                                ['name' => 'mccod', 'title' => 'Medical Certificate of Cause of Death (MCCOD)', 'desc' => 'The specific hospital report indicating cause.', 'show' => true],
                                ['name' => 'embalming', 'title' => 'Embalming Certificate', 'desc' => 'Required as more than 8 hours have passed since death.', 'show' => $requiresEmbalming, 'bg' => '#fffbeb', 'border' => '#fde68a']
                            ];
                        }

                        foreach ($checklistItems as $item): 
                            if (!$item['show']) continue;
                            $bg = $item['bg'] ?? 'var(--cp-blue-50)';
                            $border = $item['border'] ?? 'var(--cp-blue-100)';
                        ?>
                        <label class="cp-item-row" style="background: <?= $bg ?>; border: 1px solid <?= $border ?>;">
                            <?php if (!$isACCEPTED): ?>
                                <div class="cp-item-info">
                                    <span class="cp-item-title"><?= $item['title'] ?></span>
                                    <span class="cp-item-desc"><?= $item['desc'] ?></span>
                                </div>
                                <button type="button" class="compact-action-btn compact-action-btn--disabled" style="background: #e2e8f0; color: #64748b;">
                                    <i class="fas fa-lock"></i> Locked
                                </button>
                            <?php else: ?>
                                <input type="checkbox" class="required-check d-none" name="<?= $item['name'] ?>" <?= !$isLeader ? 'disabled' : '' ?>>
                                <div class="cp-custom-checkbox" style="<?= !$isLeader ? 'cursor: default;' : '' ?>"><i class="fas fa-check"></i></div>
                                <div class="cp-item-info">
                                    <span class="cp-item-title"><?= $item['title'] ?></span>
                                    <span class="cp-item-desc"><?= $item['desc'] ?></span>
                                </div>
                                <?php if (($item['name'] === 'printout_sworn' || $item['name'] === 'printout_datasheet') && !$isLeader): ?> 
                                    <span class="cp-badge">View Only</span> 
                                <?php endif; ?>
                            <?php endif; ?>
                        </label>
                        <?php endforeach; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- STEP 3: SPECIAL CONDITIONS / ADDITIONAL DOCUMENTS -->
        <div class="cp-step <?= ($canProceedToStep2 || !$isACCEPTED) ? 'cp-step--active' : '' ?>" id="step3">
            <div class="cp-checklist-card">
                <div class="cp-checklist-header">
                    <div class="cp-step-num">3</div>
                    <h4><?= ($donationType === 'ORGAN') ? '🟡 Additional Documents' : 'Special Cases & Conditions' ?></h4>
                </div>
                <div class="cp-checklist-body">
                    <div class="cp-question-wrapper">
                        <?php if ($donationType === 'ORGAN'): ?>
                            <!-- ORGAN SPECIFIC ADDITIONAL DOCS -->
                            <div class="cp-question-row mb-4">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <p style="font-weight: 600; font-size: 0.9rem; margin: 0;">Are there any of the following available ?</p>
                                </div>
                                
                                <?php 
                                $organExtra = [
                                    ['id' => 'medical_summary', 'title' => 'Medical Information Summary', 'desc' => 'Summary of past medical history.'],
                                    ['id' => 'police_report', 'title' => 'Police Report', 'desc' => 'Required for accidental or legal cases.'],
                                    ['id' => 'medico_legal', 'title' => 'Medico-Legal Clearance', 'desc' => 'Legal permission for organ retrieval.'],
                                    ['id' => 'hospital_records', 'title' => 'Hospital Medical Records', 'desc' => 'Complete patient file from the clinical ward.']
                                ];
                                ?>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                                    <?php foreach ($organExtra as $ext): ?>
                                        <div class="cp-item-row" style="background: var(--cp-blue-50); border: 1px solid var(--cp-blue-100); margin-bottom: 0;">
                                            <input type="checkbox" class="extra-check d-none" data-id="<?= $ext['id'] ?>" id="ext_<?= $ext['id'] ?>" <?= !$isLeader ? 'disabled' : '' ?>>
                                            <div class="cp-custom-checkbox" style="<?= !$isLeader ? 'cursor: default;' : '' ?>"><i class="fas fa-check"></i></div>
                                            <div class="cp-item-info">
                                                <span class="cp-item-title" style="font-size: 0.85rem;"><?= $ext['title'] ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- BODY SPECIFIC SPECIAL CONDITIONS -->
                            <div class="cp-question-row mb-4">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <p style="font-weight: 600; font-size: 0.9rem; margin: 0;">Was the death due to an accident or unnatural cause?</p>
                                    <?php if (!$isACCEPTED): ?>
                                        <button type="button" class="compact-action-btn compact-action-btn--disabled" style="background: #e2e8f0; color: #64748b;">
                                            <i class="fas fa-lock"></i> Locked
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <?php if ($isACCEPTED): ?>
                                    <div style="display: flex; gap: 20px;">
                                        <label><input type="radio" name="q_accident" value="yes" onclick="toggleExtraDoc('accident', true)" <?= !$isLeader ? 'disabled' : '' ?>> Yes</label>
                                        <label><input type="radio" name="q_accident" value="no" onclick="toggleExtraDoc('accident', false)" checked <?= !$isLeader ? 'disabled' : '' ?>> No</label>
                                    </div>
                                    <div id="extra_accident" style="display: none; margin-top: 10px;">
                                        <label class="cp-item-row" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                                            <input type="checkbox" class="extra-check d-none" data-id="police_report" <?= !$isLeader ? 'disabled' : '' ?>>
                                            <div class="cp-custom-checkbox" style="<?= !$isLeader ? 'cursor: default;' : '' ?>"><i class="fas fa-check"></i></div>
                                            <div class="cp-item-info">
                                                <span class="cp-item-title">Police Report</span>
                                                <span class="cp-item-desc">Mandatory for unnatural deaths.</span>
                                            </div>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="cp-question-row mb-4">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <p style="font-weight: 600; font-size: 0.9rem; margin: 0;">Was a post-mortem examination conducted?</p>
                                    <?php if (!$isACCEPTED): ?>
                                        <button type="button" class="compact-action-btn compact-action-btn--disabled" style="background: #e2e8f0; color: #64748b;">
                                            <i class="fas fa-lock"></i> Locked
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <?php if ($isACCEPTED): ?>
                                    <div style="flex: 1; display: flex; gap: 20px;">
                                        <label><input type="radio" name="q_postmortem" value="yes" onclick="toggleExtraDoc('postmortem', true)"> Yes</label>
                                        <label><input type="radio" name="q_postmortem" value="no" onclick="toggleExtraDoc('postmortem', false)" checked> No</label>
                                    </div>
                                    <div id="extra_postmortem" style="display: none; margin-top: 10px;">
                                        <label class="cp-item-row" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                                            <input type="checkbox" class="extra-check d-none" data-id="pm_report" <?= !$isLeader ? 'disabled' : '' ?>>
                                            <div class="cp-custom-checkbox" style="<?= !$isLeader ? 'cursor: default;' : '' ?>"><i class="fas fa-check"></i></div>
                                            <div class="cp-item-info">
                                                <span class="cp-item-title">Post-Mortem Report</span>
                                                <span class="cp-item-desc">Signed report from the pathologist.</span>
                                            </div>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="cp-question-row">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <p style="font-weight: 600; font-size: 0.9rem; margin: 0;">Was the donor infected with COVID-19 or any infectious disease?</p>
                                    <?php if (!$isACCEPTED): ?>
                                        <button type="button" class="compact-action-btn compact-action-btn--disabled" style="background: #e2e8f0; color: #64748b;">
                                            <i class="fas fa-lock"></i> Locked
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <?php if ($isACCEPTED): ?>
                                    <div style="display: flex; gap: 20px;">
                                        <label><input type="radio" name="q_infectious" value="yes" onclick="toggleExtraDoc('infectious', true)" <?= !$isLeader ? 'disabled' : '' ?>> Yes</label>
                                        <label><input type="radio" name="q_infectious" value="no" onclick="toggleExtraDoc('infectious', false)" checked <?= !$isLeader ? 'disabled' : '' ?>> No</label>
                                    </div>
                                    <div id="extra_infectious" style="display: none; margin-top: 10px;">
                                        <label class="cp-item-row" style="background: #fef2f2; border: 1px solid #fecaca;">
                                            <input type="checkbox" class="extra-check d-none" data-id="infectious_report" <?= !$isLeader ? 'disabled' : '' ?>>
                                            <div class="cp-custom-checkbox" style="<?= !$isLeader ? 'cursor: default;' : '' ?>"><i class="fas fa-check"></i></div>
                                            <div class="cp-item-info">
                                                <span class="cp-item-title">Negative COVID-19 (RT-PCR) / Infectious Disease Report</span>
                                                <span class="cp-item-desc">Mandatory confirmation for documented infectious risks.</span>
                                            </div>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 4: FINAL BUNDLE UPLOAD -->
        <div class="cp-step <?= $canProceedToStep2 ? 'cp-step--active' : '' ?>" id="step4">
            <div class="cp-checklist-card">
                <div class="cp-checklist-header">
                    <div class="cp-step-num">4</div>
                    <h4>Upload Combined Bundle Folder</h4>
                </div>
                <div class="cp-checklist-body text-center">
                    <p style="font-size: 0.9rem; color: var(--cp-gray-500); margin-bottom: 1.5rem;">Merge all your ticked documents and generated PDFs into a single <strong>ZIP</strong> or <strong>PDF</strong> file.</p>
                    
                    <form action="<?= ROOT ?>/custodian/submit-bundle" method="POST" enctype="multipart/form-data" id="finalBundleForm">
                        <div style="max-width: 400px; margin: 0 auto;">
                            <?php if ($isLeader): ?>
                                <div style="border: 2px dashed var(--cp-gray-200); padding: 20px; border-radius: 12px; background: var(--cp-gray-50);">
                                    <input type="file" name="bundle_file" accept=".zip,.pdf" id="bundleFileInput" style="width: 100%; border:0; background:transparent;" onchange="updateWorkflowState()">
                                    <p style="font-size: 0.75rem; color: var(--cp-gray-500); margin-top: 10px;">ZIP or PDF only. (Max 25MB)</p>
                                </div>
                            <?php else: ?>
                                <div style="padding: 2.5rem; border-radius: 12px; background: #f8fafc; border: 1px dashed #e2e8f0; color: #64748b;">
                                    <i class="fas fa-lock fa-3x mb-3 opacity-20"></i>
                                    <p class="cp-text-sm">Upload restricted to management.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

        <!-- STEP 5: FINAL REVIEW & SUBMISSION -->
        <div class="cp-step <?= $canProceedToStep2 ? 'cp-step--active' : '' ?>" id="step5">
            <div class="cp-checklist-card" style="border-top: 5px solid var(--cp-accent);">
                <div class="cp-checklist-header">
                    <div class="cp-step-num">5</div>
                    <h4>Review & Submit</h4>
                </div>
                <div class="cp-checklist-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                        <div>
                            <p style="font-weight: 700; font-size: 1.1rem; color: var(--cp-gray-800); margin-bottom: 4px;">
                                <span id="completeCount">0</span> / <span id="totalCount">0</span> Items Verified
                            </p>
                            <p id="submissionHelpText" style="font-size: 0.85rem; color: var(--cp-gray-500); margin: 0;">Please account for all mandatory items above.</p>
                        </div>

                        <div id="actionContainer">
                            <?php if ($is_expired): ?>
                                <button class="compact-action-btn compact-action-btn--disabled" style="padding: 14px 40px; font-size: 1rem; background: #fee2e2; color: #ef4444 !important; border: 1px solid #fecaca;">
                                    <i class="fas fa-calendar-times"></i> Window Expired
                                </button>
                            <?php elseif (!$isACCEPTED): ?>
                                <button class="compact-action-btn compact-action-btn--disabled" style="padding: 14px 40px; font-size: 1rem;">
                                    <i class="fas fa-lock"></i> Awaiting Acceptance
                                </button>
                            <?php elseif ($currentInstRequest && $currentInstRequest->document_status === 'ACCEPTED'): ?>
                                 <button class="compact-action-btn compact-action-btn--primary" style="padding: 14px 40px; font-size: 1rem; background: var(--cp-success);" disabled>
                                    <i class="fas fa-check-double"></i> Documents Approved
                                </button>
                            <?php else: ?>
                                <button type="button" class="compact-action-btn compact-action-btn--disabled" id="finalSubmitBtn" style="padding: 14px 40px; font-size: 1rem;" onclick="submitFinalBundle()">
                                    <i class="fas fa-paper-plane mr-2"></i> <span id="submitBtnText">Complete Prerequisites</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- SUMMARY SECTION WHEN SUBMITTED -->
        <div class="cp-step cp-step--active">
            <div class="cp-checklist-card">
                <div class="cp-checklist-header">
                    <i class="fas fa-clipboard-check cp-text-success"></i>
                    <h4>Submission Summary</h4>
                </div>
                <div class="cp-checklist-body">
                    <div style="background: <?= $isReviewCompleted ? '#f0fdf4' : 'var(--cp-gray-50)' ?>; padding: 20px; border-radius: 12px; border: 1px solid <?= $isReviewCompleted ? '#bbf7d0' : 'var(--cp-gray-200)' ?>;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--cp-success);"></div>
                            <span style="font-weight: 700; color: <?= $isReviewCompleted ? '#166534' : 'var(--cp-gray-800)' ?>;">
                                <?= $isReviewCompleted ? 'Bundle Verified & Accepted' : 'Bundle Files Uploaded' ?>
                            </span>
                        </div>
                        <p style="font-size: 0.9rem; color: <?= $isReviewCompleted ? '#15803d' : 'var(--cp-gray-600)' ?>; line-height: 1.6;">
                            <?php if ($isReviewCompleted): ?>
                                The medical school has successfully verified and accepted your document bundle. All legal prerequisites are complete.
                            <?php else: ?>
                                Your documentation bundle has been securely transmitted. You can no longer make changes until the medical school reviews the files. 
                                <strong>If the submission is rejected</strong>, you will be notified here with instructions on what to correct.
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if ($isReviewCompleted): ?>
                        <div style="margin-top: 24px; animation: slideDown 0.4s ease;">
                             <!-- Modern Header Strip -->
                             <div style="display: flex; align-items: center; justify-content: space-between; background: #166534; color: white; padding: 10px 18px; border-radius: 12px 12px 0 0;">
                                 <h5 style="margin: 0; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                     <i class="fas fa-handshake mr-2"></i> Final Handover Directive
                                 </h5>
                                 <span style="background: <?= ($currentInst->final_exam_status === 'ACCEPTED') ? '#22c55e' : 'rgba(255,255,255,0.2)' ?>; padding: 4px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; color: white; box-shadow: <?= ($currentInst->final_exam_status === 'ACCEPTED') ? '0 2px 4px rgba(0,0,0,0.1)' : 'none' ?>;">
                                     STATUS: <?= ($currentInst->final_exam_status === 'ACCEPTED') ? 'DONATION SUCCESSFUL' : 'EXAMINATION PENDING' ?>
                                 </span>
                             </div>

                             <div style="background: white; border: 1px solid #166534; border-top: none; padding: 20px; border-radius: 0 0 12px 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                                 
                                 <?php if ($currentInst->final_exam_status === 'ACCEPTED'): ?>
                                      <!-- PREMIUM SUCCESS SECTION -->
                                      <div class="cp-completion-banner">
                                          <div class="cp-completion-icon">
                                              <i class="fas fa-check"></i>
                                          </div>
                                          <h4 class="cp-completion-title">Anatomical Donation Successfully Completed</h4>
                                          <p class="cp-completion-text">
                                              The whole body donation process for <strong><?= htmlspecialchars($donor->first_name) ?></strong> has been formally finalized by <strong><?= htmlspecialchars($currentInst->institution_name) ?></strong>.
                                          </p>

                                          <div class="cp-completion-actions">
                                              <?php 
                                                 $certUrl = !empty($certificates) ? ROOT . "/medical-school/certificates/view?id=" . $certificates[0]->id : '';
                                                 $letterUrl = !empty($appreciation_letters) ? ROOT . "/medical-school/appreciation/view?id=" . $appreciation_letters[0]->id : '';
                                              ?>
                                              <button onclick="openRecognitionBundle('<?= $certUrl ?>', '<?= $letterUrl ?>')" class="cp-btn-bundle">
                                                  <i class="fas fa-certificate text-warning"></i>
                                                  View Certificate
                                              </button>
                                          </div>
                                      </div>
                                 <?php endif; ?>

                                 <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                                     <!-- Sleek Appointment Card -->
                                     <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 15px; border-radius: 12px; opacity: <?= ($currentInst->final_exam_status === 'ACCEPTED') ? '0.6' : '1' ?>;">
                                         <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #15803d; text-transform: uppercase; margin-bottom: 8px;">Scheduled Handover</span>
                                         <div style="display: flex; align-items: center; gap: 12px;">
                                             <div style="width: 36px; height: 36px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #166534; box-shadow: 0 2px 4px rgba(0,0,0,0.03);">
                                                <i class="fas fa-calendar-check"></i>
                                             </div>
                                             <div>
                                                 <div style="font-weight: 800; font-size: 0.95rem; color: #166534;"><?= date('M j, Y', strtotime($currentInstRequest->handover_date)) ?></div>
                                                 <div style="font-weight: 600; font-size: 0.8rem; color: #15803d;"><?= date('g:i A', strtotime($currentInstRequest->handover_time)) ?></div>
                                             </div>
                                         </div>
                                     </div>

                                     <!-- Emergency Coordination Card -->
                                     <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 12px;">
                                         <span style="display: block; font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Emergency Coordination</span>
                                         <div style="display: flex; flex-direction: column; gap: 4px;">
                                             <?php if (!empty($currentInst->contact_phone)): ?>
                                                 <div style="font-size: 0.85rem; font-weight: 700; color: #334155;"><i class="fas fa-phone mr-2 text-primary"></i> <?= htmlspecialchars($currentInst->contact_phone) ?></div>
                                             <?php endif; ?>
                                             <?php if (!empty($currentInst->contact_email)): ?>
                                                 <div style="font-size: 0.75rem; font-weight: 600; color: #64748b;"><i class="fas fa-envelope mr-2 text-primary"></i> <?= htmlspecialchars($currentInst->contact_email) ?></div>
                                             <?php endif; ?>
                                         </div>
                                     </div>
                                 </div>

                                 <?php if ($currentInst->final_exam_status !== 'ACCEPTED'): ?>
                                     <div style="background: #fffbeb; border: 1px solid #fef3c7; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                                         <p style="font-size: 0.85rem; color: #92400e; margin: 0; line-height: 1.5;">
                                             <strong>Handover Instructions:</strong> Please bring the body and all physical document copies to the <strong><?= htmlspecialchars($currentInst->institution_name ?? 'Faculty') ?></strong>. Final physical examination will be conducted upon arrival.
                                         </p>
                                         <?php if (!empty($currentInstRequest->handover_message)): ?>
                                             <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #fde68a;">
                                                 <p style="font-size: 0.8rem; color: #b45309; font-style: italic; margin: 0;">"<?= nl2br(htmlspecialchars($currentInstRequest->handover_message)) ?>"</p>
                                             </div>
                                         <?php endif; ?>
                                     </div>
                                 <?php endif; ?>

                                 <!-- Location Footer -->
                                 <div style="display: flex; align-items: center; gap: 10px; padding: 0 5px;">
                                     <i class="fas fa-location-dot" style="color: #64748b; font-size: 0.8rem;"></i>
                                     <span style="font-size: 0.8rem; color: #64748b; font-weight: 500;"><?= htmlspecialchars($currentInst->institution_address ?? 'Contact Faculty for exact location.') ?></span>
                                 </div>
                             </div>
                             
                             <!-- LEGAL RULES & CONDITIONS ACCORDION -->
                             <div style="background: white; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden;">
                                 <div style="padding: 12px 16px; background: #f8fafc; border-bottom: 1px solid #cbd5e1; font-weight: 800; color: #334155; font-size: 0.9rem;">
                                     <i class="fas fa-scale-balanced mr-2"></i> Body Donation – Rules & Conditions
                                 </div>
                                 <div style="padding: 20px; font-size: 0.8rem; color: #475569; max-height: 350px; overflow-y: auto; line-height: 1.6;">
                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">1. Legal and Consent Requirements</strong>
                                     Body donation is accepted only with the consent of the legal custodian. The custodian is responsible for the entire handover process. The intention to donate the body must be clearly stated in the official death documentation.<br><br>
                                     
                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">2. Coordination with Faculty</strong>
                                     The custodian must contact the relevant faculty or department and follow the instructions provided before bringing the body for donation.<br><br>

                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">3. Mandatory Documentation</strong>
                                     The following documents must be provided at the time of handover:
                                     <ul style="margin: 5px 0 10px 0; padding-left: 20px;">
                                         <li>Original Death Certificate or Notice of Death</li>
                                         <li>Affidavit of Legal Custodian (certified by a Justice of Peace)</li>
                                         <li>Copy of Custodian’s National Identity Card (certified)</li>
                                         <li>Required medical or health reports as specified</li>
                                     </ul>
                                     Incomplete or incorrect documentation will result in rejection. All submitted documents will be retained and will not be returned.<br><br>

                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">4. Body Preparation Requirements</strong>
                                     The body must be properly prepared according to accepted medical standards. No unauthorized cutting, damage, or disfigurement of the body is permitted prior to handover.<br><br>

                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">5. Conditions for Acceptance</strong>
                                     The faculty reserves the right to refuse acceptance of a body if it is not suitable for medical education or research purposes. This includes, but is not limited to:
                                     <ul style="margin: 5px 0 10px 0; padding-left: 20px;">
                                         <li>Decomposed bodies</li>
                                         <li>Infectious conditions</li>
                                         <li>Bodies with major surgical damage or open wounds</li>
                                         <li>Bodies that have undergone post-mortem examination</li>
                                     </ul>

                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">6. Transport and Logistics</strong>
                                     The faculty does not provide transportation. The custodian must arrange all transport and related services. Any coffin or casket used to transport the body must be removed from the premises after handover.<br><br>

                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">7. Finality of Donation</strong>
                                     Once the body is accepted by the faculty, the donation is final and irreversible. The body will not be returned under any circumstances, and no parts of the body will be returned at a later stage.<br><br>

                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">8. Restrictions After Handover</strong>
                                     After the body is handed over:
                                     <ul style="margin: 5px 0 10px 0; padding-left: 20px;">
                                         <li>No viewing or access to the body will be permitted</li>
                                         <li>No ceremonies or respects may be conducted at the faculty</li>
                                         <li>No requests for preservation or return will be entertained</li>
                                     </ul>

                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">9. Use of Donated Body</strong>
                                     The body may be used for medical education, research, and related academic purposes. The faculty may preserve and utilize the body or its parts in accordance with institutional requirements.<br><br>

                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">10. Rejection Handling</strong>
                                     If the body is not accepted, the custodian is responsible for immediately removing it from the premises. The faculty holds no responsibility after rejection.<br><br>

                                     <strong style="color: #1e293b; display: block; margin-bottom: 5px;">11. Payments</strong>
                                     No payment is required for body donation. Custodians must not provide any payment or benefit to staff members under any circumstances.<br><br>

                                     <div style="margin-top: 15px; padding: 15px; background: #f1f5f9; border-left: 4px solid #94a3b8; border-radius: 4px;">
                                         <strong style="color: #1e293b;">Final Acknowledgement:</strong> By proceeding with the donation, the custodian confirms that all conditions have been understood and accepted, and that the donation is made in full compliance with the applicable requirements.
                                     </div>
                                 </div>
                             </div>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    </div>
</div>

<?php require __DIR__ . '/recognition-viewer.php'; ?>

<script>
/**
 * MISSION CRITICAL: Checklist Interactivity & Submission Engine
 */
function toggleExtraDoc(type, show) {
    const el = document.getElementById('extra_' + type);
    if (!el) return;
    el.style.display = show ? 'block' : 'none';
    updateWorkflowState();
}

function updateWorkflowState() {
    const mandatoryChecks = document.querySelectorAll('.required-check');
    const extraChecks = document.querySelectorAll('.extra-check');
    const bundleInput = document.getElementById('bundleFileInput');
    const submitBtn = document.getElementById('finalSubmitBtn');
    const submitText = document.getElementById('submitBtnText');

    let totalRequired = mandatoryChecks.length;
    let completedCount = 0;

    // 1. Mandatory Items (Step 2)
    mandatoryChecks.forEach(ch => {
        if (ch.checked) completedCount++;
    });

    // 2. Conditional Special Items (Step 3)
    extraChecks.forEach(ch => {
        const parent = ch.closest('[id^="extra_"]');
        if (parent && parent.style.display !== 'none') {
            totalRequired++;
            if (ch.checked) completedCount++;
        }
    });

    // 3. UI Update
    const countEl = document.getElementById('completeCount');
    const totalEl = document.getElementById('totalCount');
    if (countEl) countEl.innerText = completedCount;
    if (totalEl) totalEl.innerText = totalRequired;

    // 4. Submission Button Logic
    const allDocsChecked = (completedCount >= totalRequired);
    const fileSelected = bundleInput && bundleInput.files.length > 0;

    if (allDocsChecked && fileSelected) {
        submitBtn.classList.remove('compact-action-btn--disabled');
        submitBtn.classList.add('compact-action-btn--primary');
        submitText.innerText = "Submit Case Bundle";
    } else {
        submitBtn.classList.add('compact-action-btn--disabled');
        if (submitBtn.classList.contains('compact-action-btn--primary')) {
            submitBtn.classList.remove('compact-action-btn--primary');
        }
        
        if (!allDocsChecked) submitText.innerText = "Complete Prerequisites";
        else if (!fileSelected) submitText.innerText = "Select Bundle File";
    }
}

function submitFinalBundle() {
    const bundleInput = document.getElementById('bundleFileInput');
    if (!bundleInput || bundleInput.files.length === 0) {
        alert("Please select your consolidated ZIP/PDF bundle before submitting.");
        return;
    }

    const form = document.getElementById('finalBundleForm');
    const existingHidden = form.querySelectorAll('input[name^="docs["]');
    existingHidden.forEach(h => h.remove());

    const allChecked = document.querySelectorAll('input.required-check:checked, input.extra-check:checked');
    allChecked.forEach(ch => {
        const extraParent = ch.closest('[id^="extra_"]');
        if (extraParent && extraParent.style.display === 'none') return;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'docs[]';
        input.value = ch.getAttribute('data-id') || ch.name;
        form.appendChild(input);
    });

    form.submit();
}

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('required-check') || e.target.classList.contains('extra-check') || e.target.type === 'file') {
        updateWorkflowState();
    }
});

// Initialize on Load
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", updateWorkflowState);
} else {
    updateWorkflowState();
}
</script>
