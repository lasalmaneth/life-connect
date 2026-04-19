<?php
/**
 * Custodian Portal — Report Death View
 * 
 * @var object|false|null $death_declaration
 */
$page_icon     = 'fa-heart-crack';
$page_heading  = 'Report Donor Death';
$page_subtitle = 'The entry point for initiating post-death donation protocols.';
$extra_css     = ['custodian/report_death.css'];

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <?php if ($death_declaration): ?>
        <!-- Leadership Indicator -->
        <?php if (!$isLeader): ?>
            <div class="cp-notice cp-notice--info mb-4 shadow-sm" style="border-radius: 12px; border-left: 4px solid var(--blue-500); background: #f0f9ff;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--blue-600); box-shadow: 0 2px 4px rgba(0,0,0,0.05); flex-shrink: 0;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--blue-900); font-size: 0.95rem;">Process Leader Assigned</div>
                        <div style="color: var(--blue-700); font-size: 0.85rem; font-weight: 500;">
                            This case is being managed by <strong><?= htmlspecialchars($death_declaration->declared_by_name) ?></strong>.
                            You have view-only access to follow the progress.
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="cp-report-card shadow-sm mx-auto" style="max-width: 440px; background: white; border-radius: 16px; padding: 28px; text-align: center; border: 1px solid var(--g200); position: relative; overflow: hidden; margin-top: 2rem;">
            <!-- Decorative Accent -->
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(to right, var(--blue-500), var(--blue-700));"></div>

            <div class="mb-5">
                <div style="width: 56px; height: 56px; background: #f0fdf4; color: #15803d; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.4rem; box-shadow: 0 0 0 8px #f8fafc;">
                    <i class="fas fa-check"></i>
                </div>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--g800); margin-bottom: 4px;">Report Completed</h2>
                <p style="color: var(--g500); font-weight: 500; font-size: 0.9rem;">Declaration filed and recorded.</p>
            </div>

            <!-- Data Breakdown -->
            <div style="background: #f8fafc; border: 1px solid var(--g100); border-radius: 12px; padding: 18px; text-align: left; margin-bottom: 24px; display: flex; flex-direction: column; gap: 14px;">
                
                <div style="display: flex; gap: 12px; align-items: center;">
                    <div style="width: 32px; height: 32px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--blue-600); border: 1px solid var(--g100); flex-shrink: 0; font-size: 0.85rem;">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; font-weight: 700; color: var(--g400); text-transform: uppercase; letter-spacing: 0.05em;">Date & Time</div>
                        <div style="color: var(--g800); font-weight: 700; font-size: 0.95rem;">
                            <?= date('M j, Y', strtotime($death_declaration->date_of_death)) ?> <span style="color: var(--g300); margin: 0 4px;">•</span> <?= date('h:i A', strtotime($death_declaration->time_of_death)) ?>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; align-items: flex-start;">
                    <div style="width: 32px; height: 32px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--blue-600); border: 1px solid var(--g100); flex-shrink: 0; font-size: 0.85rem; margin-top: 2px;">
                        <i class="fas fa-hospital-user"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; font-weight: 700; color: var(--g400); text-transform: uppercase; letter-spacing: 0.05em;">Place of Death</div>
                        <div style="color: var(--g800); font-weight: 600; font-size: 0.9rem; line-height: 1.4;">
                            <?= htmlspecialchars($death_declaration->place_of_death) ?>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; align-items: flex-start;">
                    <div style="width: 32px; height: 32px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--blue-600); border: 1px solid var(--g100); flex-shrink: 0; font-size: 0.85rem; margin-top: 2px;">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; font-weight: 700; color: var(--g400); text-transform: uppercase; letter-spacing: 0.05em;">Primary Cause</div>
                        <div style="color: var(--g800); font-weight: 600; font-size: 0.9rem; line-height: 1.4;">
                            <?= nl2br(htmlspecialchars($death_declaration->cause_of_death)) ?>
                        </div>
                    </div>
                </div>

            </div>

            <a href="<?= ROOT ?>/custodian/dashboard" class="cp-btn-dashboard">
                Return to Dashboard <i class="fas fa-chevron-right" style="margin-left: 8px; font-size: 0.75rem;"></i>
            </a>

            <div style="margin-top: 20px; font-size: 0.75rem; color: var(--g400); font-weight: 500;">
                <i class="fas fa-shield-halved" style="margin-right: 4px;"></i> Secure ID: #TXN-<?= str_pad($death_declaration->id, 6, '0', STR_PAD_LEFT) ?>
            </div>
        </div>

    <?php else: ?>

        <div class="cp-section-card cp-max-w-xl">
            <div class="cp-section-card__header">
                <div class="cp-section-card__title">Reporting Form</div>
            </div>
            <div class="cp-section-card__body">
                <form id="report-death-form">
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <div class="mb-4" style="flex: 1; min-width: 250px;">
                            <label class="cp-form-label mb-2">Date of Death <span class="cp-required">*</span></label>
                            <div style="position: relative;">
                                <i class="fas fa-calendar-alt" style="position: absolute; left: 16px; top: 16px; color: var(--g500);"></i>
                                <input type="text" id="datePicker" name="date_of_death" class="cp-form-control" style="padding-left: 45px; background: white; cursor: pointer;" placeholder="Select Date" required>
                            </div>
                        </div>

                        <div class="mb-4" style="flex: 1; min-width: 250px;">
                            <label class="cp-form-label mb-2">Time of Death <span class="cp-required">*</span></label>
                            <div style="position: relative;">
                                <i class="fas fa-clock" style="position: absolute; left: 16px; top: 16px; color: var(--g500);"></i>
                                <input type="text" id="timePicker" name="time_of_death" class="cp-form-control" style="padding-left: 45px; background: white; cursor: pointer;" placeholder="Select Time (AM/PM)" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="cp-form-label mb-2">Place of Death <span class="cp-required">*</span></label>
                        <input type="text" name="place_of_death" class="cp-form-control" placeholder="e.g. Hospital name, Home address" required>
                    </div>

                    <div class="mb-4">
                        <label class="cp-form-label mb-2">Primary Cause of Death <span class="cp-required">*</span></label>
                        <textarea name="cause_of_death" class="cp-form-control" rows="2" placeholder="As stated by medical professional" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="cp-form-label mb-2">Additional Notes</label>
                        <textarea name="additional_notes" class="cp-form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-5" style="background: #fdf2f2; border: 1px solid #fee2e2; border-radius: 12px; padding: 20px;">
                        <label class="cp-form-label mb-2" style="color: #991b1b; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-brain"></i> Was the donor declared brain dead? <span class="cp-required">*</span>
                        </label>
                        <p style="font-size: 0.8rem; color: #b91c1c; margin-bottom: 12px; font-weight: 500;">This information is critical for determining organ recovery eligibility by hospital teams.</p>
                        
                        <div style="display: flex; gap: 24px; flex-wrap: wrap;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600; color: #991b1b;">
                                <input type="radio" name="is_brain_dead" value="1" required style="width: 18px; height: 18px; accent-color: #dc2626;"> Yes
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600; color: #991b1b;">
                                <input type="radio" name="is_brain_dead" value="0" style="width: 18px; height: 18px; accent-color: #dc2626;"> No
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600; color: #991b1b;">
                                <input type="radio" name="is_brain_dead" value="-1" style="width: 18px; height: 18px; accent-color: #dc2626;"> Unknown
                            </label>
                        </div>
                    </div>

                    <div class="cp-notice cp-notice--warning mb-4">
                        <i class="fas fa-triangle-exclamation"></i>
                        <p class="cp-text-xs">Warning: Reporting a death is irreversible and will immediately trigger donation case logic based on the donor's consent.</p>
                    </div>

                    <div style="display:flex; justify-content:center; margin-top: 2rem;">
                        <button type="button" id="submit-btn" class="cp-submit-elegant">
                            <i class="fas fa-heart-crack"></i> Submit Death Report
                        </button>
                    </div>
                </form>
            </div>
        </div>

    <?php endif; ?>

</div>

<script>
document.getElementById('submit-btn')?.addEventListener('click', async () => {
    const form = document.getElementById('report-death-form');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const isConfirmed = await cpNotify.confirm(
        'Critical Confirmation', 
        'Are you absolutely sure you want to report this death? This will initiate the donation protocols and is irreversible.'
    );
    
    if (!isConfirmed) return;

    try {
        const response = await fetch('<?= ROOT ?>/api/custodian/declare-death', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            window.location.href = result.redirect;
        } else {
            cpNotify.alert('Submission Error', result.error || 'Failed to submit report', 'error');
        }
    } catch (e) {
        cpNotify.alert('System Error', 'An error occurred. Please try again.', 'error');
    }
});

// Initialize beautifully styled Calendar & Time selector
document.addEventListener('DOMContentLoaded', function() {
    flatpickr("#datePicker", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        maxDate: "today",
        disableMobile: "true",
        altInputClass: "cp-form-control flatpickr-padded"
    });

    flatpickr("#timePicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        altInput: true,
        altFormat: "h:i K",
        disableMobile: "true",
        altInputClass: "cp-form-control flatpickr-padded",
        minuteIncrement: 1,
        time_24hr: false
    });
});
</script>


<!-- Flatpickr Library for elegant dateTime inputs -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
