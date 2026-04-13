<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Certificate - <?= htmlspecialchars($donor_full_name) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        :root {
            --primary-color: #1a365d;
            --accent-color: #e53e3e;
            --text-color: #2d3748;
            --border-color: #cbd5e0;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: #f7fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .certificate-container {
            width: 297mm;
            height: 210mm;
            background: white;
            position: relative;
            padding: 15mm; /* Reduced from 20mm */
            box-sizing: border-box;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* Decorative Background */
        .certificate-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            height: 80%;
            background-image: url('<?= ROOT ?>/assets/images/certificate-bg.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.15; /* Transparent watermark style */
            z-index: 0;
        }

        .certificate-inner {
            position: relative;
            z-index: 1;
            height: 100%;
            border: 2px solid var(--primary-color);
            padding: 2mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .certificate-content {
            border: 1px solid var(--primary-color);
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
            box-sizing: border-box;
            text-align: center;
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px; /* Reduced from 30px */
        }

        .logo {
            width: 80px;
            margin-bottom: 10px;
        }

        .brand-name {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .ministry {
            font-size: 14px;
            color: #718096;
            font-weight: 500;
            margin-top: 5px;
        }

        .title {
            font-size: 52px; /* Slightly reduced */
            font-weight: 700;
            color: var(--primary-color);
            margin: 15px 0; /* Reduced */
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .subtitle {
            font-size: 18px;
            color: #4a5568;
            margin-top: 10px;
        }

        .donor-name {
            font-size: 56px; /* Reduced from 64px */
            font-weight: 700;
            color: var(--text-color);
            margin: 20px 0; /* Reduced from 30px */
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
            padding-bottom: 10px;
            min-width: 400px;
        }

        .pledge-details {
            font-size: 21px; /* Slightly reduced */
            color: #4a5568;
            margin-bottom: 20px; /* Reduced from 40px */
            max-width: 700px;
            line-height: 1.5;
        }

        .footer {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            padding: 0 60px;
            padding-bottom: 20px;
        }
        
        .sig-row {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
        }

        .sig-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 200px;
        }

        .signature {
            font-family: 'Dancing Script', cursive;
            font-size: 24px;
            margin-bottom: 5px;
            color: #1a365d;
        }

        .sig-line {
            width: 100%;
            border-top: 1px solid #4a5568;
            margin-bottom: 8px;
        }

        .sig-label {
            font-size: 14px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
        }

        .watermark {
            position: absolute;
            bottom: 40px;
            font-size: 12px;
            color: #cbd5e0;
            letter-spacing: 1px;
        }

        @media print {
            body {
                background: white;
            }
            .certificate-container {
                box-shadow: none;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }

        .no-print-toolbar {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
        }

        .print-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .print-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }
    </style>
    <!-- Add specialized font for signature -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
</head>
<body>

    <div class="no-print-toolbar">
        <button onclick="window.print()" class="print-btn no-print">
            <i class="fas fa-print"></i> Print Certificate
        </button>
    </div>

    <div class="certificate-container">
        <div class="certificate-bg"></div>
        <div class="certificate-inner">
            <div class="certificate-content">
                <div class="header">
                    <img src="<?= ROOT ?>/assets/images/logo.png" alt="LifeConnect Logo" class="logo">
                    <h2 class="brand-name">Life Connect</h2>
                    <div class="ministry">Ministry of Health Sri Lanka</div>
                </div>

                <div class="title">Certificate of Appreciation</div>
                <div class="subtitle">This certificate is proudly awarded to</div>

                <div class="donor-name"><?= htmlspecialchars(strtoupper($donor_full_name)) ?></div>

                <div class="pledge-details">
                    <?php if (($donation_type ?? 'organ') === 'organ'): ?>
                        For your selfless gift of life through the donation of <br>
                        <strong><?= htmlspecialchars($pledge->organ_name) ?></strong>. <br>
                    <?php elseif (($donation_type ?? 'organ') === 'financial'): ?>
                        In recognition of your generous financial contribution of <br>
                        <strong style="font-size: 26px;">LKR <?= number_format($donation->amount, 2) ?></strong>. <br>
                        <span style="font-size: 14px; color: var(--blue-500); font-family: monospace; display: block; margin-top: 5px;">
                            Ref: #<?= str_pad($donation->id, 8, '0', STR_PAD_LEFT) ?>
                        </span>
                    <?php else: ?>
                        In recognition of your monumental cumulative contribution of <br>
                        <strong style="font-size: 32px;">LKR <?= number_format($donation->amount, 2) ?></strong>. <br>
                        <div style="font-size: 14px; color: var(--blue-600); margin-top: 5px; font-weight: 600;">
                            Total Lifetime Contributions
                        </div>
                    <?php endif; ?>
                    
                    <span style="font-size: 16px; display: block; margin-top: 15px; color: #718096; font-style: italic;">
                        "A Gift of Life, A Legacy of Love"
                    </span>
                </div>

                <div class="footer">
                    <div class="sig-block">
                        <div class="signature">Director General</div>
                        <div class="sig-line"></div>
                        <div class="sig-label">Authorized Signature</div>
                    </div>

                    <div style="font-size: 14px; color: #718096; margin-bottom: 10px;">
                        Save a Life  •  Gift of Life
                    </div>

                    <div class="sig-block">
                        <div style="font-weight: 600; margin-bottom: 5px; color: var(--primary-color);">
                            <?php 
                                $issueDate = (($donation_type ?? 'organ') === 'organ') 
                                    ? $pledge->pledge_date 
                                    : $donation->created_at;
                                echo date('d F Y', strtotime($issueDate));
                            ?>
                        </div>
                        <div class="sig-line"></div>
                        <div class="sig-label">Date Issued</div>
                    </div>
                </div>

                <div class="watermark">
                    LIFECONNECT-<?= (($donation_type ?? 'organ') === 'organ') ? 'ORG' : (($donation_type ?? 'organ') === 'financial' ? 'FIN' : 'TOTAL') ?>-<?= str_pad((($donation_type ?? 'organ') === 'organ' ? $pledge->id : $donation->id), 6, '0', STR_PAD_LEFT) ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
