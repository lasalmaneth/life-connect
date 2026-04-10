<?php
/**
 * Custodian Portal — Unified Dashboard View
 */
$page_icon     = 'fa-chart-line';
$page_heading  = 'Dashboard';
$page_subtitle = 'The central command center for donor obligations.';

$isBodyFlow  = (($consent['donation_type'] ?? '') === 'BODY' || ($consent['donation_type'] ?? '') === 'BODY_AND_CORNEA');
$isOrganFlow = (($consent['donation_type'] ?? '') === 'ORGAN');

ob_start();
?>

<style>
/* Dashboard Critical Overrides */
.cp-stat__value { font-size: 1.1rem !important; font-weight: 800; line-height: 1.2; }
.cp-stat__label { font-size: 0.65rem !important; margin-bottom: 2px !important; }

/* Registration-Style Stepper Logic */
.p-track-container {
    background: #fff; border-radius: 16px; padding: 2.2rem 1.5rem 1.5rem;
    position: relative; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    margin-bottom: 2rem; border: 1px solid #e5e7eb;
}
.p-track-label {
    position: absolute; top: 0; left: 0; right: 0; color: #005baa;
    font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
    padding: 0.7rem 0; letter-spacing: 0.1em; text-align: center;
    border-bottom: 1px solid #f0f7fd; background: #fcfdfe;
}
.progress-steps { display: flex; justify-content: space-between; position: relative; margin-top: 1.8rem; }
.progress-line { position: absolute; top: 18px; left: 40px; right: 40px; height: 2px; background: #f3f4f6; z-index: 1; }
.step { position: relative; z-index: 2; text-align: center; flex: 1; display: flex; flex-direction: column; align-items: center; gap: 8px; }
.step-circle {
    width: 36px; height: 36px; border-radius: 50%; background: #f9fafb; color: #9ca3af;
    display: flex; align-items: center; justify-content: center; margin: 0 auto;
    font-weight: 800; font-size: 13px; transition: all 0.3s; border: 2px solid #f3f4f6;
}
.step-lbl { font-size: 11px; color: #6b7280; font-weight: 700; letter-spacing: .3px; }

/* Step States */
.step.active .step-circle {
    background: #005baa; color: #fff; border-color: #005baa;
    box-shadow: 0 0 0 6px rgba(0, 91, 170, 0.1), 0 0 15px rgba(0, 91, 170, 0.3);
}
.step.active .step-lbl { color: #005baa; }
.step.done .step-circle { background: #10b981; color: #fff; border-color: #10b981; }
.step.done .step-lbl { color: #1e293b; }
.step.danger .step-circle { background: #ef4444; color: #fff; border-color: #ef4444; }

@media (max-width: 800px) {
    .progress-steps { flex-direction: column; align-items: flex-start; gap: 1.5rem; margin-left: 1rem; }
    .progress-line { display: none; }
    .step { flex-direction: row; text-align: left; gap: 1rem; }
    .step::before { content: ''; position: absolute; left: 18px; top: 36px; bottom: -18px; width: 2px; background: #f3f4f6; z-index: -1; }
    .step:last-child::before { display: none; }
}
</style>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<div class="cp-content__body">

    <!-- 1. TOP SUMMARY CARDS -->
    <?php require __DIR__ . '/partials/status-cards.php'; ?>

    <!-- 2. MAIN LOGIC BLOCKS -->
    <?php if (!$death_declaration): ?>
        
        <!-- STATE: DONOR ALIVE -->
        <div class="cp-action-grid cp-action-grid--single mb-4">
            <div style="background: linear-gradient(135deg, #fff0f4 0%, #fbdde4 100%); border-radius: 16px; box-shadow: 0 8px 24px rgba(239, 68, 68, 0.15), inset 0 2px 4px rgba(255, 255, 255, 0.6); cursor: pointer; transition: all 0.3s ease; text-align: center; padding: 2.5rem;" onclick="window.location.href='<?= ROOT ?>/custodian/report-death'" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 30px rgba(239, 68, 68, 0.25), inset 0 2px 4px rgba(255, 255, 255, 0.6)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 24px rgba(239, 68, 68, 0.15), inset 0 2px 4px rgba(255, 255, 255, 0.6)';">
                <div style="margin-bottom: 0.8rem;">
                    <i class="fas fa-heart-crack" style="font-size: 3.5rem; color: #f43f5e; text-shadow: 0 4px 8px rgba(244, 63, 94, 0.4);"></i>
                </div>
                <h2 style="color: #e11d48; margin-bottom: 1rem; font-weight: 700;">Report Donor Death</h2>
                <div style="height: 1px; background: linear-gradient(90deg, transparent, rgba(225, 29, 72, 0.2), transparent); max-width: 500px; margin: 0 auto 1.5rem;"></div>
                <p style="font-size: 1.05rem; color: #334155; max-width: 600px; margin: 0 auto 1.5rem auto; line-height: 1.6;">If the donor has passed away, you must declare it here immediately to initiate the critical donation workflows.</p>
                <div style="display: inline-block; padding: 10px 24px; background: linear-gradient(180deg, #fb7185, #e11d48); color: white; border-radius: 8px; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 10px rgba(225, 29, 72, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.4); border: 1px solid #fda4af;"><i class="fas fa-exclamation-triangle" style="margin-right: 6px;"></i> Mark Donor as Deceased</div>
            </div>
        </div>

    <?php else: ?>

        <!-- DECEASED ROUTING -->
        <?php if (($consent['donation_type'] ?? 'NONE') === 'NONE'): ?>
            <!-- STATE: NO ACTIVE VALID CONSENT -->
            <div class="cp-notice cp-notice--danger mb-4">
                <i class="fas fa-ban fa-2x"></i>
                <div>
                    <strong>No active donation consent available</strong>
                    <p>The system found no legally valid consent to proceed. No further action is required.</p>
                </div>
            </div>

        <?php else: ?>
            
            <?php if (!$activeCase): ?>
                
                <!-- STATE: DONOR MARKED DECEASED (PENDING APPROVALS) -->
                <div class="cp-section-card mb-4 cp-border-warning">
                    <div class="cp-section-card__header cp-bg-amber-100 cp-text-amber-800">
                        <div class="cp-section-card__title"><i class="fas fa-hourglass-half"></i> System Validating Approvals</div>
                    </div>
                    <div class="cp-section-card__body text-center p-5">
                        <i class="fas fa-shield-check fa-4x mb-4 opacity-50"></i>
                        <h4 class="cp-text-xl cp-font-bold mb-2">Validating Legal Steps</h4>
                        <p class="cp-text-g500 mx-auto max-w-500">Waiting for other co-custodians to approve or legal validation to complete.</p>
                    </div>
                </div>

            <?php else: ?>

                <!-- ACTIVE CASE ROUTING (STRICT SPLIT) -->

                <div class="cp-grid-2 mb-4">
                    <div class="col-span-2">
                        <?php 
                        if ($isBodyFlow) {
                            require __DIR__ . '/partials/body-workflow-stepper.php';
                        } elseif ($isOrganFlow) {
                            require __DIR__ . '/partials/organ-workflow-stepper.php';
                        }
                        ?>
                    </div>

                    <!-- Workflow Specific Action Block -->
                    <div class="cp-workflow-action">
                        <?php if ($isBodyFlow): ?>
                            <!-- BODY FLOW SPECIFIC STATES -->
                            <?php if (!$currentInstRequest): ?>
                                <!-- STATE: NO SCHOOL SELECTED YET -->
                                <div class="cp-section-card cp-border-blue-500 h-100">
                                    <div class="cp-section-card__header cp-bg-blue-600 text-white border-0">
                                        <div class="cp-section-card__title text-white"><i class="fas fa-building-columns"></i> Choose a Medical School</div>
                                    </div>
                                    <div class="cp-section-card__body">
                                        <p class="mb-4">Select precisely ONE Medical School from the donor's consent mandate to begin.</p>
                                        <a href="<?= ROOT ?>/custodian/institution-requests" class="cp-btn cp-btn--primary">Open Institution Selection</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php require __DIR__ . '/partials/institution-status-card.php'; ?>
                            <?php endif; ?>

                        <?php elseif ($isOrganFlow): ?>
                            <!-- ORGAN FLOW SPECIFIC STATES -->
                            <?php if (!$currentInstRequest): ?>
                                <!-- STATE: NO HOSPITAL SELECTED YET -->
                                <div class="cp-section-card h-100 cp-border-orange">
                                    <div class="cp-section-card__header cp-bg-orange text-white border-0">
                                        <div class="cp-section-card__title text-white"><i class="fas fa-hospital text-white"></i> Choose Transplantation Hospital</div>
                                    </div>
                                    <div class="cp-section-card__body">
                                        <p class="mb-4">Select a hospital to submit the organ extraction reports.</p>
                                        <a href="<?= ROOT ?>/custodian/institution-requests" class="cp-btn cp-btn--primary cp-bg-orange cp-border-orange">Open Hospital Selection</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php require __DIR__ . '/partials/institution-status-card.php'; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Certificate Logic Card -->
                    <div class="cp-cert-card">
                        <?php require __DIR__ . '/partials/certificate-status-card.php'; ?>
                    </div>
                </div>

            <?php endif; ?>

        <?php endif; ?>

        <!-- 3. SUPPORT SECTIONS -->
        <div class="cp-grid-2">
            <?php require __DIR__ . '/partials/co-custodian-approvals.php'; ?>
            <?php require __DIR__ . '/partials/activity-timeline.php'; ?>
        </div>

    <?php endif; ?>

</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
