<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/aftercare/aftercare.css">
    <title>Aftercare Portal - LifeConnect</title>
    <style>
        body { background: #f8fafc; }
        .wrap { max-width: 960px; margin: 0 auto; padding: 2rem; }
        .top {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: #fff;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 16px 50px rgba(0, 91, 170, 0.12);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .card {
            margin-top: 1.5rem;
            background: #fff;
            border: 1px solid rgba(0, 91, 170, 0.1);
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 10px 35px rgba(0, 91, 170, 0.08);
        }
        .muted { opacity: 0.9; font-size: 0.95rem; }
        .btn {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.25);
            padding: 0.6rem 0.9rem;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn svg { stroke: #fff; }
        .grid { display:grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 720px) { .grid { grid-template-columns: 1fr; } }
        .kv { display:flex; justify-content: space-between; gap: 1rem; padding: 0.6rem 0; border-bottom: 1px solid #eef2f7; }
        .kv:last-child { border-bottom: none; }
        .k { color: #475569; font-weight: 700; }
        .v { color: #0f172a; font-weight: 700; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <div>
                <div style="font-weight: 900; font-size: 1.25rem;">Aftercare Portal</div>
                <div class="muted">Welcome<?= !empty($patient->full_name) ? ', ' . htmlspecialchars($patient->full_name) : '' ?>.</div>
            </div>
            <a class="btn" href="<?= ROOT ?>/aftercare/logout">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16,17 21,12 16,7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Logout
            </a>
        </div>

        <div class="grid">
            <div class="card">
                <div style="font-weight: 900; color: var(--secondary-color); margin-bottom: 0.75rem;">My Account</div>
                <div class="kv"><div class="k">Registration</div><div class="v"><?= htmlspecialchars($patient->registration_number ?? '—') ?></div></div>
                <div class="kv"><div class="k">Patient Type</div><div class="v"><?= htmlspecialchars($patient->patient_type ?? '—') ?></div></div>
                <div class="kv"><div class="k">Hospital</div><div class="v"><?= htmlspecialchars($patient->hospital_registration_no ?? '—') ?></div></div>
            </div>

            <div class="card">
                <div style="font-weight: 900; color: var(--secondary-color); margin-bottom: 0.75rem;">Next Steps</div>
                <div style="color:#475569; line-height: 1.6;">
                    This portal login has been enabled by your hospital. Your appointments and support requests will appear here as they are scheduled.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
