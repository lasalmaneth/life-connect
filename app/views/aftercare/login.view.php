<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/aftercare/aftercare.css">
    <title>Aftercare Login - LifeConnect</title>
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
            max-width: 460px;
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
        .btn {
            width: 100%;
            padding: 0.85rem 1rem;
            border: none;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
            background: var(--primary-color);
            color: #fff;
        }
        .note { color: #64748b; font-size: 0.9rem; margin-top: 1rem; line-height: 1.5; }
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-weight: 600;
            border: 1px solid;
        }
        .alert.error { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .alert.success { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
        .logo-row { display:flex; align-items:center; gap: 10px; margin-bottom: 0.75rem; }
        .logo-badge { width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; }
        .logo-badge svg { stroke: #fff; }
    </style>
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-head">
                <div class="logo-row">
                    <div class="logo-badge" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1>Aftercare Portal</h1>
                        <p>Login using your registration number</p>
                    </div>
                </div>
            </div>

            <div class="auth-body">
                <?php if (!empty($flash_error)): ?>
                    <div class="alert error"><?= htmlspecialchars($flash_error) ?></div>
                <?php endif; ?>
                <?php if (!empty($flash_success)): ?>
                    <div class="alert success"><?= htmlspecialchars($flash_success) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= ROOT ?>/aftercare/verify">
                    <div class="field">
                        <label class="label">Registration Number</label>
                        <input class="input" name="registration_number" placeholder="REG-2026-0001" autocomplete="username" required>
                    </div>

                    <div class="field">
                        <label class="label">Password</label>
                        <input class="input" type="password" name="password" placeholder="Default is your NIC" autocomplete="current-password" required>
                    </div>

                    <button class="btn" type="submit">Login</button>

                    <div class="note">
                        First-time login uses your NIC as the password. You will be asked to change it after login.
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
