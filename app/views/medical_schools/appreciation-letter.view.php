<?php if(!defined('ROOT')) die(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeConnect — Acknowledgement Letter: <?= htmlspecialchars($letter->ref_number) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <style>
        /* ── BASE ─────────────────────────────────────────────────── */
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #f0f3f8;
            color: #1a2535;
            font-size: 14px;
            line-height: 1.6;
            min-height: 100vh;
            padding: 0;
        }

        /* ── TOP NAV ──────────────────────────────────────────────── */
        .no-print-bar {
            position: sticky;
            top: 0;
            background: #0f172a;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .btn-ui {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-print { background: #2563eb; color: white; }
        .btn-print:hover { background: #1d4ed8; }
        .btn-back { background: rgba(255,255,255,0.1); color: white; }
        .btn-back:hover { background: rgba(255,255,255,0.2); }

        /* ── PAGE WRAPPER ─────────────────────────────────────────── */
        .page {
            max-width: 860px;
            margin: 3rem auto;
            padding: 0 1rem;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
        }

        /* ── BADGES ────────────────────────────────────────────────── */
        .badge-issued {
            margin-left: auto;
            background: #dcfce7; color: #166534;
            font-size: .65rem; font-weight: 700;
            padding: .25rem .85rem; border-radius: 999px;
            border: 1px solid rgba(22,163,74,.3);
            display: inline-flex; align-items: center; gap: .3rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── LETTER CARD ──────────────────────────────────────────── */
        .letter-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(12,36,97,.1);
            border: 1px solid rgba(26,86,219,.12);
        }
        .letter-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem 2rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .letter-header .lh-icon {
            width: 44px; height: 44px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .letter-header .lh-title { font-weight: 700; font-size: 1rem; color: #0f172a; }
        .letter-header .lh-sub { font-size: .75rem; color: #64748b; margin-top: .15rem; }

        .letter-body {
            padding: 2.5rem 4rem;
            background: #fff;
            position: relative;
            min-height: 500px;
        }
        /* LifeConnect Logo Watermark */
        .letter-body::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 450px;
            height: 450px;
            background-image: url('<?= ROOT ?>/assets/images/logo.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.07;
            pointer-events: none;
            z-index: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .letter-content { position: relative; z-index: 1; }
        
        .letter-body .date { font-size: .85rem; color: #64748b; text-align: right; margin-bottom: 2rem; }
        .letter-body .from { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 1.5rem; line-height: 1.4; }
        .letter-body .salutation { font-size: 1rem; font-weight: 600; color: #1e293b; margin-bottom: 1.25rem; }
        .letter-body .para { font-size: 1rem; color: #334155; line-height: 1.7; margin-bottom: 1.25rem; text-align: justify; }
        .letter-body .sign-off { font-size: 1rem; color: #334155; margin-bottom: 2rem; }
        
        .letter-body .sig-block .name { font-size: 1.05rem; font-weight: 700; color: #0f172a; }
        .letter-body .sig-block .dept { font-size: .85rem; color: #64748b; margin-top: 0.25rem; }
        
        .letter-footer {
            margin-top: 2rem;
            padding: 1.25rem 4rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .letter-footer .ref { font-size: .75rem; color: #94a3b8; font-weight: 500; }
        .letter-footer .status-pill {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.75rem; font-weight: 700; color: #10b981;
        }

        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print-bar { display: none !important; }
            .page { margin: 0 auto !important; width: 210mm; max-width: 100% !important; border: none !important; box-shadow: none !important; }
            .letter-card { border: none !important; box-shadow: none !important; width: 100% !important; }
            .letter-header, .letter-footer { background: white !important; border-color: #eee !important; }
            .letter-body::before { opacity: 0.15 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .badge-issued { border-color: #ccc !important; }
        }

        @media (max-width: 600px) {
            .letter-body { padding: 2rem 1.5rem; }
            .letter-footer { padding: 1.5rem; }
        }
    </style>
</head>
<body>

<div class="page">
    <div class="letter-card">
        <!-- Card Header -->
        <div class="letter-header">
            <div class="lh-icon">
                <img src="<?= ROOT ?>/assets/images/logo.png" alt="LifeConnect Logo" style="height: 32px; width: auto;">
            </div>
            <div>
                <div class="lh-title"><?= htmlspecialchars($letter->school_name) ?></div>
                <div class="lh-sub">Official Acknowledgement Registry • Ref: <?= htmlspecialchars($letter->ref_number) ?></div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="letter-body">
            <div class="letter-content">
                <div class="date"><?= date('d F Y', strtotime($letter->issued_at)) ?></div>
                
                <div class="from">
                    <?= htmlspecialchars($letter->school_name) ?><br>
                    <span style="font-weight: 400; color: #64748b; font-size: 0.8rem;"><?= htmlspecialchars($letter->school_address) ?></span>
                </div>

                <div class="salutation">Dear <?= htmlspecialchars($letter->custodian_name) ?>,</div>
                
                <div class="para">
                    On behalf of the <strong><?= htmlspecialchars($letter->school_name) ?></strong>, we write to formally acknowledge 
                    and express our deepest gratitude for the whole body donation of 
                    <strong style="color: #0f172a;">Mr. <?= htmlspecialchars($letter->first_name . ' ' . $letter->last_name) ?></strong> (NIC: <?= htmlspecialchars($letter->nic_number) ?>).
                </div>

                <div class="para">
                    The donated body has been received by the <strong><?= htmlspecialchars($letter->usage_department) ?></strong> 
                    and was utilized on <strong><?= date('d F Y', strtotime($letter->usage_date)) ?></strong> 
                    exclusively for the purpose of medical advancement in the area of <strong><?= htmlspecialchars($letter->purpose) ?></strong>, 
                    honouring the donor's selfless and extraordinary final wish.
                </div>

                <div class="para">
                    The Faculty extends its deepest respect and heartfelt condolences to you and your family. 
                    This profound gift serves as a cornerstone for the training of future physicians, benefiting 
                    countless lives. 
                </div>

                <div class="para">
                    The sacrifice and courage shown by you in fulfilling this wish is a direct contribution to 
                    medical science that will never be forgotten by our institution.
                </div>

                <div class="sign-off">Yours sincerely,</div>
                
                <div class="sig-block">
                    <div class="name">Professor of Anatomy</div>
                    <div class="dept"><?= htmlspecialchars($letter->school_name) ?></div>
                </div>
            </div>
        </div>

        <!-- Card Footer -->
        <div class="letter-footer">
            <div class="ref">System Reference: <?= htmlspecialchars($letter->ref_number) ?> • Digital Copy</div>
            <div class="status-pill">
                <i class="fas fa-shield-halved"></i>
                Digitally Authenticated via LifeConnect
            </div>
        </div>
    </div>
</div>

</body>
</html>
