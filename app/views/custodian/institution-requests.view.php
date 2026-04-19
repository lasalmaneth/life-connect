<?php
/**
 * Custodian Portal — Institution Requests View
 */
$page_icon     = 'fa-network-wired';
$page_heading  = 'Institution Requests';
$page_subtitle = 'Manage the institutional routing of the donation case.';

// [CONSOLIDATED CLINICAL FLAGS]
$resMode = ($activeCase) ? ($activeCase->resolved_deceased_mode ?? 'NONE') : 'NONE';
$track = ($activeCase) ? ($activeCase->resolved_operational_track ?? 'NONE') : 'NONE';
$isBrainDead = ($death_declaration->is_brain_dead ?? 0) == 1;

$hasKidney = str_contains($resMode, 'KIDNEY');
$hasCornea = str_contains($resMode, 'CORNEA');
$hasBody   = str_contains($resMode, 'BODY');
$isOrgan   = ($track === 'HOSPITAL_TISSUE' || str_contains($resMode, 'ORGAN'));

$kidneyDecision = ($activeCase) ? ($activeCase->kidney_decision ?? 'PENDING') : 'PENDING';
$bodyChoice     = ($activeCase) ? ($activeCase->body_cornea_decision ?? 'PENDING') : 'PENDING';

$snapshotItems = ($activeCase) ? json_decode($activeCase->operational_items_json ?? '[]', true) : [];
$corneaStatus  = $snapshotItems[4]['status'] ?? 'none';
$corneaResolved = in_array($corneaStatus, ['completed', 'skipped', 'expired']);

// Visibility Guards & Time Check
$isTimeout = false;
$hoursSinceDeath = 0;
if ($activeCase && !empty($death_declaration->time_of_death)) {
    $now = time();
    $deathTs = strtotime($death_declaration->time_of_death);
    $hoursSinceDeath = ($now - $deathTs) / 3600;
    if ($hasBody && $hoursSinceDeath > 48) {
        $isTimeout = true;
    }
}

$isDecisionPending = in_array($track, ['DECISION_REQUIRED', 'KIDNEY_DECISION_REQUIRED', 'BODY_CORNEA_DECISION_REQUIRED']);
$isKidneyOnlyCase = ($resMode === 'KIDNEY_ONLY');
$isKidneyProceeding = ($kidneyDecision === 'PROCEED' || $isKidneyOnlyCase) && $isBrainDead;
$bodyLocked = ($hasBody && $hasCornea && $bodyChoice === 'BOTH' && !$corneaResolved);

// Type safety for current view
$targetType = $_GET['type'] ?? ($hasBody ? 'MEDICAL_SCHOOL' : 'HOSPITAL');

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <?php if ($activeCase && !$isLeader): ?>
        <div class="cp-section-card sh-card mb-4" style="border-left: 5px solid #6366f1;">
            <div class="cp-section-card__body p-5">
                <div style="display: flex; align-items: flex-start; gap: 20px;">
                    <div style="width: 52px; height: 52px; background: linear-gradient(135deg, #e0e7ff, #c7d2fe); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-lock" style="color: #4f46e5; font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0 0 6px; font-weight: 800; color: #1e293b; font-size: 1.1rem;">Leader-Only Action</h3>
                        <p style="margin: 0 0 12px; color: #64748b; font-size: 0.9rem; line-height: 1.6;">
                            Institution selection and management is restricted to the process leader — 
                            <strong style="color: #1e293b;"><?= htmlspecialchars($leaderInfo->declared_by_name ?? 'the reporting custodian') ?></strong>.
                            You have full visibility on the dashboard but cannot modify institutional requests.
                        </p>
                        <a href="<?= ROOT ?>/custodian/dashboard" class="cp-btn cp-btn--secondary cp-btn--sm">
                            <i class="fas fa-arrow-left" style="margin-right: 6px;"></i> Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            $page_content = ob_get_clean();
            require dirname(__DIR__) . '/layouts/custodian.layout.php';
            return;
        ?>
    <?php endif; ?>
    <?php if (!$activeCase): ?>
        <div class="cp-section-card sh-card border-amber-light p-10 text-center mb-4">
            <i class="fas fa-hourglass-half fa-4x mb-4 opacity-10"></i>
            <h3 class="cp-text-g600">Case Resolution in Progress</h3>
            <p class="cp-text-g500 mx-auto max-w-500">
                The clinical track for this donor is still being established. 
                Please ensure death has been reported and decisions have been made on the dashboard.
            </p>
            <a href="<?= ROOT ?>/custodian/dashboard" class="mt-4 cp-btn cp-btn--secondary cp-btn--sm">Go to Dashboard</a>
        </div>
        <?php 
            $page_content = ob_get_clean();
            require dirname(__DIR__) . '/layouts/custodian.layout.php';
            return;
        ?>
    <?php endif; ?>

    <?php if ($activeCase): ?>
        <?php if ($bodyLocked && $targetType === 'MEDICAL_SCHOOL'): ?>
            <div class="cp-section-card sh-card border-blue-light p-10 text-center mb-4">
                <i class="fas fa-lock fa-4x mb-4 opacity-10"></i>
                <h3 class="cp-text-g600">Medical School Path Locked</h3>
                <p class="cp-text-g500 mx-auto max-w-500">
                    The medical school donation path is currently waiting for the cornea recovery window to resolve. 
                    Please return here after cornea recovery is marked as completed, skipped, or has expired.
                </p>
                <a href="<?= ROOT ?>/custodian/dashboard" class="mt-4 cp-btn cp-btn--secondary cp-btn--sm">Back to Dashboard</a>
            </div>
            <?php 
                $page_content = ob_get_clean();
                require dirname(__DIR__) . '/layouts/custodian.layout.php';
                return;
            ?>
        <?php endif; ?>

        <?php if ($isKidneyOnlyCase || ($isKidneyProceeding && $targetType === 'HOSPITAL')): ?>
            <div class="cp-section-card sh-card border-amber-light mb-4">
                <div class="cp-section-card__header cp-bg-amber-100 text-amber-900">
                    <div class="cp-section-card__title">
                        <i class="fas fa-hand-holding-medical"></i> 
                        <?= (!$isBrainDead) ? 'Donation Status: Unavailable' : 'Clinical Coordination: Kidney' ?>
                    </div>
                </div>
                <div class="cp-section-card__body p-5">
                    <?php if ($isBrainDead): ?>
                        <div class="flex flex-col gap-4">
                            <p class="cp-text-amber-800" style="line-height: 1.6;">
                                The donor has consented to <strong>Kidney Donation</strong>. All coordination for clinical recovery is handled directly through the <strong>treating hospital bedside team</strong>.
                            </p>
                            <div class="p-4 rounded bg-white border border-amber-200 cp-text-sm cp-text-amber-900 italic">
                                "The donor’s prior consent allows you to discuss this option with the treating team. Medical staff may not always raise this topic, but you may proceed by coordinating directly with them."
                            </div>
                            <div class="cp-notice cp-notice--info mt-2">
                                <i class="fas fa-info-circle"></i>
                                <div>
                                    <strong>Immediate Action:</strong> This clinical process is managed bedside. No institutional request via this portal is required for Kidney recovery.
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-col gap-4">
                            <p class="cp-text-g800" style="line-height: 1.6;">
                                Kidney donation cannot proceed in this case because kidney recovery under this workflow requires brain death confirmation.
                            </p>
                            <div class="p-4 rounded bg-gray-50 border border-gray-200 cp-text-sm cp-text-g600 italic">
                                Since brain death was not declared, clinical kidney protocols remain inactive.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($isKidneyOnlyCase): ?>
    <!-- Kidney-only cases handled bedside -->
    <div class="cp-empty-state modernized-empty-state py-12 text-center">
        <div class="cp-empty-state__icon-wrapper mb-6">
            <i class="fas fa-heart-pulse fa-4x opacity-20 cp-text-blue-500"></i>
        </div>
        <h3 class="cp-text-2xl cp-font-bold mb-3 text-slate-800">Bedside Recovery Protocol</h3>
        <p class="cp-text-slate-500 mx-auto max-w-lg mb-8">
            This case is resolved as <strong>Kidney-Only</strong>. Recovery is managed directly by the treating hospital's transplant team. No additional institutional nomination is required.
        </p>
        <a href="<?= ROOT ?>/custodian/dashboard" class="cp-btn cp-btn--primary">Return to Dashboard</a>
    </div>
    <?php 
        $page_content = ob_get_clean();
        require dirname(__DIR__) . '/layouts/custodian.layout.php';
        return;
    ?>
<?php endif; ?>
        <?php endif; ?>
        
        <!-- Active Request Display -->
        <?php if (!empty($institutionStatuses)): ?>
            <?php foreach ($institutionStatuses as $req): ?>
            <?php 
                $statusType = strtolower($req->institution_status);
                $avatarIcon = ($req->institution_type === 'MEDICAL_SCHOOL') ? 'fa-building-columns' : 'fa-hospital-user';
                $statusLabel = str_replace('_', ' ', $req->institution_status);
                
                // Map status to pill class
                $pillClass = 'pending';
                if ($req->institution_status === 'ACCEPTED') $pillClass = 'approved';
                if ($req->institution_status === 'REJECTED') $pillClass = 'rejected';
            ?>
            <div class="cp-inst-card cp-inst-card--<?= $pillClass ?>">
                <div class="cp-inst-card__inner">
                    <div class="cp-inst-avatar">
                        <i class="fas <?= $avatarIcon ?>"></i>
                    </div>
                    <div class="cp-inst-details">
                        <div class="cp-inst-header">
                            <div>
                                <h3 class="cp-inst-name"><?= htmlspecialchars($req->institution_name) ?></h3>
                                <div class="cp-text-xs cp-text-g500 text-uppercase tracking-widest mt-1">
                                    <?= str_replace('_', ' ', $req->institution_type) ?>
                                </div>
                            </div>
                            <div class="cp-status-pill cp-status-pill--<?= $pillClass ?>">
                                <?= $statusLabel ?>
                            </div>
                        </div>

                        <div class="cp-inst-meta">
                            <div class="cp-meta-item">
                                <i class="far fa-calendar-check"></i>
                                Submitted: <?= date('M j, Y', strtotime($req->submission_date ?: $req->created_at)) ?>
                            </div>
                            <div class="cp-meta-item">
                                <i class="far fa-clock"></i>
                                <?= date('g:i A', strtotime($req->submission_date ?: $req->created_at)) ?>
                            </div>
                        </div>

                        <?php if ($req->institution_status === 'PENDING'): ?>
                            <div class="cp-memo">
                                <span class="cp-memo-label">Current Status</span>
                                <div class="cp-memo-body">
                                    The institution has been notified. We are awaiting their confirmation of receipt and initial review. Please do not attempt to contact them directly.
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($req->institution_status === 'REJECTED'): ?>
                            <div class="cp-memo cp-memo--danger">
                                <span class="cp-memo-label">Rejection Reason</span>
                                <div class="cp-memo-body">
                                    "<?= htmlspecialchars($req->rejection_message ?: 'The institution could not accept this case at this time.') ?>"
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($req->institution_status === 'ACCEPTED'): ?>
                            <div class="cp-memo cp-memo--success">
                                <span class="cp-memo-label">Approval Notice</span>
                                <div class="cp-memo-body">
                                    This institution has formally accepted the donation. You may now proceed to the next phase of the workflow.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Selection UI (Locked check) -->
        <?php 
            // Robust check: Is there ANY active (Pending/Accepted) request for the current target type?
            $hasActiveRequestOfThisType = false;
            if (!empty($institutionStatuses)) {
                foreach ($institutionStatuses as $req) {
                    if ($req->institution_type === $targetType && in_array($req->institution_status, ['PENDING', 'ACCEPTED', 'SUBMITTED'])) {
                        $hasActiveRequestOfThisType = true;
                        break;
                    }
                }
            }
        ?>
        <?php if (!$isLeader): ?>
            <?php include __DIR__ . '/partials/lock-notice.php'; ?>
        <?php elseif ($hasActiveRequestOfThisType): ?>
            <!-- Selection UI hidden: a request for this institution type is already active -->
        <?php else: ?>
            
            <?php if ($currentInstRequest && $currentInstRequest->institution_status === 'REJECTED'): ?>
                <div class="cp-notice cp-notice--info mb-4">
                    <i class="fas fa-lightbulb fa-2x"></i>
                    <div>
                        <strong>Fallback Option Available</strong>
                        <p class="mb-0">Since the previous request was rejected, you can now nominate another medical school from the donor's sanctioned list below.</p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($isTimeout): ?>
                <div class="cp-timeout-alert">
                    <i class="fas fa-clock-rotate-left"></i>
                    <div>
                        <h4>48-Hour Deadline Exceeded</h4>
                        <p>
                            The body must be delivered within 48 hours to be legally viable for donation. 
                            Standard protocols dictate that institutions cannot accept bodies after this window.
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <div class="cp-section-card mb-4" style="border: 1px solid var(--cp-gray-200); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                    <div class="cp-section-card__header" style="background: white; border-bottom: 1px solid var(--cp-gray-200); padding: 18px 24px;">
                        <div class="cp-section-card__title" style="font-weight: 800; color: var(--cp-gray-800); display: flex; align-items: center; gap: 12px; font-size: 1rem;">
                            <div style="width: 36px; height: 36px; background: #eff6ff; color: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas <?= $hasBody ? 'fa-building-columns' : 'fa-hospital' ?>"></i> 
                            </div>
                            Request New Institution
                        </div>
                    </div>
                    <div class="cp-section-card__body" style="padding: 24px; background: white;">
                        <div class="mb-6">
                            <h4 class="cp-text-sm font-bold cp-text-slate-600 mb-4 flex items-center gap-2">
                                <i class="fas fa-list-check text-blue-500"></i> Select Targeted Recovery Items
                            </h4>
                            
                            <div class="cp-items-selection-grid">
                                <?php
                                    // Robust URL parsing
                                    $itemsParam = $_GET['items'] ?? '';
                                    if ($itemsParam !== '') {
                                        // Handle both comma and space separators just in case
                                        $requestedInUrl = preg_split('/[,\s]+/', $itemsParam, -1, PREG_SPLIT_NO_EMPTY);
                                        $_SESSION["selected_recovery_items_$targetType"] = $requestedInUrl;
                                    } else {
                                        $requestedInUrl = $_SESSION["selected_recovery_items_$targetType"] ?? [];
                                    }
                                    
                                    $availableForTrack = [];
                                    $itemTypeFilter = ($targetType === 'HOSPITAL') ? 'HOSPITAL_TISSUE' : 'BODY';
                                    
                                    foreach ($snapshotItems as $id => $data) {
                                        if (($data['type'] ?? '') === $itemTypeFilter && in_array($data['status'], ['available', 'requested', 'skipped'])) {
                                            $availableForTrack[$id] = $data;
                                        }
                                    }
                                    
                                    // If no items are selected in the session for THIS track, automatically select them all (default behavior)
                                    $hasAnySelectedForThisTrack = false;
                                    foreach ($availableForTrack as $id => $data) {
                                        if (in_array((string)$id, $requestedInUrl)) {
                                            $hasAnySelectedForThisTrack = true;
                                            break;
                                        }
                                    }
                                    if (!$hasAnySelectedForThisTrack && $itemsParam === '') {
                                        // We merge keys to array but we only need to store keys if the array was empty
                                        $requestedInUrl = array_keys($availableForTrack);
                                        $_SESSION["selected_recovery_items_$targetType"] = $requestedInUrl;
                                    }
                                ?>

                                <?php foreach ($availableForTrack as $id => $data): ?>
                                    <?php 
                                        $isChecked = false;
                                        foreach ($requestedInUrl as $reqId) {
                                            if ((string)$reqId === (string)$id) {
                                                $isChecked = true;
                                                break;
                                            }
                                        }
                                    ?>
                                    <label class="cp-selection-card <?= $isChecked ? 'is-selected' : '' ?>" id="item-card-<?= $id ?>">
                                        <input type="checkbox" 
                                               class="cp-item-checkbox sr-only" 
                                               value="<?= $id ?>" 
                                               data-name="<?= htmlspecialchars($data['name']) ?>"
                                               <?= $isChecked ? 'checked' : '' ?> 
                                               onchange="toggleItem(this, '<?= $id ?>')">
                                        
                                        <div class="cp-selection-card__content">
                                            <div class="cp-selection-card__icon">
                                                <i class="fas <?= $targetType === 'HOSPITAL' ? 'fa-dna' : 'fa-skeleton' ?>"></i>
                                            </div>
                                            <div class="cp-selection-card__info">
                                                <span class="cp-selection-card__name"><?= htmlspecialchars($data['name']) ?></span>
                                                <span class="cp-selection-card__status cp-text-xs opacity-60">
                                                    <?= $isChecked ? 'Selected' : 'Available' ?>
                                                </span>
                                            </div>
                                            <div class="cp-selection-card__check">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                            <div id="skipped-warning" class="mt-6 mb-8 p-4 rounded-xl bg-amber-50 border border-amber-200 cp-text-xs cp-text-amber-800 hidden items-center gap-3 fadeIn-animation" style="box-shadow: 0 2px 8px rgba(245, 158, 11, 0.08);">
                                <i class="fas fa-exclamation-triangle text-amber-500" style="font-size: 1.1rem; flex-shrink: 0;"></i>
                                <span style="line-height: 1.5;">
                                    <strong>Important Note:</strong> Any available items you leave unselected in this track will be automatically marked as <strong>skipped</strong> for this case once you submit the request.
                                </span>
                            </div>
                        </div>
                        
                        <?php if (count($availableInstitutions) > 0): ?>
                            <form action="<?= ROOT ?>/custodian/select-institution" method="POST" class="cp-nomination-form">
                                <input type="hidden" name="donation_case_id" value="<?= $activeCase->id ?>">
                                <input type="hidden" name="institution_type" value="<?= $targetType ?>">
                                <input type="hidden" name="track" value="<?= $track ?? 'BODY' ?>">
                                <input type="hidden" name="selected_items" value="<?= htmlspecialchars($_GET['items'] ?? '') ?>">

                                <div class="cp-form-group mb-6">
                                    <label class="cp-form-label mb-3 font-bold text-slate-700">Select Registered <?= ($targetType === 'HOSPITAL') ? 'Hospital' : 'Medical School' ?></label>
                                    <div class="cp-custom-select-wrapper">
                                        <select name="institution_id" class="cp-form-control cp-custom-select" style="width: 100%; height: 50px; border-radius: 12px; border: 1px solid var(--cp-gray-200); padding: 0 16px; font-size: 0.95rem; background-color: #f8fafc;" required>
                                            <option value="" disabled selected>Choose from available institutions...</option>
                                            <?php foreach ($availableInstitutions as $inst): ?>
                                                <option value="<?= $inst->id ?>"><?= htmlspecialchars($inst->school_name ?? $inst->hospital_name ?? 'Unknown') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="cp-form-actions mt-8">
                                    <button type="submit" class="cp-btn cp-btn--primary cp-btn--lg cp-btn--fw shadow-lg" style="width: 100%; height: 50px; background: #1e293b; color: white; border-radius: 12px; font-weight: 700; border: none; cursor: pointer;">
                                        <i class="fas fa-paper-plane mr-2"></i> Send Recovery Request
                                    </button>
                                </div>
                            </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

</div>

<?php
$page_content = ob_get_clean();
?>

<style>
.cp-items-selection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 1.5rem;
}

.cp-selection-card {
    position: relative;
    cursor: pointer;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 16px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    background: #ffffff;
    user-select: none;
}

.cp-selection-card:hover {
    border-color: #3b82f6;
    background: #f8fafc;
    transform: translateY(-2px);
}

.cp-selection-card.is-selected {
    border-color: #3b82f6;
    background: #eff6ff;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
}

.cp-selection-card__content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cp-selection-card__icon {
    width: 38px;
    height: 38px;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.is-selected .cp-selection-card__icon {
    background: #3b82f6;
    color: white;
}

.cp-selection-card__info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.cp-selection-card__name {
    font-weight: 700;
    color: #1e293b;
    font-size: 0.9rem;
}

.cp-selection-card__check {
    color: #cbd5e1;
    font-size: 1.1rem;
    transition: all 0.2s;
    opacity: 0;
}

.is-selected .cp-selection-card__check {
    color: #3b82f6;
    opacity: 1;
    transform: scale(1.1);
}

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
.fadeIn-animation {
    animation: fadeIn 0.3s ease forwards;
}
</style>

<script>
function toggleItem(checkbox, id) {
    const card = document.getElementById('item-card-' + id);
    if (checkbox.checked) {
        card.classList.add('is-selected');
        card.querySelector('.cp-selection-card__status').textContent = 'Selected';
    } else {
        card.classList.remove('is-selected');
        card.querySelector('.cp-selection-card__status').textContent = 'Available';
    }
    
    updateHiddenInput();
    
    // Background Sync to Session
    const checked = document.querySelectorAll('.cp-item-checkbox:checked');
    const ids = Array.from(checked).map(cb => cb.value).join(',');
    
    fetch('<?= ROOT ?>/custodian/persist-selection', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'type=<?= $targetType ?>&items=' + encodeURIComponent(ids)
    }).catch(err => console.error('Selection sync failed', err));
}

function updateHiddenInput() {
    const checkboxes = document.querySelectorAll('.cp-item-checkbox:checked');
    const allCheckboxes = document.querySelectorAll('.cp-item-checkbox');
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    // Update the hidden input in the form
    const hiddenInput = document.querySelector('input[name="selected_items"]');
    if (hiddenInput) {
        hiddenInput.value = ids.join(',');
    }

    // Toggle warning if some items are left unchecked
    const warning = document.getElementById('skipped-warning');
    if (warning) {
        if (checkboxes.length < allCheckboxes.length && checkboxes.length > 0) {
            warning.style.display = 'flex';
        } else {
            warning.style.display = 'none';
        }
    }
}

// Initialize warning on load
document.addEventListener('DOMContentLoaded', updateHiddenInput);
</script>

<?php
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
