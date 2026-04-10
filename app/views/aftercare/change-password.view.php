<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/aftercare/aftercare.css">
    <title>Change Password - Aftercare</title>
    <style>
        body { background: #f8fafc; }
        .auth-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .auth-card {
            width: 100%;
            max-width: 520px;
            background: var(--white-color);
            border: 1px solid rgba(0, 91, 170, 0.1);
            border-radius: 16px;
            box-shadow: 0 16px 50px rgba(0, 91, 170, 0.12);
            overflow: hidden;
        }
        .auth-head {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white-color);
            padding: 1.5rem;
        }
        .auth-head h1 { font-size: 1.25rem; margin: 0 0 0.25rem; }
        .auth-head p { opacity: 0.9; margin: 0; font-size: 0.9rem; }
        .auth-body { padding: 1.5rem; }
        .field { margin-bottom: 1rem; }
        .label { display:block; font-weight: 700; color: var(--secondary-color); margin-bottom: 0.4rem; }
        .input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 1rem;
            outline: none;
            background: #fff;
        }
        .input:focus { border-color: rgba(0, 91, 170, 0.6); box-shadow: 0 0 0 4px rgba(0, 91, 170, 0.1); }
        .btn-row { display:flex; gap: 0.75rem; }
        .btn {
            flex: 1;
            padding: 0.85rem 1rem;
            border: none;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
            background: var(--primary-color);
            color: #fff;
        }
        .btn.secondary { background: #e2e8f0; color: #0f172a; }
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-weight: 600;
            border: 1px solid;
        }
        .alert.error { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .alert.success { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
        .note { color: #64748b; font-size: 0.9rem; margin-top: 0.75rem; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-head">
                <h1>Change Password</h1>
                <p>Required on first login</p>
            </div>

            <div class="auth-body">
                <?php if (!empty($flash_error)): ?>
                    <div class="alert error"><?= htmlspecialchars($flash_error) ?></div>
                <?php endif; ?>
                <?php if (!empty($flash_success)): ?>
                    <div class="alert success"><?= htmlspecialchars($flash_success) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= ROOT ?>/aftercare/update-password">
                    <div class="field">
                        <label class="label">New Password</label>
                        <input class="input" type="password" name="new_password" minlength="8" required>
                    </div>

                    <div class="field">
                        <label class="label">Confirm Password</label>
                        <input class="input" type="password" name="confirm_password" minlength="8" required>
                    </div>

                    <div class="btn-row">
                        <button class="btn" type="submit">Update Password</button>
                        <button class="btn secondary" type="button" onclick="window.location.href='<?= ROOT ?>/aftercare/logout'">Logout</button>
                    </div>

                    <div class="note">Use at least 8 characters. Keep it private and do not share your password.</div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
