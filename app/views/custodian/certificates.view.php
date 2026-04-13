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

    <?php if (empty($certificates) && empty($appreciation_letters)): ?>
        <div class="cp-empty-state">
            <i class="fas fa-file-contract cp-empty-state__icon"></i>
            <div class="cp-empty-state__msg">Recognition Documents in Process</div>
            <div class="cp-empty-state__sub">
                Official appreciation letters and certificates will be available here once the host institution finalizes the donation process.
            </div>
            <a href="<?= ROOT ?>/custodian/dashboard" class="cp-btn cp-btn--primary">Return to Dashboard</a>
        </div>
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
