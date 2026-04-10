<?php
/**
 * Custodian Portal — Report Death View
 * 
 * @var object|false|null $death_declaration
 */
$page_icon     = 'fa-heart-crack';
$page_heading  = 'Report Donor Death';
$page_subtitle = 'The entry point for initiating post-death donation protocols.';

ob_start();
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <?php if ($death_declaration): ?>
        <div class="cp-section-card cp-max-w-xl mx-auto" style="border-top: 4px solid var(--green-500); box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 500px;">
            <div class="cp-section-card__body p-4">
                <div class="text-center mb-3">
                    <i class="fas fa-check-circle cp-text-success mb-2" style="font-size: 2.5rem;"></i>
                    <h3 style="color: var(--g800); font-weight: 700; margin-bottom: 0.25rem;">Report Completed</h3>
                    <p class="cp-text-muted" style="font-size: 0.95rem;">Details successfully recorded.</p>
                </div>

                <div style="background: var(--g50); border: 1px solid var(--g200); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--g200); padding-bottom: 0.5rem;">
                            <div class="cp-text-xs cp-text-muted text-uppercase fw-bold">Date & Time</div>
                            <div style="font-size: 0.95rem; color: var(--g800); font-weight: 500;">
                                <?= date('M j, Y', strtotime($death_declaration->date_of_death)) ?> at <?= date('h:i A', strtotime($death_declaration->time_of_death)) ?>
                            </div>
                        </div>
                        
                        <div style="border-bottom: 1px dashed var(--g200); padding-bottom: 0.5rem;">
                            <div class="cp-text-xs cp-text-muted text-uppercase fw-bold mb-1">Place of Death</div>
                            <div style="font-size: 0.95rem; color: var(--g800);">
                                <?= htmlspecialchars($death_declaration->place_of_death) ?>
                            </div>
                        </div>

                        <div>
                            <div class="cp-text-xs cp-text-muted text-uppercase fw-bold mb-1">Primary Cause</div>
                            <div style="font-size: 0.95rem; color: var(--g800); line-height: 1.4;">
                                <?= nl2br(htmlspecialchars($death_declaration->cause_of_death)) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="<?= ROOT ?>/custodian/dashboard" class="cp-btn cp-btn--primary cp-btn--sm" style="width: 100%; border-radius: 6px;">
                        Proceed to Dashboard <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
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

    if (!confirm('Are you absolutely sure you want to report this death? This will initiate the donation protocols.')) {
        return;
    }

    try {
        const response = await fetch('<?= ROOT ?>/api/custodian/declare-death', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            window.location.href = result.redirect;
        } else {
            alert(result.error || 'Failed to submit report');
        }
    } catch (e) {
        alert('An error occurred. Please try again.');
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
        altFormat: "h:i K", // 12-hour format with AM/PM
        disableMobile: "true",
        altInputClass: "cp-form-control flatpickr-padded"
    });
});
</script>

<style>
.flatpickr-padded {
    padding-left: 45px !important;
    background: white !important;
    cursor: pointer !important;
}

.cp-submit-elegant {
    width: 100%;
    max-width: 320px;
    padding: 14px 24px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 12px;
    background: linear-gradient(135deg, #fb7185 0%, #e11d48 100%);
    color: white;
    border: 1px solid #fda4af;
    box-shadow: 0 8px 24px rgba(225, 29, 72, 0.25), inset 0 2px 4px rgba(255, 255, 255, 0.3);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
}
.cp-submit-elegant:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(225, 29, 72, 0.35), inset 0 2px 4px rgba(255, 255, 255, 0.45);
}
.cp-submit-elegant:active {
    transform: translateY(1px);
    box-shadow: 0 4px 12px rgba(225, 29, 72, 0.2);
}
</style>

<!-- Flatpickr Library for elegant dateTime inputs -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
