<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - LifeConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css">
    <style>
        /* Specific overrides for long form on small screens */
        .login-card {
            max-width: 500px;
            width: 100%;
            padding: 2.5rem;
            margin: 20px auto;
        }
        .login-page-body {
            height: auto;
            min-height: 100vh;
            overflow-y: auto;
        }
        .password-requirements {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            background: rgba(0, 91, 170, 0.05);
            padding: 15px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            border: 1px solid var(--glass-border);
            text-align: left;
        }
        .requirement-item {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .requirement-item.valid {
            color: var(--success);
            font-weight: 600;
        }
        .requirement-item i {
            font-size: 0.5rem;
        }
        #username-status {
            font-size: 0.75rem;
            margin-top: 4px;
            display: block;
            text-align: left;
        }
        .status-available { color: var(--success); }
        .status-taken { color: var(--danger); }
        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
    </style>
</head>
<body class="login-page-body">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-wrapper" style="max-width: 550px;">
        <div class="login-card glass-card">
            <a href="<?= ROOT ?>/">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect Logo" class="login-logo">
            </a>
            <h1 class="text-gradient">Secure Your Account</h1>
            <p>Please update your credentials to continue </p>

            <?php if (!empty($_SESSION['security_errors'])): ?>
                <div class="error-message show" style="display: block;">
                    <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                    <ul style="margin: 0; padding-left: 20px; font-size: 0.813rem;">
                        <?php foreach ($_SESSION['security_errors'] as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['security_errors']); ?>
            <?php endif; ?>

            <form action="<?= ROOT ?>/custodian/update-security" method="POST" autocomplete="off">
                <div class="form-group">
                    <label>Current Username (NIC)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card"></i>
                        <input type="text" value="<?= htmlspecialchars($user->username ?? '') ?>" disabled style="opacity: 0.7; cursor: not-allowed;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_username">New Username (Optional)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-at"></i>
                        <input type="text" id="new_username" name="new_username" placeholder="Choose unique username" oninput="debounceCheckUsername(this.value)">
                    </div>
                    <span id="username-status">Keep blank to use NIC as username</span>
                </div>

                <div class="form-group">
                    <label for="current_password">Current Password (NIC)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="current_password" name="current_password" placeholder="••••••••" required>
                        <button type="button" class="eye-btn" onclick="tPw('current_password', this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password">New Secure Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-key"></i>
                        <input type="password" id="new_password" name="new_password" placeholder="••••••••" required oninput="validateForm()">
                        <button type="button" class="eye-btn" onclick="tPw('new_password', this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                <div class="password-requirements">
                    <div id="req-length" class="requirement-item"><i class="fas fa-circle"></i> 8+ Characters</div>
                    <div id="req-upper" class="requirement-item"><i class="fas fa-circle"></i> Uppercase</div>
                    <div id="req-lower" class="requirement-item"><i class="fas fa-circle"></i> Lowercase</div>
                    <div id="req-number" class="requirement-item"><i class="fas fa-circle"></i> Number</div>
                    <div id="req-special" class="requirement-item"><i class="fas fa-circle"></i> Special Char</div>
                    <div id="req-nic" class="requirement-item"><i class="fas fa-circle"></i> Not Same as NIC</div>
                </div>

                <input type="hidden" id="nic_val" value="<?= htmlspecialchars($user->username ?? '') ?>">

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-check-double"></i>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required oninput="validateForm()">
                        <button type="button" class="eye-btn" onclick="tPw('confirm_password', this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                <button type="submit" id="submit-btn" class="btn-premium btn-submit">
                    <span>Update and Continue</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="login-footer">
                <a href="<?= ROOT ?>/logout"><i class="fas fa-sign-out-alt"></i> Logout for now</a>
            </div>
        </div>
    </div>

    <script>
    let usernameTimeout = null;
    let isUsernameAvailable = true;

    function tPw(id, btn) {
        var field = document.getElementById(id);
        if (!field || !btn) return;
        var icon = btn.querySelector("i");
        if (field.type === "password") {
            field.type = "text";
            if (icon) icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            field.type = "password";
            if (icon) icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }

    function debounceCheckUsername(val) {
        if (usernameTimeout) clearTimeout(usernameTimeout);
        const status = document.getElementById('username-status');
        
        if (val.trim() === '') {
            status.innerText = 'Keep blank to use NIC as username';
            status.className = '';
            isUsernameAvailable = true;
            validateForm();
            return;
        }

        status.innerText = 'Checking...';
        status.className = '';

        usernameTimeout = setTimeout(() => {
            fetch(`<?= ROOT ?>/custodian/check-username?username=${encodeURIComponent(val)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.available) {
                        status.innerText = '✓ Available';
                        status.className = 'status-available';
                        isUsernameAvailable = true;
                    } else {
                        status.innerText = '✗ ' + data.message;
                        status.className = 'status-taken';
                        isUsernameAvailable = false;
                    }
                    validateForm();
                });
        }, 500);
    }

    function validateForm() {
        const pw = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;
        const btn = document.getElementById('submit-btn');

        const nic = document.getElementById('nic_val').value;

        const reqs = {
            'req-length': pw.length >= 8,
            'req-upper': /[A-Z]/.test(pw),
            'req-lower': /[a-z]/.test(pw),
            'req-number': /[0-9]/.test(pw),
            'req-special': /[^A-Za-z0-9]/.test(pw),
            'req-nic': pw !== nic && pw !== ''
        };

        let allValid = true;
        for (const [id, isValid] of Object.entries(reqs)) {
            const el = document.getElementById(id);
            const icon = el.querySelector('i');
            if (isValid) {
                el.classList.add('valid');
                icon.classList.replace('fa-circle', 'fa-check-circle');
            } else {
                el.classList.remove('valid');
                icon.classList.replace('fa-check-circle', 'fa-circle');
                allValid = false;
            }
        }

        const passesMatch = (pw === confirm && pw !== '');
        btn.disabled = !(allValid && passesMatch && isUsernameAvailable);
    }

    validateForm();
    </script>
</body>
</html>
