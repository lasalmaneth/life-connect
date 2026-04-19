<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter of Appreciation | LifeConnect Hospital</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 40px;
            font-family: 'DM Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
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
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .btn-print {
            background: #2563eb;
            color: white;
        }

        .letter-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 80px 100px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            position: relative;
        }

        .letter-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 60px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        .hospital-info {
            text-align: right;
            font-size: 0.9rem;
            color: #64748b;
        }

        .hospital-name {
            font-weight: 800;
            color: #0f172a;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .date {
            margin-bottom: 40px;
            font-weight: 600;
        }

        .recipient-info {
            margin-bottom: 40px;
        }

        .salutation {
            margin-bottom: 30px;
            font-weight: 600;
        }

        .content {
            line-height: 1.8;
            text-align: justify;
            margin-bottom: 50px;
        }

        .content p {
            margin-bottom: 20px;
        }

        .closing {
            margin-bottom: 60px;
        }

        .signature-area {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .signature-name {
            font-weight: 800;
            font-size: 1.1rem;
        }

        .signature-title {
            color: #64748b;
            font-weight: 600;
        }

        .footer-seal {
            position: absolute;
            bottom: 80px;
            right: 100px;
            opacity: 0.1;
            font-size: 6rem;
            color: #2563eb;
        }

        @media print {
            .controls { display: none; }
            body { padding: 0; background: none; }
            .letter-container { box-shadow: none; margin: 0; padding: 60px 80px; }
        }
    </style>
</head>
<body>

    <div class="controls">
        <a href="<?= ROOT ?>/hospital/surgery-prep" class="btn-action btn-back">
            <i class="fas fa-arrow-left"></i> Back to Registry
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="fas fa-print"></i> Print Appreciation Letter
        </button>
    </div>

    <div class="letter-container">
        <div class="letter-head">
            <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect" style="height: 45px;">
            <div class="hospital-info">
                <div class="hospital-name"><?= htmlspecialchars($match->hospital_name) ?></div>
                <div>Clinical Operations Department</div>
                <div>Sri Lanka National Organ Registry Network</div>
            </div>
        </div>

        <div class="date"><?= date('d/m/Y') ?></div>

        <div class="recipient-info">
            <strong>To: <?= htmlspecialchars($match->donor_first_name . ' ' . $match->donor_last_name) ?></strong><br>
            NIC: <?= htmlspecialchars($match->donor_nic) ?><br>
            Sri Lanka.
        </div>

        <div class="salutation">Dear <?= htmlspecialchars($match->donor_first_name) ?>,</div>

        <div class="content">
            <p>On behalf of <?= htmlspecialchars($match->hospital_name) ?> and the entire Life-Connect network, we are writing to express our profound gratitude for your selfless decision to become an organ donor. </p>
            
            <p>Your generous pledge of your <strong><?= htmlspecialchars($match->organ_name) ?></strong> has successfully transitioned into a clinical match that will directly impact a patient's life today. This act of kindness represents the highest form of humanitarian service, transcending individual boundaries to offer hope where it was once fading.</p>

            <p>Clinical approval for this procedure was granted on <?= date('d/m/Y', strtotime($match->match_date)) ?>. Our surgical and clinical coordinator teams are committed to ensuring the highest standards of care and respect throughout this process. Your legacy of giving is now officially recorded in the National Donor Registry.</p>

            <p>Thank you once again for your courage and for embodying the spirit of life-giving that makes our mission possible.</p>
        </div>

        <div class="closing">
            Yours sincerely,<br><br><br>
            <div class="signature-area">
                <div class="signature-name">Medical Coordinator</div>
                <div class="signature-title">Transplant Clinical Division</div>
                <div class="signature-title"><?= htmlspecialchars($match->hospital_name) ?></div>
            </div>
        </div>

        <div class="footer-seal">
            <i class="fas fa-award"></i>
        </div>
    </div>

</body>
</html>
