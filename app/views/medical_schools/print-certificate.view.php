<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Appreciation | LifeConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --gold-light: #d4c9a8;
            --gold-dark: #c9b97a;
            --deep-blue: #0c2461;
            --paper: #fdfcf9;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            background: #f0f3f8; 
            font-family: 'DM Sans', sans-serif; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .certificate-container {
            background: var(--paper);
            width: 842px; /* A4 Landscape */
            height: 595px;
            border: 1px solid var(--gold-light);
            border-radius: 4px;
            padding: 2rem 3rem;
            text-align: center;
            position: relative;
            font-family: 'Cormorant Garamond', serif;
            box-shadow: 0 4px 24px rgba(12,36,97,.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            -webkit-print-color-adjust: exact;
        }

        .certificate-container::before {
            content: '';
            position: absolute;
            inset: 15px;
            border: 1.5px solid var(--gold-dark);
            border-radius: 2px;
            pointer-events: none;
        }

        /* Premium Seal Logo */
        .cp-seal {
            width: 90px;
            height: 90px;
            aspect-ratio: 1 / 1;
            min-width: 90px;
            flex-shrink: 0;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.2rem;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 2;
            overflow: hidden;
            border: 3px solid var(--gold-dark);
        }
        .cp-seal img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Header Style */
        .cp-h1 { 
            font-size: 2.2rem; 
            font-weight: 600; 
            color: var(--deep-blue); 
            margin-bottom: .4rem; 
            position: relative;
        }

        .cp-sub {
            font-size: .9rem; 
            text-transform: uppercase;
            letter-spacing: .16em; 
            margin-bottom: 0.8rem;
            color: #5585c7;
            font-weight: 600;
        }

        .cp-rule {
            height: 1px;
            width: 80%;
            background: linear-gradient(90deg, transparent, var(--gold-dark), transparent);
            margin: 0.8rem 0;
        }

        .cp-pre { 
            font-size: 0.9rem; 
            color: #888; 
            font-style: italic; 
            margin-bottom: .3rem; 
        }

        .cp-rec { 
            font-size: 2rem; 
            font-weight: 600; 
            color: var(--deep-blue); 
            margin-bottom: 1rem; 
            font-style: italic; 
        }

        .cp-body { 
            font-size: 1.1rem; 
            line-height: 1.9; 
            color: #444; 
            margin-bottom: 1.2rem; 
            max-width: 580px; 
        }

        .cp-frule {
            height: 1px;
            width: 60%;
            background: linear-gradient(90deg, transparent, var(--gold-dark), transparent);
            margin-bottom: 1rem;
        }

        .cp-foot { 
            font-size: .85rem; 
            color: #666; 
            letter-spacing: .1em; 
            text-transform: uppercase; 
            margin-bottom: .4rem; 
            font-weight: 600;
        }

        .cp-date { 
            font-size: .8rem; 
            color: #999; 
            font-family: 'DM Sans', sans-serif;
        }

        /* Controls */
        .controls {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 12px;
            z-index: 1000;
        }

        .btn-action {
            background: #fff;
            border: 1px solid #e6eaf0;
            padding: 10px 20px;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            color: #3d4f63;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-action.primary {
            background: #059669;
            color: #fff;
            border: none;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            background: #f8fafc;
        }
        .btn-action.primary:hover { background: #047857; }

        @media print {
            body { background: white; padding: 0; }
            .controls { display: none; }
            .certificate-container { box-shadow: none; border-color: #eee; width: 100%; height: 100%; }
        }
    </style>
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
