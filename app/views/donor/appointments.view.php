<?php
/**
 * Donor Portal — Appointments Page
 * - Upcoming panel (future dates only) with Approve / Reject actions
 * - Full history list
 * - Color-coded interactive calendar
 */

include __DIR__ . '/inc/header.view.php';
include __DIR__ . '/inc/sidebar.view.php';

$today     = date('Y-m-d');
$todayDay  = (int)date('j');
$curMonth  = (int)date('m');
$curYear   = (int)date('Y');

function extractRescheduleProposedDate($notes): ?string
{
    $notes = (string)($notes ?? '');
    if ($notes === '') return null;

    if (preg_match_all('/Proposed date:\s*([0-9]{4}-[0-9]{2}-[0-9]{2})/i', $notes, $m) && !empty($m[1])) {
        $d = end($m[1]);
        return $d ?: null;
    }
    return null;
}
?>

<style>
/* ── Color tokens ─────────────────────────────── */
:root {
    --cal-green:  #16a34a;
    --cal-g-bg:   #dcfce7;
    --cal-blue:   #1d4ed8;
    --cal-b-bg:   #dbeafe;
    --cal-yellow: #b45309;
    --cal-y-bg:   #fef3c7;
    --cal-red:    #b91c1c;
    --cal-r-bg:   #fee2e2;
}

/* ── Calendar grid ─────────────────────────────── */
.cal-wrap        { padding: 1.5rem; }
.cal-nav         { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; }
.cal-nav h3      { margin:0; font-size:1.1rem; font-weight:700; color:var(--blue-900); }
.cal-grid        { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; text-align:center; }
.cal-day-hdr     { font-size:.72rem; font-weight:700; color:var(--g400); padding:.3rem 0; letter-spacing:.04em; }
.cal-day         { padding:.55rem .2rem; border-radius:8px; font-weight:600; font-size:.88rem;
                   cursor:default; position:relative; color:var(--slate); transition:all .15s; }
.cal-day.clickable { cursor:pointer; }
.cal-day.clickable:hover { filter:brightness(.93); }
.cal-day.is-today{ outline:2.5px solid var(--blue-600); }
/* status rings */
.cal-day.apt-green  { background:var(--cal-g-bg); color:var(--cal-green); }
.cal-day.apt-blue   { background:var(--cal-b-bg); color:var(--cal-blue); }
.cal-day.apt-yellow { background:var(--cal-y-bg); color:var(--cal-yellow); }
.cal-day.apt-red    { background:var(--cal-r-bg); color:var(--cal-red); }

/* legend */
.cal-legend      { display:flex; flex-wrap:wrap; gap:.5rem .85rem; margin-top:1.1rem; }
.cal-legend-item { display:flex; align-items:center; gap:.35rem; font-size:.75rem; font-weight:600; }
.cal-legend-dot  { width:10px; height:10px; border-radius:50%; flex-shrink:0; }

/* ── Appointment cards ─────────────────────────── */
.appt-card {
    padding:1.1rem 1.25rem; border-radius:10px; border:1px solid var(--g200);
    background:#fff; display:flex; align-items:center; gap:1.1rem;
    justify-content:space-between; transition:.2s;
}
.appt-card:hover { border-color:var(--blue-300); box-shadow:0 6px 20px rgba(0,91,170,.07); }
.appt-card-left  { display:flex; align-items:center; gap:1rem; }
.appt-icon       { width:44px; height:44px; border-radius:10px; display:flex; align-items:center;
                   justify-content:center; font-size:1.2rem; flex-shrink:0; }
.appt-type       { font-weight:700; font-size:.95rem; color:var(--blue-900); }
.appt-meta       { font-size:.82rem; color:var(--g500); margin-top:.15rem; }
.appt-card-right { display:flex; align-items:center; gap:.65rem; flex-shrink:0; }

/* ── Action buttons ────────────────────────────── */
.btn-approve {
    padding:.38rem .85rem; border-radius:7px; font-size:.8rem; font-weight:700;
    border:none; cursor:pointer; background:#16a34a; color:#fff;
    display:flex; align-items:center; gap:.35rem; transition:.2s;
}
.btn-approve:hover:not(:disabled) { background:#15803d; }
.btn-reject {
    padding:.38rem .85rem; border-radius:7px; font-size:.8rem; font-weight:700;
    border:1.5px solid #dc2626; cursor:pointer; background:#fff; color:#dc2626;
    display:flex; align-items:center; gap:.35rem; transition:.2s;
}
.btn-reject:hover:not(:disabled)  { background:#fee2e2; }
.btn-reschedule {
    padding:.38rem .85rem; border-radius:7px; font-size:.8rem; font-weight:700;
    border:1.5px solid var(--blue-600); cursor:pointer; background:#fff; color:var(--blue-700);
    display:flex; align-items:center; gap:.35rem; transition:.2s;
}
.btn-reschedule:hover:not(:disabled) { background:var(--blue-50); }
.btn-approve:disabled, .btn-reject:disabled { opacity:.45; cursor:not-allowed; }

/* ── Status badges ─────────────────────────────── */
.badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.28rem .75rem; border-radius:999px;
    font-size:.78rem; font-weight:700;
}
.badge-green  { background:var(--cal-g-bg); color:var(--cal-green); }
.badge-blue   { background:var(--cal-b-bg); color:var(--cal-blue); }
.badge-yellow { background:var(--cal-y-bg); color:var(--cal-yellow); }
.badge-red    { background:var(--cal-r-bg); color:var(--cal-red); }

/* ── Modals ─────────────────────────────────────── */
.modal-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.45);
    z-index:2000; align-items:center; justify-content:center; backdrop-filter:blur(3px);
}
.modal-overlay.active { display:flex; }
.modal-card {
    background:#fff; border-radius:16px; width:100%; max-width:430px;
    padding:2rem; box-shadow:0 24px 60px rgba(0,0,0,.18);
    animation:slideUp .28s ease; position:relative;
}
@keyframes slideUp { from{transform:translateY(18px);opacity:0} to{transform:translateY(0);opacity:1} }
.modal-icon {
    width:56px; height:56px; border-radius:14px; display:flex;
    align-items:center; justify-content:center; font-size:1.6rem; margin-bottom:1rem;
}
.modal-title { font-size:1.2rem; font-weight:800; color:var(--blue-900); margin:0 0 .35rem; }
.modal-sub   { font-size:.88rem; color:var(--g500); margin:0 0 1.25rem; line-height:1.5; }
.modal-info  { background:var(--blue-50); border-radius:10px; padding:1rem 1.1rem;
               margin-bottom:1.25rem; font-size:.88rem; }
.modal-info strong { display:block; font-size:1rem; color:var(--blue-900); margin-bottom:.2rem; }
.modal-label { font-size:.75rem; font-weight:700; color:var(--blue-400); text-transform:uppercase; letter-spacing:.05em; }
.modal-textarea {
    width:100%; border:1.5px solid var(--g300); border-radius:8px; padding:.75rem;
    font-size:.9rem; font-family:inherit; resize:vertical; min-height:90px;
    margin-top:.35rem; transition:border-color .2s; box-sizing:border-box;
}
.modal-textarea:focus { outline:none; border-color:var(--blue-500); }
.modal-actions { display:flex; gap:.65rem; margin-top:1rem; }
.modal-actions button { flex:1; padding:.65rem; border-radius:8px; font-size:.9rem; font-weight:700; cursor:pointer; }
.btn-confirm-approve {
    background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; border:none;
}
.btn-confirm-approve:hover { opacity:.9; }
.btn-confirm-reject  {
    background:linear-gradient(135deg,#dc2626,#b91c1c); color:#fff; border:none;
}
.btn-confirm-reject:hover  { opacity:.9; }
.btn-cancel { background:#f1f5f9; color:var(--slate); border:none; }
.btn-cancel:hover { background:#e2e8f0; }

/* ── Detail modal ──────────────────────────────── */
.detail-row { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
.detail-field label { font-size:.72rem; font-weight:700; color:var(--blue-400); text-transform:uppercase; display:block; margin-bottom:.2rem; }
.detail-field span  { font-weight:600; color:var(--blue-900); font-size:.9rem; }

/* ── Section headers ────────────────────────────── */
.section-tag {
    display:inline-flex; align-items:center; gap:.4rem;
    background:var(--blue-50); color:var(--blue-700);
    border-radius:999px; padding:.25rem .8rem;
    font-size:.78rem; font-weight:700; letter-spacing:.03em;
    margin-bottom:.85rem;
}

/* ── Toast ──────────────────────────────────────── */
#apt-toast {
    position:fixed; bottom:1.5rem; right:1.5rem; z-index:3000;
    padding:.75rem 1.25rem; border-radius:10px; font-weight:600;
    font-size:.9rem; box-shadow:0 8px 24px rgba(0,0,0,.15);
    transform:translateY(80px); opacity:0; transition:all .35s ease;
    max-width:320px;
}
#apt-toast.show { transform:translateY(0); opacity:1; }
#apt-toast.toast-success { background:#16a34a; color:#fff; }
#apt-toast.toast-error   { background:#dc2626; color:#fff; }
</style>

<main class="d-content">
    <div class="d-content__header">
        <div>
            <h2><i class="fas fa-calendar-check text-accent"></i> Appointments &amp; Investigations</h2>
            <p>Manage your scheduled medical appointments and track investigation results.</p>
        </div>
    </div>

    <div class="d-content__body">

        <?php
        // ── Prepare calendar data ──────────────────────────────
        // Map each date to its "priority" colour class:
        //   red > yellow > green > blue  (higher priority overwrites lower)
        $calMap = []; // 'Y-m-d' => ['class'=>'apt-*', 'entries'=>[...]]

        foreach ($all_appointments as $apt) {
            if (empty($apt->test_date)) continue;
            $dateKey  = date('Y-m-d', strtotime($apt->test_date));
            $isFuture = ($dateKey >= $today);
            $status   = $apt->status ?? 'Pending';

            // If donor requested reschedule, also highlight the proposed date on the calendar.
            $proposed = extractRescheduleProposedDate($apt->notes ?? '');

            if ($status === 'Rejected')                  $cls = 'apt-red';
            elseif (($status === 'Pending' || $status === 'Scheduled') && $isFuture)  $cls = 'apt-yellow';   // upcoming & pending/scheduled
            elseif ($status === 'Approved' && $isFuture) $cls = 'apt-green';    // upcoming & approved
            else                                         $cls = 'apt-blue';     // past (any approved/completed)

            // Overwrite only with higher priority
            $priority = ['apt-blue'=>1,'apt-green'=>2,'apt-yellow'=>3,'apt-red'=>4];
            if (!isset($calMap[$dateKey]) || $priority[$cls] > $priority[$calMap[$dateKey]['class']]) {
                $calMap[$dateKey]['class'] = $cls;
            }
            $calMap[$dateKey]['entries'][] = [
                'id'          => $apt->id,
                'test_type'   => $apt->test_type ?? 'Medical Investigation',
                'hospital'    => $apt->hospital_registration_no ?? 'N/A',
                'date'        => date('F j, Y', strtotime($apt->test_date)),
                'date_raw'    => $dateKey,
                'status'      => $status,
                'notes'       => $apt->notes ?? '',
            ];

            // Add a second calendar entry for the proposed date (keeps original schedule intact).
            if (!empty($proposed) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $proposed)) {
                // Always highlight the requested new date in red so it stands out.
                $pCls = 'apt-red';
                $priority = ['apt-blue'=>1,'apt-green'=>2,'apt-yellow'=>3,'apt-red'=>4];
                if (!isset($calMap[$proposed]) || $priority[$pCls] > $priority[$calMap[$proposed]['class']]) {
                    $calMap[$proposed]['class'] = $pCls;
                }
                $calMap[$proposed]['entries'][] = [
                    'id'        => $apt->id,
                    'test_type' => ($apt->test_type ?? 'Medical Investigation') . ' (Requested new date)',
                    'hospital'  => $apt->hospital_registration_no ?? 'N/A',
                    'date'      => date('F j, Y', strtotime($proposed)),
                    'date_raw'  => $proposed,
                    'status'    => 'Reschedule Requested',
                    'notes'     => $apt->notes ?? '',
                ];
            }
        }
        ?>

        <!-- ══ TOP SECTION: Upcoming Panel + Calendar ══════════════════ -->
        <div style="display:grid; grid-template-columns:1fr 310px; gap:1.75rem; align-items:start; margin-bottom:1.75rem;">

            <!-- ── Upcoming Appointments Panel ──────────────────────── -->
            <div class="d-widget">
                <div class="d-widget__header">
                    <div class="d-widget__title"><i class="fas fa-hourglass-half text-accent"></i> Upcoming Appointments</div>
                    <span class="section-tag"><i class="fas fa-filter"></i> Future dates only</span>
                </div>
                <div class="d-widget__body">
                    <?php if (!empty($upcoming_appointments)): ?>
                        <div style="display:grid; gap:.9rem;">
                            <?php foreach ($upcoming_appointments as $apt):
                                $status   = $apt->status ?? 'Pending';
                                $isPending = ($status === 'Pending' || $status === 'Scheduled');
                                $isRequested = ($status === 'Requested');
                                $proposed = extractRescheduleProposedDate($apt->notes ?? '');
                                
                                // Handle both test_date (upcoming_appointments) and appointment_date (aftercare_appointments)
                                $dateField = $apt->test_date ?? $apt->appointment_date ?? date('Y-m-d');
                                
                                // Color scheme: yellow=Pending, green=Approved, blue=Requested, red=other
                                $iconBg    = $isPending     ? 'background:#fef3c7;color:#b45309;'
                                           : ($isRequested  ? 'background:#dbeafe;color:#1d4ed8;'
                                           : ($status==='Approved' ? 'background:#dcfce7;color:#16a34a;'
                                           : 'background:#fee2e2;color:#b91c1c;'));
                                $badgeCls  = ($status === 'Pending' || $status === 'Scheduled') ? 'badge-yellow'
                                           : ($isRequested  ? 'badge-blue'
                                           : ($status==='Approved' ? 'badge-green' : 'badge-red'));
                                $aptJson   = json_encode([
                                    'id'       => $apt->id,
                                    'test_type'=> $apt->test_type ?? $apt->appointment_type ?? 'Medical Investigation',
                                    'hospital' => $apt->hospital_registration_no ?? 'N/A',
                                    'date'     => date('F j, Y', strtotime($dateField)),
                                    'status'   => $status,
                                    'notes'    => $apt->notes ?? '',
                                ]);
                            ?>
                            <div class="appt-card" id="apt-card-<?= $apt->id ?>" style="flex-direction: column; align-items: stretch; gap: 0.9rem;">
                                <!-- Top row: icon + info + status badge -->
                                <div style="display:flex; align-items:center; gap:1rem; justify-content:space-between;">
                                    <div class="appt-card-left">
                                        <div class="appt-icon" style="<?= $iconBg ?>">
                                            <i class="fas fa-stethoscope"></i>
                                        </div>
                                        <div>
                                            <div class="appt-type"><?= htmlspecialchars($apt->test_type ?? $apt->appointment_type ?? 'Medical Investigation') ?></div>
                                            <div class="appt-meta">
                                                <i class="far fa-calendar-alt"></i>
                                                <?= date('D, M d Y', strtotime($dateField)) ?>
                                                <?php if (!empty($proposed)): ?>
                                                    &nbsp;·&nbsp; <span style="color:var(--blue-700); font-weight:700;">
                                                        Requested: <?= date('D, M d Y', strtotime($proposed)) ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($apt->hospital_registration_no)): ?>
                                                    &nbsp;·&nbsp; <i class="fas fa-hospital-alt"></i>
                                                    <?= htmlspecialchars($apt->hospital_registration_no) ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Status badge always top-right -->
                                    <span class="badge <?= $badgeCls ?>" style="flex-shrink:0;">
                                        <?php if ($status === 'Approved'): ?><i class="fas fa-check-circle"></i>
                                        <?php elseif ($status === 'Rejected'): ?><i class="fas fa-times-circle"></i>
                                        <?php else: ?><i class="fas fa-clock"></i>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </div>

                                <?php if ($isPending): ?>
                                <!-- Action buttons row — below, full width, side by side -->
                                <div class="apt-actions-row" style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:.65rem; padding-top:.6rem; border-top:1px solid #f1f5f9;">
                                    <button class="btn-approve"
                                            onclick="openApprove(<?= $apt->id ?>, this)"
                                            data-apt='<?= htmlspecialchars($aptJson, ENT_QUOTES) ?>'
                                            id="btn-approve-<?= $apt->id ?>"
                                            style="justify-content:center; padding:.55rem 0; border-radius:8px; font-size:.85rem;">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn-reject"
                                            onclick="openReject(<?= $apt->id ?>, this)"
                                            data-apt='<?= htmlspecialchars($aptJson, ENT_QUOTES) ?>'
                                            id="btn-reject-<?= $apt->id ?>"
                                            style="justify-content:center; padding:.55rem 0; border-radius:8px; font-size:.85rem;">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                    <button class="btn-reschedule"
                                            onclick="openReschedule(<?= $apt->id ?>, this)"
                                            data-apt='<?= htmlspecialchars($aptJson, ENT_QUOTES) ?>'
                                            id="btn-reschedule-<?= $apt->id ?>"
                                            style="justify-content:center; padding:.55rem 0; border-radius:8px; font-size:.85rem;">
                                        <i class="fas fa-calendar-alt"></i> Another date
                                    </button>
                                </div>
                                <?php else: ?>
                                <!-- Locked indicator -->
                                <div style="padding-top:.5rem; border-top:1px solid #f1f5f9; font-size:.78rem; color:var(--g400); font-style:italic;">
                                    <i class="fas fa-lock"></i> Action locked — decision already recorded.
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="padding:2.5rem; text-align:center; border:2px dashed var(--g200); border-radius:12px; color:var(--g500);">
                            <i class="fas fa-calendar-times" style="font-size:2.2rem; display:block; margin-bottom:.75rem; opacity:.5;"></i>
                            No upcoming appointments scheduled.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ── Interactive Calendar ──────────────────────────────── -->
            <div class="d-widget cal-wrap">
                <div class="cal-nav">
                    <button class="d-btn d-btn--sm d-btn--outline" onclick="calNav(-1)" style="padding:.25rem .6rem;"><i class="fas fa-chevron-left"></i></button>
                    <h3 id="cal-title"><?= date('F Y') ?></h3>
                    <button class="d-btn d-btn--sm d-btn--outline" onclick="calNav(1)" style="padding:.25rem .6rem;"><i class="fas fa-chevron-right"></i></button>
                </div>

                <!-- Day headers -->
                <div class="cal-grid" style="margin-bottom:.3rem;">
                    <?php foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $d): ?>
                        <div class="cal-day-hdr"><?= $d ?></div>
                    <?php endforeach; ?>
                </div>

                <div class="cal-grid" id="cal-body"></div>

                <!-- Legend -->
                <div class="cal-legend">
                    <div class="cal-legend-item">
                        <div class="cal-legend-dot" style="background:var(--cal-green);"></div> Approved (upcoming)
                    </div>
                    <div class="cal-legend-item">
                        <div class="cal-legend-dot" style="background:#1d4ed8;"></div> Past / Approved
                    </div>
                    <div class="cal-legend-item">
                        <div class="cal-legend-dot" style="background:var(--cal-yellow);"></div> Pending
                    </div>
                    <div class="cal-legend-item">
                        <div class="cal-legend-dot" style="background:var(--cal-red);"></div> Rejected
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ BOTTOM SECTION: Full History ════════════════════════════ -->
        <div class="d-widget">
            <div class="d-widget__header">
                <div class="d-widget__title"><i class="fas fa-list-alt text-accent"></i> All Investigation Records</div>
            </div>
            <div class="d-widget__body">
                <?php if (!empty($all_appointments)): ?>
                    <div style="display:grid; gap:.75rem;">
                        <?php foreach ($all_appointments as $apt):
                            $status   = $apt->test_date ? ($apt->status ?? 'Pending') : 'Pending';
                            $isFuture = !empty($apt->test_date) && date('Y-m-d', strtotime($apt->test_date)) >= $today;
                            if ($status==='Rejected')                    $badgeCls='badge-red';
                            elseif ($status==='Approved' && $isFuture)   $badgeCls='badge-green';
                            elseif ($status==='Pending' || $status==='Scheduled')      $badgeCls='badge-yellow';
                            else                                         $badgeCls='badge-blue';
                            $cardData = json_encode([
                                'id'       => $apt->id,
                                'test_type'=> $apt->test_type ?? 'Medical Investigation',
                                'hospital' => $apt->hospital_registration_no ?? 'N/A',
                                'date'     => !empty($apt->test_date) ? date('F j, Y', strtotime($apt->test_date)) : 'N/A',
                                'status'   => $status,
                                'notes'    => $apt->notes ?? '',
                            ]);
                        ?>
                        <div class="appt-card" onclick='openDetail(<?= $cardData ?>)' style="cursor:pointer;">
                            <div class="appt-card-left">
                                <div class="appt-icon" style="background:var(--blue-50); color:var(--blue-600);">
                                    <i class="fas fa-file-medical"></i>
                                </div>
                                <div>
                                    <div class="appt-type"><?= htmlspecialchars($apt->test_type ?? 'Investigation') ?></div>
                                    <div class="appt-meta">
                                        <i class="far fa-calendar-alt"></i>
                                        <?= !empty($apt->test_date) ? date('d/m/Y', strtotime($apt->test_date)) : 'N/A' ?>
                                        <?php if (!empty($apt->hospital_registration_no)): ?>
                                            &nbsp;·&nbsp; <i class="fas fa-hospital"></i>
                                            <?= htmlspecialchars($apt->hospital_registration_no) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <span class="badge <?= $badgeCls ?>">
                                <?php if ($status==='Approved'): ?><i class="fas fa-check-circle"></i>
                                <?php elseif ($status==='Rejected'): ?><i class="fas fa-times-circle"></i>
                                <?php elseif ($status==='Pending'): ?><i class="fas fa-clock"></i>
                                <?php else: ?><i class="fas fa-history"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="padding:3rem; text-align:center; border:2px dashed var(--g200); border-radius:12px; color:var(--g500);">
                        <i class="fas fa-notes-medical" style="font-size:2.5rem; margin-bottom:1rem; display:block; opacity:.4;"></i>
                        No investigation records found.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- /.d-content__body -->
</main>

<!-- ════════════════════════════════════════════════════════
     MODAL: Approve Confirmation
═════════════════════════════════════════════════════════ -->
<div id="approveModal" class="modal-overlay" onclick="closeOnBackdrop(event)">
    <div class="modal-card">
        <div class="modal-icon" style="background:#dcfce7; color:#16a34a;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="modal-title">Confirm Approval</h3>
        <p class="modal-sub">Are you sure you want to approve this appointment? This action cannot be undone.</p>
        <div class="modal-info">
            <div class="modal-label">Appointment</div>
            <strong id="apr-title">—</strong>
            <div style="margin-top:.4rem; display:grid; grid-template-columns:1fr 1fr; gap:.5rem;">
                <div>
                    <div class="modal-label">Date</div>
                    <span id="apr-date" style="font-size:.88rem; font-weight:600; color:var(--blue-800);">—</span>
                </div>
                <div>
                    <div class="modal-label">Hospital / Lab</div>
                    <span id="apr-hospital" style="font-size:.88rem; font-weight:600; color:var(--blue-800);">—</span>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="modal-actions button btn-cancel" onclick="closeModal('approveModal')">Cancel</button>
            <button class="modal-actions button btn-confirm-approve" onclick="submitApprove()">
                <i class="fas fa-check"></i> Yes, Approve
            </button>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════
     MODAL: Reject with Reason
═════════════════════════════════════════════════════════ -->
<div id="rejectModal" class="modal-overlay" onclick="closeOnBackdrop(event)">
    <div class="modal-card">
        <div class="modal-icon" style="background:#fee2e2; color:#dc2626;">
            <i class="fas fa-times-circle"></i>
        </div>
        <h3 class="modal-title">Reject Appointment</h3>
        <p class="modal-sub">Please provide a reason for rejection. This will be saved and cannot be changed later.</p>
        <div class="modal-info">
            <div class="modal-label">Appointment</div>
            <strong id="rej-title">—</strong>
            <div style="margin-top:.4rem;">
                <div class="modal-label">Date</div>
                <span id="rej-date" style="font-size:.88rem; font-weight:600; color:var(--blue-800);">—</span>
            </div>
        </div>
        <div>
            <label class="modal-label" for="reject-reason">Rejection Reason <span style="color:#dc2626;">*</span></label>
            <textarea id="reject-reason" class="modal-textarea" placeholder="Enter reason for rejection (e.g., scheduling conflict, medical condition, etc.)..."></textarea>
            <div id="rej-reason-err" style="color:#dc2626; font-size:.8rem; margin-top:.3rem; display:none;">
                <i class="fas fa-exclamation-circle"></i> Please enter a rejection reason.
            </div>
        </div>
        <div class="modal-actions">
            <button class="modal-actions button btn-cancel" onclick="closeModal('rejectModal')">Cancel</button>
            <button class="modal-actions button btn-confirm-reject" onclick="submitReject()">
                <i class="fas fa-times"></i> Confirm Rejection
            </button>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════
     MODAL: Reschedule Request
═════════════════════════════════════════════════════════ -->
<div id="rescheduleModal" class="modal-overlay" onclick="closeOnBackdrop(event)">
    <div class="modal-card">
        <div class="modal-icon" style="background:var(--blue-50); color:var(--blue-700);">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <h3 class="modal-title">Request Another Date</h3>
        <p class="modal-sub">Suggest a new date and provide a brief reason. The hospital will review your request.</p>
        <div class="modal-info">
            <div class="modal-label">Appointment</div>
            <strong id="res-title">—</strong>
            <div style="margin-top:.4rem;">
                <div class="modal-label">Current Date</div>
                <span id="res-date" style="font-size:.88rem; font-weight:600; color:var(--blue-800);">—</span>
            </div>
        </div>

        <div style="display:grid; gap:.65rem;">
            <div>
                <label class="modal-label" for="res-proposed-date">Proposed Date <span style="color:#dc2626;">*</span></label>
                <input id="res-proposed-date" type="date" class="modal-textarea" style="min-height:auto; height:44px; padding:.55rem .75rem;" />
            </div>
            <div>
                <label class="modal-label" for="res-reason">Reason <span style="color:#dc2626;">*</span></label>
                <textarea id="res-reason" class="modal-textarea" placeholder="e.g., Scheduling conflict / travel / work commitments..."></textarea>
                <div id="res-err" style="color:#dc2626; font-size:.8rem; margin-top:.3rem; display:none;">
                    <i class="fas fa-exclamation-circle"></i> Please enter a proposed date and reason.
                </div>
            </div>
        </div>

        <div class="modal-actions">
            <button class="modal-actions button btn-cancel" onclick="closeModal('rescheduleModal')">Cancel</button>
            <button class="modal-actions button" style="background:linear-gradient(135deg,#1d4ed8,#0b4a86); color:#fff; border:none;" onclick="submitReschedule()">
                <i class="fas fa-paper-plane"></i> Send Request
            </button>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════
     MODAL: Detail View (from calendar / history click)
═════════════════════════════════════════════════════════ -->
<div id="detailModal" class="modal-overlay" onclick="closeOnBackdrop(event)">
    <div class="modal-card">
        <div style="display:flex; align-items:center; gap:.75rem; margin-bottom:1rem;">
            <div id="det-icon" class="modal-icon" style="background:var(--blue-50); color:var(--blue-600); flex-shrink:0;">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div>
                <h3 class="modal-title" id="det-title" style="margin-bottom:.15rem;">—</h3>
                <span id="det-badge" class="badge">—</span>
            </div>
        </div>
        <div class="modal-info">
            <div class="detail-row">
                <div class="detail-field">
                    <label>Date</label>
                    <span id="det-date">—</span>
                </div>
                <div class="detail-field">
                    <label>Hospital / Lab</label>
                    <span id="det-hospital">—</span>
                </div>
            </div>
            <div style="margin-top:.75rem;" id="det-notes-wrap">
                <div class="detail-field">
                    <label>Notes / Reason</label>
                    <span id="det-notes" style="font-style:italic;">—</span>
                </div>
            </div>
        </div>
        <button onclick="closeModal('detailModal')" class="d-btn d-btn--outline" style="width:100%; margin-top:.5rem;">
            Close
        </button>
    </div>
</div>

<!-- Toast notification -->
<div id="apt-toast"></div>

<script>
/* ═══════════════════════════════════════════════
   Calendar Data (from PHP)
═══════════════════════════════════════════════ */
const CAL_DATA  = <?= json_encode(array_map(function($k, $v) { return ['date'=>$k,'class'=>$v['class'],'entries'=>$v['entries']]; }, array_keys($calMap), $calMap)) ?>;
const TODAY_STR = '<?= $today ?>';
const ROOT      = '<?= ROOT ?>';

let calYear  = <?= $curYear ?>;
let calMonth = <?= $curMonth ?>; // 1-based

/* ─── Render calendar ─────────────────────── */
function renderCal() {
    const months = ['January','February','March','April','May','June',
                    'July','August','September','October','November','December'];
    document.getElementById('cal-title').innerText = months[calMonth-1] + ' ' + calYear;

    const body    = document.getElementById('cal-body');
    body.innerHTML = '';

    const firstDay   = new Date(calYear, calMonth-1, 1).getDay();
    const daysInMon  = new Date(calYear, calMonth, 0).getDate();

    for (let i = 0; i < firstDay; i++) body.appendChild(Object.assign(document.createElement('div')));

    for (let d = 1; d <= daysInMon; d++) {
        const dateStr = calYear + '-' +
            String(calMonth).padStart(2,'0') + '-' +
            String(d).padStart(2,'0');

        const div = document.createElement('div');
        div.className = 'cal-day';
        div.innerText = d;

        // today ring
        if (dateStr === TODAY_STR) div.classList.add('is-today');

        // appointment colour
        const entry = CAL_DATA.find(e => e.date === dateStr);
        if (entry) {
            div.classList.add(entry.class, 'clickable');
            div.title = entry.entries.map(e => e.test_type).join(', ');
            div.onclick = () => openCalDetail(entry.entries);
        }
        body.appendChild(div);
    }
}
renderCal();

function calNav(dir) {
    calMonth += dir;
    if (calMonth > 12) { calMonth = 1; calYear++; }
    if (calMonth < 1)  { calMonth = 12; calYear--; }
    renderCal();
}

/* ─── Active appointment ID ──────────────── */
let _activeId = null;

/* ─── Approve modal ──────────────────────── */
function openApprove(id, btn) {
    _activeId = id;
    const data = JSON.parse(btn.dataset.apt);
    document.getElementById('apr-title').innerText    = data.test_type;
    document.getElementById('apr-date').innerText     = data.date;
    document.getElementById('apr-hospital').innerText = data.hospital;
    document.getElementById('approveModal').classList.add('active');
}

function submitApprove() {
    sendAction('approve', _activeId, '', function(ok, msg) {
        closeModal('approveModal');
        showToast(ok, msg);
        if (ok) lockCard(_activeId, 'Approved');
    });
}

/* ─── Reject modal ───────────────────────── */
function openReject(id, btn) {
    _activeId = id;
    const data = JSON.parse(btn.dataset.apt);
    document.getElementById('rej-title').innerText = data.test_type;
    document.getElementById('rej-date').innerText  = data.date;
    document.getElementById('reject-reason').value = '';
    document.getElementById('rej-reason-err').style.display = 'none';
    document.getElementById('rejectModal').classList.add('active');
}

function submitReject() {
    const reason = document.getElementById('reject-reason').value.trim();
    if (!reason) {
        document.getElementById('rej-reason-err').style.display = 'block';
        return;
    }
    document.getElementById('rej-reason-err').style.display = 'none';
    sendAction('reject', _activeId, reason, function(ok, msg) {
        closeModal('rejectModal');
        showToast(ok, msg);
        if (ok) lockCard(_activeId, 'Rejected');
    });
}

/* ─── Reschedule modal ───────────────────── */
function openReschedule(id, btn) {
    _activeId = id;
    const data = JSON.parse(btn.dataset.apt);
    document.getElementById('res-title').innerText = data.test_type;
    document.getElementById('res-date').innerText  = data.date;
    document.getElementById('res-proposed-date').value = '';
    document.getElementById('res-reason').value = '';
    document.getElementById('res-err').style.display = 'none';
    document.getElementById('rescheduleModal').classList.add('active');
}

function submitReschedule() {
    const proposed = document.getElementById('res-proposed-date').value;
    const reason = document.getElementById('res-reason').value.trim();
    if (!proposed || !reason) {
        document.getElementById('res-err').style.display = 'block';
        return;
    }
    document.getElementById('res-err').style.display = 'none';

    sendAction('reschedule', _activeId, reason, function(ok, msg) {
        closeModal('rescheduleModal');
        showToast(ok, msg);
        if (ok) setTimeout(() => location.reload(), 1200);
    }, { proposed_date: proposed });
}

/* ─── Detail modal ───────────────────────── */
function openDetail(data) {
    const badgeMap = {
        'Approved': {cls:'badge-green', icon:'fa-check-circle'},
        'Rejected': {cls:'badge-red',   icon:'fa-times-circle'},
        'Pending':  {cls:'badge-yellow',icon:'fa-clock'},
        'Reschedule Requested': {cls:'badge-red', icon:'fa-calendar-alt'},
    };
    const bm = badgeMap[data.status] || {cls:'badge-blue', icon:'fa-history'};

    document.getElementById('det-title').innerText    = data.test_type;
    document.getElementById('det-date').innerText     = data.date;
    document.getElementById('det-hospital').innerText = data.hospital;

    const badge = document.getElementById('det-badge');
    badge.className = 'badge ' + bm.cls;
    badge.innerHTML = `<i class="fas ${bm.icon}"></i> ${data.status}`;

    const notesWrap = document.getElementById('det-notes-wrap');
    const notesEl   = document.getElementById('det-notes');
    if (data.notes) {
        notesEl.innerText = data.notes;
        notesWrap.style.display = 'block';
    } else {
        notesWrap.style.display = 'none';
    }

    document.getElementById('detailModal').classList.add('active');
}

function openCalDetail(entries) {
    if (entries.length === 1) { openDetail(entries[0]); return; }
    // For multiple appointments on same day, open the first
    openDetail(entries[0]);
}

/* ─── AJAX action ────────────────────────── */
function sendAction(action, id, reason, cb, extra = null) {
    const fd = new FormData();
    fd.append('action', action);
    fd.append('id', id);
    if (reason) fd.append('reason', reason);
    if (extra && typeof extra === 'object') {
        Object.entries(extra).forEach(([k, v]) => {
            if (v !== undefined && v !== null && String(v) !== '') fd.append(k, v);
        });
    }

    fetch(ROOT + '/donor/appointment-action', {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(async (r) => {
            const ct = (r.headers.get('content-type') || '').toLowerCase();
            const text = await r.text();
            if (!ct.includes('application/json')) {
                console.error('Non-JSON response from appointment-action:', text);
                return cb(false, 'Server returned an unexpected response.');
            }
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', text);
                return cb(false, 'Server returned invalid JSON.');
            }
            cb(!!data.success, data.message || (data.success ? 'Done' : 'Error'));
        })
        .catch((e) => {
            console.error('Network error:', e);
            cb(false, 'Network error');
        });
}

/* ─── Lock card UI after action ─────────── */
function lockCard(id, newStatus) {
    const card = document.getElementById('apt-card-' + id);
    if (!card) { setTimeout(()=> location.reload(), 900); return; }

    // Update badge
    const badge = card.querySelector('.badge');
    const badgeMap = {
        'Approved': {cls:'badge-green', icon:'fa-check-circle'},
        'Rejected': {cls:'badge-red',   icon:'fa-times-circle'},
    };
    const bm = badgeMap[newStatus];
    if (badge && bm) {
        badge.className = 'badge ' + bm.cls;
        badge.innerHTML = `<i class="fas ${bm.icon}"></i> ${newStatus}`;
    }

    // Remove action buttons row, replace with locked indicator (layout-safe)
    const actionsRow = card.querySelector('.apt-actions-row');
    if (actionsRow) {
        actionsRow.className = 'apt-locked-row';
        actionsRow.style.display = 'block';
        actionsRow.style.paddingTop = '.5rem';
        actionsRow.style.borderTop = '1px solid #f1f5f9';
        actionsRow.style.fontSize = '.78rem';
        actionsRow.style.color = 'var(--g400)';
        actionsRow.style.fontStyle = 'italic';
        actionsRow.innerHTML = '<i class="fas fa-lock"></i> Action locked — decision already recorded.';
    }

    // Reload cal after short delay so colours update
    setTimeout(() => location.reload(), 1200);
}

/* ─── Helpers ────────────────────────────── */
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}
function closeOnBackdrop(e) {
    if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('active');
}
function showToast(success, msg) {
    const t = document.getElementById('apt-toast');
    t.className = 'show ' + (success ? 'toast-success' : 'toast-error');
    t.innerText = (success ? '✓ ' : '✕ ') + msg;
    setTimeout(() => { t.className = ''; }, 3500);
}
</script>

<?php include __DIR__ . '/inc/footer.view.php'; ?>
