<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeConnect Login - Sri Lanka</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css">
    </head>
<body class="login-page-body">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-wrapper">
        <div class="login-card glass-card">
            <a href="<?= ROOT ?>/">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect Logo" class="login-logo" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            </a>
            <h1 class="text-gradient">Welcome back</h1>
            <p>Enter your credentials to access the portal</p>

            <div id="errorBox" class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                <span id="errorText"></span>
            </div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="johndoe" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <button type="button" class="eye-btn" onclick="tPw('password', this)"><i class="fas fa-eye"></i></button>
                    </div>
                </div>

                <div style="text-align: right; margin-bottom: 1rem;">
                    <a href="<?= ROOT ?>/forgot-password" style="font-size: 0.813rem; color: var(--text-muted); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color='var(--text-muted)'">Forgot password?</a>
                </div>

                <button type="submit" class="btn-premium btn-submit">
                    <span>Sign In</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="login-footer">
                Don't have an account? <a href="<?= ROOT ?>/signup">Create an account</a>
            </div>
        </div>
    </div>

    <script>
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

    function showError(message) {
        const errorBox = document.getElementById('errorBox');
        const errorText = document.getElementById('errorText');
        errorText.textContent = message;
        errorBox.classList.add('show');
    }

    function hideError() {
        document.getElementById('errorBox').classList.remove('show');
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        hideError();

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const submitBtn = this.querySelector('.btn-submit');
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span> Signing In...</span>';
        submitBtn.disabled = true;

        fetch('<?= ROOT ?>/login/verify', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                submitBtn.innerHTML = '<i class="fas fa-check"></i><span> Success! Redirecting...</span>';

                setTimeout(() => {
                    const baseUrl = '<?= ROOT ?>/';
                    const userRole = (data.role || '').trim().toUpperCase();
                    switch (userRole) {
                        case 'U_ADMIN':
                            window.location.href = baseUrl + 'user-admin';
                            break;
                        case 'F_ADMIN':
                            window.location.href = baseUrl + 'financial-admin';
                            break;
                        case 'AC_ADMIN':
                            window.location.href = baseUrl + 'aftercare-admin';
                            break;
                        case 'D_ADMIN':
                            window.location.href = baseUrl + 'donation-admin';
                            break;
                        case 'FINANCIAL_DONOR':
                            window.location.href = baseUrl + 'donor';
                            break;
                        case 'DONOR':
                            window.location.href = baseUrl + 'donor';
                            break;
                        case 'HOSPITAL':
                            window.location.href = baseUrl + 'hospital';
                            break;
                        case 'MEDICAL_SCHOOL':
                            window.location.href = baseUrl + 'medical-school';
                            break;
                        case 'CUSTODIAN':
                            window.location.href = baseUrl + 'custodian';
                            break;
                        default:
                            showError('Unknown user role: ' + userRole + '. Contact Administrator.');
                            submitBtn.innerHTML = '<span>Sign In</span> <i class="fas fa-arrow-right"></i>';
                            submitBtn.disabled = false;
                            // Remove auto-redirect to home on unknown roles so user can see the error
                    }
                }, 1000);
            } else {
                showError(data.message || 'Invalid username or password.');
                submitBtn.innerHTML = '<span>Sign In</span> <i class="fas fa-arrow-right"></i>';
                submitBtn.disabled = false;
            }
        })
        .catch(err => {
            console.error('Login error:', err);
            showError('An unexpected error occurred. Please try again.');
            submitBtn.innerHTML = '<span>Sign In</span> <i class="fas fa-arrow-right"></i>';
            submitBtn.disabled = false;
        });
    });
    </script>
</body>
</html>
