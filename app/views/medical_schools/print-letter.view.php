<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anatomical Usage Recognition | LifeConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600;700&family=Libre+Baskerville:ital@1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --paper: #ffffff;
            --ink: #1e293b;
            --faculty-blue: #1e40af;
            --gold: #b45309;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            background: #f1f5f9; 
            font-family: 'Inter', sans-serif; 
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 50px 20px;
            color: var(--ink);
        }

        /* Letter Wrapper */
        .letter-container {
            background: var(--paper);
            width: 850px;
            min-height: 1100px;
            padding: 80px 90px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            border-top: 10px solid var(--faculty-blue);
        }

        /* Subtle Watermark */
        .letter-container::before {
            content: "\f471";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 400px;
            color: rgba(30, 64, 175, 0.03);
            pointer-events: none;
            z-index: 0;
        }

        /* Header Layout */
        .letter-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 60px;
            position: relative;
            z-index: 1;
        }

        .faculty-info h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: var(--faculty-blue);
            margin-bottom: 5px;
        }
        .faculty-info p {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            line-height: 1.5;
        }

        .lifeconnect-branding {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }
        .lc-logo {
            height: 45px;
            opacity: 0.9;
        }
        .lc-ref {
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.05em;
        }

        /* Date & Content */
        .letter-date {
            text-align: right;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 50px;
        }

        .letter-content {
            position: relative;
            z-index: 1;
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .salutation {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }

        .paragraph {
            margin-bottom: 25px;
            text-align: justify;
        }

        .highlight-text {
            color: var(--faculty-blue);
            font-weight: 700;
        }

        .quote {
            font-family: 'Libre Baskerville', serif;
            font-style: italic;
            color: #475569;
            padding: 20px 40px;
            border-left: 3px solid #cbd5e1;
            margin: 30px 0;
            font-size: 0.95rem;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .sig-block {
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            min-width: 250px;
        }
        .sig-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--faculty-blue);
        }
        .sig-title {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .seal-area {
            width: 100px;
            height: 100px;
            border: 2px dashed #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            color: #cbd5e1;
            transform: rotate(-15deg);
        }

        /* Footer Info */
        .letter-footer {
            margin-top: 100px;
            padding-top: 30px;
            border-top: 1px solid #f1f5f9;
            font-size: 0.75rem;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }

        /* Controls */
        .action-bar {
            position: fixed;
            top: 20px;
            right: 40px;
            display: flex;
            gap: 15px;
            z-index: 1000;
        }
        .btn-print {
            background: var(--faculty-blue);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(30, 64, 175, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .btn-print:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(30, 64, 175, 0.3); }

        @media print {
            body { background: white; padding: 0; }
            .action-bar { display: none; }
            .letter-container { box-shadow: none; border-top: none; width: 100%; margin: 0; }
        }
    </style>
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
