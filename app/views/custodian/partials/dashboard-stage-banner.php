<?php
/**
 * Custodian Portal — Dashboard Stage Banner
 * 
 * Shows a contextual action/status banner based on current workflow state.
 * Derives all state from $currentInstRequest and $activeCase.
 */

// Skip if no active case
if (!$activeCase) return;

$instReq = $currentInstRequest ?? null;
$reqStatus = $instReq->request_status ?? null;
$instStatus = $instReq->institution_status ?? null;
$docStatus = $instReq->document_status ?? null;
$instName = $instReq->institution_name ?? 'Institution';
$bundleStatus = $activeCase->bundle_status ?? 'PENDING';
$mode = $activeCase->resolved_deceased_mode ?? 'NONE';
$track = $activeCase->resolved_operational_track ?? 'NONE';

// Determine the current stage
$stage = 'NO_REQUEST'; // Default

if ($instReq) {
    if ($instStatus === 'REJECTED') {
        $stage = 'REJECTED';
    } elseif ($instStatus === 'PENDING') {
        $stage = 'AWAITING_RESPONSE';
    } elseif ($reqStatus === 'ACCEPTED' || $instStatus === 'ACCEPTED') {
        if ($docStatus === 'NOT_STARTED' || $bundleStatus === 'PENDING') {
            $stage = 'ACCEPTED_UPLOAD_DOCS';
        } elseif ($docStatus === 'PENDING_REVIEW') {
            $stage = 'DOCS_UNDER_REVIEW';
        } elseif ($docStatus === 'NEED_MORE_DOCS') {
            $stage = 'DOCS_NEED_CORRECTION';
        } elseif ($docStatus === 'REJECTED') {
            $stage = 'DOCS_REJECTED';
        } elseif ($docStatus === 'ACCEPTED') {
            if (!empty($instReq->handover_date)) {
                $stage = 'HANDOVER_SCHEDULED';
            } else {
                $stage = 'DOCS_APPROVED';
            }
        }
    }
}

// Also check for completion
if ($activeCase->overall_status === 'COMPLETED') {
    $stage = 'COMPLETED';
}

// Stage config map
$stageConfig = [
    'NO_REQUEST' => null, // No banner needed
    'AWAITING_RESPONSE' => [
        'icon' => 'fa-clock',
        'bg' => 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)',
        'border' => '#f59e0b',
        'iconBg' => '#f59e0b',
        'titleColor' => '#92400e',
        'textColor' => '#a16207',
        'title' => 'Request Pending — Awaiting Institution Response',
        'text' => "Your request has been sent to <strong>{$instName}</strong>. They will review and respond. You will be notified when they accept or decline.",
        'action' => null,
        'pulse' => true
    ],
    'REJECTED' => [
        'icon' => 'fa-exclamation-circle',
        'bg' => 'linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%)',
        'border' => '#e11d48',
        'iconBg' => '#e11d48',
        'titleColor' => '#9f1239',
        'textColor' => '#be123c',
        'title' => 'Request Declined by ' . htmlspecialchars($instName),
        'text' => !empty($instReq->rejection_message) 
            ? 'Reason: <em>' . htmlspecialchars($instReq->rejection_message) . '</em>. You may select another institution.'
            : 'The institution has declined this request. You may select another institution from the available list.',
        'action' => $isLeader ? '<a href="' . ROOT . '/custodian/institution-requests" class="cp-btn cp-btn--primary cp-btn--sm" style="background: #e11d48; border-color: #e11d48;"><i class="fas fa-redo-alt mr-1"></i> Choose Another Institution</a>' : null,
        'pulse' => false
    ],
    'ACCEPTED_UPLOAD_DOCS' => [
        'icon' => 'fa-file-upload',
        'bg' => 'linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%)',
        'border' => '#10b981',
        'iconBg' => '#10b981',
        'titleColor' => '#065f46',
        'textColor' => '#047857',
        'title' => htmlspecialchars($instName) . ' Accepted — Upload Documents',
        'text' => 'The institution has accepted your request. Prepare and upload the required document bundle (Cadaver Data Sheet, Sworn Statement, and supporting documents).',
        'action' => $isLeader ? '<a href="' . ROOT . '/custodian/documents" class="cp-btn cp-btn--primary cp-btn--sm" style="background: #10b981; border-color: #10b981;"><i class="fas fa-folder-open mr-1"></i> Go to Documents</a>' : null,
        'pulse' => true
    ],
    'DOCS_UNDER_REVIEW' => [
        'icon' => 'fa-search',
        'bg' => 'linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%)',
        'border' => '#0284c7',
        'iconBg' => '#0284c7',
        'titleColor' => '#075985',
        'textColor' => '#0369a1',
        'title' => 'Documents Under Review',
        'text' => "Your document bundle has been submitted to <strong>{$instName}</strong>. The institution is now reviewing your files. You will be notified of the result.",
        'action' => null,
        'pulse' => true
    ],
    'DOCS_NEED_CORRECTION' => [
        'icon' => 'fa-pen-to-square',
        'bg' => 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)',
        'border' => '#d97706',
        'iconBg' => '#d97706',
        'titleColor' => '#92400e',
        'textColor' => '#b45309',
        'title' => 'Corrections Required — Re-upload Documents',
        'text' => 'The institution has requested corrections to your document bundle. Please review the feedback, update your documents, and resubmit.',
        'action' => $isLeader ? '<a href="' . ROOT . '/custodian/documents" class="cp-btn cp-btn--primary cp-btn--sm" style="background: #d97706; border-color: #d97706;"><i class="fas fa-redo mr-1"></i> Fix & Resubmit</a>' : null,
        'pulse' => true
    ],
    'DOCS_REJECTED' => [
        'icon' => 'fa-file-excel',
        'bg' => 'linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%)',
        'border' => '#e11d48',
        'iconBg' => '#e11d48',
        'titleColor' => '#9f1239',
        'textColor' => '#be123c',
        'title' => 'Documents Rejected by ' . htmlspecialchars($instName),
        'text' => 'Your document bundle was not accepted. You may need to re-upload corrected documents or contact the institution.',
        'action' => $isLeader ? '<a href="' . ROOT . '/custodian/documents" class="cp-btn cp-btn--primary cp-btn--sm" style="background: #e11d48; border-color: #e11d48;"><i class="fas fa-redo-alt mr-1"></i> Review & Resubmit</a>' : null,
        'pulse' => false
    ],
    'DOCS_APPROVED' => [
        'icon' => 'fa-check-double',
        'bg' => 'linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%)',
        'border' => '#059669',
        'iconBg' => '#059669',
        'titleColor' => '#065f46',
        'textColor' => '#047857',
        'title' => 'Documents Approved — Awaiting Handover Details',
        'text' => "All documents have been verified by <strong>{$instName}</strong>. The institution will schedule a handover date and communicate logistics.",
        'action' => null,
        'pulse' => false
    ],
    'HANDOVER_SCHEDULED' => [
        'icon' => 'fa-truck-medical',
        'bg' => 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)',
        'border' => '#16a34a',
        'iconBg' => '#16a34a',
        'titleColor' => '#166534',
        'textColor' => '#15803d',
        'title' => 'Handover Scheduled',
        'text' => 'Proceed with handover at <strong>' . (!empty($instReq->handover_date) ? date('M j, Y', strtotime($instReq->handover_date)) : '') . '</strong>' 
                . (!empty($instReq->handover_time) ? ' at <strong>' . date('g:i A', strtotime($instReq->handover_time)) . '</strong>' : '')
                . ' with <strong>' . htmlspecialchars($instName) . '</strong>.'
                . (!empty($instReq->handover_message) ? '<br><em style="color:#92400e;">"' . htmlspecialchars($instReq->handover_message) . '"</em>' : ''),
        'action' => null,
        'pulse' => true
    ],
    'COMPLETED' => [
        'icon' => 'fa-award',
        'bg' => 'linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%)',
        'border' => '#7c3aed',
        'iconBg' => '#7c3aed',
        'titleColor' => '#4c1d95',
        'textColor' => '#6d28d9',
        'title' => 'Case Completed',
        'text' => 'This donation case has been fully processed. Certificates and appreciation letters are available in the Recognition section.',
        'action' => '<a href="' . ROOT . '/custodian/certificates" class="cp-btn cp-btn--primary cp-btn--sm" style="background: #7c3aed; border-color: #7c3aed;"><i class="fas fa-certificate mr-1"></i> View Certificates</a>',
        'pulse' => false
    ],
];

$cfg = $stageConfig[$stage] ?? null;

if ($cfg): ?>
<div class="cp-stage-banner" style="
    background: <?= $cfg['bg'] ?>; 
    border: 1px solid <?= $cfg['border'] ?>20; 
    border-left: 5px solid <?= $cfg['border'] ?>; 
    padding: 20px 24px; 
    border-radius: 14px; 
    margin-bottom: 20px;
    <?= $cfg['pulse'] ? 'animation: stagePulse 3s ease-in-out infinite;' : '' ?>
">
    <div style="display: flex; gap: 16px; align-items: flex-start;">
        <div style="
            width: 44px; height: 44px; 
            background: <?= $cfg['iconBg'] ?>; 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            color: white; flex-shrink: 0;
            box-shadow: 0 4px 12px <?= $cfg['iconBg'] ?>40;
        ">
            <i class="fas <?= $cfg['icon'] ?>"></i>
        </div>
        <div style="flex: 1; min-width: 0;">
            <h4 style="margin: 0 0 6px; color: <?= $cfg['titleColor'] ?>; font-weight: 800; font-size: 0.95rem;">
                <?= $cfg['title'] ?>
            </h4>
            <p style="margin: 0; color: <?= $cfg['textColor'] ?>; font-size: 0.875rem; line-height: 1.6;">
                <?= $cfg['text'] ?>
            </p>
            <?php if ($cfg['action']): ?>
                <div style="margin-top: 14px;">
                    <?= $cfg['action'] ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
@keyframes stagePulse {
    0%, 100% { box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
    50% { box-shadow: 0 4px 20px <?= $cfg['border'] ?>15; }
}
</style>
<?php endif; ?>
