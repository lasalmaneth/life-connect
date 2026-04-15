<?php
/**
 * Custodian Portal — Unified Dashboard View
 */
$page_icon     = 'fa-chart-line';
$page_heading  = 'Dashboard';
$page_subtitle = 'The central command center for donor obligations.';

$isBodyFlow  = (($consent['donation_type'] ?? '') === 'BODY' || ($consent['donation_type'] ?? '') === 'BODY_AND_CORNEA');
$isOrganFlow = (($consent['donation_type'] ?? '') === 'ORGAN');
$extra_css   = ['custodian/dashboard.css'];

ob_start();
?>


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
                <i class="fas fa-heart-crack"></i>
                <h2>Report Donor Death</h2>
                <div class="divider"></div>
                <p>If the donor has passed away, you must declare it here immediately to initiate the critical donation workflows.</p>
                <div class="btn-fake"><i class="fas fa-exclamation-triangle" style="margin-right: 6px;"></i> Mark Donor as Deceased</div>
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
            
            <!-- Global Read-Only Notice for Co-Custodians -->
            <?php include __DIR__ . '/partials/lock-notice.php'; ?>
            
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
                                <div class="cp-section-card h-full cp-border-blue-500">
                                    <div class="cp-section-card__header cp-bg-blue-600 text-white border-0">
                                        <div class="cp-section-card__title text-white"><i class="fas fa-building-columns"></i> Choose a Medical School</div>
                                    </div>
                                    <div class="cp-section-card__body">
                                        <p class="mb-4">Select precisely ONE Medical School from the donor's consent mandate to begin.</p>
                                        <?php if ($isLeader): ?>
                                            <a href="<?= ROOT ?>/custodian/institution-requests" class="cp-btn cp-btn--primary cp-btn--fw">Open Institution Selection</a>
                                        <?php else: ?>
                                            <button class="cp-btn cp-btn--primary cp-btn--fw cp-btn--locked" disabled>Selection Locked</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php require __DIR__ . '/partials/institution-status-card.php'; ?>
                            <?php endif; ?>

                        <?php elseif ($isOrganFlow): ?>
                            <!-- ORGAN FLOW SPECIFIC STATES -->
                            <?php if (!$currentInstRequest): ?>
                                <!-- STATE: NO HOSPITAL SELECTED YET -->
                                <div class="cp-section-card h-full cp-border-orange">
                                    <div class="cp-section-card__header cp-bg-orange text-white border-0">
                                        <div class="cp-section-card__title text-white"><i class="fas fa-hospital text-white"></i> Choose Transplantation Hospital</div>
                                    </div>
                                    <div class="cp-section-card__body">
                                        <p class="mb-4">Select a hospital to submit the organ extraction reports.</p>
                                        <?php if ($isLeader): ?>
                                            <a href="<?= ROOT ?>/custodian/institution-requests" class="cp-btn cp-btn--primary cp-btn--fw cp-bg-orange cp-border-orange">Open Hospital Selection</a>
                                        <?php else: ?>
                                            <button class="cp-btn cp-btn--primary cp-btn--fw cp-btn--locked" disabled>Selection Locked</button>
                                        <?php endif; ?>
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
