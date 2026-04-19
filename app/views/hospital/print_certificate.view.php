<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Certificate | LifeConnect Hospital</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --primary-blue: #2563eb;
            --deep-slate: #1e293b;
            --gold: #d4af37;
        }

        body {
            margin: 0;
            padding: 40px;
            font-family: 'DM Sans', sans-serif;
            background-color: #f1f5f9;
            color: var(--deep-slate);
        }

        .controls {
            max-width: 800px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            transition: all 0.2s;
        }

        .btn-back {
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .btn-print {
            background: var(--primary-blue);
            color: white;
        }

        .certificate-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 60px;
            border: 20px solid white;
            outline: 2px solid #e2e8f0;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            background-image: radial-gradient(circle at 2px 2px, #f1f5f9 1px, transparent 0);
            background-size: 40px 40px;
        }

        .certificate-inner {
            border: 2px solid #2563eb;
            padding: 40px;
            text-align: center;
            position: relative;
        }

        .logo-box {
            margin-bottom: 30px;
        }

        .logo-box img {
            height: 60px;
        }

        h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3.5rem;
            color: var(--deep-slate);
            margin: 0 0 10px;
            font-weight: 600;
            letter-spacing: -1px;
        }

        .subtitle {
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 0.9rem;
            margin-bottom: 40px;
        }

        .presented-to {
            font-style: italic;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem;
            color: #64748b;
            margin-bottom: 10px;
        }

        .donor-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--deep-slate);
            border-bottom: 2px solid #e2e8f0;
            display: inline-block;
            padding: 0 40px 5px;
            margin-bottom: 30px;
        }

        .description {
            line-height: 1.8;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 50px;
            color: #475569;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 60px;
        }

        .sig-box {
            text-align: center;
        }

        .sig-line {
            border-top: 1px solid #cbd5e1;
            padding-top: 10px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .meta-info {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 40px;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
        }

        @media print {
            .controls { display: none; }
            body { padding: 0; background: none; }
            .certificate-container { box-shadow: none; border: none; outline: none; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="controls">
        <a href="<?= ROOT ?>/hospital/surgery-prep" class="btn-action btn-back">
            <i class="fas fa-arrow-left"></i> Back to Registry
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="fas fa-print"></i> Print Official Certificate
        </button>
    </div>

    <div class="certificate-container">
        <div class="certificate-inner">
            <div class="logo-box">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect">
            </div>

            <h1>Certificate of Donation</h1>
            <div class="subtitle">Official Clinical Registry of Life-Connect</div>

            <div class="presented-to">This recognition is honorably presented to</div>
            <div class="donor-name"><?= htmlspecialchars($match->donor_first_name . ' ' . $match->donor_last_name) ?></div>

            <div class="description">
                For the extraordinarily noble and humanitarian act of <b><?= htmlspecialchars($match->organ_name) ?></b> donation. This selfless contribution has provided a new lease on life to a recipient in critical need, reflecting the highest ideals of human solidarity and clinical excellence.
            </div>

            <div class="footer-grid">
                <div class="sig-box">
                    <div style="height: 50px;"></div>
                    <div class="sig-line">Date of Surgery</div>
                    <div style="font-size: 0.85rem; margin-top: 5px;"><?= date('d/m/Y', strtotime($match->surgery_date)) ?></div>
                </div>
                <div class="sig-box">
                    <div style="height: 50px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-award" style="font-size: 2.5rem; color: #d4af37;"></i>
                    </div>
                    <div class="sig-line">Official Seal</div>
                </div>
                <div class="sig-box">
                    <div style="height: 50px;"></div>
                    <div class="sig-line">Medical Director</div>
                    <div style="font-size: 0.85rem; margin-top: 5px;"><?= htmlspecialchars($match->hospital_name) ?></div>
                </div>
            </div>

            <div class="meta-info">
                <span>Registry ID: MAT-<?= str_pad($match->match_id, 5, '0', STR_PAD_LEFT) ?></span>
                <span>Issued On: <?= date('d/m/Y') ?></span>
                <span>Verification: lifeconnect.lk/verify/<?= $match->match_id ?></span>
            </div>
        </div>
    </div>

</body>
</html>
