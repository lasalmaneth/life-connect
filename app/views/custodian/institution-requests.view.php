<?php
/**
 * Custodian Portal — Institution Requests View
 */
$page_icon     = 'fa-network-wired';
$page_heading  = 'Institution Requests';
$page_subtitle = 'Manage the institutional routing of the donation case.';

$isBody = (($consent['donation_type'] ?? '') === 'BODY' || ($consent['donation_type'] ?? '') === 'BODY_AND_CORNEA');
$isOrgan = (($consent['donation_type'] ?? '') === 'ORGAN');

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<?php
// Global time logic check for Institution Selection
$isTimeout = false;
$hoursSinceDeath = 0;
if ($activeCase && !empty($activeCase->date_of_death) && !empty($activeCase->time_of_death)) {
    $now = new DateTime();
    $deathDateTime = new DateTime($activeCase->date_of_death . ' ' . $activeCase->time_of_death);
    $interval = $now->diff($deathDateTime);
    $hoursSinceDeath = ($interval->days * 24) + $interval->h + ($interval->i / 60);
    if ($isBody && $hoursSinceDeath > 48) {
        $isTimeout = true;
    }
}
?>

<div class="cp-content__body">

    <?php if (!$activeCase): ?>
        <div class="cp-notice cp-notice--info">
            <i class="fas fa-info-circle fa-2x"></i>
            <div>
                <strong>No Active Workflow</strong>
                <p>Institution selection is locked until a death is reported and a donation case is created.</p>
            </div>
        </div>
    <?php else: ?>

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
        <?php if (!$isLeader): ?>
            <?php include __DIR__ . '/partials/lock-notice.php'; ?>
        <?php elseif (!$currentInstRequest || $currentInstRequest->institution_status === 'REJECTED' || $currentInstRequest->institution_status === 'WITHDRAWN'): ?>
            
            <?php if ($currentInstRequest && $currentInstRequest->institution_status === 'REJECTED'): ?>
                <div class="cp-notice cp-notice--info mb-4">
                    <i class="fas fa-lightbulb fa-2x"></i>
                    <div>
                        <strong>Fallback Option Available</strong>
                        <p class="mb-0">Since the previous request was rejected, you can now nominate another medical school from the donor's sanctioned list below.</p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (count($availableInstitutions) > 0): ?>
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
                                    <i class="fas <?= $isBody ? 'fa-building-columns' : 'fa-hospital' ?>"></i> 
                                </div>
                                Request New Institution
                            </div>
                        </div>
                        <div class="cp-section-card__body" style="padding: 24px; background: white;">
                            <form id="select-inst-form">
                                <input type="hidden" name="institution_type" value="<?= $institutionType ?>">
                                <div style="display: flex; gap: 16px; align-items: flex-start;">
                                    <div style="flex: 1;">
                                        <select name="institution_id" class="cp-form-control" style="width: 100%; height: 50px; border-radius: 12px; border: 1px solid var(--cp-gray-200); padding: 0 16px; font-size: 0.95rem; background-color: #f8fafc;" required>
                                            <option value="" disabled selected>-- Choose from consented institutions --</option>
                                            <?php foreach ($availableInstitutions as $inst): ?>
                                                <option value="<?= $inst->id ?>"><?= htmlspecialchars($inst->school_name ?? $inst->hospital_name ?? 'Unknown') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div style="width: 180px;">
                                        <button type="button" id="submit-inst-btn" class="cp-btn" style="width: 100%; height: 50px; background: #1e293b; color: white; border-radius: 12px; font-weight: 700; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; box-shadow: 0 4px 12px rgba(30, 41, 59, 0.15);">
                                            Send Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

</div>

<script>
document.getElementById('submit-inst-btn')?.addEventListener('click', async () => {
    const form = document.getElementById('select-inst-form');
    const formData = new FormData(form);

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    try {
        const response = await fetch('<?= ROOT ?>/api/custodian/select-institution', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            window.location.reload();
        } else {
            cpNotify.alert('Selection Error', result.error || 'Failed to request institution', 'error');
        }
    } catch (e) {
        cpNotify.alert('System Error', 'An error occurred. Please try again.', 'error');
    }
});
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
