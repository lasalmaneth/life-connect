<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life-Connect Digital Donor Card</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e293b;
            --accent: #2563eb;
            --bg: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--text-main);
        }

        /* Card Container - Standard ID-1 Dimensions: 85.6mm x 53.98mm */
        .card {
            width: 85.6mm;
            height: 53.98mm;
            background: var(--bg);
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            user-select: none;
        }

        /* Header Section */
        .card-header {
            background: var(--primary);
            color: white;
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-weight: 800;
            font-size: 14px;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .brand i {
            color: #60a5fa;
            font-size: 12px;
        }

        .registry-tag {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            background: rgba(255,255,255,0.1);
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Content Section */
        .card-body {
            flex: 1;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .card-body::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
            background: url("<?= ROOT ?>/assets/images/logo.png") no-repeat center;
            background-size: contain;
            opacity: 0.05;
            z-index: 0;
        }

        .card-title {
            font-size: 9px;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            z-index: 1;
        }

        .donor-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 12px;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            z-index: 1;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            z-index: 1;
        }

        .detail-item label {
            display: block;
            font-size: 7px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .detail-item .value {
            font-size: 11px;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            color: var(--text-main);
        }

        /* Footer Section */
        .card-footer {
            background: var(--accent);
            height: 8px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 6px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Action Buttons - Hidden during print */
        .controls {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-print {
            background: var(--accent);
            color: white;
        }

        .btn-print:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .btn-back {
            background: #e2e8f0;
            color: #475569;
        }

        .btn-back:hover {
            background: #cbd5e1;
        }

        /* Print Specific Styles */
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            .controls {
                display: none !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd;
                position: absolute;
                top: 0;
                left: 0;
            }
            @page {
                size: 85.6mm 54mm;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="card-header">
            <div class="brand">
                <img src="<?= ROOT ?>/assets/images/logo.png" alt="Logo" style="height: 24px; width: auto; margin-right: 4px;">
                LIFE-CONNECT
            </div>
            <div class="registry-tag">National Registry</div>
        </div>

        <div class="card-body">
            <div class="card-title">Official Donor Card</div>
            <div class="donor-name"><?= strtoupper($donor_full_name) ?></div>

            <div class="details-grid">
                <div class="detail-item">
                    <label>Registry ID</label>
                    <div class="value"><?= $donor_id_display ?></div>
                </div>
                <div class="detail-item">
                    <label>NIC Number</label>
                    <div class="value"><?= $donor_data['nic_number'] ?></div>
                </div>
                <div class="detail-item">
                    <label>Blood Group</label>
                    <div class="value"><?= $donor_data['blood_group'] ?: 'N/A' ?></div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            VERIFY AT LIFE-CONNECT.LK/REGISTER
        </div>
    </div>

    <div class="controls">
        <a href="<?= ROOT ?>/donor/documents" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Documents
        </a>
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Save Card as PDF
        </button>
    </div>

    <script>
        // Optional: Auto-open print dialog if hinted
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('print')) {
            window.onload = () => window.print();
        }
    </script>
</body>
</html>
