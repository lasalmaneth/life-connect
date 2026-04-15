<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anatomical Usage Recognition | LifeConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&family=Libre+Baskerville:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/medicalschools/print_letter.css?v=<?= time() ?>">
</head>
<body>

    <div class="action-bar">
        <a href="javascript:window.print()" class="btn-print">
            <i class="fas fa-print"></i> Print Legal Copy
        </a>
    </div>

    <div class="letter-container">
        <div class="letter-header">
            <div class="faculty-info">
                <h1>Faculty of Medicine</h1>
                <p>Anatomical Recognition Programme</p>
                <p>Department of Anatomy, University of Colombo</p>
                <p>PO Box 271, Kinsey Road, Colombo 08, Sri Lanka</p>
            </div>
            <div class="lifeconnect-branding">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" class="lc-logo" alt="LifeConnect">
                <span class="lc-ref">REF: <?= htmlspecialchars($letter->ref_number ?? 'BD-2026-AUT') ?></span>
            </div>
        </div>

        <div class="letter-date">
            <?= date('jS F, Y', strtotime($letter->issued_at ?? 'now')) ?>
        </div>

        <div class="letter-content">
            <div class="salutation">To the Family of the Late <?= htmlspecialchars(($letter->first_name ?? 'Donor') . ' ' . ($letter->last_name ?? '')) ?>,</div>
            
            <div class="paragraph">
                It is with the most profound respect and gratitude that we acknowledge the contribution made by your family through the whole-body donation of <span class="highlight-text"><?= htmlspecialchars(($letter->first_name ?? 'Donor') . ' ' . ($letter->last_name ?? '')) ?></span>. 
            </div>

            <div class="paragraph">
                The body has been formally received and cataloged under our Anatomical Usage Programme. We wish to officially inform you that it is currently being utilized within the <span class="highlight-text"><?= htmlspecialchars($letter->school_name ?? 'Faculty of Medicine') ?></span> for the purpose of advancement in <span class="highlight-text"><?= htmlspecialchars($letter->usage_type ?? 'Medical Education') ?></span>.
            </div>

            <div class="quote">
                "Where the deceased teach the living, and the silence of the selfless echoes through generations of healing."
            </div>

            <div class="paragraph">
                This noble sacrifice ensures that the next generation of physicians and researchers in Sri Lanka receive the absolute best training possible. Your loved one's legacy now lives on through every life saved by the doctors trained within these halls.
            </div>

            <div class="paragraph">
                The Faculty extends its deepest condolences and remains available for any further institutional support you may require.
            </div>
        </div>

        <div class="signature-section">
            <div class="sig-block">
                <p style="font-size: 0.9rem; margin-bottom: 30px;">Yours Faithfully,</p>
                <div class="sig-name">Head of the Department</div>
                <div class="sig-title">Professor of Anatomy</div>
                <div class="sig-title" style="margin-top: 5px;"><?= htmlspecialchars($letter->school_name ?? 'Faculty of Medicine') ?></div>
            </div>
            
            <div class="seal-area">
                INSTITUTIONAL SEAL
            </div>
        </div>

        <div class="letter-footer">
            <span>LifeConnect Programme Reference: LC-BD-<?= date('Y') ?></span>
            <span>Digital Authenticity Verified • <?= date('H:i') ?></span>
        </div>
    </div>

</body>
</html>
