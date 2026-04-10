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
                // Determine styling based on status
                $statusColor = 'blue';
                $statusIcon = 'fa-spinner fa-spin';
                $statusMsg = 'Pending Institution Review';
                $cardStatusClass = 'pending';
                
                if ($req->institution_status === 'ACCEPTED') {
                    $statusColor = 'success';
                    $statusIcon = 'fa-check-circle';
                    $statusMsg = 'Request ACCEPTED';
                    $cardStatusClass = 'approved';
                } elseif ($req->institution_status === 'REJECTED') {
                    $statusColor = 'danger';
                    $statusIcon = 'fa-circle-xmark';
                    $statusMsg = 'Request Rejected';
                    $cardStatusClass = 'rejected';
                } elseif ($req->institution_status === 'WITHDRAWN') {
                    $statusColor = 'g500';
                    $statusIcon = 'fa-ban';
                    $statusMsg = 'Request Withdrawn';
                    $cardStatusClass = 'withdrawn';
                }
            ?>
            <div class="cp-section-card cp-req-card cp-req-card--<?= $cardStatusClass ?> mb-4">
                <div class="cp-section-card__header">
                    <div class="cp-section-card__title">
                        <i class="fas <?= $statusIcon ?>"></i> <?= $statusMsg ?>
                    </div>
                </div>
                <div class="cp-section-card__body">
                    <div class="cp-req-header-row">
                        <div>
                            <h3 class="cp-text-xl cp-font-bold mb-1"><?= htmlspecialchars($req->institution_name) ?></h3>
                            <div class="cp-text-sm cp-text-g500 mb-4">
                                <i class="fas fa-building mr-1"></i> <?= str_replace('_', ' ', $req->institution_type) ?>
                            </div>
                            <span class="badge cp-req-status-badge cp-req-status-badge--<?= $cardStatusClass ?>">
                                <?= str_replace('_', ' ', $req->institution_status) ?>
                            </span>
                        </div>
                        <div class="cp-req-date-badge">
                            <div class="cp-text-xs cp-text-g500 text-uppercase tracking-wider mb-1"><i class="far fa-calendar-alt mr-1"></i> Submitted On</div>
                            <div class="cp-font-semibold"><?= date('M j, Y', strtotime($req->submission_date ?: $req->created_at)) ?></div>
                            <div class="cp-text-xs cp-text-g500 mt-1"><?= date('g:i A', strtotime($req->submission_date ?: $req->created_at)) ?></div>
                        </div>
                    </div>

                    <?php if ($req->institution_status === 'PENDING'): ?>
                        <div class="cp-req-info-box">
                            <p class="mb-0 cp-text-blue-800 cp-text-sm">
                                <strong><i class="fas fa-info-circle mr-1"></i> Notice:</strong> 
                                The medical school must review this request before you can proceed to the document upload phase. Please wait for their approval.
                            </p>
                            <?php if (count($availableInstitutions) > 0): ?>
                                <hr class="my-2" style="border-color: rgba(30, 64, 175, 0.2);">
                                <p class="mb-0 cp-text-blue-800 cp-text-sm">
                                    <strong><i class="fas fa-code-branch mr-1"></i> Multiple Consents Detected:</strong> 
                                    Because the donor consented to multiple medical schools, if this request happens to be rejected, you will be able to apply to another consented university.
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($req->institution_status === 'REJECTED'): ?>
                        <div class="cp-req-reject-box">
                            <i class="fas fa-circle-exclamation fa-2x cp-text-red-600"></i>
                            <div>
                                <strong class="cp-text-red-900 cp-req-rejected-title">Request Rejected by Institution</strong>
                                <div class="cp-req-reject-reason">
                                    <p class="mb-0 cp-text-g700">
                                        <strong>Reason provided:</strong><br>
                                        <span class="cp-text-red-800 cp-req-reject-text">
                                            "<?= htmlspecialchars($req->rejection_message ?: 'No specific reason provided by the institution.') ?>"
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Selection UI -->
        <?php if (!$currentInstRequest || $currentInstRequest->institution_status === 'REJECTED' || $currentInstRequest->institution_status === 'WITHDRAWN'): ?>
            <?php if ($currentInstRequest && $currentInstRequest->institution_status === 'REJECTED'): ?>
                <?php if (count($availableInstitutions) > 0): ?>
                    <div class="cp-req-fallback-notice">
                        <i class="fas fa-lightbulb fa-2x cp-text-blue-600"></i>
                        <div>
                            <strong class="cp-text-blue-900 cp-req-multiple-consents-title">Multiple Consents Available</strong>
                            <p class="cp-text-blue-800 mt-1 mb-0">
                                Since the donor consented to multiple medical schools during registration, you have the option to reroute this donation. Please try submitting an application to your next preferred institution from the dropdown below.
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="cp-req-fallback-notice" style="background-color: var(--red-50); border-left-color: var(--red-500);">
                        <i class="fas fa-xmark-circle fa-2x cp-text-red-600"></i>
                        <div>
                            <strong class="cp-text-red-900 cp-req-multiple-consents-title">No Fallback Institutions Available</strong>
                            <p class="cp-text-red-800 mt-1 mb-0">
                                This institution request was rejected, and there are no other medical schools listed in the donor's consent profile. Unfortunately, this may mean the body cannot be accepted for donation. Please contact support if you need further assistance.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (count($availableInstitutions) > 0): ?>
                <?php if ($isTimeout): ?>
                    <div class="cp-section-card cp-req-card mb-4" style="border: 1px solid var(--red-200);">
                        <div class="cp-section-card__header cp-bg-red-50">
                            <div class="cp-section-card__title cp-text-red-800">
                                <i class="fas <?= $isBody ? 'fa-building-columns' : 'fa-hospital' ?>"></i> 
                                Select <?= $isBody ? 'Medical School' : 'Hospital' ?>
                            </div>
                        </div>
                        <div class="cp-section-card__body">
                            <div class="alert alert-danger p-3 mb-0" style="background-color: var(--red-50); border: 1px solid var(--red-200); color: var(--red-900); border-radius: 8px;">
                                <div style="display:flex; gap:12px; align-items:flex-start;">
                                    <i class="fas fa-ban fa-2x mt-1" style="color: var(--red-600);"></i>
                                    <div>
                                        <strong>48-Hour Legal Limit Reached</strong>
                                        <p class="mb-0 mt-1 cp-text-sm">
                                            Unfortunately, you can no longer submit an application to a new university. Medical Faculty regulations mandate that physical delivery of the body must unconditionally take place within 48 hours. Because roughly <strong><?= floor($hoursSinceDeath) ?> hours</strong> have passed since death, standard protocols dictate immediate rejection due to putrefaction risks. 
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="cp-section-card cp-req-card mb-4">
                        <div class="cp-section-card__header cp-bg-g50">
                            <div class="cp-section-card__title">
                                <i class="fas <?= $isBody ? 'fa-building-columns' : 'fa-hospital' ?>"></i> 
                                Select <?= $isBody ? 'Medical School' : 'Hospital' ?>
                            </div>
                        </div>
                        <div class="cp-section-card__body">
                            <p class="cp-text-sm cp-text-g500 mb-4">
                                <?php if ($isBody): ?>
                                    Please select precisely one Medical School from the donor's sanctioned list to receive the body.
                                <?php else: ?>
                                    Select a hospital for organ harvesting and medical report review.
                                <?php endif; ?>
                            </p>

                            <form id="select-inst-form">
                                <input type="hidden" name="institution_type" value="<?= $institutionType ?>">
                                <div class="mb-4">
                                    <select name="institution_id" class="cp-form-control" required>
                                        <option value="" disabled selected>-- Choose Institution --</option>
                                        <?php foreach ($availableInstitutions as $inst): ?>
                                            <option value="<?= $inst->id ?>"><?= htmlspecialchars($inst->school_name ?? $inst->hospital_name ?? 'Unknown') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="cp-req-justify-end">
                                    <button type="button" id="submit-inst-btn" class="cp-btn cp-btn--primary">Initiate Request</button>
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
            alert(result.error || 'Failed to request institution');
        }
    } catch (e) {
        alert('An error occurred. Please try again.');
    }
});
</script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>


