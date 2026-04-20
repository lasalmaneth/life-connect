<?php
/**
 * Custodian Portal — Unified Dashboard View
 */
$page_icon     = 'fa-chart-line';
$page_heading  = 'Dashboard';
$page_subtitle = 'The central command center for donor obligations.';
$extra_css     = ['custodian/dashboard.css'];

// ── Guided Wisdom Overlay ─────────────────────────────────────────────
$showOverlay = false;
if ($activeCase && !empty($activeCase->guidance_message)) {
    // Only show if it's the first time in this session or for specific kidney flag
    if (!isset($_SESSION['guidance_shown_' . $activeCase->id])) {
        $showOverlay = true;
        $_SESSION['guidance_shown_' . $activeCase->id] = true;
    }
}

ob_start();
?>

<?php if ($showOverlay): ?>
    <div id="guidanceOverlay" class="cp-guided-overlay active">
        <div class="cp-guided-overlay__card">
            <button class="cp-guided-overlay__close" onclick="closeGuidance()" aria-label="Close guidance">
                <i class="fas fa-times"></i>
            </button>
            <div class="cp-guided-overlay__header">
                <div class="cp-guided-overlay__icon <?= $activeCase->show_kidney_popup ? 'is-urgent' : '' ?>">
                    <i class="fas <?= $activeCase->show_kidney_popup ? 'fa-heart-pulse' : 'fa-lightbulb-on' ?>"></i>
                </div>
                <h2 class="cp-guided-overlay__title">
                    <?= $activeCase->show_kidney_popup ? 'Vital Coordination Required' : 'Guidance for Next Steps' ?>
                </h2>
            </div>
            <div class="cp-guided-overlay__body">
                <p class="cp-guided-overlay__text">
                    <?php if ($activeCase->show_kidney_popup): ?>
                        The donor has been declared brain-dead and has consented to kidney donation. While hospitals may not always initiate this conversation, your support can help ensure the donor’s wishes are honored and may help save a life.
                    <?php else: ?>
                        <?= nl2br(htmlspecialchars($activeCase->guidance_message)) ?>
                    <?php endif; ?>
                </p>
                <?php if ($activeCase->show_kidney_popup): ?>
                    <div class="cp-guided-overlay__alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        Timely coordination with the hospital is important to proceed.
                    </div>
                <?php endif; ?>
            </div>
            <div class="cp-guided-overlay__footer">
                <button class="cp-btn cp-btn--primary cp-btn--fw" onclick="closeGuidance()">Understood, Continue to Dashboard</button>
            </div>
        </div>
    </div>
<?php endif; ?>



<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <!-- 1. TOP SUMMARY CARDS -->
    <?php require __DIR__ . '/partials/status-cards.php'; ?>

    <!-- Workflow Locking Notice -->
    <?php include __DIR__ . '/partials/lock-notice.php'; ?>

    <!-- 2. MAIN LOGIC BLOCKS -->
    <?php if (!$death_declaration): ?>
        
        <!-- STATE: DONOR ALIVE -->
        <div class="cp-action-grid cp-action-grid--single mb-4">
            <div class="cp-report-death-card" onclick="window.location.href='<?= ROOT ?>/custodian/report-death'">
                <div class="cp-report-death-icon">
                    <i class="fas fa-heart-crack"></i>
                </div>
                <div class="cp-report-death-content">
                    <h2>Report Donor Death</h2>
                    <p>If the donor has passed away, you must declare it here immediately to initiate critical donation protocols and save lives.</p>
                    <div class="cp-report-death-btn">
                        Mark Donor as Deceased <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>

        <!-- DECEASED ROUTING -->
        <?php if (!$activeCase): ?>
             <!-- STATE: DONOR MARKED DECEASED (PENDING SYSTEM RESOLUTION) -->
             <div class="cp-section-card mb-4 cp-border-warning shadow-sm">
                <div class="cp-section-card__header cp-bg-amber-50 cp-text-amber-800">
                    <div class="cp-section-card__title"><i class="fas fa-hourglass-half"></i> System Resolving Protocol</div>
                </div>
                <div class="cp-section-card__body text-center p-5">
                    <i class="fas fa-shield-check fa-4x mb-4 opacity-50 cp-text-amber-300"></i>
                    <h4 class="cp-text-xl cp-font-bold mb-2">Establishing Operational Track</h4>
                    <p class="cp-text-g500 mx-auto max-w-500">Wait a moment while the system creates the legal snapshot for this donor. Refresh if this persists.</p>
                </div>
            </div>
        <?php else: ?>
            
            <?php 
                // [DATA EXTRACTION]
                $snapshotItems = json_decode($activeCase->operational_items_json ?? '[]', true);
                $timeLimits = json_decode($activeCase->operational_time_limits_json ?? '[]', true);
                $track = $activeCase->resolved_operational_track;
                $mode = $activeCase->resolved_deceased_mode;
                
                // [CLINICAL FLAGS]
                $isBrainDead = ($death_declaration->is_brain_dead ?? 0) == 1;
                $hasKidney = str_contains($mode, 'KIDNEY');
                $hasCornea = str_contains($mode, 'CORNEA');
                $hasOther = ($mode === 'ORGAN_ONLY' || $mode === 'ORGANS_PLUS_CORNEA' || $mode === 'KIDNEY_PLUS_OTHERS');
                $hasBody = str_contains($mode, 'BODY');
                
                $deathTs = strtotime($death_declaration->time_of_death ?? 'now');
                $hoursSinceDeath = (time() - $deathTs) / 3600;

                $kidneyDecision = $activeCase->kidney_decision ?? 'PENDING';
                $bodyChoice = $activeCase->body_cornea_decision ?? 'PENDING';

                // [VISIBILITY RULES]
                $corneaId = 4;
                $corneaStatus = $snapshotItems[$corneaId]['status'] ?? 'none';
                $corneaResolved = in_array($corneaStatus, ['completed', 'skipped', 'expired']);
                
                $bodyLockedByCornea = ($hasBody && $hasCornea && $bodyChoice === 'BOTH' && !$corneaResolved);
                
                // Show Hospital Section if Cornea/Other within windows OR decision made
                $canShowHospitalSection = (
                    (!($hasKidney && $isBrainDead && $kidneyDecision === 'PENDING')) && // must decide kidney first if applicable
                    (!($hasBody && $hasCornea && $bodyChoice === 'PENDING')) && // must decide path first if applicable
                    (
                        ($hasCornea && $hoursSinceDeath <= 8) || 
                        ($hasOther && $hoursSinceDeath <= 20) ||
                        ($hasKidney && ($hasCornea || $hasOther) && !$isBrainDead) ||
                        ($kidneyDecision === 'DECLINE' && ($hasCornea || $hasOther)) ||
                        ($kidneyDecision === 'PROCEED')
                    )
                );

                // Show Medical School Section if Body within 48h and NOT locked
                $canShowMedicalSchoolSection = (
                    (!($hasKidney && $isBrainDead && $kidneyDecision === 'PENDING')) && 
                    (!($hasBody && $hasCornea && $bodyChoice === 'PENDING')) &&
                    ($hasBody && $hoursSinceDeath <= 48 && !$bodyLockedByCornea)
                );
                
                // No Action scenario
                $showNoActionMessage = ($hasKidney && !$isBrainDead && !$hasCornea && !$hasOther && !$hasBody);
            ?>

            <!-- [SECTION 1] CLINICAL CASE SUMMARY CARD -->
            <div class="cp-case-summary-card mb-4">
                <div class="cp-case-summary-card__header">
                    <div class="flex items-center gap-3">
                        <div class="cp-case-summary-card__icon"><i class="fas fa-file-medical"></i></div>
                        <div>
                            <h2 class="m-0 cp-text-white cp-text-lg">Case Summary: <?= htmlspecialchars($donor->first_name . ' ' . $donor->last_name) ?></h2>
                            <p class="m-0 cp-text-white opacity-80 cp-text-xs">Case #<?= $activeCase->case_number ?></p>
                        </div>
                    </div>
                </div>
                <div class="cp-case-summary-card__body p-4 bg-white cp-border shadow-sm rounded-bottom">
                    <div class="cp-summary-grid">
                        <div class="summary-item">
                            <span class="cp-label-mini">Time of Death</span>
                            <div class="font-bold cp-text-g800"><?= date('M j, Y — g:i A', $deathTs) ?></div>
                        </div>
                        <div class="summary-item">
                            <span class="cp-label-mini">Brain Death Status</span>
                            <div class="font-bold <?= $isBrainDead ? 'text-success' : 'text-danger' ?>">
                                <?= $isBrainDead ? '<i class="fas fa-check-circle"></i> CONFIRMED' : '<i class="fas fa-times-circle"></i> NOT DECLARED' ?>
                            </div>
                        </div>
                        <div class="summary-item">
                            <span class="cp-label-mini">Case Leader</span>
                            <div class="font-bold cp-text-blue-900"><?= htmlspecialchars($isLeader ? 'You (Lead)' : ($activeCase->declared_by_name ?? 'Reporting Custodian')) ?></div>
                        </div>
                        <div class="summary-item">
                            <span class="cp-label-mini">Active Mode</span>
                            <div class="font-bold cp-text-g800 track-badge"><?= str_replace('_', ' ', $mode) ?></div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t flex items-center justify-between">
                        <div class="flex items-center gap-2 cp-text-sm cp-text-g600">
                            <i class="fas fa-location-dot"></i> Reported at treating hospital facility
                        </div>
                        <div class="next-action-badge px-3 py-1 rounded-full bg-blue-50 text-blue-700 cp-text-xs border border-blue-100 font-bold animate-pulse">
                            <i class="fas fa-arrow-right"></i> Next: 
                            <?php 
                                if ($track === 'DECISION_REQUIRED') echo 'Complete Clinical Track Selection';
                                elseif ($track === 'BODY_CORNEA_DECISION_REQUIRED') echo 'Choose Body / Organ Path';
                                elseif ($track === 'KIDNEY_INFO_ONLY') echo 'Bedside Coordination';
                                elseif ($bodyChoice === 'BOTH' && !$corneaResolved) echo 'Resolve Cornea Recovery First';
                                else echo 'Manage Institutional Requests';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- STEP-BY-STAGE PROGRESS INDICATOR -->
            <?php require __DIR__ . '/partials/stepper.php'; ?>
            
            <div class="cp-dashboard-grid mb-4">

                <!-- [SECTION 2] DONATION OUTCOME & GUIDANCE CARD -->
                <?php if ($mode === 'KIDNEY_ONLY' || ($hasKidney && ($hasCornea || $hasOther)) || $mode === 'BODY_PLUS_CORNEA'): ?>
                <div class="col-span-1 md:col-span-2">
                    
                    <!-- SCENARIO 1/2: KIDNEY ONLY -->
                    <?php if ($mode === 'KIDNEY_ONLY'): ?>
                        <div class="cp-section-card sh-card border-amber-light">
                            <div class="cp-section-card__header cp-bg-amber-100 text-amber-900">
                                <div class="cp-section-card__title"><i class="fas fa-hand-holding-medical"></i> Clinical Outcome: Kidney</div>
                            </div>
                            <div class="cp-section-card__body p-5">
                                <?php if ($isBrainDead): ?>
                                    <div class="flex flex-col gap-4">
                                        <h3 class="m-0 cp-text-amber-900">Coordination Advantage: Kidney</h3>
                                        <p class="cp-text-amber-800 cp-text-sm leading-relaxed">
                                            The donor had already expressed a wish to donate a kidney. Because brain death was declared, kidney donation may be possible through the current treating hospital. Medical staff may not always raise this topic because of the emotional nature of the situation, but the donor’s prior consent allows you to discuss this option with the treating team.
                                        </p>
                                        <div class="p-4 rounded bg-amber-50 border border-amber-200 cp-text-sm cp-text-amber-900 flex items-center gap-3">
                                            <i class="fas fa-info-circle fa-lg"></i>
                                            <div><strong>Next Step:</strong> Coordination happens bedside. No official portal request is required for Kidney recovery.</div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="<?= ROOT ?>/custodian/consent-registry" class="cp-btn cp-btn--secondary cp-btn--sm">View Donor Consent</a>
                                            <button onclick="cpNotify.alert('Hospital Support', 'You can find the treating hospital staff in the facility ward. The ICU team is usually the point of contact.', 'info')" class="cp-btn cp-btn--secondary cp-btn--sm">Coordination Contact Info</button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center p-4">
                                        <i class="fas fa-ban fa-3x mb-3 text-danger opacity-40"></i>
                                        <h3 class="m-0 cp-text-danger">Kidney donation cannot proceed</h3>
                                        <p class="cp-text-g600 cp-text-sm mt-2">
                                            Kidney donation cannot proceed in this case because kidney recovery under this workflow requires brain death confirmation.
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- SCENARIO 3/4: MIXED TRACK (KIDNEY + OTHERS) -->
                    <?php if ($hasKidney && ($hasCornea || $hasOther)): ?>
                        <div class="cp-section-card sh-card border-amber-light mb-4 shadow-sm">
                            <div class="cp-section-card__header cp-bg-amber-100 text-amber-900 font-bold">
                                <div class="cp-section-card__title"><i class="fas fa-balance-scale"></i> Critical Track Decision</div>
                            </div>
                            <div class="cp-section-card__body p-5" style="min-height: 320px; display: flex; flex-direction: column;">
                                <?php if ($isBrainDead): ?>
                                    <?php if ($kidneyDecision === 'PENDING'): ?>
                                        <div style="display: flex; align-items: flex-start; gap: 1.5rem;">
                                            <div style="width: 3.5rem; height: 3.5rem; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem; font-weight: bold; border: 2px solid #fbbf24;">?</div>
                                            <div>
                                                <h4 class="m-0 cp-text-amber-900 mb-2 font-bold">Kidney & Bedside Recovery Choice</h4>
                                                <p class="cp-text-amber-800 cp-text-sm mb-4 leading-relaxed">
                                                    The donor is brain-dead, meaning kidney recovery can happen right here at the <strong>treating hospital</strong>. 
                                                    <br><br>
                                                    If you proceed, coordination for all items is active at this hospital. 
                                                    If you skip kidney recovery, you can manually choose a specific hospital for the <strong>Cornea and other tissues</strong>.
                                                </p>
                                                <?php if ($isLeader): ?>
                                                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.8rem; margin-top: 1.5rem; width: 100%;">
                                                        <button onclick="decideKidney('PROCEED')" class="cp-btn cp-btn--primary cp-bg-amber-600 cp-border-amber-600" style="flex: 1; min-width: 220px; padding: 1rem; font-size: 0.9rem; text-align: center; border-radius: 12px; font-weight: 700;">Yes, Proceed at Treating Hospital</button>
                                                        <button onclick="decideKidney('DECLINE')" class="cp-btn cp-btn--secondary border-amber-300 text-amber-800" style="flex: 1; min-width: 220px; padding: 1rem; font-size: 0.9rem; text-align: center; border-radius: 12px; background: white;">No, Skip Kidney / Choose Hospital</button>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 cp-text-sm cp-text-amber-700 flex items-center gap-3">
                                                        <i class="fas fa-hourglass-half fa-spin"></i>
                                                        <div>
                                                            <strong>Awaiting Lead Decision</strong><br>
                                                            Waiting for <strong><?= htmlspecialchars($leaderInfo->declared_by_name ?? 'Lead Custodian') ?></strong> to choose the recovery path.
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php elseif ($kidneyDecision === 'PROCEED'): ?>
                                        <h4 class="cp-text-amber-900 m-0 font-bold"><i class="fas fa-check-circle text-success mr-2"></i> Path: Treating Hospital (Kidney Included)</h4>
                                        <p class="cp-text-amber-800 cp-text-sm mt-3 leading-relaxed">
                                            The decision was made to proceed with kidney recovery bedside. Coordination for all selected items (including kidneys) is active at this facility, ensuring a streamlined surgical process.
                                        </p>
                                        <div class="mt-auto pt-4">
                                            <div class="p-4 rounded-xl bg-green-50 border border-green-100 flex items-center gap-4">
                                                <div class="cp-text-2xl text-green-600"><i class="fas fa-hospital-user"></i></div>
                                                <div class="cp-text-xs cp-text-green-700">
                                                    <strong>Logistical Summary:</strong><br>
                                                    The treating hospital serves as the primary recovery center. No external hospital nomination is required for tissue harvesting in this mode.
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif ($kidneyDecision === 'DECLINE' || $kidneyDecision === 'DECLINED' || $kidneyDecision === 'SKIPPED'): ?>
                                        <h4 class="cp-text-amber-900 m-0 font-bold"><i class="fas fa-times-circle text-danger mr-2"></i> Path: Specified Hospital (Kidney Deffered)</h4>
                                        <p class="cp-text-amber-800 cp-text-sm mt-3 leading-relaxed">
                                            The custodian team has chosen to **donate other organs** (such as cornea and heart valves) while electing to skip kidney recovery. This choice allows the team to nominate a specialized **Transplantation Hospital** for the retrieval process.
                                        </p>
                                        <div class="mt-auto pt-4">
                                            <div class="p-4 rounded-xl bg-amber-50 border border-amber-100 flex items-center gap-4">
                                                <div class="cp-text-2xl text-amber-600"><i class="fas fa-route"></i></div>
                                                <div class="cp-text-xs cp-text-amber-700">
                                                    <strong>Logistical Summary:</strong><br>
                                                    The process has transitioned to a **Directed Tissue Track**. You can now manually nominate the best-suited hospital for the remaining items in the section on the right.
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <h4 class="cp-text-amber-900 m-0 font-bold"><i class="fas fa-info-circle text-info mr-2"></i> Decision Recorded</h4>
                                        <p class="cp-text-amber-800 cp-text-sm mt-2 mb-0">Status: <strong><?= htmlspecialchars(str_replace('_', ' ', $kidneyDecision)) ?></strong>. Coordination is proceeding based on this logistical choice.</p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <h4 class="cp-text-amber-900 m-0 font-bold"><i class="fas fa-info-circle mr-2 text-info"></i> Clinical Track: Directed Tissue Recovery</h4>
                                    <p class="cp-text-amber-800 cp-text-sm mt-3 leading-relaxed">
                                        Kidney recovery is clinically unavailable (Brain death criteria not met). The custodian has proceeded with the **Tissue Donation track** for all other consented items.
                                    </p>
                                    <div class="mt-auto pt-4">
                                        <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 flex items-center gap-4">
                                            <div class="cp-text-2xl text-blue-600"><i class="fas fa-info-circle"></i></div>
                                            <div class="cp-text-xs cp-text-blue-700">
                                                <strong>Logistical Summary:</strong><br>
                                                Recovery will be managed by the **Hospital-Managed Section** on the right. Please ensure the nominated hospital accepts the tissue retrieval request.
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- SCENARIO 9: BODY / CORNEA CHOICE -->
                    <?php if ($mode === 'BODY_PLUS_CORNEA'): ?>
                        <div class="cp-section-card sh-card border-blue-light mb-4 shadow-sm">
                            <div class="cp-section-card__header cp-bg-blue-100 cp-text-blue-900">
                                <div class="cp-section-card__title"><i class="fas fa-route"></i> Path Selection Info</div>
                            </div>
                            <div class="cp-section-card__body p-5">
                                <?php if ($bodyChoice === 'PENDING'): ?>
                                    <p class="cp-text-blue-800 cp-text-sm mb-4">The donor has consented to both Cornea and Body donation. Choose the path to proceed:</p>
                                    <?php if ($isLeader): ?>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <div class="choice-card p-4 border rounded hover-bg-blue cursor-pointer transition-all f-1" onclick="decideBodyCornea('CORNEA_ONLY')">
                                                <div class="flex items-center gap-2 mb-2 cp-text-blue-700">
                                                    <i class="fas fa-eye"></i> <span class="font-bold">Cornea Only</span>
                                                </div>
                                                <p class="cp-text-xs cp-text-g500">Only proceed with eye recovery at a hospital.</p>
                                            </div>
                                            <div class="choice-card p-4 border rounded hover-bg-blue cursor-pointer transition-all f-1" onclick="decideBodyCornea('BODY_ONLY')">
                                                <div class="flex items-center gap-2 mb-2 cp-text-blue-700">
                                                    <i class="fas fa-university"></i> <span class="font-bold">Body Only</span>
                                                </div>
                                                <p class="cp-text-xs cp-text-g500">Only proceed with medical school donation.</p>
                                            </div>
                                            <div class="choice-card p-4 border-blue-500 bg-blue-50 rounded cursor-pointer transition-all f-1" onclick="decideBodyCornea('BOTH')">
                                                <div class="flex items-center gap-2 mb-2 cp-text-blue-700">
                                                    <i class="fas fa-layer-group"></i> <span class="font-bold">Both (Sequential)</span>
                                                </div>
                                                <p class="cp-text-xs cp-text-blue-600">Cornea first, then Body (subject to windows).</p>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-4 rounded bg-blue-50 border border-blue-200 cp-text-sm cp-text-blue-700 flex items-center gap-3">
                                            <i class="fas fa-hourglass-half fa-lg"></i>
                                            <div>
                                                <strong>Awaiting Lead Decision</strong><br>
                                                <span class="cp-text-xs">Waiting for <strong><?= htmlspecialchars($leaderInfo->declared_by_name ?? 'Lead Custodian') ?></strong> to choose the donation path.</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php elseif ($bodyChoice === 'CORNEA_ONLY'): ?>
                                    <h4 class="cp-text-blue-900 m-0"><i class="fas fa-check-circle text-success mr-2"></i> Path: Local Hospital Recovery (Cornea Only)</h4>
                                    <p class="cp-text-blue-700 cp-text-sm mt-2 mb-0">The decision was made to proceed with Cornea recovery only. Body donation for medical research has been bypassed.</p>
                                <?php elseif ($bodyChoice === 'BODY_ONLY'): ?>
                                    <h4 class="cp-text-blue-900 m-0"><i class="fas fa-check-circle text-success mr-2"></i> Path: Medical School Donation (Body Only)</h4>
                                    <p class="cp-text-blue-700 cp-text-sm mt-2 mb-0">The decision was made to proceed with Body donation only. Local hospital recovery for tissues (Cornea) has been bypassed.</p>
                                <?php elseif ($bodyChoice === 'BOTH'): ?>
                                    <h4 class="cp-text-blue-900 m-0"><i class="fas fa-check-circle text-success mr-2"></i> Path: Combined Donation (Cornea then Body)</h4>
                                    <p class="cp-text-blue-700 cp-text-sm mt-2 mb-0">The decision was made to proceed with both. Cornea recovery will be handled at the hospital first, followed by transition to the medical school.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- [SECTION 3 & 4] ACTIONS & NOTICES RESTRUCTURING NEXT -->
                </div>
                <?php endif; ?>

                <!-- [SECTION 3] OPERATIONAL ACTION AREA -->
                
                <!-- HOSPITAL-MANAGED RECOVERY SECTION -->
                <?php if ($canShowHospitalSection): ?>
                    <div class="cp-section-card sh-card col-span-1 md:col-span-2">
                        <div class="cp-section-card__header cp-bg-orange cp-text-white border-0">
                            <div class="cp-section-card__title text-white"><i class="fas fa-hospital"></i> Hospital-Managed Section</div>
                        </div>
                        <div class="cp-section-card__body">
                            <div class="cp-label-mini mb-3">Actionable Items</div>
                            
                            <?php 
                                $hospitalItems = array_filter($snapshotItems, function($it, $id) {
                                    // Exclude Kidney (9/1) and Body (10) from hospital card
                                    return $id != 9 && $id != 1 && $id != 10 && !str_starts_with($id, 'BODY_');
                                }, ARRAY_FILTER_USE_BOTH);
                            ?>

                            <?php if (empty($hospitalItems)): ?>
                                <div class="p-4 text-center cp-text-g400 italic">No tissue items available for hospital request in this case.</div>
                            <?php else: ?>
                                <?php 
                                    // Isolate hospital request BEFORE the loop to avoid undefined variable warnings
                                    $hospReq = null;
                                    foreach($allInstitutionStatuses as $st) {
                                        if ($st->institution_type === 'HOSPITAL' && $st->is_current) {
                                            $hospReq = $st; break;
                                        }
                                    }
                                ?>
                                <div class="flex flex-col gap-2">
                                    <?php 
                                        $selectedItems = $_SESSION['selected_recovery_items_HOSPITAL'] ?? null;
                                        foreach ($hospitalItems as $hid => $hi): ?>
                                        <?php 
                                            $hExp = $timeLimits[$hid] ?? null;
                                            $hIsExpired = $hExp && time() > strtotime($hExp);
                                            $hStatus = ($hi['status'] === 'available' && $hIsExpired) ? 'expired' : $hi['status'];
                                            $isChecked = ($selectedItems === null) ? true : in_array((string)$hid, $selectedItems);
                                        ?>
                                        <div class="cp-item-row cp-item-row--simple <?= $hStatus ?>" style="display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 14px; padding: 14px 16px; border-left-width: 4px;">
                                            <?php if ($hStatus === 'available' && !$hospReq): ?>
                                                <input type="checkbox" class="hospital-item-checkbox" value="<?= $hid ?>" <?= $isChecked ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: #ea580c; cursor: pointer; margin: 0;">
                                            <?php else: ?>
                                                <div style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 0.8rem;">
                                                    <i class="fas fa-lock"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div style="font-weight: 700; font-size: 0.95rem; color: #1e293b; line-height: 1.2;">
                                                <?= htmlspecialchars($hi['name']) ?>
                                            </div>

                                            <div class="item-meta" style="margin-left: auto;">
                                                <?php if ($hStatus === 'available'): ?>
                                                    <span class="countdown" data-expire="<?= $hExp ?>" style="white-space: nowrap; font-family: monospace; font-weight: 800; padding: 4px 8px; font-size: 0.75rem; letter-spacing: 0.02em;">
                                                        <?= $hExp ? '...' : 'No Limit' ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="status-badge-mini" style="white-space: nowrap;"><?= strtoupper($hStatus) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php /* $hospReq already calculated above */ ?>
                                <?php if (!$hospReq): ?>
                                    <?php if ($isLeader): ?>
                                        <div class="mt-4 pt-3 border-t">
                                            <a href="javascript:void(0)" 
                                               onclick="submitHospitalRequest()"
                                               id="hospital-request-btn" 
                                               class="cp-btn cp-btn--primary cp-btn--fw cp-bg-orange cp-border-orange">
                                                Request Hospital Recovery
                                            </a>
                                            <p class="cp-text-center cp-text-xs cp-text-g500 mt-2">Nominate a registered hospital to begin recovery.</p>
                                        </div>
                                        <script>
                                            function submitHospitalRequest() {
                                                const checkboxes = document.querySelectorAll('.hospital-item-checkbox:checked');
                                                if (checkboxes.length === 0) {
                                                    alert('Please select at least one item for recovery.');
                                                    return;
                                                }
                                                const checkedIds = Array.from(checkboxes).map(cb => cb.value);
                                                window.location.href = '<?= ROOT ?>/custodian/institution-requests?type=HOSPITAL&items=' + checkedIds.join(',');
                                            }
                                            
                                            function updateHospitalBtnState() {
                                                const btn = document.getElementById('hospital-request-btn');
                                                const checkboxes = document.querySelectorAll('.hospital-item-checkbox:checked');
                                                if (btn) {
                                                    if (checkboxes.length === 0) {
                                                        btn.style.opacity = '0.5';
                                                        btn.style.filter = 'grayscale(1)';
                                                    } else {
                                                        btn.style.opacity = '1';
                                                        btn.style.filter = 'none';
                                                    }
                                                }
                                                // Background Sync to Session
                                                const ids = Array.from(checkboxes).map(cb => cb.value).join(',');
                                                fetch('<?= ROOT ?>/custodian/persist-selection', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                    body: 'type=HOSPITAL&items=' + encodeURIComponent(ids)
                                                }).catch(err => console.error('Selection sync failed', err));
                                            }
                                            document.querySelectorAll('.hospital-item-checkbox').forEach(cb => cb.addEventListener('change', updateHospitalBtnState));
                                            setTimeout(updateHospitalBtnState, 50);
                                        </script>
                                    <?php else: ?>
                                        <div class="mt-4 pt-3 border-t">
                                            <div class="p-4 text-center bg-orange-50 border border-orange-100 rounded-lg cp-text-sm cp-text-orange-800 flex items-center justify-center gap-2">
                                                <i class="fas fa-hourglass-half"></i> Waiting for <strong><?= htmlspecialchars($leaderInfo->declared_by_name ?? 'Lead Custodian') ?></strong> to select a hospital.
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="mt-4 pt-4 border-t">
                                        <?php 
                                        $originalReq = $currentInstRequest;
                                        $currentInstRequest = $hospReq;
                                        require __DIR__ . '/partials/institution-status-card.php'; 
                                        $currentInstRequest = $originalReq;
                                        ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- MEDICAL SCHOOL PATH SECTION -->
                <?php if ($hasBody): ?>
                    <div class="cp-section-card sh-card col-span-1 md:col-span-2">
                        <div class="cp-section-card__header cp-bg-blue-600 text-white border-0">
                            <div class="cp-section-card__title text-white"><i class="fas fa-building-columns"></i> Medical School Section</div>
                        </div>
                        <div class="cp-section-card__body">
                            <?php if ($bodyLockedByCornea): ?>
                                <div class="cp-locked-box p-5 text-center bg-gray-50 rounded border border-dashed">
                                    <i class="fas fa-lock fa-3x mb-3 text-gray-300"></i>
                                    <h4 class="cp-text-g700">Body Path Locked</h4>
                                    <p class="cp-text-g500 cp-text-xs px-4">The medical school donation is waiting until the cornea donation path is resolved (completed, skipped, or expired).</p>
                                </div>
                            <?php elseif ($canShowMedicalSchoolSection): ?>
                                <div class="cp-label-mini mb-3">Education Donation Flow</div>
                                <div class="cp-item-row available mb-4">
                                    <span class="font-bold cp-text-sm">Whole Body Donation</span>
                                    <div class="item-meta">
                                        <span class="countdown" data-expire="<?= $timeLimits[10] ?? ($timeLimits['BODY_DEFAULT'] ?? '') ?>">...</span>
                                    </div>
                                </div>

                                <?php 
                                    // Isolate medical school request
                                    $medReq = null;
                                    foreach($allInstitutionStatuses as $st) {
                                        if ($st->institution_type === 'MEDICAL_SCHOOL' && $st->is_current) {
                                            $medReq = $st; break;
                                        }
                                    }
                                ?>
                                <?php if (!$medReq): ?>
                                    <?php if ($isLeader): ?>
                                        <a href="<?= ROOT ?>/custodian/institution-requests?type=MEDICAL_SCHOOL" class="cp-btn cp-btn--primary cp-btn--fw cp-bg-blue-700">Request Medical School</a>
                                        <p class="cp-text-center cp-text-xs cp-text-g500 mt-2">Select a university program for body donation.</p>
                                    <?php else: ?>
                                        <div class="p-4 text-center bg-blue-50 border border-blue-100 rounded-lg cp-text-sm cp-text-blue-800 flex items-center justify-center gap-2">
                                            <i class="fas fa-hourglass-half"></i> Waiting for <strong><?= htmlspecialchars($leaderInfo->declared_by_name ?? 'Lead Custodian') ?></strong> to select a school.
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php 
                                    $originalReq = $currentInstRequest;
                                    $currentInstRequest = $medReq;
                                    require __DIR__ . '/partials/institution-status-card.php'; 
                                    $currentInstRequest = $originalReq;
                                    ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="p-5 text-center text-gray-400 italic">
                                    <i class="fas fa-history mb-2"></i><br>Window for body donation has elapsed.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- [SECTION 4] NOTICES & LAGGING MESSAGES -->
                <?php if ($showNoActionMessage): ?>
                    <div class="col-span-1 md:col-span-2 cp-section-card sh-card p-10 text-center">
                        <div class="max-w-400 mx-auto">
                            <i class="fas fa-ban fa-4x mb-4 opacity-10"></i>
                            <h3 class="cp-text-g600 m-0">No Action Required</h3>
                            <p class="cp-text-g500 mt-2 cp-text-sm leading-relaxed">
                                Kidney donation cannot proceed because brain death was not declared. Since there are no other donation items present, your role for this case is complete. 
                            </p>
                            <a href="<?= ROOT ?>/custodian/consent-registry" class="mt-4 cp-btn cp-btn--secondary cp-btn--sm">View Registry Details</a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 3. SUPPORT SECTIONS -->
                <?php if (!empty($custodianApprovals)): ?>
                <div class="col-span-1 md:col-span-2">
                    <?php require __DIR__ . '/partials/co-custodian-approvals.php'; ?>
                </div>
                <?php endif; ?>
                
                <div class="col-span-1 md:col-span-2">
                    <?php require __DIR__ . '/partials/activity-timeline.php'; ?>
                </div>

            </div>

        <?php endif; ?>

    <?php endif; ?>

</div>

<script>
async function decideKidney(decision) {
    const isYes = (decision === 'PROCEED');
    const msg = isYes 
        ? 'Are you sure you want to proceed with Kidney recovery at the current hospital? This will lock the operational track.' 
        : 'Are you sure you want to skip Kidney recovery? You will be able to select any registered hospital for other donated items.';
    
    const isConfirmed = await cpNotify.confirm('Confirm Decision', msg);
    if (!isConfirmed) return;

    try {
        const formData = new FormData();
        formData.append('decision', decision);
        const response = await fetch('<?= ROOT ?>/custodian/decide-kidney', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            window.location.href = result.redirect;
        } else {
            cpNotify.alert('Error', result.error || 'Failed to record decision', 'error');
        }
    } catch (e) {
        cpNotify.alert('System Error', 'Network error', 'error');
    }
}

// Dashboard Expiry Countdown logic
function updateCountdowns() {
    const elements = document.querySelectorAll('.countdown');
    elements.forEach(el => {
        const expireStr = el.dataset.expire;
        if (!expireStr || expireStr === '...') {
            el.innerText = "No Limit";
            return;
        }

        const expireDate = new Date(expireStr);
        if (isNaN(expireDate.getTime())) {
            el.innerText = "No Limit";
            return;
        }

        const expireTs = expireDate.getTime();
        const now = new Date().getTime();
        const dist = expireTs - now;
        
        if (dist < 0) {
            el.innerText = "EXPIRED";
            const row = el.closest('.cp-item-row');
            if (row) {
                row.classList.remove('available'); row.classList.add('expired');
            }
            return;
        }
        
        const h = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
        el.innerText = h + "h " + m + "m rem.";
    });
}
setInterval(updateCountdowns, 60000);
updateCountdowns();

async function skipItem(itemId) {
    const isConfirmed = await cpNotify.confirm(
        'Skip Recovery?', 
        'Are you sure you want to explicitly skip this item? This will unlock subsequent donation paths if applicable.'
    );
    if (!isConfirmed) return;

    try {
        const formData = new FormData();
        formData.append('item_id', itemId);
        const response = await fetch('<?= ROOT ?>/custodian/skip-item', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            window.location.reload();
        } else {
            cpNotify.alert('Error', result.error || 'Failed to skip item', 'error');
        }
    } catch (e) {
        cpNotify.alert('System Error', 'Network error', 'error');
    }
}
function closeGuidance() {
    const overlay = document.getElementById('guidanceOverlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => overlay.remove(), 400);
    }
}
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
