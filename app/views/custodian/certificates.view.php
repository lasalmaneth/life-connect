<?php
/**
 * Custodian Portal — Recognition & Appreciation View
 */
$page_icon     = 'fa-award';
$page_heading  = 'Recognition & Appreciation';
$page_subtitle = 'Official appreciation letters and donation certificates honoring the donor.';

ob_start();

// Fetch URLs for the premium modal bundle
$certUrl = !empty($certificates) ? ROOT . "/medical-school/certificates/view?id=" . $certificates[0]->id : '';
$letterUrl = !empty($appreciation_letters) ? ROOT . "/medical-school/appreciation/view?id=" . $appreciation_letters[0]->id : '';
?>

<?php include __DIR__ . '/partials/page-header.php'; ?>

<!-- Include the Premium Modal (Shared Component) -->
<?php require __DIR__ . '/partials/recognition-viewer.php'; // Resolves PHP warnings ?>

<div class="cp-content__body">

    <?php if (!$activeCase || $activeCase->overall_status !== 'COMPLETED' || (empty($certificates) && empty($appreciation_letters))): ?>
        <div class="cp-empty-state-premium mt-12">
            <div class="cp-empty-state-premium__content">
                <div class="cp-empty-state-premium__icon-wrap">
                    <div class="cp-empty-state-premium__pulse"></div>
                    <i class="fas fa-file-contract cp-empty-state-premium__icon"></i>
                </div>
                
                <h2 class="cp-empty-state-premium__title">Recognition Documents in Process</h2>
                <p class="cp-empty-state-premium__msg mb-8">
                    Official appreciation letters and certificates honor the donor's generous gift. 
                    These will become available here once the host institution confirms the final donation completion.
                </p>

                <div class="cp-empty-state-premium__actions mt-6">
                    <a href="<?= ROOT ?>/custodian/dashboard" class="cp-btn cp-btn--primary">
                        <i class="fas fa-arrow-left"></i> Return to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <style>
            .cp-empty-state-premium {
                max-width: 600px;
                margin: 4rem auto;
                padding: 3rem 2rem;
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                border-radius: 24px;
                text-align: center;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
                animation: cpFadeIn 0.8s ease-out;
            }
            
            .cp-empty-state-premium__icon-wrap {
                position: relative;
                width: 90px;
                height: 90px;
                margin: 0 auto 2rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .cp-empty-state-premium__pulse {
                position: absolute;
                inset: 0;
                background: var(--blue-100);
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
                animation: morphing 10s infinite linear forwards;
                opacity: 0.6;
            }
            
            .cp-empty-state-premium__icon {
                font-size: 2.25rem;
                color: var(--blue-600);
                position: relative;
                z-index: 2;
            }
            
            .cp-empty-state-premium__title {
                font-size: 1.5rem;
                font-weight: 800;
                color: var(--slate);
                margin-bottom: 1rem;
                letter-spacing: -0.02em;
            }
            
            .cp-empty-state-premium__msg {
                font-size: 0.95rem;
                line-height: 1.6;
                color: var(--g500);
                max-width: 460px;
                margin: 0 auto;
            }
            
            /* Stepper Style */
            .cp-progress-stepper {
                display: flex;
                justify-content: space-between;
                position: relative;
                max-width: 440px;
                margin: 2rem auto;
            }
            
            .cp-progress-stepper::before {
                content: '';
                position: absolute;
                top: 7px;
                left: 0;
                right: 0;
                height: 2px;
                background: var(--g200);
                z-index: 1;
            }
            
            .cp-progress-step {
                position: relative;
                z-index: 2;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
            }
            
            .cp-progress-step__dot {
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background: var(--white);
                border: 2px solid var(--g300);
                transition: all 0.3s ease;
            }
            
            .cp-progress-step.active .cp-progress-step__dot {
                background: var(--blue-600);
                border-color: var(--blue-600);
                box-shadow: 0 0 0 4px var(--blue-50);
            }
            
            .cp-progress-step.current .cp-progress-step__dot {
                border-color: var(--blue-600);
                background: var(--white);
                animation: dotPulse 2s infinite;
            }
            
            .cp-progress-step__label {
                font-size: 0.72rem;
                font-weight: 700;
                color: var(--g400);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            
            .cp-progress-step.active .cp-progress-step__label,
            .cp-progress-step.current .cp-progress-step__label {
                color: var(--slate);
            }
            
            @keyframes morphing {
                0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
                25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
                50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
                75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
                100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            }
            
            @keyframes dotPulse {
                0% { box-shadow: 0 0 0 0 rgba(0, 91, 170, 0.4); }
                70% { box-shadow: 0 0 0 10px rgba(0, 91, 170, 0); }
                100% { box-shadow: 0 0 0 0 rgba(0, 91, 170, 0); }
            }
        </style>
    <?php else: ?>
        <div class="cp-grid-3">
            <!-- Donation Certificates -->
            <?php foreach ($certificates as $cert): ?>
                <?php $thisCertUrl = ROOT . "/medical-school/certificates/view?id=" . $cert->id; ?>
                <div class="cp-info-card cp-card-cert">
                    <div class="cp-info-card__header">
                        <div class="cp-info-card__title"><i class="fas fa-award"></i> Donation Certificate</div>
                    </div>
                    <div class="cp-info-card__body p-4 text-center">
                        <h4 class="mb-2 cp-font-bold text-slate"><?= htmlspecialchars($cert->institution_name) ?></h4>
                        <div class="cp-text-sm cp-text-g500 mb-4">Issued: <?= date('F j, Y', strtotime($cert->issued_at ?? $cert->created_at)) ?></div>
                        <button onclick="openRecognitionBundle('<?= $thisCertUrl ?>', '')" class="cp-btn cp-btn--outline cp-w-100">
                            <i class="fas fa-eye cp-mr-2"></i> View Certificate
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Appreciation Letters -->
            <?php foreach ($appreciation_letters as $letter): ?>
                <?php $thisLetterUrl = ROOT . "/medical-school/appreciation/view?id=" . $letter->id; ?>
                <div class="cp-info-card cp-card-appreciation">
                    <div class="cp-info-card__header">
                        <div class="cp-info-card__title text-info"><i class="fas fa-envelope-open-text"></i> Appreciation Letter</div>
                    </div>
                    <div class="cp-info-card__body p-4 text-center">
                        <h4 class="mb-2 cp-font-bold text-slate"><?= htmlspecialchars($letter->institution_name) ?></h4>
                        <div class="cp-text-sm cp-text-g500 mb-4">Issued: <?= date('F j, Y', strtotime($letter->issued_at ?? $letter->created_at)) ?></div>
                        <button onclick="openRecognitionBundle('', '<?= $thisLetterUrl ?>')" class="cp-btn cp-btn--outline cp-w-100">
                            <i class="fas fa-file-alt cp-mr-2"></i> View Appreciation
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php
$page_content = ob_get_clean();
require dirname(__DIR__) . '/layouts/custodian.layout.php';
?>
