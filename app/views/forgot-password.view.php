<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - LifeConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/style.css">
    <style>
        .form-section {
            display: none;
            animation: fadeIn 0.4s ease forwards;
        }
        .form-section.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .validation-msg {
            color: #dc3545;
            font-size: 0.813rem;
            margin-top: 0.5rem;
            text-align: left;
            display: none;
        }
        .validation-msg.show {
            display: block;
        }
        /* Make sure subtitle exactly matches login paragraph spacing */
        .login-card p.subtitle {
            margin-bottom: 2rem;
            color: var(--text-muted);
            font-size: 1rem;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 40px;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body class="login-page-body">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-wrapper">
        <div class="login-card glass-card">
            <a href="<?= ROOT ?>/">
                <img src="<?= ROOT ?>/public/assets/images/logo.png" alt="LifeConnect Logo" class="login-logo" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            </a>

            <!-- Global Error box matching login exactly -->
            <div id="generalErrorBox" class="error-message">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                <span id="generalErrorText"></span>
            </div>

            <!-- Step 1: Send OTP -->
            <div id="step1" class="form-section active">
                <h1 class="text-gradient">Forgot Password?</h1>
                <p class="subtitle">Enter your email or username below</p>

                <form id="forgotPasswordForm">
                    <div class="form-group">
                        <label for="identifier">Email Address or Username</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="text" id="identifier" placeholder="Enter your email or username" oninput="hideValidation('identifierError')">
                        </div>
                        <div id="identifierError" class="validation-msg">
                            <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i><span class="msg-text"></span>
                        </div>
                    </div>

                    <button type="submit" class="btn-premium btn-submit" id="submitBtn1">
                        <span>Send OTP</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>

                    <div class="login-footer" style="margin-top: 1.5rem; margin-bottom: 0;">
                        Remember your password? <a href="<?= ROOT ?>/login">Sign in</a>
                    </div>
                </form>
            </div>

            <!-- Step 2: Verify OTP -->
            <div id="step2" class="form-section">
                <h1 class="text-gradient">Enter OTP</h1>
                <p class="subtitle">Enter the 6-digit code sent to your email.</p>

                <form id="otpForm">
                    <div class="form-group">
                        <label for="otpCode">6-Digit OTP</label>
                        <div class="input-wrapper">
                            <i class="fas fa-key"></i>
                            <input type="text" id="otpCode" placeholder="Enter 6-digit OTP code" maxlength="6" autocomplete="off" oninput="hideValidation('otpError')">
                        </div>
                        <div id="otpError" class="validation-msg">
                            <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i><span class="msg-text"></span>
                        </div>
                    </div>

                    <button type="submit" class="btn-premium btn-submit" id="submitBtn2">
                        <span>Verify OTP</span>
                        <i class="fas fa-check-circle"></i>
                    </button>

                    <div class="login-footer" style="margin-top: 1.5rem; margin-bottom: 0;">
                        <a href="#" onclick="showStep(1); return false;">Try a different email</a>
                    </div>
                </form>
            </div>

            <!-- Step 3: Reset Password -->
            <div id="step3" class="form-section">
                <h1 class="text-gradient">New Password</h1>
                <p class="subtitle">Please structure a secure new password below.</p>

                <form id="resetForm">
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="newPassword" placeholder="Minimum 8 characters" oninput="hideValidation('resetError'); onForgotPw()">
                            <button type="button" class="eye-btn" onclick="tPw('newPassword', this)"><i class="fas fa-eye"></i></button>
                        </div>
                        <div class="strength-row" style="display: flex; gap: 4px; margin-top: 8px;">
                            <div class="sbar" id="fsb1" style="height: 4px; flex: 1; background: #e2e8f0; border-radius: 2px; transition: background 0.3s;"></div>
                            <div class="sbar" id="fsb2" style="height: 4px; flex: 1; background: #e2e8f0; border-radius: 2px; transition: background 0.3s;"></div>
                            <div class="sbar" id="fsb3" style="height: 4px; flex: 1; background: #e2e8f0; border-radius: 2px; transition: background 0.3s;"></div>
                            <div class="sbar" id="fsb4" style="height: 4px; flex: 1; background: #e2e8f0; border-radius: 2px; transition: background 0.3s;"></div>
                        </div>
                        <span class="hint" id="newPasswordH" style="font-size: 0.813rem; color: var(--text-muted); display: block; margin-top: 4px;">Must include uppercase, lowercase, number and special character</span>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="confirmPassword" placeholder="Confirm your new password" oninput="hideValidation('resetError')">
                            <button type="button" class="eye-btn" onclick="tPw('confirmPassword', this)"><i class="fas fa-eye"></i></button>
                        </div>
                        <!-- Validation message under the last box -->
                        <div id="resetError" class="validation-msg">
                            <i class="fas fa-exclamation-circle" style="margin-right: 4px;"></i><span class="msg-text"></span>
                        </div>
                    </div>

                    <button type="submit" class="btn-premium btn-submit" id="submitBtn3">
                        <span>Save New Password</span>
                        <i class="fas fa-save"></i>
                    </button>
                </form>
            </div>

            <!-- Step 4: Success -->
            <div id="step4" class="form-section">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h3 style="margin-bottom: 1rem; color: var(--text-main);">Reset Successful!</h3>
                <p class="subtitle">Your password has been changed. You can now log in.</p>
                <button class="btn-premium btn-submit" onclick="window.location.href='<?= ROOT ?>/login'">
                    <span>Proceed to Login</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
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

        const ROOT = '<?= ROOT ?>';
        
        function showValidation(elementId, message) {
            const el = document.getElementById(elementId);
            if (el) {
                el.querySelector('.msg-text').innerText = message;
                el.classList.add('show');
            }
        }

        function hideValidation(elementId) {
            const el = document.getElementById(elementId);
            if (el) {
                el.classList.remove('show');
            }
            document.getElementById('generalErrorBox').classList.remove('show');
        }

        function showGeneralError(message) {
            const box = document.getElementById('generalErrorBox');
            document.getElementById('generalErrorText').innerText = message;
            box.classList.add('show');
        }

        function showStep(step) {
            document.querySelectorAll('.form-section').forEach(el => el.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');
            document.getElementById('generalErrorBox').classList.remove('show');
        }

        // Step 1: Request OTP
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            hideValidation('identifierError');
            
            const identifier = document.getElementById('identifier').value.trim();
            
            if (!identifier) {
                showValidation('identifierError', 'Please enter your email address or username.');
                return;
            }
            if (identifier.length < 3) {
                showValidation('identifierError', 'Must be at least 3 characters.');
                return;
            }
            // Basic format validation if it resembles an email
            if (identifier.includes('@') && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(identifier)) {
                showValidation('identifierError', 'Please enter a valid email format.');
                return;
            }
            
            const btn = document.getElementById('submitBtn1');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>Sending...</span><i class="fas fa-spinner fa-spin"></i>';
            
            fetch(ROOT + '/forgot-password/sendOtp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ identifier })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                
                if (data.success) {
                    showStep(2);
                } else {
                    showValidation('identifierError', data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                showGeneralError('A network error occurred. Try again.');
            });
        });

        // Step 2: Verify OTP
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            hideValidation('otpError');
            
            const identifier = document.getElementById('identifier').value.trim();
            const otpCode = document.getElementById('otpCode').value.trim();
            
            if (!otpCode || otpCode.length !== 6 || !/^\d{6}$/.test(otpCode)) {
                showValidation('otpError', 'Please enter a valid 6-digit OTP.');
                return;
            }
            
            const btn = document.getElementById('submitBtn2');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>Verifying...</span><i class="fas fa-spinner fa-spin"></i>';
            
            fetch(ROOT + '/forgot-password/verifyOtp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ identifier, otp: otpCode })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;

                if(data.success) {
                    showStep(3);
                } else {
                    showValidation('otpError', data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                showGeneralError('A network error occurred. Try again.');
            });
        });

        // Step 3: Reset Password
        function onForgotPw() {
            var pw = document.getElementById("newPassword");
            var hint = document.getElementById("newPasswordH");
            var value = pw.value;
            var bars = ["fsb1", "fsb2", "fsb3", "fsb4"].map(id => document.getElementById(id));
            bars.forEach(bar => bar.style.backgroundColor = "#e2e8f0");
            
            if (!value) {
                hint.textContent = "Must include uppercase, lowercase, number and special character";
                hint.style.color = "var(--text-muted)";
                return;
            }
            var missing = [];
            if (value.length < 8) missing.push("8+ chars");
            if (!/[A-Z]/.test(value) || !/[a-z]/.test(value)) missing.push("upper & lowercase");
            if (!/\d/.test(value)) missing.push("number");
            if (!/[^A-Za-z0-9]/.test(value)) missing.push("symbol");
            
            var score = 4 - missing.length;
            var color = score <= 1 ? "#ef4444" : score <= 2 ? "#f59e0b" : "#10b981";
            
            for (var i = 0; i < score; i += 1) {
                bars[i].style.backgroundColor = color;
            }
            if (score === 4) {
                hint.textContent = "Strong password";
                hint.style.color = "#10b981";
            } else {
                hint.textContent = "Missing: " + missing.join(", ");
                hint.style.color = "#ef4444";
            }
        }

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            hideValidation('resetError');
            
            const identifier = document.getElementById('identifier').value.trim();
            const password = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;
            
            if (password.length < 8) {
                showValidation('resetError', 'Password must be at least 8 characters.');
                return;
            }
            let missing = [];
            if (!/[A-Z]/.test(password)) missing.push("uppercase");
            if (!/[a-z]/.test(password)) missing.push("lowercase");
            if (!/\d/.test(password)) missing.push("number");
            if (!/[^A-Za-z0-9]/.test(password)) missing.push("special character");
            if (missing.length > 0) {
                showValidation('resetError', 'Password missing: ' + missing.join(", "));
                return;
            }
            if (password !== confirm) {
                showValidation('resetError', 'Passwords do not match!');
                return;
            }
            
            const btn = document.getElementById('submitBtn3');
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>Saving...</span><i class="fas fa-spinner fa-spin"></i>';
            
            fetch(ROOT + '/forgot-password/reset', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ identifier, password, confirm })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;

                if(data.success) {
                    showStep(4);
                } else {
                    showGeneralError(data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                showGeneralError('A network error occurred. Try again.');
            });
        });
    </script>
</body>
</html>

