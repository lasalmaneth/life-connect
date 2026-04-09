<?php
// Hospital Portal — Add Recipient Aftercare Account
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo ROOT; ?>/assets/css/hospital/hospital.css">
    <title>Add Recipient - Aftercare Portal</title>
    <style>
        .ac-alert { padding: 1rem; border-radius: 10px; margin-bottom: 1.25rem; border-left: 4px solid; font-weight: 600; }
        .ac-alert.success { background: #ecfdf5; color: #065f46; border-left-color: #10b981; }
        .ac-alert.error { background: #fef2f2; color: #991b1b; border-left-color: #ef4444; }
        .cred-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem; margin-top: 0.75rem; }
        .cred-row { display:flex; justify-content: space-between; gap: 1rem; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb; }
        .cred-row:last-child { border-bottom: none; }
        .cred-k { color: #475569; font-weight: 700; }
        .cred-v { color: #0f172a; font-weight: 800; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        .cred-actions { display:flex; gap: 0.75rem; margin-top: 0.75rem; flex-wrap: wrap; }
        .btn-inline { padding: 0.55rem 1rem; border-radius: 8px; border: 1px solid var(--border-color); cursor: pointer; background: #fff; font-weight: 700; }
        .btn-inline.primary { background: var(--primary-color); color: #fff; border-color: var(--primary-color); }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?php echo ROOT ?? '/life-connect'; ?>" style="text-decoration:none; display:flex; align-items:center; gap:10px;">
                    <img src="<?php echo ROOT ?? '/life-connect'; ?>/public/assets/images/logo.png" alt="LifeConnect" style="height:40px; width: auto;">
                    <div>
                        <strong style="display:block; font-size:1.1rem; color:#003b6e; line-height:1.2;">LifeConnect</strong>
                        <p style="margin:0; font-size:.68rem; color:#6b7280; padding-top:2px;">Hospital Portal</p>
                    </div>
                </a>
            </div>
            <div class="user-info">
                <div class="user-avatar"><?php echo strtoupper(substr($hospital_details['name'], 0, 1)); ?></div>
                <div class="user-details">
                    <div style="font-weight: 600; font-size: 0.9rem;"><?php echo htmlspecialchars($hospital_details['name']); ?></div>
                    <div style="font-size: 0.8rem; opacity: 0.8;"><?php echo htmlspecialchars($hospital_details['role']); ?></div>
                </div>
                <div class="user-actions">
                    <button class="btn-logout" onclick="window.location.href='<?php echo ROOT; ?>/logout'" title="Logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16,17 21,12 16,7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h3>Hospital Portal</h3>
                    <p>Clinical coordination</p>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">NAVIGATION</div>
                    <a href="<?php echo ROOT; ?>/hospital" class="menu-item" style="text-decoration:none; color:inherit; display:block;">
                        <span class="icon"></span>
                        <span>Main Dashboard</span>
                    </a>
                    <a href="<?php echo ROOT; ?>/hospital/addpatient" class="menu-item" style="text-decoration:none; color:inherit; display:block;">
                        <span class="icon"></span>
                        <span>Aftercare Accounts</span>
                    </a>
                </div>
            </div>

            <div class="content-area">
                <div class="content-section" style="display:block;">
                    <div class="content-header">
                        <h2>Add Recipient Patient</h2>
                        <p>Create an Aftercare Portal account using a registration number (auto-generated if left empty).</p>
                    </div>

                    <div class="content-body">
                        <?php if (!empty($_SESSION['flash_error'])): ?>
                            <div class="ac-alert error"><?php echo htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['flash_success'])): ?>
                            <div class="ac-alert success"><?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['generated_aftercare_credentials'])): ?>
                            <?php $cred = $_SESSION['generated_aftercare_credentials']; unset($_SESSION['generated_aftercare_credentials']); ?>
                            <div class="ac-alert success">
                                Credentials generated successfully. Save them now — they will not be shown again.
                                <div class="cred-box" id="credBox">
                                    <div class="cred-row"><div class="cred-k">Registration Number</div><div class="cred-v" id="credReg"><?php echo htmlspecialchars($cred['registration_number'] ?? ''); ?></div></div>
                                    <div class="cred-row"><div class="cred-k">Default Password</div><div class="cred-v" id="credPass"><?php echo htmlspecialchars($cred['password'] ?? ''); ?></div></div>
                                </div>
                                <div class="cred-actions">
                                    <button type="button" class="btn-inline primary" onclick="copyCredentials()">Copy</button>
                                    <button type="button" class="btn-inline" onclick="printCredentials()">Print</button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                            <form action="<?php echo ROOT; ?>/hospital/addpatient/recipient" method="POST">
                                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                    <div>
                                        <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--secondary-text-color);">Full Name *</label>
                                        <input type="text" name="recipient_name" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--secondary-text-color);">NIC *</label>
                                        <input type="text" name="recipient_nic" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--secondary-text-color);">Registration Number</label>
                                        <input type="text" name="registration_number" placeholder="e.g., REG-2026-0001 (optional)" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem;">
                                    </div>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                                    <div>
                                        <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--secondary-text-color);">Age</label>
                                        <input type="number" name="recipient_age" min="0" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem;">
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--secondary-text-color);">Gender</label>
                                        <select name="recipient_gender" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem; background: white;">
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--secondary-text-color);">Blood Group</label>
                                        <select name="recipient_blood_group" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem; background: white;">
                                            <option value="">Select</option>
                                            <option value="A+">A+</option><option value="A-">A-</option>
                                            <option value="B+">B+</option><option value="B-">B-</option>
                                            <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                            <option value="O+">O+</option><option value="O-">O-</option>
                                        </select>
                                    </div>
                                </div>

                                <div style="margin-bottom: 1.5rem;">
                                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--secondary-text-color);">Contact Details</label>
                                    <input type="text" name="recipient_contact" placeholder="Phone or Address" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem;">
                                </div>

                                <div style="margin-bottom: 1.5rem;">
                                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--secondary-text-color);">Medical Details (Optional)</label>
                                    <textarea name="recipient_medical" rows="3" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 8px; font-size: 1rem;" placeholder="Treatment notes..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 1rem; cursor: pointer;">Register & Generate Credentials</button>
                            </form>

                            <div style="margin-top: 1.25rem; color:#64748b; font-size: 0.9rem; line-height: 1.5;">
                                Login details: use the generated registration number. Default password is the NIC (first login only).
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyCredentials() {
            const reg = document.getElementById('credReg')?.innerText || '';
            const pass = document.getElementById('credPass')?.innerText || '';
            const text = `Aftercare Portal Credentials\nRegistration Number: ${reg}\nPassword: ${pass}`;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => alert('Credentials copied.')).catch(() => alert('Copy failed.'));
            } else {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); alert('Credentials copied.'); } catch(e) { alert('Copy failed.'); }
                ta.remove();
            }
        }

        function printCredentials() {
            const box = document.getElementById('credBox');
            if (!box) return;

            const html = `
                <html>
                    <head>
                        <title>Aftercare Credentials</title>
                        <meta charset="UTF-8" />
                        <style>
                            body{font-family: Arial, sans-serif; padding: 24px;}
                            h1{font-size: 18px; margin-bottom: 12px;}
                            .row{display:flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb;}
                            .k{font-weight: 700; color:#334155;}
                            .v{font-weight: 800; font-family: monospace;}
                        </style>
                    </head>
                    <body>
                        <h1>LifeConnect Aftercare Portal Credentials</h1>
                        ${box.innerHTML}
                    </body>
                </html>
            `;

            const w = window.open('', '_blank');
            if (!w) return;
            w.document.open();
            w.document.write(html);
            w.document.close();
            w.focus();
            w.print();
        }
    </script>
</body>
</html>
