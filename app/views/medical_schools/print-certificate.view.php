<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Appreciation | LifeConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/medicalschools/certificate.css?v=<?= time() ?>">
</head>
<body>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'MEDICAL_SCHOOL'): ?>
    <div class="controls">
        <?php 
            $backUrl = (isset($_GET['from']) && $_GET['from'] === 'examinations') 
                ? ROOT . '/medical-school/final-examinations' 
                : ROOT . '/medical-school/usage-logs';
        ?>
        <a href="<?= $backUrl ?>" class="btn-action">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <button onclick="window.print()" class="btn-action primary">
            <i class="fas fa-print"></i> Print Certificate
        </button>
    </div>
<?php endif; ?>

    <div class="certificate-container">
        <div class="cp-seal">
            <img src="<?= ROOT ?>/assets/images/logo.png" alt="LifeConnect">
        </div>

        <h1 class="cp-h1">Certificate of Appreciation</h1>
        <div class="cp-sub">In Recognition of Full Body Donation</div>

        <div class="cp-rule"></div>

        <div class="cp-pre">Presented to</div>
        <div class="cp-rec"><?= htmlspecialchars(($certificate->first_name ?? 'Donor') . ' ' . ($certificate->last_name ?? '')) ?></div>

        <div class="cp-body">
            This certificate is presented in honour of the extraordinary and selfless act of whole body donation to medical education and research. The Faculty of Medicine acknowledges this gift with the deepest respect and gratitude.
        </div>

        <div class="cp-frule"></div>

        <div class="cp-foot">
            Faculty of Medicine Recognition · <?= htmlspecialchars($certificate->school_name ?? 'LifeConnect Programme') ?>
        </div>
        <div class="cp-date">
            <?= date('d F Y', strtotime($certificate->issued_at ?? 'now')) ?>
        </div>
    </div>

</body>
</html>
